<?php

namespace Branzia\Settings\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'tenant_id'];
    public $timestamps = false;

    protected $casts = [
        'value' => 'string',
    ];
}
