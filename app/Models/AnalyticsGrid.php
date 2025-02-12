<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsGrid extends Model
{
    use HasFactory;

    protected $fillable = ['analytics_qualification_id', 'identifier', 'data'];

    protected $casts = [
        'data' => 'array'
    ];
}
