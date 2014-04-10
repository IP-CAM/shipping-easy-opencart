<?php echo $header; ?>
<div id="content">
  <?php echo $this->data['heading_title'] ;


  if (isset($success)) { ?>
    <div class="success"><?php echo $success; ?></div>
  <?php } 


  $sql = 'SELECT * FROM '.DB_PREFIX.'api1';
  $query = $this->db->query($sql);
  if($query->num_rows == 0 ) {
    $api = 'Enter your API key';
		$secretkey = 'Enter your Secret key';  
		$baseurl = 'Enter your Base URL';
    $storeapi = 'Enter your store API';
  }
	else {
	  $api = $query->row['api']; 
		$secretkey = $query->row['secretkey'];  
		$baseurl = $query->row['baseurl'];
    $storeapi = $query->row['storeapi'];  
  }?>
					 
  <form action="index.php?route=config/api&token=<?php echo $this->session->data['token']; ?>"" method="post" enctype="multipart/form-data">
    <?php echo $this->data['text_api'] ; ?><br><input type="text" name="apikey" value= "<?php echo $api; ?>"><br><br>
    <?php echo $this->data['text_Secretkey'] ; ?><br><input type="text" name="secretkey" value="<?php echo $secretkey; ?>"><br><br>
    <?php echo $this->data['text_baseurl'] ; ?><br><input type="text" name="baseurl" value="<?php echo $baseurl; ?>"><br><br>
    <?php echo $this->data['text_storeapi'] ; ?><br><input type="text" name="storeapi" value="<?php echo $storeapi; ?>"><br><br>
    <input type="submit" value="Submit" class="button">
  </form>
</div> 
<?php echo $footer; ?>
