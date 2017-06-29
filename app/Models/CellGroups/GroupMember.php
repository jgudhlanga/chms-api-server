<?php

namespace App\Models\CellGroups;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    protected $fillable = ['cell_group_id', 'member_id', 'roleId', 'createdBy'];

	public function group()
	{
		return $this->belongsTo('App\Models\CellGroups\CellGroup');
	}
}
