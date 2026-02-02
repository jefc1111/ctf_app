<?php

namespace App\Filament\AvatarProviders;

use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\AvatarProviders\UiAvatarsProvider;

class JetstreamProfilePhotoAvatarProvider implements AvatarProvider
{
    public function get(Model | Authenticatable $record): string
    {
        return $record->profile_photo_path 
        ? asset('storage/'.$record->profile_photo_path)
        : (new UiAvatarsProvider())->get($record);
    }
}