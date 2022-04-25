<?php
// <script
//   src="https://code.jquery.com/jquery-3.6.0.min.js"
//   integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
//   crossorigin="anonymous"></script>



add_action('wp_enqueue_scripts', 'porto_child_css', 1001);
wp_enqueue_script('customscript', get_stylesheet_directory_uri() . '/js/custom.js', array ( 'jquery' ), 1.1, true);
// wp_enqueue_script('jquerycdn', 'https://code.jquery.com/jquery-3.6.0.min.js', array ( 'jquery' ), 3.6, true); // new
 
// Load CSS
function porto_child_css() {
    // porto child theme styles
    wp_deregister_style( 'styles-child' );
    wp_register_style( 'styles-child', get_stylesheet_directory_uri() . '/style.css' );
    wp_enqueue_style( 'styles-child' );
    wp_enqueue_style( 'pagecss', get_stylesheet_directory_uri().'/ali243files/page.css');
    wp_enqueue_style( 'customcss', get_stylesheet_directory_uri().'/custom.css');
    //wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), null, true);
    

    if (is_rtl()) {
        wp_deregister_style( 'styles-child-rtl' );
        wp_register_style( 'styles-child-rtl', get_stylesheet_directory_uri() . '/style_rtl.css' );
        wp_enqueue_style( 'styles-child-rtl' );
    }
}
/**
 * Create a product variation for a defined variable product ID.
 *
 * @since 3.0.0
 * @param int   $product_id | Post ID of the product parent variable product.
 * @param array $variation_data | The data to insert in the product.
 */

function create_product_variation( $product_id, $variation_data ){
    // Get the Variable product object (parent)
    $product = wc_get_product($product_id);
$title = $variation_data['title'];
    $variation_post = array(
        'post_title'  => $title,
        'post_name'   => 'product-'.$product_id.'-variation',
        'post_status' => 'publish',
        'post_parent' => $product_id,
        'post_type'   => 'product_variation',
        'guid'        => $product->get_permalink()
    );
    


    // Creating the product variation
    $variation_id = wp_insert_post( $variation_post );

    // Get an instance of the WC_Product_Variation object
    $variation = new WC_Product_Variation( $variation_id );

// Iterating through the variations attributes
    foreach ($variation_data['attributes'] as $attribute => $term_slug )
    {
      
        //$taxonomy = $attribute; // The attribute taxonomy
      $taxonomy = wc_attribute_taxonomy_name($attribute); // The taxonomy slug
       
 /*
        // If taxonomy doesn't exists we create it (Thanks to Carl F. Corneil)
        if( ! taxonomy_exists( $taxonomy ) ){
            register_taxonomy(
                $taxonomy,
               'product_variation',
                          array(
                            'hierarchical' => false,
                            'label' => ucfirst( $attribute ),
                            'query_var' => true,
                            'rewrite' => array( 'slug' => sanitize_title($attribute) )  // The base slug
                              )
                          );
                 }

        // Check if the Term name exist and if not we create it.
        if( ! term_exists( $term_name, $taxonomy ) )
            wp_insert_term( $term_name, $taxonomy ); // Create the term

        $term_slug = get_term_by('name', $term_name, $taxonomy )->slug; // Get the term slug

        // Get the post Terms names from the parent variable product.
        $post_term_names =  wp_get_post_terms( $product_id, $taxonomy, array('fields' => 'names') );

        // Check if the post term exist and if not we set it in the parent variable product.
        if( ! in_array( $term_name, $post_term_names ) )
            wp_set_post_terms( $product_id, $term_name, $taxonomy, true );
       */
        // Set/save the attribute data in the product variation
        update_post_meta( $variation_id, 'attribute_'.$taxonomy, $term_slug );
       
    }

/*
// example image
$image = $variation_data['image'];

 update_post_meta( $variation_id, '_knawatfibu_url', $image );
 
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');


// magic sideload image returns an HTML image, not an ID
$media = media_sideload_image($image, $variation_id);

// therefore we must find it so we can set it as featured ID
if(!empty($media) && !is_wp_error($media)){
    $args = array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'post_status' => 'any',
        'post_parent' => $variation_id
    );

    // reference new image to set as featured
    $attachments = get_posts($args);

    if(isset($attachments) && is_array($attachments)){
        foreach($attachments as $attachment){
            // grab source of full size images (so no 300x150 nonsense in path)
            $image = wp_get_attachment_image_src($attachment->ID, 'full');
            // determine if in the $media image we created, the string of the URL exists
            if(strpos($media, $image[0]) !== false){
                // if so, we found our image. set it as thumbnail
                set_post_thumbnail($variation_id, $attachment->ID);
                // only want one image
                break;
            }
        }
    }
}
*/
    ## Set/save all other data

    // SKU
   if( ! empty( $variation_data['sku'] ) )
     $variation->set_sku( $variation_data['sku'] );

    // Prices
    if( empty( $variation_data['sale_price'] ) ){
        $variation->set_price( $variation_data['regular_price'] );
    } else {
        $variation->set_price( $variation_data['sale_price'] );
        $variation->set_sale_price( $variation_data['sale_price'] );
    }
    $variation->set_regular_price( $variation_data['regular_price'] );

    // Stock
    if( ! empty($variation_data['stock_qty']) ){
        $variation->set_stock_quantity( $variation_data['stock_qty'] );
        $variation->set_manage_stock(true);
        $variation->set_stock_status('');
    } else {
        $variation->set_manage_stock(false);
    }

    $variation->set_weight(''); // weight (reseting)

    $variation->save(); // Save the data
     WC_Product_Variable::sync( $product_id );
}



