<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Asset extends Model
{
    use HasFactory, HasUuids;

    protected $fillable =[
        'tagnumber',  
        'assetcategory',
        'assettype',
        'assetname',
        'assetdescription',
        'landsize',
        'arealocated',
        'landmark',
        'gpslocation',
        'make',
        'modelno',
        'serialno',
        'colour',
        'year',
        'engineno',
        'chasisno',
        'vehicleno',
        'datepurchase',
        'purchasedamount',
        'status',
        'mdaid',
        'assetlocation',
        'locationdescription',
        'custodian',
        'pict1',
        'pict2',
        'pict3',
        'pict4',
        'video',
        'submittedby',
        'user_id'
    ];


    
    public function user() {
        return $this->belongsTo(User::class);
    }

    
    protected function casts(): array
    {
        return [
            'datepurchase' => 'datetime',
        ];
    }




}
