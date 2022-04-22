<?php

/**
 * Plugin Name: FedEx Envios Mexico
 * Plugin URI: https://fedex.com
 * Description: FedEx Envios Chile
 * Version: 1.0
 * Author: Sannchiss Pérez
 * Author URI: https://fedex.com
 * Text Domain: FedexPlugin
 * Domain Path: /lenguages
 * Licence: GPL2
 * 
 * Plugin de Software libre
 *
 * @package FedexPlugin
 */
/**
 * Returns the main instance of WC.
 *
 * @since  1.0
 * @return FedexPlugin
 */

defined('ABSPATH') || exit;

//Constante de la ruta del plugin de nombre: PLUGIN_DIR_PATH
define('PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));

class FedexPlugin
{

    public function __construct()
    {

        global $wpdb;
        global $table_prefix;

        $this->wpdb = $wpdb;
        $this->table_prefix = $table_prefix;
        $this->table_name_configuration = $table_prefix . 'fedex_shipping_intra_MX_configuration';
        $this->table_name_originShipper = $table_prefix . 'fedex_shipping_intra_MX_originshipper';
        $this->table_name_orderDetail = $table_prefix . 'fedex_shipping_intra_MX_orderDetail';


        add_action('admin_menu', array($this, 'fedex_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_ajax_load_configuration', array($this, 'load_configuration'));
        add_action('wp_ajax_save_configuration', array($this, 'save_configuration'));
        add_action('wp_ajax_save_originShipper', array($this, 'save_originShipper'));
        add_action('wp_ajax_create_OrderShipper', array($this, 'create_OrderShipper'));
        add_action('wp_ajax_get_order', array($this, 'get_order'));
        add_action('wp_ajax_get_itemsOrder', array($this, 'get_itemsOrder'));

        $this->required();
        $this->init();

        
    }
   

    public function required()
    {
        require_once PLUGIN_DIR_PATH . 'includes/helpers-menu.php';
        require_once PLUGIN_DIR_PATH . 'includes/helpers-createTables.php';
        require_once PLUGIN_DIR_PATH . 'controllers/createShippingController.php';

        require_once PLUGIN_DIR_PATH . 'includes/checkOut_functions.php';

     
    }  

    public function init()
    {

        $init = new createTables();

        register_activation_hook(__FILE__, array($init, 'configuration'));
        register_activation_hook(__FILE__, array($init, 'originShipper'));
        register_activation_hook(__FILE__, array($init, 'orderSend'));
        register_activation_hook(__FILE__, array($init, 'orderDetail'));



    }

    /**
     * The single instance of the class.
     *
     * @var FedexPlugin
     * 
     * @since 1.0
     *  */

    protected static $_instance = null;

    /**
     * Main FedexPlugin Instance.
     *
     * Ensures only one instance of FedexPlugin is loaded or can be loaded.
     *
     * @since 1.0
     * @static
     * @return FedexPlugin - Main instance.
     */

    public static function instance(){
         if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance; 
    }

    /**
     * Cloning is forbidden.
     *
     * @since 1.0
     */

    public function __clone(){
        _doing_it_wrong(__FUNCTION__, __('No está permitido clonar esta instancia.', 'FedexPlugin'), '1.0');
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0
     */


    public function __wakeup(){
        _doing_it_wrong(__FUNCTION__, __('No está permitido deserializar esta instancia.', 'FedexPlugin'), '1.0');
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since 1.0
     */

    public function load_plugin_textdomain(){
        load_plugin_textdomain('FedexPlugin', false, dirname(plugin_basename(__FILE__)) . '/lenguages');
    }

    /**
     * Register the Fedex Shipping Method.
     *
     * @since 1.0
     */

    public function fedex_shipping_method(){
        require_once PLUGIN_DIR_PATH . 'includes/class-fedex-shipping-method.php';
    }

    /**
     * Add the Fedex Shipping Method to WooCommerce.
     *
     * @since 1.0
     * @param array $methods
     * @return array
     */


     /**
      * función que agrega el menú de configuración
      */

      public function fedex_menu()
      {

        
        $menus = [];

         $menus[] = [
            'pageTitle' => 'FedEx Chile',
            'menuTitle' => 'E-commerce Mexico',
            'capability' => 'manage_options',
            'menuSlug' =>   'FX_MENU',
            'menu_slug'  =>  plugin_dir_path(__FILE__) . 'views/envios.php',  //Ruta absoluta
            'functionName' => null, //LLama a la función
            'iconUrl' => plugin_dir_url(__FILE__) . 'resources/img/Fedex-GroundIconWP.png',  //Ruta absoluta
            'position' => 19
        ];

        addMenusPanel($menus);

        $submenu = [];

        $submenu[] = [
            'parent_slug' => 'FX_MENU', // Slug Padre
            'page_title'  => 'Gestor de Envíos',
            'menu_title'  => 'Gestor de Envíos',
            'capabality'  => 'manage_options',
            'menu_slug'   =>  plugin_dir_path(__FILE__) . 'views/envios.php',  //Ruta absoluta
            'functionName' => '' //Lamado a la funcion
        ];


        $submenu[] = [
            'parent_slug' => 'FX_MENU', // Slug Padre
            'page_title'  => 'Envios confirmados',
            'menu_title'  => 'Envios confirmados',
            'capabality'  => 'manage_options',
            'menu_slug'   =>  plugin_dir_path(__FILE__) . 'view/enviosConfirmados.php',  //Ruta absoluta
            'functionName' => '' //Lamado a la funcion
        ];


        $submenu[] = [
            'parent_slug' => 'FX_MENU', // Slug Padre
            'page_title'  => 'Retiros confirmados',
            'menu_title'  => 'Retiros confirmados',
            'capabality'  => 'manage_options',
            'menu_slug'   =>  plugin_dir_path(__FILE__) . 'view/confirmacion.php',  //Ruta absoluta
            'functionName' => '' //Lamado a la funcion
        ]; 

        $submenu[] = [
            'parent_slug' => 'FX_MENU', // Slug Padre
            'page_title'  => 'Configuración',
            'menu_title'  => 'Configuración',
            'capabality'  => 'manage_options',
            'menu_slug'   =>  plugin_dir_path(__FILE__) . 'views/configuracion.php',  //Ruta absoluta
            'functionName' => '' //Lamado a la funcion
        ];

        addSubMenusPanel($submenu);


      }

     public function enqueue_scripts()
    {

        /**Libreria para mensajes */
        wp_register_script( 'jquery', 'https://code.jquery.com/jquery-3.6.0.js', null, null, true );
        wp_enqueue_script('jquery');

        /**Libreria para mensajes */
        wp_register_script( 'sweetalert2', '//cdn.jsdelivr.net/npm/sweetalert2@11', null, null, true );
        wp_enqueue_script('sweetalert2');


        /**Libreria Bootstrap Js*/
        wp_register_script( 'Bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', null, null, true );
        wp_enqueue_script('Bootstrap');

        /**Libreria para DataTable */
        wp_register_script( 'DataTable', '//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js', null, null, true );
        wp_enqueue_script('DataTable');

        /**Libreria para DataTable */
        wp_register_script( 'DataTableBootstrap', '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js', null, null, true );
        wp_enqueue_script('DataTableBootstrap');

        /**Libreria para DataTable */
        wp_register_script( 'DataTableButtons', '//cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js', null, null, true );
        wp_enqueue_script('DataTableButtons');

       



        wp_enqueue_script(
            'loadConfiguration',
            plugins_url('resources/js/load.js', __FILE__),
            array('jquery'),
            null,
            '1.0',
            true
        );

            

      }

     public function enqueue_styles()
     {

        /**Libreria para iconos Fontawesome */
        wp_register_style( 'Font_Awesome', '//use.fontawesome.com/releases/v5.15.4/css/all.css' );
        wp_enqueue_style('Font_Awesome');

        /**Libreria para Bootstrap Css*/
        wp_register_style( 'Bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' );
        wp_enqueue_style('Bootstrap');

        /**Libreria para DataTable */
        wp_register_style( 'DataTable', '//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css' );
        wp_enqueue_style('DataTable');

        /**Libreria para DataTable */
        wp_register_style( 'DataTableBootstrap', '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css' );
        wp_enqueue_style('DataTableBootstrap');

        wp_register_style( 'stackpath', '//stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css');
        wp_enqueue_style('stackpath');

        
      }

      public function load_configuration(){

        $nonce = sanitize_text_field($_POST['nonce']);

        /* if(!wp_verify_nonce($nonce, 'load_configuration')){
            wp_send_json_error('No tienes permisos para realizar esta acción');
        } */
 
        

         $resultConfig = $this->wpdb->get_results("SELECT * FROM $this->table_name_configuration", ARRAY_A);
         $resultShipper = $this->wpdb->get_results("SELECT * FROM $this->table_name_originShipper", ARRAY_A);


         foreach($resultConfig as $row){

            $configuration['configuration'] = $row;

        }

        foreach($resultShipper as $row){

            $shipper['shipper'] = $row;

        }


         $merged = array_merge( $configuration, $shipper );

        echo json_encode($merged, true);
        die();
 
      }



     public function save_configuration(){

    
       $data = $this->unserializeForm($_POST['inputs']);
    
    
       $collection = array(

           'accountNumber' => $data['accountNumber'],
           'meterNumber' => $data['meterNumber'],
           'wskeyUserCredential' => $data['wskeyUserCredential'],
           'wskeyPasswordCredential' => $data['wskeyPasswordCredential'],
           'serviceType' => $data['serviceType'],
           'packagingType' => $data['packagingType'],
           'paymentType' => $data['paymentType'],
           'labelType' => $data['labelType'],
           'measurementUnits' => $data['measurementUnits'],
           'environment' => $data['environment'],

       );
    

       $sql  = $this->wpdb->get_results("SELECT * FROM ".$this->table_name_configuration." WHERE id = 1");
      

        if(count($sql) > 0){

           $this->wpdb->update($this->table_name_configuration, $collection, array('id' => 1));

           print "Se actualizo la configuración";
           die();

       }else{

           $this->wpdb->insert($this->table_name_configuration, $collection);

           print "Se guardo la configuración";
           die();
        } 
    
       
    }


    public function save_originShipper(){


        $data = $this->unserializeForm($_POST['inputs']);
            
           $collection = array(
            'personNameShipper' => $data['personNameShipper'],
            'phoneShipper' => $data['phoneShipper'],
            'companyNameShipper' => $data['companyNameShipper'],
            'emailShipper' => $data['emailShipper'],
            'vatNumberShipper' => $data['vatNumberShipper'],
            'cityShipper' => $data['cityShipper'],
            'stateOrProvinceCodeShipper' => $data['stateOrProvinceCodeShipper'],
            'postalCodeShipper' => $data['postalCodeShipper'],
            'countryCodeShipper' => $data['countryCodeShipper'],
            'addressLine1Shipper' => $data['addressLine1Shipper'],
            'addressLine2Shipper' => $data['addressLine2Shipper'],
            'taxIdShipper' => $data['taxIdShipper'],
            'ieShipper' => $data['ieShipper'],
        );

        $sql  = $this->wpdb->get_results("SELECT * FROM ".$this->table_name_originShipper." WHERE id = 1");

        if(count($sql) > 0){

            $this->wpdb->update($this->table_name_originShipper, $collection, array('id' => 1));

            print "Se actualizaron los datos";
            die();

        }else{

            $this->wpdb->insert($this->table_name_originShipper, $collection);

            print "Datos guardados";
            die();
        }  
 


    }
  

    public function get_order(){


        $orderId = sanitize_text_field($_POST['orderId']);

        $order = wc_get_order($orderId);

        
        $orderData = $order->get_data();


        $orderItems = $order->get_items();


        $orderItemsData = [];

        foreach($orderItems as $item){

            $orderItemsData[] = $item->get_data();

        }

        $orderData['items'] = $orderItemsData;


        /**Detalle de Orden BD */

        $sql = $this->wpdb->get_results("SELECT * FROM $this->table_name_orderDetail WHERE orderNumber = $orderId", ARRAY_A);

        if(count($sql) > 0){

            foreach($sql as $row){

                $orderDetails['orderDetails'] = $row;
    
            }

        }else{
                
                $orderDetails['orderDetail'] = [];
    
            }


        /** */

        /** Función para unir dos array */
        $merge = array_merge($orderData, $orderDetails);


        echo json_encode($merge, true);
        die();

    }



    public function get_itemsOrder(){

        $orderId = sanitize_text_field($_POST['orderId']);

        $order = wc_get_order($orderId);

        $orderItems = $order->get_items();

        $orderItemsData = [];

        foreach($orderItems as $item){

            $orderItemsData[] = $item->get_data();


        }


        echo json_encode($orderItemsData, true);
        die();



    }



    public function create_OrderShipper(){

        $data = $this->unserializeForm($_POST['inputs']);


        $collection = array(

                'orderNumber' =>  $data['orderNumber'],
                'orderDate' =>  $data['orderDate'],
                'totalOrderAmount' =>  '',
                'personNameRecipient' =>  $data['personNameRecipient'], 
                'phoneNumberRecipient' =>  $data['phoneNumberRecipient'], 
                'companyNameRecipient' =>  $data['companyNameRecipient'],
                'vatNumberRecipient' =>  $data['vatNumberRecipient'],
                'emailRecipient' =>  $data['emailRecipient'],
                'cityRecipient' =>  $data['cityRecipient'],
                'notesRecipient' =>  $data['notesRecipient'],
                'stateOrProvinceCodeRecipient' =>  $data['stateOrProvinceCodeRecipient'],
                'postalCodeRecipient' =>  $data['postalCodeRecipient'],
                'countryCodeRecipient' =>  $data['countryCodeRecipient'], 
                'streetLine1Recipient' =>  $data['streetLine1Recipient'], 
                'streetLine2Recipient' =>  $data['streetLine2Recipient'],  
                'serviceType' =>  $data['serviceType'],
                'packagingType' =>  $data['packagingType'],
                'paymentType' =>  $data['paymentType'],
                'labelType' =>  $data['labelType'],
                'measurementUnits' =>  $data['measurementUnits'],
                'numberOfPieces' =>  $data['numberOfPieces'],
                'weight' =>  $data['weight'],
                'weightUnits' =>  $data['weightUnits'],
                'length' =>  $data['length'],
                'width' =>  $data['width'],
                'height' =>  $data['height'],
                'dimensionUnits' =>  $data['dimensionUnits'],
                'masterTrackingNumber' =>  '', 
                'labelBase64' =>  '', 
                'personNameShipper' =>  $data['personNameShipper'], 
                'phoneShipper' =>  $data['phoneShipper'], 
                'companyNameShipper' =>  $data['companyNameShipper'],
                'emailShipper' =>  $data['emailShipper'],
                'vatNumberShipper' =>  $data['vatNumberShipper'],
                'cityShipper' =>  $data['cityShipper'],
                'stateOrProvinceCodeShipper' =>  $data['stateOrProvinceCodeShipper'],
                'postalCodeShipper' =>  $data['postalCodeShipper'],
                'countryCodeShipper' =>  $data['countryCodeShipper'],
                'addressLine1Shipper' =>  $data['addressLine1Shipper'], 
                'addressLine2Shipper' =>  $data['addressLine2Shipper'], 
                'taxIdShipper' =>  $data['taxIdShipper'], 
                'ieShipper' =>  $data['ieShipper'], 
                'status' =>  $data['status'],
                'error' =>  'error', 

        );


        $object = new createShippingController();
        $object->index($collection);




    }


    public function inyection_ws_shipping(){




    }


   public function unserializeForm($form)
    {

        foreach ($form as $key => $value) {

            $fill[$value['name']] = $value['value'];

        } 

         return $fill;


    }





}

/*Instantiate class*/

$GLOBALS['fedexPlugin'] = new fedexPlugin();