function save_product_attribute_from_name( $name, $label='', $set=true ){
    if( ! function_exists ('get_attribute_id_from_name') ) return;

    global $wpdb;

    $label = $label == '' ? ucfirst($name) : $label;
    $attribute_id = get_attribute_id_from_name( $name );

    if( empty($attribute_id) ){
        $attribute_id = NULL;
    } else {
        $set = false;
    }
    $args = array(
        'attribute_id'      => $attribute_id,
        'attribute_name'    => $name,
        'attribute_label'   => $label,
        'attribute_type'    => 'select',
        'attribute_orderby' => 'menu_order',
        'attribute_public'  => 0,
    );


    if( empty($attribute_id) ) {
        $wpdb->insert(  "{$wpdb->prefix}woocommerce_attribute_taxonomies", $args );
        set_transient( 'wc_attribute_taxonomies', false );
    }

    if( $set ){
        $attributes = wc_get_attribute_taxonomies();
        $args['attribute_id'] = get_attribute_id_from_name( $name );
        $attributes[] = (object) $args;
        //print_r($attributes);
        set_transient( 'wc_attribute_taxonomies', $attributes );
    } else {
        return;
    }
}

/**
 * Get the product attribute ID from the name.
 *
 * @since 3.0.0
 * @param string $name | The name (slug).
 */
function get_attribute_id_from_name( $name ){
    global $wpdb;
    $attribute_id = $wpdb->get_col("SELECT attribute_id
    FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
    WHERE attribute_name LIKE '$name'");
    return reset($attribute_id);
}

add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
function custom_override_checkout_fields($fields)
 {
 $fields['billing']['billing_first_name']['label'] = 'Name';
 return $fields;
 }
 
 function shipchange( $translated_text, $text, $domain ) {
switch ( $translated_text ) {
case 'Ship to a different address?' :
$translated_text = __( 'Ship to my personal freight forwarder in China', 'woocommerce' );
break;
case 'Billing details' :
$translated_text = __( 'Shipping Details', 'woocommerce' );
break;
}
return $translated_text;
}

add_filter('gettext', 'shipchange', 20, 3);

add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );

/**
 * Exclude products from a particular category on the shop page
 */
function custom_pre_get_posts_query( $q ) {

    $tax_query = (array) $q->get( 'tax_query' );

    $tax_query[] = array(
           'taxonomy' => 'product_cat',
           'field' => 'slug',
           'terms' => array( 'Shipping','not_for_frontend' ), // Don't display products in the selected category on the shop page.
           'operator' => 'NOT IN'
    );


    $q->set( 'tax_query', $tax_query );

}
add_action( 'woocommerce_product_query', 'custom_pre_get_posts_query' );

