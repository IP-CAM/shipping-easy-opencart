<?php 
class ControllerShipmentCallback extends Controller {
  public function index() {  
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    $this->language->load('shipment/callback');
    //$template="shipment/callback.tpl"; // .tpl location and file

    $this->template = 'default/template/shipment/callback.tpl';

    $this->children = array(
        'common/header',
        'common/footer'
    );

  	$this->data['heading_title'] = $this->language->get('heading_title');
$this->response->setOutput($this->render());
  }
}

