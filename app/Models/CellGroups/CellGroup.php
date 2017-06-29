<?php

namespace App\Models\CellGroups;

use Illuminate\Database\Eloquent\Model;

class CellGroup extends Model
{
	protected $fillable = ['name',  'alias', 'description',  'groupId',  'groupLeaderId',  'createdBy',  'meetingTime',  'dateFormed',  'meetingLocation','isActive'];

	public function groupMembers()
	{
		return $this->hasMany('App\Models\CellGroups\GroupMember');
	}

	public function addMember(GroupMember $member)
	{
		return $this->groupMembers()->save($member);
	}

	public function delete()
	{
		$this->groupMembers()->delete();
		Parent::delete();
	}
}
