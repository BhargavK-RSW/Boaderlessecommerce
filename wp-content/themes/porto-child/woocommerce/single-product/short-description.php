<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

if ( ! $short_description ) {
	return;
}

?>
<div class="woocommerce-product-details__short-description">
	<?php echo $short_description; // WPCS: XSS ok.
	
	?>
	<?php

$endpoint = 'http://login.kjy.cn/webservice/PublicService.asmx/ServiceInterfaceUTF8';
 
$body = [
    'appKey'  => '1a312970ed33d69631204d3de498877a1a312970ed33d69631204d3de498877a',
    'appToken' => '5dc79d8d504bf2de5c2adadffb678f4b',
    'serviceMethod' => 'feetrail',
    'paramsJson' => '{"country_code":"US","weight":"1.5"}'
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
 
//$urll = wp_remote_post( $endpoint, $options );

?>
</div>
