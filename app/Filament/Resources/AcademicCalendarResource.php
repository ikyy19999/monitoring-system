<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicCalendarResource\Pages;
use App\Models\AcademicCalendar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class AcademicCalendarResource extends Resource
{
    protected static ?string $model = AcademicCalendar::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Kalender Akademik';

    protected static ?string $modelLabel = 'Kalender Akademik';

    protected static ?string $pluralModelLabel = 'Kalender Akademik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('academic_year')
                    ->label('Tahun Ajaran')
                    ->placeholder('Contoh: 2026/2027')
                    ->required()
                    ->maxLength(9)
                    ->regex('/^\d{4}\/\d{4}$/')
                    ->validationMessages([
                        'regex' => 'Format tahun ajaran harus seperti 2026/2027.',
                    ])
                    ->rule(function () {
                        return function (string $attribute, mixed $value, \Closure $fail): void {
                            if (! preg_match('/^(\d{4})\/(\d{4})$/', $value, $matches)) {
                                return;
                            }

                            if ((int) $matches[2] !== ((int) $matches[1] + 1)) {
                                $fail('Tahun ajaran harus menggunakan dua tahun berurutan.');
                            }
                        };
                    }),

                Forms\Components\Select::make('semester')
                    ->label('Semester')
                    ->options([
                        1 => 'Semester 1',
                        2 => 'Semester 2',
                    ])
                    ->required()
                    ->native(false),

                Forms\Components\DatePicker::make('start_date')
                    ->label('Tanggal Mulai Semester')
                    ->native(false)
                    ->required()
                    ->live(),

                Forms\Components\DatePicker::make('end_date')
                    ->label('Tanggal Akhir Semester')
                    ->native(false)
                    ->required()
                    ->afterOrEqual('start_date')
                    ->validationMessages([
                        'after_or_equal' => 'Tanggal akhir semester tidak boleh sebelum tanggal mulai semester.',
                    ])
                    ->live(),

                Forms\Components\Section::make('Periode Pengisian Nilai')
                    ->description('Tentukan kapan wali kelas diperbolehkan menginput nilai akhir semester.')
                    ->schema([
                        Forms\Components\DatePicker::make('grade_input_start_date')
                            ->label('Mulai Pengisian Nilai')
                            ->native(false)
                            ->nullable()
                            ->afterOrEqual('start_date')
                            ->beforeOrEqual('grade_input_end_date')
                            ->validationMessages([
                                'after_or_equal' => 'Pengisian nilai tidak boleh dimulai sebelum semester dimulai.',
                                'before_or_equal' => 'Tanggal mulai pengisian nilai tidak boleh setelah tanggal akhir pengisian nilai.',
                            ])
                            ->live(),

                        Forms\Components\DatePicker::make('grade_input_end_date')
                            ->label('Akhir Pengisian Nilai')
                            ->native(false)
                            ->nullable()
                            ->afterOrEqual('grade_input_start_date')
                            ->validationMessages([
                                'after_or_equal' => 'Tanggal akhir pengisian nilai tidak boleh sebelum tanggal mulai pengisian nilai.',
                            ])
                            ->live(),
                    ])
                    ->columns(2),

                Forms\Components\Toggle::make('is_active')
                    ->label('Kalender Aktif')
                    ->default(true)
                    ->helperText('Hanya kalender akademik yang aktif yang digunakan oleh sistem.'),

                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3)
                    ->maxLength(1000)
                    ->placeholder('Tambahkan catatan jika diperlukan.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('academic_year')
                    ->label('Tahun Ajaran')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->formatStateUsing(
                        fn (int $state): string => 'Semester ' . $state
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai Semester')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Akhir Semester')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('grade_input_start_date')
                    ->label('Mulai Input Nilai')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('grade_input_end_date')
                    ->label('Akhir Input Nilai')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('start_date', 'desc')
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
            'index' => Pages\ListAcademicCalendars::route('/'),
            'create' => Pages\CreateAcademicCalendar::route('/create'),
            'edit' => Pages\EditAcademicCalendar::route('/{record}/edit'),
        ];
    }
}
