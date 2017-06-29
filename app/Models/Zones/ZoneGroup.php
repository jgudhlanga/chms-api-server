<?php

namespace App\Models\Zones;

use Illuminate\Database\Eloquent\Model;

class ZoneGroup extends Model
{
    protected $fillable = ['zone_id', 'group_id', 'createdBy'];

	public function zone()
	{
		return $this->belongsTo('App\Models\Zones\Zone');
	}
}
