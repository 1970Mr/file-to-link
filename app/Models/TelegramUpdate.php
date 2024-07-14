<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TelegramUpdate extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['request'];

    public function request(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => json_decode($value, false, 512, JSON_THROW_ON_ERROR),
            set: static fn ($value) => json_encode($value, JSON_THROW_ON_ERROR),
        );
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('default')
            ->useDisk('files');
    }
}
