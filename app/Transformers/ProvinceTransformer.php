<?php
namespace App\Transformers;
use App\Models\Provinces\Province;
use League\Fractal\TransformerAbstract;

class ProvinceTransformer extends TransformerAbstract {

    public function transform(Province $province) {
        $status = ($province->isActive == 1) ? 'Active' : 'Inactive';
        return [
            'id' => $province->id,
            'name' => $province->name,
            'alias' => $province->shortName,
            'description' => $province->description,
            'countryId' => $province->country_id,
            'isActive' => $province->isActive,
            'status' => $status,
        ];
    }

}
