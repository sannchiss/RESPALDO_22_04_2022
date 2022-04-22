<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;
use Cache;

class App extends Model
{
    Use MaintainerModels;
	protected $guarded = [];

	public $path;

	public static function findByCode($code) {
    	return Cache::remember(
            "app:{$code}", env('CACHE_TIME',30),
            function () use ($code) {
                return self::where('code', '=', $code)->first();
            }
       	);
    }


    public static function appInfo(){
        $app = self::findByCode('ROUTES_ANDROID');
        
        $path = storage_path("app/public/apps");
        if(!file_exists($path)){
            mkdir($path,0775,true);
        }
        $file = "{$path}/routes_android_{$app->latest_version_code}.apk";
        $url = '';
        if(file_exists($file)){
            $url= asset("storage/apps/routes_android_{$app->latest_version_code}.apk");
        }

        $appArray = $app->toArray();
        $appArray['url'] = $url;
        return $appArray;
    }
}
