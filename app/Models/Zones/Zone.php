<?php

namespace App\Models\Zones;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
	protected $fillable = ['name',  'alias', 'description',  'zoneLeaderId',  'createdBy',  'meetingTime',  'dateFormed',  'meetingLocation','isActive'];

	public function zoneGroups()
	{
		return $this->hasMany('App\Models\Zones\ZoneGroup');
	}

	public function addGroup(ZoneGroup $group)
	{
		return $this->zoneGroups()->save($group);
	}

	public function delete()
	{
		$this->zoneGroups()->delete();
		Parent::delete();
	}
}
