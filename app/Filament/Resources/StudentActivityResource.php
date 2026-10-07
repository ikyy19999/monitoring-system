<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentActivityResource\Pages;
use App\Models\StudentActivity;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentActivityResource extends Resource
{
    protected static ?string $model = StudentActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Aktivitas Siswa';

    protected static ?string $modelLabel = 'Aktivitas Siswa';

    protected static ?string $pluralModelLabel = 'Aktivitas Siswa';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('student_id')
                ->label('Siswa')
                ->options(function (): array {
                    return Student::query()
                        ->with('schoolClass')
                        ->where('status', 'active')
                        ->orderBy('full_name')
                        ->get()
                        ->mapWithKeys(function (Student $student): array {
                            return [
                                $student->id => $student->nis
                                    . ' - '
                                    . $student->full_name
                                    . ' ('
                                    . $student->schoolClass->name
                                    . ')',
                            ];
                        })
                        ->toArray();
                })
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\DatePicker::make('activity_date')
                ->label('Tanggal Kegiatan')
                ->native(false)
                ->default(now())
                ->required(),

            Forms\Components\Select::make('category')
                ->label('Kategori')
                ->options([
                    'pembelajaran' => 'Pembelajaran',
                    'ekstrakurikuler' => 'Ekstrakurikuler',
                    'organisasi' => 'Organisasi',
                    'lomba' => 'Lomba',
                    'kegiatan_sekolah' => 'Kegiatan Sekolah',
                    'lainnya' => 'Lainnya',
                ])
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('title')
                ->label('Judul Kegiatan')
                ->required()
                ->maxLength(255)
                ->placeholder('Contoh: Praktik Membuat Aplikasi Laravel'),

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')
                ->rows(4)
                ->maxLength(2000)
                ->placeholder('Jelaskan kegiatan yang dilakukan siswa.')
                ->columnSpanFull(),

            Forms\Components\Textarea::make('notes')
                ->label('Catatan')
                ->rows(3)
                ->maxLength(1000)
                ->placeholder('Catatan tambahan mengenai aktivitas siswa.')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('activity_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.schoolClass.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pembelajaran' => 'Pembelajaran',
                        'ekstrakurikuler' => 'Ekstrakurikuler',
                        'organisasi' => 'Organisasi',
                        'lomba' => 'Lomba',
                        'kegiatan_sekolah' => 'Kegiatan Sekolah',
                        'lainnya' => 'Lainnya',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label('Kegiatan')
                    ->searchable()
                    ->limit(45)
                    ->tooltip(fn (StudentActivity $record): string => $record->title),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->tooltip(fn (StudentActivity $record): ?string => $record->description)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'pembelajaran' => 'Pembelajaran',
                        'ekstrakurikuler' => 'Ekstrakurikuler',
                        'organisasi' => 'Organisasi',
                        'lomba' => 'Lomba',
                        'kegiatan_sekolah' => 'Kegiatan Sekolah',
                        'lainnya' => 'Lainnya',
                    ]),

                Tables\Filters\SelectFilter::make('student_id')
                    ->label('Siswa')
                    ->relationship(
                        name: 'student',
                        titleAttribute: 'full_name',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->where('status', 'active')
                            ->orderBy('full_name')
                    )
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('activity_date')
                    ->label('Tanggal Kegiatan')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari')
                            ->native(false),

                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    'activity_date',
                                    '>=',
                                    $date
                                )
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    'activity_date',
                                    '<=',
                                    $date
                                )
                            );
                    }),
            ])
            ->defaultSort('activity_date', 'desc')
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
            'index' => Pages\ListStudentActivities::route('/'),
            'create' => Pages\CreateStudentActivity::route('/create'),
            'edit' => Pages\EditStudentActivity::route('/{record}/edit'),
        ];
    }
}
