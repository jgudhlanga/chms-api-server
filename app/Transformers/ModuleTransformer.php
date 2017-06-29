<?php

namespace App\Transformers;
use App\Models\Modules\Module;
use League\Fractal\TransformerAbstract;

class ModuleTransformer extends TransformerAbstract {

	/* Initialise module relationships i.e oneToMany, ManyToMany relationships */
	protected $defaultIncludes = [
		'pages',
	];
	/**
	 * @param \App\Models\Modules\Module $module
	 * @return an object of transformed data
	 * Transformation allows the customisation of array keys to suit your like e.g. id to module_id
	 */
	public function transform(Module $module) {
		return [
			'id' => $module->id,
			'name' => $module->name,
			'title' => $module->title,
			'path' => $module->path,
			'isActive' => $module->isActive,
			'moduleIconClass' => $module->moduleIconClass,
			'position' => $module->position,
			'description' => $module->description,
		];
	}

	public function includePages(Module $module)
	{
		$pages = $module->pages;
		return $this->collection($pages, new PageTransformer);
	}
}
