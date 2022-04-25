<?php

include wp-load.php;

$title = $_GET['title'];
$imageurl = $_GET['imageurl'];

$price = $_GET['price'];
$productlink = $_GET['productlink'];
$quantity = $_GET['quantity'];

$description = $_GET['content'].' <br/> '.$_GET['productlink'];

$new_post = array(
    'post_title' => $title,
    'post_content' => $description,
    'post_status' => 'publish',
    'post_type' => 'product'
);
 

//$skuu = randomsku('csm','custom',6);
echo $post_id = wp_insert_post($new_post);
$price1 = $price/$quantity;
update_post_meta( $post_id, '_regular_price', $price1 );
update_post_meta( $post_id, '_knawatfibu_url', $imageurl );

if($post_id){
echo $post_id;
global $woocommerce;
$woocommerce->cart->add_to_cart( $product_id, $quantity );
}
?>
