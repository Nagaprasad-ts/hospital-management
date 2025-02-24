<?php

namespace App\Filament\Resources\ViewMyScheduleResource\Pages;

use App\Filament\Resources\ViewMyScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditViewMySchedule extends EditRecord
{
    protected static string $resource = ViewMyScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
