<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Custodian extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'custodianname',
        'mdaid'
    ];

    public function mda() {
        return $this->belongsTo(Mda::class, 'mdaid', 'id');
    }

}
