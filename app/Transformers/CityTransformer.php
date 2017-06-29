<?php
namespace App\Transformers;
use App\Models\Cities\City;
use League\Fractal\TransformerAbstract;

class CityTransformer extends TransformerAbstract {

    public function transform(City $city) {
        $status = ($city->isActive == 1) ? 'Active' : 'Inactive';
        $province = (isset($city->province)) ? $city->province : [];
        return [
            'id' => $city->id,
            'name' => $city->name,
            'shortName' => $city->shortName,
            'description' => $city->description,
            'provinceId' => $city->province_id,
            'province' => isset($province->name) ? $province->name : '',
            'isActive' => $city->isActive,
            'status' => $status,
        ];
    }

}
