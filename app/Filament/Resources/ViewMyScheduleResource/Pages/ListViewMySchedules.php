<?php

namespace App\Filament\Resources\ViewMyScheduleResource\Pages;

use App\Filament\Resources\ViewMyScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListViewMySchedules extends ListRecords
{
    protected static string $resource = ViewMyScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
