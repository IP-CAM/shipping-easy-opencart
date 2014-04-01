<?php

class ControllerConfigApi extends Controller{ 
    public function index(){

      error_reporting(E_ALL);
      ini_set("display_errors", 1);
      $this->language->load('config/api');
      $template="config/api.tpl"; // .tpl location and file
      $this->load->model('config/api');
      $this->template = ''.$template.'';
      $this->children = array(
          'common/header',
          'common/footer'
      );
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['text_message'] = $this->language->get('text_message');
			$this->data['text_api'] = $this->language->get('text_api');
			$this->data['text_Secretkey'] = $this->language->get('text_Secretkey');
			$this->data['text_baseurl'] = $this->language->get('text_baseurl');
			$this->data['text_storeapi'] = $this->language->get('text_storeapi');

      if(!empty($this->request->post)) {
         $apikey = $this->request->post['apikey'];
         $secretkey = $this->request->post['secretkey'];
         $baseurl = $this->request->post['baseurl'];
         $storeapi = $this->request->post['storeapi'];

         $sql = 'SELECT * FROM '.DB_PREFIX.'api1';
         $query = $this->db->query($sql);
         //print_r($query->num_rows); die;
         if($query->num_rows == 0 ) {
           $this->db->query("INSERT INTO " . DB_PREFIX . "api1 (api,secretkey,baseurl,storeapi) VALUES('$apikey','$secretkey','$baseurl','$storeapi')");
         }
         else {
           $this->db->query("UPDATE `" . DB_PREFIX . "api1` SET api = '$apikey', secretkey = '$secretkey', baseurl = '$baseurl', storeapi = '$storeapi' WHERE id = '1'");


            //$this->db->query("UPDATE " . DB_PREFIX . "api1 (api,secretkey,baseurl) VALUES('$apikey','$secretkey','$baseurl')");
         }
	       echo '<div class="success">Success: Form updated successfully.</div>'; 
       }
       $this->response->setOutput($this->render());
    }
}
?>
