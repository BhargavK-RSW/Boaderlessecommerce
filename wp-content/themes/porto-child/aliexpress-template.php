<?php /* Template Name: AliExpress */ 

get_header(); 

$IDfromurl = explode('item/', $_GET['url']);
$IDfromurl1 = explode('.html', $IDfromurl[1]);
if(!empty($IDfromurl1[0])){

$curlopturl = 'https://ali-express1.p.rapidapi.com/product/'.$IDfromurl1[0].'?language=en';
$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => $curlopturl,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => array(
		"x-rapidapi-host: ali-express1.p.rapidapi.com",
		"x-rapidapi-key: 89b301d303mshe4450617c8e1053p16a702jsnc6088edc3ad1"
	),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
    
$data = json_decode($response,true);


$title = $data['pageModule']['title'];
$descriptionurl = $data['descriptionModule']['descriptionUrl'];

$description = $data['pageModule']['description'].'<br />'.file_get_contents($descriptionurl);




$new_post = array(
    'post_title' => $title,
    'post_content' => $description,
    'post_status' => 'publish',
    'post_type' => 'product'
);

//$skuu = randomsku('csm','custom',6);
$post_id = wp_insert_post($new_post);
//update_post_meta($post_id, '_sku', $skuu );
wp_set_object_terms ($post_id, 'variable', 'product_type');

//require_once(ABSPATH . 'wp-admin/includes/media.php');
//require_once(ABSPATH . 'wp-admin/includes/file.php');
//require_once(ABSPATH . 'wp-admin/includes/image.php');




$image = $data['pageModule']['imagePath'];
$imageModule = array();
$imageModule = $data['imageModule']['imagePathList'];

$gallaryimages = array();
if(is_array($imageModule)){
    $galleryimg = array();
    
foreach($imageModule as $imagegallary){
    
    $galleryimg['url'] = $imagegallary;
    $galleryimg['width'] = '640';
    $galleryimg['height'] = '640';
     
   $gallaryimages[] = $galleryimg;
  
}
}
 

 update_post_meta( $post_id, '_knawatfibu_url', $image );
 update_post_meta( $post_id, '_knawatfibu_wcgallary', $gallaryimages);



$skuModule = $data['skuModule']['productSKUPropertyList'];
$product_attributes = array();

foreach($skuModule as $skuMod){
    
   
    
    echo $insideskukey = $skuMod['skuPropertyName'];
    
         $taxonomy = wc_attribute_taxonomy_name($insideskukey); // The taxonomy slug
         $attr_label = ucfirst($insideskukey);  // attribute label name
         $attr_name = ( wc_sanitize_taxonomy_name($insideskukey)); // attribute slug

        // NEW Attributes: Register and save them
        if( ! taxonomy_exists( $taxonomy ) )
            save_product_attribute_from_name( $attr_name, $attr_label );

        $product_attributes[$taxonomy] = array (
            'name'         => $taxonomy,
            'value'        => '',
            'position'     => '',
            'is_visible'   => 1,
            'is_variation' => 1,
            'is_taxonomy'  => 1
        );

    
    foreach($skuMod['skuPropertyValues'] as $skuModkeyv)
    {
        $value = $skuModkeyv['propertyValueDisplayName'];
        
            $valuekey = $skuMod['skuPropertyId'].'_'.$skuModkeyv['propertyValueId'];
    
             $term_name = ucfirst($value);
             $term_slug = $valuekey;

            // Check if the Term name exist and if not we create it.
            if( ! term_exists( $value, $taxonomy ) )
                wp_insert_term( $term_name, $taxonomy, array('slug' => $term_slug ) ); // Create the term
    
            // Set attribute values
          wp_set_post_terms( $post_id, $term_name, $taxonomy, true );
         
   
   
}
    }
    
update_post_meta( $post_id, '_product_attributes', $product_attributes );


/*


 $terms = array();
foreach( $attribute_data['attributes'] as $key => $terms ){
        $taxonomy = wc_attribute_taxonomy_name($key); // The taxonomy slug
        $attr_label = ucfirst($key); // attribute label name
        $attr_name = ( wc_sanitize_taxonomy_name($key)); // attribute slug

        // NEW Attributes: Register and save them
        if( ! taxonomy_exists( $taxonomy ) )
            save_product_attribute_from_name( $attr_name, $attr_label );

        $product_attributes[$taxonomy] = array (
            'name'         => $taxonomy,
            'value'        => '',
            'position'     => '',
            'is_visible'   => 0,
            'is_variation' => 1,
            'is_taxonomy'  => 1
        );

        foreach( $terms as $value ){
            $term_name = ucfirst($value);
            $term_slug = sanitize_title($value);

            // Check if the Term name exist and if not we create it.
            if( ! term_exists( $value, $taxonomy ) )
                wp_insert_term( $term_name, $taxonomy, array('slug' => $term_slug ) ); // Create the term

            // Set attribute values
            wp_set_post_terms( $post_id, $term_name, $taxonomy, true );
        
        
        
        }
        */
        



$skuvariationarr = array();
$skuvariationarr = $data['skuModule']['skuPriceList'];
$variation_data = array();

foreach($skuvariationarr as $skuvariation){
    
    if($skuvariation['skuVal']['isActivity'] == true)
    {
    
    
    $sku_vararray = array();
    $attributesids = explode(':', $skuvariation['skuAttr']);
    $sku_vararray[] = $attributesids[0];
    foreach($attributesids as $attributesid){
        
        $attributeid = explode(';', $attributesid);
        if(!empty($attributeid[1]))
        $sku_vararray[] = $attributeid[1];
        
    }
    
    
    $skupropids = explode(',', $skuvariation['skuPropIds']);
   $i=0;
   // foreach($skupropids as $skupropid){
        
         $variation_attri_data = array();
        
        foreach($sku_vararray as $sku_var){
                            
                        $propertyidrev = $data['skuModule']['productSKUPropertyList'];
                        
                        $id = array_search($sku_var, array_column($propertyidrev, 'skuPropertyId')); 
                        
                         $attributename = $propertyidrev[$id]['skuPropertyName'];
                        
                        $propertyidrev1 = $propertyidrev[$id]['skuPropertyValues'];
                        
                        $id1 = array_search($skupropids[$i], array_column($propertyidrev1, 'propertyValueId')); 
                        
                        $attributevalue = $sku_var.'_'.$skupropids[$i]; //$propertyidrev1[$id1]['propertyValueDisplayName']; 
                        $i++;
                          $variation_attri_data[$attributename] = $attributevalue;
                          
                          if(!empty($propertyidrev1[$id1]['skuPropertyImageSummPath']))
                             $imageinloop = $propertyidrev1[$id1]['skuPropertyImageSummPath'];
                        
                                 }
                                 
                     
                        

                  //     }
                       
       $variation_data['attributes'] = $variation_attri_data;
       $variation_data['sku'] = $skuvariation['skuId'];
       if(empty($skuvariation['skuVal']['skuBigSalePrice']))
       $variation_data['sale_price'] = $skuvariation['skuVal']['actSkuCalPrice'];
       else
       $variation_data['sale_price'] = $skuvariation['skuVal']['skuBigSalePrice'];
       
       $variation_data['regular_price'] = $skuvariation['skuVal']['skuCalPrice'];
       
       $variation_data['stock_qty'] = $skuvariation['skuVal']['availQuantity'];
       $variation_data['image'] = $imageinloop;
   
      create_product_variation( $post_id, $variation_data );    

             
                       
                       
    }

}



update_post_meta( $post_id, '_visibility', 'search' );

$redirecturl = get_permalink( $post_id );

wp_redirect( $redirecturl );

exit;

/*


// The variation data

$variation_data =  array(
    'attributes' => array(
        'size'  => 'S',
        'color' => 'Yellow',
    ),
    'sku'           => '',
    'regular_price' => '23.00',
    'sale_price'    => '',
    'stock_qty'     => 15,
    'image'     => 'https://ae01.alicdn.com/kf/HTB10W6BymBYBeNjy0Feq6znmFXaU/Brand-Clothing-Jacket-Men-Double-sided-Military-Jackets-Coats-Pure-Cotton-Men-s-Jacket-Autumn-Jaqueta.jpg_640x640.jpg',
);

$variation_data1 =  array(
    'attributes' => array(
        'size'  => 'M',
        'color' => 'Blue',
    ),
    'sku'           => '',
    'regular_price' => '28.00',
    'sale_price'    => '24',
    'stock_qty'     => 13,
    'image'     => 'https://ae01.alicdn.com/kf/HTB1QKeSyeuSBuNjy1Xcq6AYjFXai/Brand-Clothing-Jacket-Men-Double-sided-Military-Jackets-Coats-Pure-Cotton-Men-s-Jacket-Autumn-Jaqueta.jpg_640x640.jpg',
);



$attribute_data =  array(
     'attributes'    => array(
        'Size'   =>  array( 'S', 'M' ),
        'Color'   =>  array( 'Blue', 'Green', 'Yellow' )
    ));

  // $product_attributes = array();

    

// The function to be run
if(create_product_variation( $post_id, $variation_data ))
{
    echo "varitian done ";
}
*/

}


}
else
{
    echo 'Sorry, Entered URL product code can not recognize. Please try again';
}

get_footer(); ?>