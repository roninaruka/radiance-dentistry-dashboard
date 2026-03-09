<?php

namespace App\Filament\Resources\BlockedSlotResource\Pages;

use App\Filament\Resources\BlockedSlotResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBlockedSlot extends CreateRecord
{
    protected static string $resource = BlockedSlotResource::class;

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
