<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicGradeResource\Pages;
use App\Models\AcademicCalendar;
use App\Models\AcademicGrade;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AcademicGradeResource extends Resource
{
    protected static ?string $model = AcademicGrade::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Nilai Akademik';

    protected static ?string $modelLabel = 'Nilai Akademik';

    protected static ?string $pluralModelLabel = 'Nilai Akademik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('academic_calendar_id')
                    ->label('Periode Akademik')
                    ->options(function (): array {
                        return AcademicCalendar::query()
                            ->where('is_active', true)
                            ->orderByDesc('start_date')
                            ->get()
                            ->mapWithKeys(function (AcademicCalendar $calendar): array {
                                return [
                                    $calendar->id => $calendar->academic_year
                                        . ' - Semester '
                                        . $calendar->semester,
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                    ->helperText(
                        fn (): string => self::gradeInputStatusMessage()
                    ),

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

                Forms\Components\Select::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->relationship(
                        name: 'subject',
                        titleAttribute: 'name',
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('score')
                    ->label('Nilai')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (
                        Forms\Set $set,
                        $state
                    ): void {
                        if ($state === null || $state === '') {
                            $set('grade', null);

                            return;
                        }

                        $set(
                            'grade',
                            self::calculateGrade((float) $state)
                        );
                    }),

                Forms\Components\TextInput::make('grade')
                    ->label('Predikat')
                    ->disabled()
                    ->dehydrated()
                    ->placeholder('Otomatis dari nilai'),

                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3)
                    ->maxLength(1000)
                    ->placeholder('Catatan perkembangan siswa.')
                    ->columnSpanFull(),

                Forms\Components\Placeholder::make('input_period_status')
                    ->label('Status Pengisian Nilai')
                    ->content(
                        fn (): string => self::gradeInputStatusMessage()
                    )
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('academicCalendar.academic_year')
                    ->label('Tahun Ajaran')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('academicCalendar.semester')
                    ->label('Semester')
                    ->formatStateUsing(
                        fn (int $state): string => 'Semester ' . $state
                    )
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

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('score')
                    ->label('Nilai')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('grade')
                    ->label('Predikat')
                    ->badge()
                    ->sortable(),

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
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListAcademicGrades::route('/'),
            'create' => Pages\CreateAcademicGrade::route('/create'),
            'edit' => Pages\EditAcademicGrade::route('/{record}/edit'),
        ];
    }

    public static function calculateGrade(float $score): string
    {
        return match (true) {
            $score >= 90 => 'A',
            $score >= 80 => 'B',
            $score >= 70 => 'C',
            default => 'D',
        };
    }

    public static function isGradeInputOpen(
        ?AcademicCalendar $calendar = null
    ): bool {
        $calendar ??= AcademicCalendar::query()
            ->where('is_active', true)
            ->orderByDesc('start_date')
            ->first();

        if (! $calendar) {
            return false;
        }

        if (
            ! $calendar->grade_input_start_date
            || ! $calendar->grade_input_end_date
        ) {
            return false;
        }

        $today = now()->startOfDay();

        return $today->between(
            $calendar->grade_input_start_date->startOfDay(),
            $calendar->grade_input_end_date->endOfDay()
        );
    }

    public static function canCreate(): bool
    {
        return self::isGradeInputOpen();
    }

    public static function canEdit(Model $record): bool
    {
        return self::isGradeInputOpen(
            $record->academicCalendar
        );
    }

    public static function canDelete(Model $record): bool
    {
        return self::isGradeInputOpen(
            $record->academicCalendar
        );
    }

    public static function gradeInputStatusMessage(): string
    {
        $calendar = AcademicCalendar::query()
            ->where('is_active', true)
            ->orderByDesc('start_date')
            ->first();

        if (! $calendar) {
            return 'Tidak ada kalender akademik aktif.';
        }

        $today = now()->startOfDay();

        if (
            $calendar->grade_input_start_date
            && $today->lt($calendar->grade_input_start_date->startOfDay())
        ) {
            return 'Pengisian nilai belum dibuka. Mulai '
                . $calendar->grade_input_start_date->format('d M Y')
                . '.';
        }

        if (
            $calendar->grade_input_end_date
            && $today->gt($calendar->grade_input_end_date->endOfDay())
        ) {
            return 'Periode pengisian nilai sudah ditutup.';
        }

        if (
            $calendar->grade_input_start_date
            && $calendar->grade_input_end_date
            && $today->between(
                $calendar->grade_input_start_date->startOfDay(),
                $calendar->grade_input_end_date->endOfDay()
            )
        ) {
            return 'Pengisian nilai sedang dibuka sampai '
                . $calendar->grade_input_end_date->format('d M Y')
                . '.';
        }

        return 'Periode pengisian nilai belum ditentukan.';
    }
}
