<?php
namespace App\Transformers;
use App\Models\Departments\Department;
use App\Models\Members\Member;
use League\Fractal\TransformerAbstract;
class DepartmentTransformer extends TransformerAbstract{
	/**
	 * @param \App\Models\Departments\Department $department
	 * @return array
	 */
        protected $defaultIncludes = ['departmentMembers']; 
	public function transform(Department $department)
	{
		$status = ($department->isActive == 1) ? 'Active' : 'Inactive';
		$dateFormed = ($department->dateFormed > 0) ? date('d-M-Y', $department->dateFormed)  : 'Not Set';
		//get the member names
		$hodNames = ($department->hod > 0) ? Member::find($department->hod) : [];
		return array(
			'id' => $department->id,
			'name' => $department->name,
			'alias' => $department->alias,
			'description' => $department->description,
                        'duties' => $department->duties,
			'hod' => $department->hod,
			'isActive' => $department->isActive,
                        'membership' => count($department->departmentMembers),
			'status' => $status,
			'dateFormed' =>$dateFormed,
                        'hodNames' => (isset($hodNames->firstName)) ? $hodNames->firstName .' '. $hodNames->surname: 'not set',
			'hodFName' => (isset($hodNames->firstName)) ? $hodNames->firstName: 'not set',
			'hodSurname' => (isset($hodNames->surname)) ? $hodNames->surname : 'not set',
			'hodInitial' => (isset($hodNames->firstName)) ? substr($hodNames->firstName, 0, 1) : '',
		);
	}
        
        public function includeDepartmentMembers(Department $department) {
         return $this->collection($department->departmentMembers, new DepartmentMemberTransformer());
    }
}
