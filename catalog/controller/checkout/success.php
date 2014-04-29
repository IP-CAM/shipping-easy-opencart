<?php
class ControllerCheckoutSuccess extends Controller {
private $error = array();
  public function index() {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    if (isset($this->session->data['order_id'])) {
      $orderid = $this->session->data['order_id'];
      $shippingzone_id = $this->session->data['shipping_zone_id'];

      //Include shippingeasy file.
      include ('shipping_easy-php/lib/ShippingEasy.php');
      //Fetch API Key , Secret Key, Base URl from E-commerce databse.
      $sql = 'SELECT * FROM '.DB_PREFIX.'api1';
      $query = $this->db->query($sql);
      //Customer API Key.
      $api_key = $query->row['api'];
      //Customer Secret Key.
      $secret_key = $query->row['secretkey'];
      //Base URL.
      $base_url = $query->row['baseurl'];
      //Store API.
			$storeapi = $query->row['storeapi'];

      ShippingEasy::setApiBase($base_url);
      ShippingEasy::setApiKey($api_key);
      ShippingEasy::setApiSecret($secret_key);

      //Check whether all products are downloadable or not.
      $check_download = "SELECT b.tax_class_id , b.price , a.quantity FROM oc_product b , oc_order_product a WHERE b.product_id = a.product_id and a.order_id = '$orderid'";
      $check_downloads = $this->db->query($check_download);
      $products_id = array();
      foreach($check_downloads->rows as $products_id){
        $rr[] = $products_id;
        $products_id_count = count($rr);
        if(in_array(10 , $products_id)) {
          $check_download += 1;
        }
        else {
          $total_orderprice = $products_id['price'] * $products_id['quantity'];
          $total_prices[] = $total_orderprice;
        }
      }
      
      if($products_id_count > $check_download) {

      //Fetch all the values of related order ID.
      $sql = "SELECT * FROM ".DB_PREFIX."order WHERE order_id = '$orderid'";
      $query = $this->db->query($sql);
      $rates = array();
      foreach($query->rows as $result){
        $rates[] = $result;
      }

      $shipping_method = $rates[0]['shipping_method'];
      $first_name = $rates[0]['payment_firstname'];
      $last_name = $rates[0]['payment_lastname'];
      $company = $rates[0]['payment_company'];
      $email = $rates[0]['email'];
      $phone_number = $rates[0]['telephone'];
      $address = $rates[0]['payment_address_1'];
      $address2 = $rates[0]['payment_address_2'];
      $province = $rates[0]['payment_zone'];
      $state = $rates[0]['payment_zone'];
      $city = $rates[0]['payment_city'];
      $postal_code = $rates[0]['payment_postcode'];
      $country = $rates[0]['payment_country'];

      $billing_company = $rates[0]['shipping_company'];
      $billing_first_name = $rates[0]['shipping_firstname'];
      $billing_last_name = $rates[0]['shipping_lastname'];
      $billing_address = $rates[0]['shipping_address_1'];
      $billing_address2 = $rates[0]['shipping_address_2'];
      $billing_city = $rates[0]['shipping_city'];
      $billing_state = $rates[0]['shipping_zone'];
      $billing_postal_code = $rates[0]['shipping_postcode'];
      $billing_country = $rates[0]['shipping_country'];
      $billing_phone_number = $rates[0]['email'];
      $billing_email = $rates[0]['telephone'];
      $comment = $rates[0]['comment'];
      $products = "SELECT * FROM ".DB_PREFIX."order_total WHERE order_id = '$orderid'";
      $products_detail = $this->db->query($products);
      $prices = array();
      foreach($products_detail->rows as $res){
        $prices[] = $res;
      }

      $total_excluding_tax = 0;
      $price_arr_count = count($total_prices);
      for($i=0 ; $i<$price_arr_count ; $i++) {
        $total_excluding_tax += $total_prices[$i];
      }

      $total_tax = floatval($prices[1]['value']);
      $total_including_tax = $total_excluding_tax + $total_tax;

      //Calculate the time.
      $time = time();
      $date = date('Y-m-d H:i:s',$time);
      // die;
      //Creating order array.
      $values = array( "external_order_identifier" => "$orderid",
        "ordered_at" => "$date",
        "order_status" => "awaiting_shipment",
        "subtotal_including_tax" => "$total_including_tax",
        "total_including_tax" => "$total_including_tax",
        "total_excluding_tax" => "$total_excluding_tax",
        "discount_amount" => "0.00",
        "coupon_discount" => "0.00",
        "subtotal_including_tax" => "$total_including_tax",
        "subtotal_excluding_tax" => "$total_excluding_tax",
        "subtotal_excluding_tax" => "$total_excluding_tax",
        "subtotal_tax" => "$total_tax",
        "total_tax" => "$total_tax",
        "base_shipping_cost" => "$total_tax",
        "shipping_cost_including_tax" => "$total_tax",
        "shipping_cost_excluding_tax" => "0.00",
        "shipping_cost_tax" => "$total_tax",
        "base_handling_cost" => "0.00",
        "handling_cost_excluding_tax" => "0.00",
        "handling_cost_including_tax" => "0.00",
        "handling_cost_tax" => "0.00",
        "base_wrapping_cost" => "0.00",
        "wrapping_cost_excluding_tax" => "0.00",
        "wrapping_cost_including_tax" => "0.00",
        "wrapping_cost_tax" => "0.00",
        "notes" => "$comment",
        "billing_company" => "$billing_company",
        "billing_first_name" => "$billing_first_name",
        "billing_last_name" => "$billing_last_name",
        "billing_address" => "$billing_address",
        "billing_address2" => "$billing_address2",
        "billing_city" => "$billing_city",
        "billing_state" => "$billing_state",
        "billing_postal_code" => "$billing_postal_code",
        "billing_country" => "$billing_country",
        "billing_phone_number" => "$billing_phone_number",
        "billing_email" => "test@test.com",
        "recipients" => array(
          array (
            "first_name" => "$first_name",
            "last_name" => "$last_name",
            "company" => "$company",
            "email" => "$email",
            "phone_number" => "$phone_number",
            "residential" => "true",
            "address" => "$address",
            "address2" => "$address2",
            "province" => "$province",
            "state" => "$state",
            "city" => "$city",
            "postal_code" => "$postal_code",
            "postal_code_plus_4" => "",
            "country" => "$country",
            "shipping_method" => "$shipping_method",
            "base_cost" => "$total_excluding_tax",
            "cost_excluding_tax" => "$total_excluding_tax",
            "cost_tax" => "$total_tax",
            "base_handling_cost" => "0.00",
            "handling_cost_excluding_tax" => "0.00",
            "handling_cost_including_tax" => "0.00",
            "handling_cost_tax" => "0.00",
            "shipping_zone_id" => "$shippingzone_id",
            "shipping_zone_name" => "$state",
            "items_total" => "1",
            "items_shipped" => "0",
            "line_items" => $this->test($orderid)
          )
        )
      );
      //Call ShippingEasy API to place order.
      try {
        $order=new ShippingEasy_Order($storeapi,$values);
        $order->create();
      } catch (Exception $e) {
        $this->data['warning'] = $e->getMessage();//$this->language->get('warning');
      }
      }
			$this->cart->clear();

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);	
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}	

