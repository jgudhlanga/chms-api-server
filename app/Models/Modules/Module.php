<?php

namespace App\Models\Modules;

use Illuminate\Database\Eloquent\Model;

class Module extends Model {

    protected $fillable = ['name', 'title', 'path', 'description', 'moduleColor', 'moduleIconClass', 'position', 'isActive', 'isStandAlone'];

    public function pages() {
        return $this->hasMany('App\Models\Modules\Page');
    }

    public function addPage(Page $page) {
        return $this->pages()->save($page);
    }

    public function delete() {
        $this->pages()->delete();
        Parent::delete();
    }

}