function get_subcategory_terms( $terms, $taxonomies, $args ) {
 
	$new_terms 	= array();
	$hide_category 	= array( 320 ); // Ids of the category you don't want to display on the shop page
 	
 	  // if a product category and on the shop page
	if ( in_array( 'product_cat', $taxonomies ) && !is_admin() && is_shop() ) {

	    foreach ( $terms as $key => $term ) {

		if ( ! in_array( $term->term_id, $hide_category ) ) { 
			$new_terms[] = $term;
		}
	    }
	    $terms = $new_terms;
	}
  return $terms;
}
add_filter( 'get_terms', 'get_subcategory_terms', 10, 3 );

 
add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy ();
}


function new_search_form(){

echo '<form id="searchformtop" class="searchform" method="get" name="searchform" action="http://borderlesscommerce.net/taobao-to-borderless-commerce/">';
echo '<div class="input-group input-group-new"><input id="url" class="form-control inputurl" autocomplete="off" name="url" type="text" placeholder="Enter the product link" /><br />';
echo '<select class="api-url" name="api-url" required=""><option value="TAOBAO">TAOBAO</option><option value="1688.com">1688.com</option><option value="PinDuoDuo">PinDuoDuo</option></select><br />';
echo '<button class="btn btn-dark p-2 p-2-new" type="submit"><i class="fas fa-search m-2 m-2-new"></i></button></div>';
echo '</form>';

}

add_filter( 'woocommerce_cart_item_quantity', 'wc_cart_item_quantity', 10, 3 );
function wc_cart_item_quantity( $product_quantity, $cart_item_key, $cart_item ){
    if( is_cart()  ){
        $product_quantity = sprintf( '%2$s <input type="hidden" name="cart[%1$s][qty]" value="%2$s" />', $cart_item_key, $cart_item['quantity'] );
    }
    return $product_quantity;
}

