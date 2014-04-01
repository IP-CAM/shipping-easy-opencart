<?php
class ControllerCheckoutSuccess extends Controller {

  public function index() {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    if (isset($this->session->data['order_id'])) {
      $orderid = $this->session->data['order_id'];
      //$orderid = 15;
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

      $date = $rates[0]['date_modified'];
      $products = "SELECT * FROM ".DB_PREFIX."order_total WHERE order_id = '$orderid'";
      $products_detail = $this->db->query($products);
      $prices = array();
      foreach($products_detail->rows as $res){
        $prices[] = $res;
      }
      if(isset($prices[2])){
        $total_including_tax = $prices[2]['value'];
      }
      else {
        $total_including_tax = 0.00;
      }
      $total_tax = $prices[1]['value'];
      $total_excluding_tax = $prices[0]['value'];

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
        "notes" => "Please send promptly.",
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
            "postal_code_plus_4" => "1234",
            "country" => "$country",
            "shipping_method" => "$shipping_method",
            "base_cost" => "$total_excluding_tax",
            "cost_excluding_tax" => "$total_excluding_tax",
            "cost_tax" => "$total_tax",
            "base_handling_cost" => "0.00",
            "handling_cost_excluding_tax" => "0.00",
            "handling_cost_including_tax" => "0.00",
            "handling_cost_tax" => "0.00",
            "shipping_zone_id" => "$state",
            "shipping_zone_name" => "$state",
            "items_total" => "1",
            "items_shipped" => "0",
            "line_items" => $this->test($orderid)
          )
        )
      );

      //Call ShippingEasy API to place order.
      $order=new ShippingEasy_Order($storeapi,$values);
      $order->create();

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

    $temp = array();
    $data = "SELECT * FROM ".DB_PREFIX."order_product WHERE order_id = '$orderid'";
    $result = $this->db->query($data);
    $data_detail = array();
    foreach($result->rows as $res){
      $data_detail[] = $res;
    }

    $count = count($data_detail);
    for($i=0 ; $i<$count ; $i++) {
      $item_tax = $data_detail[$i]['tax'];
      $item_tax = number_format($item_tax, 2);
      $item_price = $data_detail[$i]['price'];
      $item_prices = number_format($item_price, 2);
      $b = str_replace( ',', '', $item_prices);
      $item_name = $data_detail[$i]['name'];
      $item_quantity = $data_detail[$i]['quantity'];
      $item_product_id = $data_detail[$i]['product_id'];
      $data_sku = "SELECT sku , weight FROM ".DB_PREFIX."product WHERE product_id = '$item_product_id'";
      $result_sku = $this->db->query($data_sku);

      foreach($result_sku->rows as $res_sku){
        $sku_value = $res_sku['sku'];
        $weight = $res_sku['weight'];
      }
      $temp[] = array(
		    "item_name" => "$item_name",
		    "sku" => "$sku_value",
		    "bin_picking_number" => "7",
		    "unit_price" => "$b",
		    "total_excluding_tax" => "$item_tax",
		    "weight_in_ounces" => "$weight",
		    "quantity" => "$item_quantity",
		  );
    }
  return $temp;
  }
} ?>
