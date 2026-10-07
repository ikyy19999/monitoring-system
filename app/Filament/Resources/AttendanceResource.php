<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Absensi';

    protected static ?string $modelLabel = 'Absensi';

    protected static ?string $pluralModelLabel = 'Absensi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teaching_assignment_id')
                    ->label('Pengajaran')
                    ->relationship(
                        name: 'teachingAssignment',
                        titleAttribute: 'id',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->whereHas('teacher', function (Builder $query) {
                                $query->role('guru mata pelajaran');
                            })
                            ->with([
                                'teacher',
                                'subject',
                                'schoolClass',
                            ]),
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn ($record): string => $record->teacher->name
                            . ' - '
                            . $record->subject->name
                            . ' - '
                            . $record->schoolClass->name
                            . ' ('
                            . $record->schoolClass->academic_year
                            . ')'
                    )
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),

                Forms\Components\Select::make('student_id')
                    ->label('Siswa')
                    ->options(function (Forms\Get $get, ?Attendance $record): array {
                        $assignmentId = $get('teaching_assignment_id');

                        if (! $assignmentId) {
                            return [];
                        }

                        $assignment = \App\Models\TeachingAssignment::find($assignmentId);

                        if (! $assignment) {
                            return [];
                        }

                        $query = $assignment->schoolClass
                            ->students()
                            ->where('status', 'active')
                            ->orderBy('full_name');

                        if ($record?->student_id) {
                            $query->orWhere('id', $record->student_id);
                        }

                        return $query
                            ->get()
                            ->pluck('full_name', 'id')
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn (Forms\Get $get): bool => ! $get('teaching_assignment_id')),

                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal')
                    ->default(now())
                    ->native(false)
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpa' => 'Alpa',
                    ])
                    ->default('hadir')
                    ->required(),

                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3)
                    ->maxLength(1000)
                    ->placeholder('Tambahkan catatan jika diperlukan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('teachingAssignment.teacher.name')
                    ->label('Guru')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('teachingAssignment.subject.name')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('teachingAssignment.schoolClass.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'hadir' => 'Hadir',
                            'izin' => 'Izin',
                            'sakit' => 'Sakit',
                            'alpa' => 'Alpa',
                            default => $state,
                        }
                    ),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(40)
                    ->tooltip(
                        fn (?string $state): ?string => $state
                    )
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('date', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
