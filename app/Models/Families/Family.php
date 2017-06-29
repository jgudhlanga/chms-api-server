<?php

namespace App\Models\Families;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable= ['name', 'alias', 'description', 'createdBy', 'isActive', 'headOfFamilyId', 'location'];

	public function familyMembers()
	{
		return $this->hasMany('App\Models\Families\FamilyMember');
	}

	public function addMember(FamilyMember $member)
	{
		return $this->familyMembers()->save($member);
	}

	public function delete()
	{
		$this->familyMembers()->delete();
		Parent::delete();
	}
}
