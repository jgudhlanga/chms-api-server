<?php
namespace App\Transformers;
use App\Models\Members\Member;
use App\Transformer;
use App\Models\CellGroups\MemberGroup;
use League\Fractal\TransformerAbstract;

class ZoneGroupTransformer extends TransformerAbstract{
	/**
	 * @param \App\Models\Zones\ZoneGroup $zoneGroup
	 * @return array
	 */
	public function transform(ZoneGroup $zoneGroup)
	{
		//get the group details
		$group = CellGroup::find($zoneGroup->cell_group_id);
		return [
			'id' => $group->id,
			'zoneId' => $group->zone_id,
			'groupName' => (isset($group->name)) ? $group->name: 'not set',
			'groupAlias' => (isset($group->alias)) ? $group->alias : 'not set',
		];
	}
}
