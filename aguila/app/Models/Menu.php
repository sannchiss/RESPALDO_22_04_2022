<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;
use Cache;
class Menu extends Model
{
	Use MaintainerModels;
    protected $guarded = [];
    protected static $permissions;
    protected static $originalCollection;

    public function menu()
    {
        return $this->belongsTo('App\Models\Menu');
    }

    public function buildMenu($view, $menu, $parentId = 0) 
	{ 
	  $result = null;
	  foreach ($menu as $item) 
	    if ($item->menu_id == $parentId) { 
	      $result .= "<li class='dd-item nested-list-item' data-order='{$item->order}' data-id='{$item->id}'>
	      <div class='dd-handle nested-list-handle'>
	        <i class='fas fa-arrows-alt'></i>
	      </div>
	      <div class='nested-list-content'>{$item->label}";

	       $result .= view('helpers.actionButtons',[
		                    'show' => ['edit', 'view', 'delete'],
		                    'updateRoute' => "{$view}.edit",
		                    'showRoute'   => "{$view}.show",
		                    'deleteRoute' => "{$view}.destroy",
	                    	'id' 		  => $item->id
	                    ])->render();

	      $result.= "</div>".$this->buildMenu($view, $menu, $item->id) . "</li>"; 
	    } 
	  return $result ?  "\n<ol class=\"dd-list\">\n$result</ol>\n" : null; 
	} 

	public static function builMenuApp() {
		$role_id = \Auth::user()->role_id;
		self::$permissions = RolePermission::getUserPermission($role_id);

		return Cache::rememberForever(
            "menu:{$role_id}",
            function () {
                $menus = self::get();
				return self::getMenuItems($menus,null);
            }
       	);
		
	}

	private static function getMenuItems($menus, $parentId){
		$items = $menus->where('menu_id', $parentId)->sortBy('order');
		$menuString = '';
		foreach ($items as $item) {			
			if($item->route != '#'){
				//Valida
				if(!in_array($item->route, self::$permissions)){
					continue;
				}
			}
			$html =  self::getMenuItems($menus, $item->id);
			$menuString .= view('helpers.menu.items',[
				'item' => $item,
				'html' => $html,
			])->render();

		}

		return $menuString;
	}

	// Getter for the HTML menu builder
	public function getHTML($view, $items)
	{
		return $this->buildMenu($view, $items);
	}
}
