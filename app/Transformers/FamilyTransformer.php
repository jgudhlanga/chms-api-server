<?php

namespace App\Transformers;

use App\Models\Families\Family;
use App\Models\Members\Member;
use League\Fractal\TransformerAbstract;

class FamilyTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'familyMembers',
    ];

    /**
     * @param \App\Models\Families\Family $family
     * @return array
     */
    public function transform(Family $family) {
        $status = ($family->isActive == 1) ? 'Active' : 'Inactive';
        //get the member names
        $familyLeader = ($family->headOfFamilyId > 0) ? Member::find($family->headOfFamilyId) : [];
        return array(
            'id' => $family->id,
            'name' => $family->name,
            'alias' => $family->alias,
            'description' => $family->description,
            'headOfFamilyId' => $family->headOfFamilyId,
            'location' => $family->location,
            'isActive' => $family->isActive,
            'status' => $status,
            'membership' => count($family->familyMembers),
            'familyHead' => (isset($familyLeader->firstName)) ? $familyLeader->firstName .' '. $familyLeader->surname : '',
            'leaderFName' => (isset($familyLeader->firstName)) ? $familyLeader->firstName : 'not set',
            'leaderSurname' => (isset($familyLeader->surname)) ? $familyLeader->surname : 'not set',
            'leaderInitial' => (isset($familyLeader->firstName)) ? substr($familyLeader->firstName, 0, 1) : '',
        );
    }

    public function includeFamilyMembers(Family $family) {
        $familyMembers = $family->familyMembers;
        return $this->collection($familyMembers, new FamilyMemberTransformer());
    }

}
