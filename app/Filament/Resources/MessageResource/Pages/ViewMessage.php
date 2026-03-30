<?php

namespace App\Filament\Resources\MessageResource\Pages;

use App\Filament\Resources\MessageResource;
use Filament\Resources\Pages\ViewRecord;

class ViewMessage extends ViewRecord
{
    protected static string $resource = MessageResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->isRead() ?: $this->record->markAsRead();
        return $data;
    }
}
