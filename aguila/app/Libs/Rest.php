<?php

namespace App\Libs;

class Rest
{
    public static function callApi($method, $url, $data = false, $SendAsJson = true, $resultIsJson = true)
    {
        $curl = curl_init();
        $json = json_encode($data);

        switch ($method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
                }
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data) {
                    $url = sprintf('%s?%s', $url, http_build_query($data));
                }
        }
        if($SendAsJson){ 
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: '.strlen($json),
            ]);
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        if($resultIsJson){
           $result = json_decode($result,true);
        }
            
        curl_close($curl);
        
        return $result;
        
    }
}
