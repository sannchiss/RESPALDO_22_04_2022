<?php

if (!defined('ABSPATH')) {
    die();
}


require_once PLUGIN_DIR_PATH . 'controllers/ordersController.php';

$args = array(
    'created_via' => 'checkout',
);
$orders = wc_get_orders( $args );


include PLUGIN_DIR_PATH . 'views/modal/content/envios.php';
include PLUGIN_DIR_PATH . 'views/modal/content/itemsOrder.php';

?>

<div class="container">

    <div class="row">
        <div class="col-md-12">
            <h1>-</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ordenes de compra</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover shippingList">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>Cliente</th>
                                            <th>Ciudad</th>
                                            <th>Cod.Estado</th>
                                            <th>Orden</th>
                                            <th>Items</th>
                                            <th>Estatus</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($orders as $order) {
                                            ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $order->date_created; ?></td>
                                            <td><?php echo $order->get_billing_first_name(); ?></td>
                                            <td><?php echo $order->get_billing_city(); ?></td>
                                            <td><?php echo $order->get_billing_state(); ?></td>
                                            <td><?php echo $order->get_order_number(); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary position-relative btn-xs itemsOrder"
                                                data-bs-toggle="modal"  data-bs-target="#modal-itemsOrder"
                                                data-order="<?php echo $order->get_order_number(); ?>"
                                                    >
                                                    Orden
                                                    <span
                                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                        <?php echo $order->get_item_count(); ?>
                                                        <span class="visually-hidden">unread messages</span>
                                                    </span>
                                                </button>
                                            </td>
                                            <td><?php
                                            
                                            if($order->post_status == 'wc-pending'){
                                                echo '<span class="badge badge-warning">Pendiente</span>';
                                            }elseif($order->post_status == 'wc-processing'){
                                                echo '<span class="badge bg-success">Procesado</span>';
                                            }elseif($order->post_status == 'wc-on-hold'){
                                                echo '<span class="badge bg-primary">En espera</span>';
                                            }elseif($order->post_status == 'wc-completed'){
                                                echo '<span class="badge bg-success">Completado</span>';
                                            }elseif($order->post_status == 'wc-cancelled'){
                                                echo '<span class="badge bg-danger">Cancelado</span>';
                                            }elseif($order->post_status == 'wc-refunded'){
                                                echo '<span class="badge bg-danger">Reembolsado</span>';
                                            }elseif($order->post_status == 'wc-failed'){
                                                echo '<span class="badge bg-danger">Fallido</span>';
                                            }
                                            
                                             ?>
                                                
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" data-bs-toggle="modal"
                                                    data-bs-target="#modal-order"
                                                    data-order="<?php echo $order->get_order_number(); ?>"
                                                    class="btn rounded-pill btn-outline-primary bg-primary text-white btn-sm order">
                                                    <i class="fas fa-paper-plane"></i></a>
                                                <a href="javascript:void(0);" 
                                                    data-order="<?php echo $order->get_order_number(); ?>"
                                                    class="btn rounded-pill btn-outline-danger bg-danger text-white btn-sm deleteOrder">
                                                    <i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                            $i++;
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </div>


</div>

<script>


</script>