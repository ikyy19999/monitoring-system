<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParentProfileResource\Pages;
use App\Models\ParentProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ParentProfileResource extends Resource
{
    protected static ?string $model = ParentProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Orang Tua';

    protected static ?string $modelLabel = 'Orang Tua';

    protected static ?string $pluralModelLabel = 'Orang Tua';

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
                Forms\Components\Select::make('user_id')
                    ->label('Akun')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('full_name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->maxLength(255),

                Forms\Components\Textarea::make('address')
                    ->label('Alamat')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Nomor Telepon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('students_count')
                    ->label('Jumlah Anak')
                    ->counts('students')
                    ->sortable(),

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
            'index' => Pages\ListParentProfiles::route('/'),
            'create' => Pages\CreateParentProfile::route('/create'),
            'edit' => Pages\EditParentProfile::route('/{record}/edit'),
        ];
    }
}
