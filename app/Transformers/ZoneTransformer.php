<?php

namespace App\Transformers;

use App\Transformer;
use App\Models\Zones\Zone;
use App\Models\Members\Member;
use League\Fractal\TransformerAbstract;

class ZoneTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'zoneGroups',
    ];

    /**
     * @param \App\Models\Zones\Zone $zone
     * @return array
     */
    public function transform(Zone $zone) {
        $status = ($zone->isActive == 1) ? 'Active' : 'Inactive';
        $dateFormed = ($zone->dateFormed > 0) ? date('d-M-Y', $zone->dateFormed) : 'Not Set';
        //get the member names
        $zoneLeader = ($zone->zoneLeaderId > 0) ? Member::find($zone->zoneLeaderId) : [];
        return array(
            'id' => $zone->id,
            'name' => $zone->name,
            'alias' => $zone->alias,
            'description' => $zone->description,
            'meetingLocation' => $zone->meetingLocation,
            'meetingTime' => $zone->meetingTime,
            'zoneLeaderId' => $zone->zoneLeaderId,
            'isActive' => $zone->isActive,
            'status' => $status,
            'dateFormed' => $dateFormed,
            'groups' => count($zone->zoneGroups),
            'zoneLeader' => (isset($zoneLeader->firstName)) ? $zoneLeader->firstName .' '. $zoneLeader->surname: '',
            'leaderFName' => (isset($zoneLeader->firstName)) ? $zoneLeader->firstName : 'not set',
            'leaderSurname' => (isset($zoneLeader->surname)) ? $zoneLeader->surname : 'not set',
            'leaderInitial' => (isset($zoneLeader->firstName)) ? substr($zoneLeader->firstName, 0, 1) : '',
        );
    }

    public function includeZoneGroups(Zone $zone) {
        $zoneGroups = $zone->zoneGroups;
        return $this->collection($zoneGroups, new ZoneGroupTransformer());
    }

}
