<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolClassResource\Pages;
use App\Models\SchoolClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SchoolClassResource extends Resource
{
    protected static ?string $model = SchoolClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Kelas';

    protected static ?string $modelLabel = 'Kelas';

    protected static ?string $pluralModelLabel = 'Kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kelas')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('grade')
                    ->label('Tingkat')
                    ->required()
                    ->maxLength(20),

                Forms\Components\TextInput::make('academic_year')
                    ->label('Tahun Ajaran')
                    ->required()
                    ->maxLength(20)
                    ->placeholder('2026/2027'),

                Forms\Components\Select::make('homeroom_teacher_id')
                    ->label('Wali Kelas')
                    ->relationship(
                        name: 'homeroomTeacher',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->role('wali kelas'),
                    )
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('grade')
                    ->label('Tingkat')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('academic_year')
                    ->label('Tahun Ajaran')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('homeroomTeacher.name')
                    ->label('Wali Kelas')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Belum ditentukan'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
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
            'index' => Pages\ListSchoolClasses::route('/'),
            'create' => Pages\CreateSchoolClass::route('/create'),
            'edit' => Pages\EditSchoolClass::route('/{record}/edit'),
        ];
    }
}
