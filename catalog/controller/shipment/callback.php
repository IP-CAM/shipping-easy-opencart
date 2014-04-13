<?php 
class ControllerShipmentCallback extends Controller {
  public function index() {
    $this->language->load('shipment/callback');
  	$this->data['heading_title'] = $this->language->get('heading_title');
    $values = file_get_contents('php://input');
    $output = json_decode($values, true);
    if(isset($output)) {
      $id = $output['shipment']['orders'][0]['external_order_identifier'];
      $output['shipment']['orders']['id'];
      $shipping_id = $output['shipment']['id'];
      $tracking_number = $output['shipment']['tracking_number'];
      $carrier_key = $output['shipment']['carrier_key'];
      $carrier_service_key = $output['shipment']['carrier_service_key'];

      $comment_update = ' Shipment Tracking Number: ' .$tracking_number. '<br/> Carrier Key: ' .$carrier_key. '<br/> Carrier Service Key: ' .$carrier_service_key ;

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

