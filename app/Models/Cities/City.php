<?php

namespace App\Models\Cities;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $fillable = ['name', 'shortName', 'province_id', 'createdBy', 'description'];
    
    public function province(){
        return $this->belongsTo('App\Models\Provinces\Province');
    }
}
