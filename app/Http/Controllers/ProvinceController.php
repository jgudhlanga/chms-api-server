<?php

namespace App\Http\Controllers;

use App\Transformers\ProvinceTransformer;
use Auth;
use App\Models\Provinces\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $provinces = Province::all();
        return response()->collection($provinces, new ProvinceTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //return $request;
        $rules = ['name' => ['required']];

        $payload = app('request')->only('name');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            return response()->validationError($validator->errors());
        } else {
            $params = $request->all();
            $params['createdBy'] = Auth::guard()->user()->id;
            $province = new Province($params);
            $province->save();
            if (isset($province->id)) {
                $province = Province::find($province->id);
                return response()->item($province, new ProvinceTransformer())->setStatusCode(200);
            } else {
                return response()->serverError();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $province = Province::find($id);
        if (isset($province->id)) {
            return response()->item($province, new ProvinceTransformer());
        } else {
            return response()->dataNotFound();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
         $province = ($id > 0) ? Province::find($id) : [];
        if (isset($province->id)) {
            $rules = ['name' => ['required']];
            $payload = app('request')->only('name');
            $validator = app('validator')->make($payload, $rules);
            if ($validator->fails()) {
                return response()->validationError($validator->errors());
            } else {
                $params = $request->all();
                $province->update($params);
                return response()->item($province, new ProvinceTransformer())->setStatusCode(200);
            }
        } else {
            return response()->dataNotFound('The province was not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $province = Province::find(id);
        if (isset($province->id)) {
            $province->delete();
            return response()->message('The province was successfully deleted');
        } else {
            return response()->dataNotFound('The intended province was not found');
        }
    }

    /**
     * 
     * @param type $id
     * @param type $activate
     */
    public function activate($id, $activate) {
        $province = ($id > 0) ? Province::find($id) : [];
        if (isset($province->id)) {
            if ((int) $activate == 0 || (int) $activate == 1) {
                $province->update(['isActive' => $activate]);
                if ((int) $activate == 0) {
                    return response()->message('Province successfully deactivated');
                } else {
                    return response()->message('Province successfully activated');
                }
            } else {
                return response()->badRequest();
            }
        } else {
            return response()->dataNotFound('The intended province was not found');
        }
    }
}
