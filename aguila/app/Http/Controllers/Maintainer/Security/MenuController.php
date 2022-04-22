<?php

namespace App\Http\Controllers\Maintainer\Security;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;
use App\Models\Menu;
use DB;
use Validator;

class MenuController extends Controller
{
	use MaintainerControllers; 
	protected $view = 'maintainer.security.menu';
    protected $model = 'App\Models\Menu';
    protected $storeExcept = [];
    protected $updateExcept = [];
    protected $avoidValidate = [];


    #override
    protected function validationRules($request = null){
        return [
            'icon'  => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'route' => 'required|string|max:255',
        ];
    }


    //@override
    public function index(Request $request)
	{	
		$items 	= Menu::orderBy('order')->get();

		$menu 	= new Menu;
		$menu   = $menu->getHTML($this->view, $items);

		if($request->ajax()){
            return $menu;
        }

		return view("{$this->view}.index",['menu'=>$menu]);
	}

	//@override
    protected function createEditData($data = null){
    	$data = DB::select("
    		WITH RECURSIVE tree_menu as (
				SELECT id, label::text, menu_id, array[id] AS path_info 
				FROM menus 
				WHERE menu_id IS NULL
				UNION ALL 
				SELECT b.id, (p.label ||' > '|| b.label)::text, b.menu_id, p.path_info|| b.id
				FROM menus b
					JOIN tree_menu p on b.menu_id = p.id
				)
				SELECT id, label, path_info FROM tree_menu ORDER BY path_info;
			");
        return ['menus' => $data];
    }

    //Se incluye reordenamiento
    //@override
    public function update(Request $request, $id)
    {
    	$reordering = $request->input('reordering', false);

    	if(!$reordering) { //Save form  flujo normal
	        $model          = $this->model::find($id);
	        $validateFlieds = $this->avoidValidationRules($model, $request);

	        Validator::make($request->all(), $validateFlieds )->validate();

	        $data = $model->update($request->except($this->updateExcept));

	    } else {  //save ajax reordering

	    	$source       = $request->input('source');
		    $destination  = $request->input('destination',null);

		    $item             = Menu::find($source);
		    $item->menu_id  = $destination;  
		    $item->save();

		    $ordering       = $request->input('order');
		    $rootOrdering   = $request->input('rootOrder');

		    if($ordering){
		    	foreach($ordering as $order => $item_id){
		        	if($itemToOrder = Menu::find($item_id)){
		            	$itemToOrder->order = $order;
		            	$itemToOrder->save();
		        	}
		      	}
		    } else {
		    	foreach($rootOrdering as $order => $item_id){
		        	if($itemToOrder = Menu::find($item_id)){
		            	$itemToOrder->order = $order;
		            	$itemToOrder->save();
		        	}
		      	}
		    }

	    	return 'ok';

	    } 
        
    }
}
