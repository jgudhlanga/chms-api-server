<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Cities\City;
use App\Transformers\CityTransformer;
use Auth;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $cities = City::all();
        return response()->collection($cities, new CityTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $rules = ['name' => ['required']];
        $payload = app('request')->only('name');
        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            return response()->validationError($validator->errors());
        } else {
            $params = $request->all();
            $params['createdBy'] = Auth::guard()->user()->id;
            $city = new City($params);
            $city->save();
            if (isset($city->id)) {
                $city = City::find($city->id);
                return response()->item($city, new CityTransformer())->setStatusCode(200);
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
        $city = City::find($id);
        if (isset($city->id)) {
            return response()->item($city, new CityTransformer());
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
         $city = ($id > 0) ? City::find($id) : [];
        if (isset($city->id)) {
            $rules = ['name' => ['required']];
            $payload = app('request')->only('name');
            $validator = app('validator')->make($payload, $rules);
            if ($validator->fails()) {
                return response()->validationError($validator->errors());
            } else {
                $params = $request->all();
                $city->update($params);
                return response()->item($city, new CityTransformer())->setStatusCode(200);
            }
        } else {
            return response()->dataNotFound('The city was not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $city = City::find(id);
        if (isset($city->id)) {
            $city->delete();
            return response()->message('The city was successfully deleted');
        } else {
            return response()->dataNotFound('The intended city was not found');
        }
    }

    /**
     * 
     * @param int $activate
     * @param int $id
     * @return String
     */
    public function activate($activate,$id) {
        $city = ($id > 0) ? City::find($id) : [];
        if (isset($city->id)) {
            if ((int) $activate == 0 || (int) $activate == 1) {
                $city->update(['isActive' => $activate]);
                if ((int) $activate == 0) {
                    return response()->message('City successfully deactivated');
                } else {
                    return response()->message('City successfully activated');
                }
            } else {
                return response()->badRequest();
            }
        } else {
            return response()->dataNotFound('The intended city was not found');
        }
    }
}
