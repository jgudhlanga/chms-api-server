<?php

namespace App\Transformers;
use App\Models\CellGroups\CellGroup;
use App\Models\Members\Member;
use League\Fractal\TransformerAbstract;

class CellGroupTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'groupMembers',
    ];

    /**
     * @param \App\Models\CellGroups\CellGroup $group
     * @return array
     */
    public function transform(CellGroup $group) {
        $status = ($group->isActive == 1) ? 'Active' : 'Inactive';
        $dateFormed = ($group->dateFormed > 0) ? date('d-M-Y', $group->dateFormed) : 'Not Set';
        //get the member names
        $groupLeader = ($group->groupLeaderId > 0) ? Member::find($group->groupLeaderId) : [];
        return ['id' => $group->id,
            'name' => $group->name,
            'alias' => $group->alias,
            'description' => $group->description,
            'groupId' => $group->groupId,
            'meetingLocation' => $group->meetingLocation,
            'meetingTime' => $group->meetingTime,
            'groupLeaderId' => $group->groupLeaderId,
            'isActive' => $group->isActive,
            'status' => $status,
            'dateFormed' => $dateFormed,
            'membership' => count($group->groupMembers),
            'groupZone' => 'not set',
            'groupLeader' => (isset($groupLeader->firstName)) ? $groupLeader->firstName.' '.$groupLeader->surname : '',
            'leaderFName' => (isset($groupLeader->firstName)) ? $groupLeader->firstName : 'not set',
            'leaderSurname' => (isset($groupLeader->surname)) ? $groupLeader->surname : 'not set',
            'leaderInitial' => (isset($groupLeader->firstName)) ? substr($groupLeader->firstName, 0, 1) : '',
            ];
    }

    public function includeGroupMembers(CellGroup $group) {
        $groupMembers = $group->groupMembers;
        return $this->collection($groupMembers, new GroupMemberTransformer());
    }

}
