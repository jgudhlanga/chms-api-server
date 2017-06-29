<?php

namespace App\Models\Families;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
	protected $fillable = ['family_id', 'member_id', 'roleId', 'createdBy'];

	public function family()
	{
		return $this->belongsTo('App\Models\Families\Family');
	}
}
