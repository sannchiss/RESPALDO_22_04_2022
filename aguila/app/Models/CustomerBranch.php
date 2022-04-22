<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libs\Rest;
use Log;
use Cache;
use DB;
class CustomerBranch extends Model
{
    protected $guarded = [];

    public static function findByCodeOrCreate($customer_id, $code, $data, $commune) {

        $custumerBranch = Cache::get("customer_branches:{$customer_id}:{$code}:{$data['rut']}");

        $commune = $commune == 'PEÑAFLOR' ? 'PENAFLOR' : $commune;
        $commune = $commune == 'PENALOLEN' ? 'PEÑALOLEN' : $commune;
        $commune = $commune == 'EL SALADO' ? 'CHANARAL' : $commune;
        $commune = $commune == 'PAPOSO' ? 'TALTAL' : $commune;
        $commune = $commune == 'ISLA TEJA' ? 'VALDIVIA' : $commune;
        $commune_id = Commune::whereRaw('UPPER(label) = UPPER(?)', [$commune])->first()->id;
        //direccion para google
        $address = "{$data['address']}, {$commune}, CHILE";
        if(is_null($custumerBranch)){
            $custumerBranch = self::where([   
                    ['rut', '=', $data['rut'] ],
                    ['code', '=', $code],
                    ['customer_id', '=', $customer_id],
                ])->first();
            //creo
            if( is_null($custumerBranch)){
                $geoLocation = self::getLatLonByAddress($address);
                $custumerBranch = self::create(
                    [
                        'rut'         => $data['rut'],
                        'code'        => $code,
                        'customer_id' =>$customer_id,
                        'commune_id'  => $commune_id,
                        'lat'         => $geoLocation['lat'] ?? $data['lat'],
                        'lon'         => $geoLocation['lon'] ?? $data['lon'],
                        'email'       => $data['email'],
                        'label'       => $data['label'],
                        'seller_id'   => $data['seller_id'],
                        'address'     => $data['address'],
                        'phone'       => $data['phone'],
                        'created_at'  => date('Y-m-d H:i:s'),
                        'updated_at'  => date('Y-m-d H:i:s'),
                    ]
                );
            }
        }

        //actualizacion
        if( is_null($custumerBranch->lat) == true || is_null($custumerBranch->lon) == true || 
            $custumerBranch->address != $data['address'] || $custumerBranch->commune_id != $commune_id){

            $geoLocation = self::getLatLonByAddress($address);
            \Log::info("UPDATE ADDRESS ->", [
                   'lat' => ['OLD' => $custumerBranch->lat, 'NEW' => $geoLocation['lat'] ],
                   'lon' => ['OLD' => $custumerBranch->lon, 'NEW' => $geoLocation['lon'] ],
                   'address' => ['OLD' => $custumerBranch->address, 'NEW' => $data['address'] ],
                   'commune_id' =>  ['OLD' =>$custumerBranch->commune_id, 'NEW' => $commune_id ],
                ]
            );
            //actualizacion  si no tiene
            $custumerBranch->lat = $geoLocation['lat'];
            $custumerBranch->lon = $geoLocation['lon'];
            $custumerBranch->address    =  $data['address'];
            $custumerBranch->commune_id =  $commune_id;
            $custumerBranch->save();

          
        }

        if($custumerBranch->seller_id != $data['seller_id']){
            \Log::info("UPDATE SELLER  ->", ['OLD' => $custumerBranch->seller_id, 'NEW' => $data['seller_id'] ]);
            $custumerBranch->seller_id  = $data['seller_id'];
            $custumerBranch->save();

            
        }

        Cache::put("customer_branches:{$customer_id}:{$code}:{$data['rut']}", $custumerBranch, env('CACHE_TIME',30));
        return $custumerBranch;
       
    }

    public static function getLatLonByAddress($address) {
        $data = [
            'address' => $address,
            'key'     => env('MAP_API','')
        ];

        $out = Rest::callApi(
            'GET',
            'https://maps.googleapis.com/maps/api/geocode/json',
            $data,
            false
        );

        if($out['status'] != 'OK'){
            $lat = null;
            $lon = null;
            Log::info($address, [$out]);
            Log::info("No se pudo obtener la geolocalizacon del cliente. ".$address);
        }else {
            $geoLocation = $out['results'][0]['geometry']['location'];
            $lat = $geoLocation['lat'];
            $lon = $geoLocation['lng'];
        }

        return ['lat' => $lat, 'lon' => $lon];
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
}
