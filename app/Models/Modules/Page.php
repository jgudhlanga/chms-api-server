<?php

namespace App\Models\Modules;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
	protected $fillable = ['name','path', 'title','description', 'pageColor', 'pageIconClass', 'position', 'isActive'];

	public function module()
	{
		return $this->belongsTo('App\Models\Modules\Module');
	}
}
