<?php
class ModelConfigApi extends Model {

  public function install() {
    $this->load->model('config/api');
    $this->model_custom_hello->createTable(); 
    $this->load->model('setting/setting');
    $this->model_setting_setting->editSetting('hello', array('hello_status'=>1));
  }

  public function uninstall() {
	  $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "api1`;");
  }
}
?>
