<?php


class ordersController {

    public function __construct(){

        global $wpdb;
        global $table_prefix;

        $this->wpdb = $wpdb;
        $this->table_prefix = $table_prefix;

    }

    public function index(){


       /*  $query = ("SELECT " . $this->table_prefix . "wc_order_stats.order_id, CONCAT( " .
        $this->table_prefix . "wc_customer_lookup.first_name, ' ', " . $this->table_prefix . "wc_customer_lookup.last_name) AS nombre ,
" . $this->table_prefix . "wc_order_stats.date_created,  " . $this->table_prefix . "wc_order_stats.num_items_sold , " .
        $this->table_prefix . "wc_customer_lookup.city, " . $this->table_prefix . "wc_customer_lookup.state , " .
        $this->table_prefix . "wc_order_stats.customer_id FROM " . $this->table_prefix . "wc_order_stats
        INNER JOIN " . $this->table_prefix . "wc_customer_lookup ON
" . $this->table_prefix . "wc_order_stats.customer_id =  " . $this->table_prefix . "wc_customer_lookup.customer_id
INNER JOIN  " . $this->table_prefix . "woocommerce_order_items ON
" . $this->table_prefix . "wc_order_stats.order_id =  " . $this->table_prefix . "woocommerce_order_items.order_id
INNER JOIN  " . $this->table_prefix . "posts ON
" . $this->table_prefix . "wc_order_stats.order_id =  " . $this->table_prefix . "posts.ID
WHERE  (" . $this->table_prefix . "posts.post_status = 'wc-processing' OR " . $this->table_prefix . "posts.post_status = 'wc-on-hold' OR " . $this->table_prefix . "posts.post_status = 'wc-completed'
 )
  GROUP BY  " . $this->table_prefix . "wc_order_stats.order_id ORDER BY  " . $this->table_prefix . "wc_order_stats.order_id DESC");

        $result = $this->wpdb->get_results($query); */


        $args = array(
            'created_via' => 'checkout',
        );
        $orders = wc_get_orders( $args );








        return $orders;


    }

}