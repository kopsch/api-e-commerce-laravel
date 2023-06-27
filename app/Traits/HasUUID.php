<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUUID
{
    /**
     * Verifica se o model tem uuid e o transforma em string.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(fn ($model) => $model->uuid = Str::uuid()->toString());
    }
}
