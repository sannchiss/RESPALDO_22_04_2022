<?php


class Controller
{
    public function __construct()
    {
      /*   add_action('woocommerce_shipping_init', array($this, 'init'));
        add_filter('woocommerce_shipping_methods', array($this, 'addMethod')); */
    }

    public function init()
    {
/*         require_once 'FedexShippingIntra_MX.php';
 */    }

    public function addMethod($methods)
    {
/*         $methods['fedex_shipping_intra_MX'] = 'FedexShippingIntra_MX';
        return $methods;
 */    }

}