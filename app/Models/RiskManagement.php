<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskManagement extends Model
{
    use HasFactory;



    public function record_initiator()
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    public function division()
    {
        return $this->belongsTo(QMSDivision::class);
    }

    public function initiator()
    {
        return $this->belongsTo(User::class);
    }
}
