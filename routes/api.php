<?php

use Illuminate\Support\Facades\Route;

/* This auth route is not authorized */
Route::post('auth', 'AuthController@auth');
/* The below routes are engulfed in a middleware that checks if the user is logged in */
Route::group(['middleware' => 'auth:api'], function() {
    //logged in user
    Route::get('authservice/user', 'AuthController@user');

    /* Module Service */
    //list modules
    Route::get('moduleservice/modules', 'ModulesController@index');
    //get a single module
    Route::get('moduleservice/modules/{module}', 'ModulesController@getModule');
    //save a module
    Route::post('moduleservice/modules', 'ModulesController@createModule');
    //update a module
    Route::put('moduleservice/modules/{module}', 'ModulesController@updateModule');
    //delete  a module
    Route::delete('moduleservice/modules/{module}', 'ModulesController@deleteModule');
    //activate - deactivate module
    Route::put('moduleservice/modules/activate/{module}', 'ModulesController@activateModule');
    //order  module
    Route::put('moduleservice/modules/order/{module}', 'ModulesController@orderModule');
    //get page
    Route::get('moduleservice/modules/page/{pageId}', 'ModulesController@getPage');
    //add page
    Route::post('moduleservice/modules/page', 'ModulesController@createPage');
    //update page
    Route::put('moduleservice/modules/page/{pageId}', 'ModulesController@updatePage');
    //delete page
    Route::delete('moduleservice/modules/page/{pageId}', 'ModulesController@deletePage');
    //activate - deactivate page
    Route::patch('moduleservice/modules/page/activate/{pageId}', 'ModulesController@activatePage');
    //order pages
    Route::patch('moduleservice/modules/page/order/{pageId}/{direction}', 'ModulesController@orderPage');

    /* Cell Groups Routes */
    //All cell groups
    Route::get('groupservice/groups', 'CellGroupController@index');
    //single cell group
    Route::get('groupservice/groups/{group}', 'CellGroupController@getCellGroup');
    //save cell group
    Route::post('groupservice/groups', 'CellGroupController@saveCellGroup');
    //update cell group
    Route::put('groupservice/groups/{group}', 'CellGroupController@updateCellGroup');
    //delete cell group
    Route::delete('groupservice/groups/{group}', 'CellGroupController@deleteCellGroup');
    /* add group membership */
    Route::post('groupservice/groups/membership/{group}', 'CellGroupController@storeMembership');
    /* delete group membership */
    Route::delete('groupservice/groups/membership/{id}', 'CellGroupController@deleteMembership');
    //activate - deactivate cell group
    Route::put('groupservice/groups/activate/{group}', 'CellGroupController@activateCellGroup');

    /* Department Routes */
    //All departments
    Route::get('departmentservice/departments', 'DepartmentController@index');
    //single department
    Route::get('departmentservice/departments/{department}', 'DepartmentController@getDepartment');
    //save department
    Route::post('departmentservice/departments', 'DepartmentController@storeDepartment');
    //update department
    Route::put('departmentservice/departments/{department}', 'DepartmentController@updateDepartment');
    //delete department
    Route::delete('departmentservice/departments/{department}', 'DepartmentController@deleteDepartment');
    //activate - deactivate department
    Route::put('departmentservice/departments/activate/{department}', 'DepartmentController@activateDepartment');

    /* Zones Routes */
    //All zones
    Route::get('zoneservice/zones', 'ZoneController@index');
    //single zone
    Route::get('zoneservice/zones/{zone}', 'ZoneController@getZone');
    //save zone
    Route::post('zoneservice/zones', 'ZoneController@saveZone');
    //update zone
    Route::put('zoneservice/zones/{zone}', 'ZoneController@updateZone');
    //delete zone
    Route::delete('zoneservice/zones/{zone}', 'ZoneController@deleteZone');
    /* zone groups */
    Route::post('zoneservice/zones/groups/{zone}', 'ZoneController@storeGroups');
    //activate - deactivate zone
    Route::put('zoneservice/zones/activate/{zone}', 'ZoneController@activateZone');

    /* Families Routes */
    //All Families
    Route::get('familyservice/families', 'FamilyController@index');
    //single family
    Route::get('familyservice/families/{family}', 'FamilyController@show');
    //save family
    Route::post('familyservice/families', 'FamilyController@store');
    //update family
    Route::put('familyservice/families/{family}', 'FamilyController@update');
    //delete family
    Route::delete('familyservice/families/{family}', 'FamilyController@destroy');
    /* family membership */
    Route::post('familyservice/families/membership/{family}', 'FamilyController@storeMembers');
    //activate - deactivate family
    Route::put('familyservice/families/activate/{family}', 'FamilyController@activate');

    /* MEMBERS Routes */
    //get all members
    Route::get('memberservice/members', 'MembersController@index');
    //get single member row
    Route::get('memberservice/members/{memberId}', 'MembersController@getMember');
    //create member
    Route::post('memberservice/members', 'MembersController@createMember');
    //update member
    Route::put('memberservice/members/{memberId}', 'MembersController@updateMember');
    //believers details
    Route::post('memberservice/members/{memberId}/believer', 'MembersController@storeBeliever');
    //create member qualification
    Route::post('memberservice/members/{memberId}/qualification', 'MembersController@createQualification');
    //update member qualification
    Route::put('memberservice/members/qualification/{id}', 'MembersController@updateQualification');
    //delete member qualification
    Route::delete('memberservice/members/qualification/{id}', 'MembersController@deleteQualification');
    //create member skill
    Route::post('memberservice/members/{memberId}/skill', 'MembersController@createSkill');
    //update member skill
    Route::put('memberservice/members/skill/{id}', 'MembersController@updateSkill');
    //delete member skill
    Route::delete('memberservice/members/skill/{id}', 'MembersController@deleteSkill');

    //delete member
    Route::delete('memberservice/members/{memberId}', 'MembersController@deleteMember');

    //profile picture
    Route::post('memberservice/members/{memberId}/profilepicture', 'MembersController@storePicture');

    /* DATA SERVICE */
    //CITIES
    //get all cities
    Route::get('dataservice/cities', 'CityController@index');
    //get one city
    Route::get('dataservice/cities/{id}', 'CityController@show');
    //create city
    Route::post('dataservice/cities', 'CityController@store');
    //update city
    Route::put('dataservice/cities/{id}', 'CityController@update');
    //activate/deactivate city
    Route::put('dataservice/cities/{id}/{activate}', 'CityController@activate');
    //delete city
    Route::delete('dataservice/cities/{id}', 'CityController@destroy');

    //PROVINCE
    //get all provinces
    Route::get('dataservice/provinces', 'ProvinceController@index');
    //get one province
    Route::get('dataservice/provinces/{id}', 'ProvinceController@show');
    //create province
    Route::post('dataservice/provinces', 'ProvinceController@store');
    //update province
    Route::put('dataservice/provinces/{id}', 'ProvinceController@update');
    //activate/deactivate province
    Route::put('dataservice/provinces/{id}/{activate}', 'ProvinceController@activate');
    //delete province
    Route::delete('dataservice/provinces/{id}', 'ProvinceController@destroy');

    /* ROLES */
    //get all the roles;
    Route::get('securityservice/roles', 'RolesController@index');
    //get a role
    Route::get('securityservice/roles/{roleId}', 'RolesController@show');
    //post a role
    Route::post('securityservice/roles', 'RolesController@store');
    //update a role
    Route::put('securityservice/roles/{id}', 'RolesController@update');
    //delete a role
    Route::delete('securityservice/roles/{roleId}', 'RolesController@destroy');
    //activate role
    Route::put('securityservice/roles/{id}/{activate}', 'RolesController@activate');
});
