<?php echo $header; ?>
<div id="content">
  <?php echo $this->data['heading_title'] ; 
      $values = file_get_contents('php://input');
$rr = 'test';
$this->db->query("INSERT INTO " . DB_PREFIX . "shipping_request (`values`) VALUES('$values')");



      $output = json_decode($values, true);
      echo "<pre>"; print_r($output); echo "</pre>";
      $id = $output['shipment']['orders']['external_order_identifier'];
      //$output['shipment']['orders']['id'];
      $shipping_id = $output['shipment']['id'];
      $tracking_number = $output['shipment']['tracking_number'];
      $carrier_key = $output['shipment']['carrier_key'];
      $carrier_service_key = $output['shipment']['carrier_service_key'];
      $external_order_identifier = $output['shipment']['orders']['external_order_identifier'];

      $rrr = 'External Order Identifier :' .$external_order_identifier . '<br/> Shipping Tracking Number :' .$tracking_number. '<br/> Carrier Key :' .$carrier_key. '<br/> Carrier Service Key :' .$carrier_service_key ;

      $this->db->query("UPDATE `" . DB_PREFIX . "order_history` SET comment = '$rrr', order_status_id = '3' WHERE order_id = '$id'");
      //$this->db->query("UPDATE `" . DB_PREFIX . "order_history` SET order_status_id = '3' WHERE order_id = '$id'");

?>
</div> 
<?php echo $footer; ?>
