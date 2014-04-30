<?php 
$values = file_get_contents('php://input');
//Json decode.
$output = json_decode($values, true);

if (file_exists('config.php')) {
	require_once('config.php');
}  
require_once(DIR_SYSTEM . 'startup.php');
// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

if(isset($output)) {
  //Intialize all the order variable.
  $id = $output['shipment']['orders'][0]['external_order_identifier'];
  $shipping_id = $output['shipment']['id'];
  $tracking_number = $output['shipment']['tracking_number'];
  $carrier_key = $output['shipment']['carrier_key'];
  $carrier_service_key = $output['shipment']['carrier_service_key'];
  $external_order_identifier = $output['shipment']['orders'][0]['external_order_identifier'];
  $shipment_cost_cents = $output['shipment']['shipment_cost'];
  $shipment_cost = ($shipment_cost_cents / 100);
  $shipping_text = '$' . $shipment_cost;

  //Update opencart order table
  $db->query("UPDATE `" . DB_PREFIX . "order_total` SET value = '$shipment_cost' , text = '$shipping_text' WHERE order_id = '$id' && code = 'shipping'");

  $sql = 'SELECT title , value FROM '.DB_PREFIX.'order_total WHERE order_id ='. $id  ;
  $query = $db->query($sql);
  $total = 0;
  $count_ = count($query->rows);
  $i = 1;
  foreach($query->rows as $k  ){
    if( $count_ == $i ){
      break;
    }
  $total += $k['value'];
  $i++;
  }

  $db->query("UPDATE `" . DB_PREFIX . "order_total` SET value = '$total' , text = '$total' WHERE order_id = '$id' && code = 'total'");

  //Update order comment.
  $comment_update = ' Shipment Tracking Number: ' .$tracking_number. '<br/> Carrier Key: ' .$carrier_key. '<br/> Carrier Service Key: ' .$carrier_service_key. '<br/> Shipment Cost: ' .$shipment_cost; ;

  $db->query("UPDATE `" . DB_PREFIX . "order_history` SET comment = '$comment_update', order_status_id = '5' WHERE order_id = '$id'");
  $db->query("UPDATE `" . DB_PREFIX . "order_history` SET order_status_id = '5' WHERE order_id = '$id'");

  echo 'Order has been updated successfully!';
}
else {
  echo 'Something went wrong with update';
}
?>
