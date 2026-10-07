<?php

namespace App\Filament\Resources\AcademicGradeResource\Pages;

use App\Filament\Resources\AcademicGradeResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAcademicGrade extends EditRecord
{
    protected static string $resource = AcademicGradeResource::class;

    protected function beforeSave(): void
    {
        $record = $this->getRecord();

        if (! AcademicGradeResource::isGradeInputOpen($record->academicCalendar)) {
            Notification::make()
                ->title('Pengubahan nilai tidak tersedia')
                ->body('Nilai hanya dapat diubah selama periode pengisian nilai.')
                ->danger()
                ->send();

            $this->halt();
        }
    }
}
