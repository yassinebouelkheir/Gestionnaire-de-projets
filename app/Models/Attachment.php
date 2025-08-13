<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'filename', 'path', 'attachable_type', 'attachable_id',
    ];

    public function attachable()
    {
        return $this->morphTo();
    }
}
