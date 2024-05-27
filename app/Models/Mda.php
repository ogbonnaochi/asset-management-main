<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Mda extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'mda_name',
    ];


    
    public function locations () {
        return $this->hasMany(Location::class, 'mdaid', 'id');
    }

    
    public function custodian () {
        return $this->hasMany(Custodian::class, 'mdaid', 'id');
    }
}
