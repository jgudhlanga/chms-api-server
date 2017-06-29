<?php
namespace App\Transformers;
use App\Transformer;
use App\Models\Modules\Page;
use League\Fractal\TransformerAbstract;
class PageTransformer extends TransformerAbstract{

	/**
	 * @param \App\Models\Modules\Page $page
	 * @return array
	 * With transformers you can output calculated operation value such date calculations etc or output custom fields
	 * which are not in the database;
	 */
	public function transform(Page $page)
	{
		return [
			'id' => $page->id,
			'name' => $page->name,
			'title' => $page->title,
			'path' => $page->path,
			'description' => $page->description,
			'module_id' => $page->module_id,
			'icon' => $page->icon,
			'pageIconClass' => $page->pageIconClass,
			'position' => $page->position,
			'isActive' => $page->isActive,
		];
	}
}