<?php

namespace App\Filament\Resources\AcademicGradeResource\Pages;

use App\Filament\Resources\AcademicGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcademicGrades extends ListRecords
{
    protected static string $resource = AcademicGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
