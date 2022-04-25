<?php
function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

   function curl_file_post_contents($wholeUrl, $curlPost = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $wholeUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Content-Length:' . strlen($curlPost)
        ]);

        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }
    
function get_shipping_charges($weight,$country_code=''){
    
    if($country_code==''){
        $country_code = ip_info("Visitor", "Country Code");
    }
    
   
    
            $params = [
            'app_key' => 'Q97p3XaprJwAW3s7GG0VS0WTSs1UzX',
            'app_secret' => 'Y7GSGlr73LEMKYfOKo6gh7jWQctReKe6LTgEzfyP',
        ];//common params about key,secret


        $params['params'] = [
            'country_code' => $country_code,
            'sku_goods_type' => 1,
            'sku_weight' => $weight,
            'sku_height' => '20',
            'sku_width' => '40',
            'sku_length' => '50'
        ];
        $params = json_encode($params);
        $response = curl_file_post_contents('https://superoms.super-ton.com/api/index/calculate_freight', $params);


  /*  
    $curl = curl_init();
    
    
    $paramsJson = '{"country_code":"'.$country_code.'","weight":"'.$weight.'"}';
curl_setopt_array($curl, array(
  CURLOPT_PORT => "8013",
  CURLOPT_URL => "http://login.kjy.cn:8013/webservice/PublicService.asmx/ServiceInterfaceUTF8",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "appToken=5dc79d8d504bf2de5c2adadffb678f4b&appKey=1a312970ed33d69631204d3de498877a1a312970ed33d69631204d3de498877a&serviceMethod=feetrail&paramsJson=".$paramsJson,
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/x-www-form-urlencoded",
    "postman-token: 36fc9ba4-94cb-2239-06c8-da1ec99e698d"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
*/

if ($err) {
  echo "cURL Error #:" . $err;
} else {
    
  $response2= json_decode($response);
  if(isset($response2->data)){
    $data2 = $response2->data;
    
    //echo '<pre>';
   // print_r($data2);
    
    foreach($data2 as $key=>$dd){
        if($dd->ServiceEnName=='GZPOST-SB'){
             $totalcharges = $dd->TotalFee;
             $totalcharges = $totalcharges/6.54+5; // coversion rate 6.54 cny to usd and 5$ domestic shipping
			return number_format((float)$totalcharges, 2, '.', '');
            
        }
    }
    return 0.00;
  }
  else{
      return 0.00;
  }
  
}
//die();
$endpoint = 'http://login.kjy.cn:8013/webservice/PublicService.asmx/ServiceInterfaceUTF8';
 
$body = [
    'appKey'  => '1a312970ed33d69631204d3de498877a1a312970ed33d69631204d3de498877a',
    'appToken' => '5dc79d8d504bf2de5c2adadffb678f4b',
    'serviceMethod' => 'feetrail',
    'paramsJson' => '{"country_code":"'.$country_code.'","weight":"'.$weight.'"}'
];
 
//$body = wp_json_encode( $body );
 
$options = [
    'body'        => $body,
    'headers'     => [
        'Content-Type' => 'application/json',
    ],
    'timeout'     => 60,
    'redirection' => 5,
    'blocking'    => true,
    'httpversion' => '1.0',
    'sslverify'   => false,
    'data_format' => 'body',
];
// return 0.00;
//$urll = wp_remote_post( $endpoint, $options );
//print_r($urll);
}


function weight_add_cart_fee() {

    // Set here your percentage
    $percentage = 0.15;

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    // Get weight of all items in the cart
     //$float = WC_Cart::get_cart_contents_weight();

    $cart_weight = WC()->cart->get_cart_contents_weight();
   // $cart_weight= (int) $cart_weight;
    if($cart_weight>50){
        $cart_weight=50;
    }

    // calculate the fee amount
    $shipping_country = WC()->customer->get_shipping_country();
	if($shipping_country == 'CN'){
		$charges = 5;
	}
	else{
    $charges = get_shipping_charges($cart_weight,$shipping_country);
	}

    // If weight amount is not null, adds the fee calcualtion to cart
    if ( !empty( $cart_weight ) ) { 
        $shipping_note = 'Shipping Charges for Total Weight: '.$cart_weight.'Kg & Country: '.$shipping_country;
        WC()->cart->add_fee( __($shipping_note, 'woocommerce'), $charges, false );
    }
}
add_action( 'woocommerce_cart_calculate_fees','weight_add_cart_fee' );


// NEW CODE 24-feb-22 add admin menu page to add charge to currency converter

add_action('admin_menu', 'charge_currency_exchange');
function charge_currency_exchange()
{
    $page_title = 'Borderless Charges';     $menu_title = 'Borderless Charges Menu';
    $capability = 'manage_options';         $menu_slug = 'charges-settings'; 
    $function   = 'charges_menu_page';
    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url = '', $position = 10 );
}

function charges_menu_page()
{ 
    if(isset($_POST['save_currency_charge'])) {
        update_option('currency_exchange_charge',$_POST['currencyexchange_fee']);
    }
    if(isset($_POST['save_shipping_charge'])) {
        update_option('admin_shipping_charge',$_POST['shipping_fee']);
    }

    $currency_exchange_charge = get_option('currency_exchange_charge');
    $shipping_charge = get_option('admin_shipping_charge');
    ?>
    <table>    
        <form method="POST">
            <tr>
                <td>
                    <label>Currency exchange charge (in % terms)</label>
                </td>
                <td>
                    <input type="number" class="" name="currencyexchange_fee" value="<?= $currency_exchange_charge ?>">
                </td>
                <td>                    
                    <input type="submit" name="save_currency_charge" class="button" value="Save">
                </td>
            </tr>
        </form>
    
        <form method="POST">
            <tr>
                <td>
                    <label>Shipping charge (in % terms)</label>
                </td>
                <td>
                    <input type="number" class="" name="shipping_fee" value="<?= $shipping_charge ?>">
                </td>
                <td>
                    <input type="submit" name="save_shipping_charge" class="button" value="Save">
                </td>
            </tr>
        </form>
    
    </table>
<?php
}

function disable_shipping_calc_on_cart( $show_shipping ) { // Remove shipping from cart
    if( is_cart() ) {
        return false;
    }
    return $show_shipping;
}
add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99 );
