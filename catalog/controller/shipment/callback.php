<?php 
class ControllerShipmentCallback extends Controller {
  public function index() {
    $this->language->load('shipment/callback');
  	$this->data['heading_title'] = $this->language->get('heading_title');
    $values = file_get_contents('php://input');
    $output = json_decode($values, true);

    if(isset($output)) {
      $id = $output['shipment']['orders'][0]['external_order_identifier'];
      $shipping_id = $output['shipment']['id'];
      $tracking_number = $output['shipment']['tracking_number'];
      $carrier_key = $output['shipment']['carrier_key'];
      $carrier_service_key = $output['shipment']['carrier_service_key'];
      $external_order_identifier = $output['shipment']['orders'][0]['external_order_identifier'];
      $shipment_cost = $output['shipment']['shipment_cost'];
      $shipping_text = '$' . $shipment_cost;
        
      $this->db->query("UPDATE `" . DB_PREFIX . "order_total` SET value = '$shipment_cost' , text = '$shipping_text' WHERE order_id = '$id' && code = 'shipping'");

      $sql = 'SELECT title , value FROM '.DB_PREFIX.'order_total WHERE order_id ='. $id  ;
      $query = $this->db->query($sql);
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

      $this->db->query("UPDATE `" . DB_PREFIX . "order_total` SET value = '$total' , text = '$total' WHERE order_id = '$id' && code = 'total'");

      $comment_update = ' Shipment Tracking Number: ' .$tracking_number. '<br/> Carrier Key: ' .$carrier_key. '<br/> Carrier Service Key: ' .$carrier_service_key. '<br/> Shipment Cost: ' .$shipment_cost; ;

      $this->db->query("UPDATE `" . DB_PREFIX . "order_history` SET comment = '$comment_update', order_status_id = '5' WHERE order_id = '$id'");
      $this->db->query("UPDATE `" . DB_PREFIX . "order_history` SET order_status_id = '5' WHERE order_id = '$id'");

      //$this->response->setOutput($this->render());
      $this->response->setOutput('Order has been updated successfully!');
    }
    else {
      $this->response->setOutput('Something went wrong with update');
    }
  }
}


