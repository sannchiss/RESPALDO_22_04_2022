<?php

require_once PLUGIN_DIR_PATH . 'traits/configurationTrait.php';
require_once PLUGIN_DIR_PATH . 'lib/RestClient.php';


class createShippingController {

    use configurationTrait;
    
    public function __construct(){
        
        global $wpdb;
        global $table_prefix;
        
        $this->wpdb = $wpdb;
        $this->table_prefix = $table_prefix;
        
    }
    
    public function index($collection){
        

        $params  = configurationTrait::account();


        /**URLs */

        if($params['environment'] == 'PRODUCTION'){
            $url = '';
        }else{
            $url = 'https://lac4-ship-service-v2dev.app.wtcdev2.paas.fedex.com/ship/createShipment';
        }

        /** */

        $request =

        '{
            "credential": {
                "accountNumber": "' .$params['accountNumber']. '",
                "meterNumber": "' .$params['meterNumber']. '",
                "wskeyUserCredential": "' .$params['wskeyUserCredential']. '",
                "wspasswordUserCredential": "' . $params['wskeyPasswordCredential'] . '"
            },
            "shipper": {
                "contact": {
                    "personName": "' .$collection['personNameShipper']. '",
                    "phoneNumber": "' .$collection['phoneNumberShipper']. '",
                    "companyName": "' .$collection['companyNameShipper']. '",
                    "email": "' .$collection['emailShipper']. '",
                    "vatNumber": "' .$collection['vatNumberShipper']. '"
                },
                "address": {
                    "city": "' .$collection['cityShipper'].'",
                    "stateOrProvinceCode": "' .$collection['stateOrProvinceCodeShipper']. '",
                    "postalCode": "' .$collection['postalCodeShipper']. '",
                    "countryCode": "' .$collection['countryCodeShipper']. '",
                    "residential": false,
                    "streetLine1": "' .$collection['addressLine1Shipper']. '",
                    "streetLine2": " ' .$collection['addressLine2Shipper']. '",
                    "taxId": " ' .$collection['taxIdShipper']. '",
                    "ie": "' .$collection['ieShipper']. '"
                }
            },
            "recipient": {
                "contact": {
                    "personName": "' .$collection['personNameRecipient']. '", 
                    "phoneNumber": "' .$collection['phoneNumberRecipient']. '",
                    "companyName": "' .$collection['companyNameRecipient']. '",
                    "email": "' .$collection['emailRecipient']. '",
                    "vatNumber": "' .$collection['vatNumberRecipient']. '"
                },
                "address": {
                    "city": "' .$collection['cityRecipient']. '",
                    "stateOrProvinceCode": "' .$collection['stateOrProvinceCodeRecipient']. '",
                    "postalCode": "' .$collection['postalCodeRecipient']. '",
                    "countryCode": "' .$collection['countryCodeRecipient']. '",
                    "residential": false,
                    "streetLine1": "' .$collection['streetLine1Recipient']. '",
                    "streetLine2": "' .$collection['streetLine2Recipient']. '",
                    "taxId": "' .$collection['taxIdRecipient']. '",
                    "ie": "' .$collection['ieRecipient']. '"
                }
            },
            "shipDate": "'.$collection['orderDate'].'", // Cargar fecha actual dd-mm-yyyy
            "serviceType": "' .$collection['serviceType']. '",
            "packagingType": "' .$collection['packagingType']. '",
            "shippingChargesPayment": {
                "paymentType": "' .$collection['paymentType']. '",
                "accountNumber": "' .$params['accountNumber']. '"
            },
            "labelType": "PNG",
            "requestedPackageLineItems": [
                {
                    "itemDescription": "Ver Nota Fiscal", // Descripcion de Item compra
                    "weight": {
                        "value": "' .$collection['weight']. '",
                        "units": "' .$collection['weightUnits']. '"
                    },
                    "dimensions": {
                        "length": "' .$collection['length']. '",
                        "width": "' .$collection['width']. '",
                        "height": "' .$collection['height']. '",
                        "units": "' .$collection['dimensionUnits']. '"
                    }
                },
                
            ],
            "clearanceDetail": {
                "documentContent": "NON_DOCUMENT",
                "commodities": [
                    {
                        "description": "Ver Nota Fiscal",
                        "countryOfManufacture": "MX",
                        "numberOfPieces": 1,
                        "weight": {
                            "value": "' .$collection['weight']. '",
                            "units": "' .$collection['weightUnits']. '"
                        },
                        "quantity": "' .$collection['numberOfPieces']. '",
                        "quantityUnits": "unit",
                        "unitPrice": {
                            "amount": 0,
                            "currency": "NMP"
                        }
                    }
                ]
            },
            "references": [
                {
                    "customerReferenceType": "CUSTOMER_REFERENCE",
                    "value": "' .$collection['orderNumber']. '"
                }
            ],
            "declaredValue": {
                "amount": 0,
                "currency": "NMP"
            },
            "insuranceValue": {
                "amount": 0,
                "currency": "NMP"
            }
        }';

        

        var_dump($request);





    }
}