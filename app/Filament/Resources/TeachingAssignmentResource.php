<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeachingAssignmentResource\Pages;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeachingAssignmentResource extends Resource
{
    protected static ?string $model = TeachingAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Pengajaran';

    protected static ?string $modelLabel = 'Pengajaran';

    protected static ?string $pluralModelLabel = 'Pengajaran';

    public static function shouldRegisterNavigation(): bool
    {
        return filament()->getCurrentPanel()->getId() === 'admin';
    }

    public static function canAccess(): bool
    {
        return filament()->getCurrentPanel()->getId() === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->label('Guru Mata Pelajaran')
                    ->relationship(
                        name: 'teacher',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->role('guru mata pelajaran'),
                    )
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

                Forms\Components\Select::make('school_class_id')
                    ->label('Kelas')
                    ->relationship(
                        name: 'schoolClass',
                        titleAttribute: 'name',
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Guru')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('schoolClass.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('schoolClass.academic_year')
                    ->label('Tahun Ajaran')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
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
            'index' => Pages\ListTeachingAssignments::route('/'),
            'create' => Pages\CreateTeachingAssignment::route('/create'),
            'edit' => Pages\EditTeachingAssignment::route('/{record}/edit'),
        ];
    }
}
