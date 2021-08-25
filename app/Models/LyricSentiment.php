<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LyricSentiment extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'id';
    }
}
