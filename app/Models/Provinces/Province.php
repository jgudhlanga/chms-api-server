<?php

namespace App\Models\Provinces;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['name', 'shortName', 'country_id', 'createdBy', 'description'];
}
