<?php
namespace App\Http\Traits;


trait MaintainerModels{

	
	/*
	* Devuelve un array con nombres y valores 
	* de cada attributo (o columna DB) del modelo eloquent
	* @params Array Key => value; El Key del array debe contener el nombre real de attributo (tal y como sale en la tabla de la DB).
	* El Value del Array debe contener el nuevo nombre en caso de que lo necesite del attributo ( usar el mismo si no lo necesita).
	*/
	public function getModelAttributes(Array $columns){
		$attributes = self::getAttributes();
		$newAttributes = [];
		foreach ($columns as $key => $value) {
			$newAttributes[$value] = $attributes[$key];
		}

		return $newAttributes;
	}
}