		$this->language->load('checkout/success');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array(); 

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/cart'),
			'text'      => $this->language->get('text_basket'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
			'text'      => $this->language->get('text_checkout'),
			'separator' => $this->language->get('text_separator')
		);	

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/success'),
			'text'      => $this->language->get('text_success'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		//$this->data['warning'] = '';

		if ($this->customer->isLogged()) {
			$this->data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
		} else {
			$this->data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$this->data['button_continue'] = $this->language->get('button_continue');

		$this->data['continue'] = $this->url->link('common/home');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
			$this->template = 'default/template/common/success.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'			
		);

		$this->response->setOutput($this->render());
	}

  public function test($orderid){
    //intializing temp array.
    $temp = array();
    //fetch product values from databse. 
    $data = "SELECT * FROM ".DB_PREFIX."order_product WHERE order_id = '$orderid'";
    $result = $this->db->query($data);
    $data_detail = array();
    foreach($result->rows as $res){
      $data_detail[] = $res;
    }
    $total_option = 0; 
    $count = count($data_detail);
    for($i=0 ; $i<$count ; $i++) {
      $item_tax = $data_detail[$i]['tax'];
      $item_tax = number_format($item_tax, 2);
      $item_price = $data_detail[$i]['price'];
      $item_prices = number_format($item_price, 2);
      $unit_price = str_replace( ',', '', $item_prices);
      $item_name = $data_detail[$i]['name'];
      $item_quantity = $data_detail[$i]['quantity'];
      $item_product_id = $data_detail[$i]['product_id'];
      $data_sku = "SELECT sku , weight , weight_class_id , tax_class_id FROM ".DB_PREFIX."product WHERE product_id = '$item_product_id'";
      $result_sku = $this->db->query($data_sku);
      //Check whether product is downloadable / virtual or not.
      if($result_sku->row['tax_class_id'] != 10) {
        foreach($result_sku->rows as $res_sku){
          //product sku , weight and weight class id.
          $sku_value = $res_sku['sku'];
          $weight = $res_sku['weight'];
          $weight = floatval($weight);
          $weight_class_id = $res_sku['weight_class_id'];
        }
        //weight convert to OZ.
        $weight_oz = $this->weight->convert($weight , $weight_class_id , 6);
        //temp array.
        $temp[] = array(
		      "item_name" => "$item_name",
		      "sku" => $sku_value,
		      "bin_picking_number" => 0,
		      "unit_price" => 12,
		      "total_excluding_tax" => $unit_price,
		      "weight_in_ounces" => $weight_oz,
		      "quantity" => $item_quantity,
		    );

        $product_detail1 = "SELECT product_id FROM ".DB_PREFIX."product_option WHERE product_id = '$item_product_id'";
        $product_arr1 = $this->db->query($product_detail1);
        $pr_values1 = array();
        foreach($product_arr1->rows as $product_option_value1){
          $pr_values1[] = $product_option_value1;
        }

        if(!empty($pr_values1)) {
          $product_detail = "SELECT order_product_id FROM ".DB_PREFIX."order_product WHERE product_id = '$item_product_id' AND order_id = '$orderid' limit $total_option,1";
          $product_arr = $this->db->query($product_detail);
          foreach($product_arr->rows as $product_option_value){
            $pr_values = $product_option_value;
          }
          $pr_value = $pr_values['order_product_id'];
          $check_option = "SELECT name , value FROM ".DB_PREFIX."order_option WHERE order_id = '$orderid' AND order_product_id = '$pr_value'";
          $check_options = $this->db->query($check_option);
          $option_values = array();
          $total_num_rows = $check_options->num_rows;
            foreach($check_options->rows as $option_value){
              $option_values[] = $option_value;
            }
            //fetch options values and key.
            for($j=0 ; $j<$total_num_rows ; $j++){
              $option_key = $option_values[$j]['name'];
              $option_arr[$option_key] = $option_values[$j]['value'];
            }
            $option_product['product_options'] = $option_arr;
            foreach($temp as $temp_arraykey => $temp_arrayvalue) {
              $temp_arraykey = $temp_arraykey;
            }
            //update temp array
            $temp[$temp_arraykey] = $temp[$temp_arraykey] + $option_product;
            $total_option += 1;
        }
      }
    }
    //Check whether weight is blank or zero.
    $temp_count = count($temp);
    for($i=0 ; $i<$temp_count ; $i++) {
      if($temp[$i]['weight_in_ounces'] == 0) {
        unset($temp[$i]['weight_in_ounces']);
      }
    }
  return $temp;
  }
} ?>