function getProductid($des){
    $taobao=strpos($des,"taobao.com");
    $a1688=strpos($des,"1688.com");

    if($taobao!==false){
        $start = 'item/';
        $end = '.htm';
        $startpos = strpos($des, $start) + strlen($start);
        if (strpos($des, $start) !== false) {
            $endpos = strpos($des, $end, $startpos);
            if (strpos($des, $end, $startpos) !== false) {
                $itemid=substr($des, $startpos, $endpos - $startpos);
                return $itemid;
            }
        }
    }
    else if($a1688!==false){
        $start = 'offer/';
        $end = '.htm';     
        $startpos = strpos($des, $start) + strlen($start);
        if (strpos($des, $start) !== false) {
            $endpos = strpos($des, $end, $startpos);
            if (strpos($des, $end, $startpos) !== false) {
                $itemid=substr($des, $startpos, $endpos - $startpos);
                return 'abb-'.$itemid;
            }
        }
    }
    else{
       return "";
    }
}
function getProductArray($itemId){
    define('CFG_SERVICE_INSTANCEKEY', '9e060d6f-44fc-4864-9078-0ad08b14e99d');
    define('CFG_REQUEST_LANGUAGE', 'en');

    $link = 'http://otapi.net/OtapiWebService2.asmx/GetItemFullInfoWithPromotions?instanceKey=' . CFG_SERVICE_INSTANCEKEY . '&language=' . CFG_REQUEST_LANGUAGE . '&itemId=' . $itemId;
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);

    $result = curl_exec($curl);
    if ($result === FALSE) {
        echo "cURL Error: " . curl_error($curl); die();
    }
    $xmlObject = simplexml_load_string($result);

    $json = json_encode($xmlObject);
    $data = json_decode($json,true);
    curl_close($curl);

    if ((string)$xmlObject->ErrorCode !== 'Ok') {
       echo "Error: " . $xmlObject->ErrorDescription; die();
    }
    $product_attributes = $data['OtapiItemFullInfo']['Attributes']['ItemAttribute'];
    $MasterQuantity=$data['OtapiItemFullInfo']['MasterQuantity'];
    $ConfiguredItems=$data['OtapiItemFullInfo']['ConfiguredItems']['OtapiConfiguredItem'];
    $arr=array();

    if($ConfiguredItems!="" || $ConfiguredItems!=NULL )
    {    
        foreach($ConfiguredItems as $ci){
            $newarr=array();
            foreach($ci['Configurators']['ValuedConfigurator'] as $cic){           
                if($cic['@attributes'])
                {
                    $Ppid=$cic['@attributes']['Pid'];     
                    $Vvid=$cic['@attributes']['Vid'];
                }
                else{
                    $Ppid=$cic['Pid'];     
                    $Vvid=$cic['Vid'];
                }
                foreach($product_attributes as $pa){
                    if($pa['@attributes'])
                    {
                        $Pid=$pa['@attributes']['Pid'];     
                        $Vid=$pa['@attributes']['Vid'];
                        $PropertyName=$pa['PropertyName']; 
                        $Value=$pa['Value'];
                    }
                    else{
                        $Pid=$pa['Pid'];     
                        $Vid=$pa['Vid'];
                        $PropertyName=$pa['PropertyName']; 
                        $Value=$pa['Value'];
                    }
                    if(($Ppid==$Pid) && ($Vvid==$Vid)){
                        $newarr[$PropertyName]=$Value;
                    }
                    else{
                        //array_push($arr,1);  
                    }            
                }
                //echo $cic['Pid'];
            }
            $newarr['Id']=$ci['Id'];
            array_push($arr,$newarr);
        }    
    }
    return $arr;
}
function getVariation($des){
    $text=stristr($des,"{");
    $text2=strip_tags($text);
    $json_arr = json_decode($text2, true);
    return $json_arr;
}
function getProductVariation($getVariationArray, $getProductDetails){
    $cart_arr=$getVariationArray;
    $actual_arr=$getProductDetails;

    foreach ($cart_arr['att'] as $key=>$value){
        foreach ($actual_arr as $keyy =>$valuee){
            $idarry=array_diff_assoc($valuee, $value);
            if (array_key_exists("Id",$idarry) && sizeof($idarry)==1){
                $id_original=$idarry['Id'];
            }
        }
    }
    return $id_original;
}
function APIAutoAddOrder( $order_id ) {
    define('INSTANCEKEY', '9e060d6f-44fc-4864-9078-0ad08b14e99d');
    define('LANGUAGE', 'en');
    define('SIGNATURE', '');
    define('TIMESTAMP', '');
    define('SESSIONID', '323ce6e5-29fa-4e43-ba78-96fa4225af60');
    $order = wc_get_order( $order_id );

    $html='<OrderAddData>
        <DeliveryModeId>ChinPost</DeliveryModeId>        
        <Comment>Need special package</Comment>
        <UserProfileId>2574758</UserProfileId>                  
        <Items>';

    foreach( $order->get_items() as $item_id => $item ){
        $product = $item->get_product();
        $quantity = $item->get_quantity();
        $des= $product->get_description();

        $itemId=getProductid($des);
        $getVariations=getVariation($des);
        $getProductDetails=getProductArray($itemId);
        $ProductVariation=getProductVariation($getVariations, $getProductDetails);
        $html.='<Item>
                    <Id>'.$itemId.'</Id>             
                    <ConfigurationId>'.$ProductVariation.'</ConfigurationId>    
                    <Quantity>'.$quantity.'</Quantity>
                </Item>';
    }
    $html.='</Items>
    </OrderAddData>';
    $link = 'http://otapi.net/OtapiWebService2.asmx/AddOrder?instanceKey='.INSTANCEKEY.'&language='.LANGUAGE.'&signature=&timestamp=&sessionId='.SESSIONID.'&xmlAddData='.urlencode($html);  

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);

    $result = curl_exec($curl);
    if ($result === FALSE) {
        echo "cURL Error: " . curl_error($curl); die();
    }
    $xmlObject = simplexml_load_string($result);

    $json = json_encode($xmlObject);
    $data = json_decode($json,true);
    curl_close($curl);

    if ((string)$xmlObject->ErrorCode !== 'Ok') {
       echo "Error: " . $xmlObject->ErrorDescription; die();
    }
}
add_action( 'woocommerce_order_status_processing', 'APIAutoAddOrder', 10, 1 );

require_once( __DIR__ . '/includes/check-cart-item.php');
require_once( __DIR__ . '/includes/extra-functions.php');
remove_filter( 'the_content', 'wptexturize' );

?>