<?php

/* Template Name: 1688 to Borderless Commerce */

get_header();
$provider=$_POST['api-url'];

$_SESSION['provider'] = $provider;


define('CFG_SERVICE_INSTANCEKEY', '9e060d6f-44fc-4864-9078-0ad08b14e99d');
define('CFG_REQUEST_LANGUAGE', 'en');
define('CFG_SESSIONID', '');
define('CFG_BLOCKLIST', '');
define('PREFIX1688', 'abb-');
define('CFG_FRAMESIZE', '20'); 
define('CFG_FRAMESIZE_RSW', '50'); // for image search filter only


function getcategory($catid){


$link = 'http://otapi.net/OtapiWebService2.asmx/GetCategoryInfo?instanceKey=' . CFG_SERVICE_INSTANCEKEY
        . '&language=' . CFG_REQUEST_LANGUAGE
        . '&categoryId=' . $catid;
        
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
 
$categoryInfo = $data['OtapiCategory'];
$categoryname=$categoryInfo['Name'];
/*
echo $categoryname." | ";

if($categoryInfo['ParentId']){
    getcategory($categoryInfo['ParentId']);
}
*/   
return $categoryname;

}


function getcategoryrootpath($catid){


$link = 'http://otapi.net/OtapiWebService2.asmx/GetCategoryRootPath?instanceKey=' . CFG_SERVICE_INSTANCEKEY
        . '&language=' . CFG_REQUEST_LANGUAGE
        . '&categoryId=' . $catid;
        
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
 
  
return $data;

}

function getvendorinfo($vendorid){

$link = 'http://otapi.net/OtapiWebService2.asmx/GetVendorInfo?instanceKey=' . CFG_SERVICE_INSTANCEKEY
        . '&language=' . CFG_REQUEST_LANGUAGE
        . '&vendorId=' . $vendorid;
        
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
 
  
return $data['VendorInfo']['PictureUrl'];

}

if(!isset($_GET['url'])){
$url = $_POST['url'];
}
else{
  $url = $_GET['url'];  
}

//https://detail.1688.com/offer/523156164621.html?spm=a260j.12536084.jwu4t0nz.2.6caf2822Fq3wqW

// Validate url
if (filter_var($url, FILTER_VALIDATE_URL)) {
    
     $pattern = "/1688.com/i";
    if(preg_match($pattern, $url))
    {
    preg_match_all('!\d{12}|\d{11}|\d{10}|\d{9}!', $url, $matches);
    $itemId = $matches[0][0];
    }
    else
    {
       echo "<script>
        alert('Error: Please input correct 1688.com product link.');
        window.location.href='https://borderlesscommerce.net/';
        </script>"; 
    }
/*
  $str2 = explode(".html",$url);
  $str3 = explode("offer/",$str2[0]);
  
  $itemId = $str3[1];
   
  $itemId = (isset($itemId)) ? $itemId : "";
  if(!isset($itemId) ||  $itemId==""){
    
echo "<script>
alert('Error: Please input correct 1688.com product link.');
window.location.href='https://borderlesscommerce.net/';
</script>";

}
 */ 
  $link = 'http://otapi.net/OtapiWebService2.asmx/GetItemFullInfoWithPromotions?instanceKey=' . CFG_SERVICE_INSTANCEKEY
          . '&language=' . CFG_REQUEST_LANGUAGE
          . '&itemId='.PREFIX1688.$itemId;
        
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
    echo "<script>
        alert('Error: Product Not Found');
        </script>"; 
    //echo "Error: " . $xmlObject->ErrorDescription; die();
}
 
$itemInfo = $xmlObject->Result->Item;
$itemInfo = $data['OtapiItemFullInfo'];

 $eur_to_usd = get_field( "euro_to_usd_conversion_rate", 143 );

	//exchange rate code start
	//$usd_to_cdf = 1900;
//	$eur_to_cdf = 2000;

/*
include('/home/ali243/public_html/aliexpress/simplehtmldom/simple_html_dom.php');
$html = file_get_html($url);
 $html1 = $html->find('head');
echo $html1[0];

$file = file_get_contents($url);
//$html1 = $html->find('div[class="product-info"]');
$body = $html->find('body');

echo $body[0];
}

*/

?>
<?php 
$PhysicalParameters=$itemInfo['PhysicalParameters'];
$PhysicalParameters=json_encode($PhysicalParameters);
?>
  <header class="header py-0 my-0"></header>
    <section id="single-product-app">
        <div class="container">
            <div class="row">
                <div class="col-md-5 mb-7 mb-md-0">


                     <div class="product-images images">

                  <div class="container my-4">

                    <hr class="my-4">

                    <!--Carousel Wrapper-->
                    <div id="carousel-thumb" class="carousel carousel-fade carousel-thumbnails" data-ride="carousel">
                      <!--Slides-->
                      <div class="carousel-inner" id="gallarybox" role="listbox">
                        <?php

                           
                                          $imageModule = $data['OtapiItemFullInfo']['Pictures']['ItemPicture'];


																					$gi=1;
                                          if(isset($imageModule[0])){
                                            foreach($imageModule as $imagegallary){
                    
                                                ?>
                    
                                            <div id="img_<?php echo $gi; ?>" class="carousel-item<?php if($gi==1){ echo " active";}?>">
                                              <img class="d-block w-100" src="<?php echo $imagegallary['Url']; ?>">
                                            </div>
                                            
                                            
                    
                                            <?php $gi++; } } 
                                            else{
                                                ?>
                                                <div id="img_<?php echo $gi; ?>" class="carousel-item<?php if($gi==1){ echo " active";}?>">
                                              <img class="d-block w-100" src="<?php echo $imageModule['Url']; ?>">
                                            </div>
                                            <?php
                                            }
                                            ?>
                    
                                          </div>
                      <!--/.Slides-->
                      <!--Controls-->
                      <a class="carousel-control-prev" href="#carousel-thumb" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                      </a>
                      <a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                      </a>

									<ul class="gallarythumbs">
										<!--
										<li data-target="#carousel-thumb" data-slide-to="galleryimage_0" class="active">
											<img class="d-block w-100" src="<?php //echo $image; ?>" class="img-fluid">
										</li> -->
										 <?php
										 
										 	$gi=1;
                       if(isset($imageModule[0])){

										 foreach($imageModule as $imagegallary){
												?>

										 <li data-target="#carousel-thumb" data-slides="img_<?php echo $gi; ?>" class="gallarythumb">
											 <img class="d-block w-100" src="<?php echo $imagegallary['Small']; ?>" class="img-fluid">
											</li>

										 <?php $gi++; } } ?>

									</ul>

								</div></div></div>
                  <!--/.Carousel Wrapper-->



						 <!--/.Controls-->

                </div>
                
                 <?php
							  
							  $original_price = $data['OtapiItemFullInfo']['Price']['ConvertedPriceWithoutSign'];
							  $service_fee = get_field( "service_fees", 143 )/100;
							 $payment_gateway_fee = get_field( "payment_gateway_fees", 143 )/100;
							 $exchange_rate = get_field( "exchange_rate", 143 )/100;
                              $product_inspection_fee = get_field( "product_inspection_fees", 143 )/100;
                              $product_consolidation_fee = get_field( "product_consolidation_fees", 143 )/100;
                              $fixed_product_inspection_fee = get_field( "fixed_price_product_inspection_fees", 143 );
                              $fixed_product_consolidation_fee = get_field( "fixed_price_product_consolidation_fees", 143 );
                              $original_amount=$original_price + ($original_price * $service_fee) + ($original_price * $payment_gateway_fee) + ($original_price * $exchange_rate);
                              
                              
                              ?>

                <div class="col-md-7 py-4 bg-gray" id="product-details">
                    <h5 id="product-title" data-src-link="<?php echo $url;?>"><?php echo $itemInfo['Title'] ?></h5>
                    <hr class="mt-2 mb-3">
                    <?php $ranges=$itemInfo['QuantityRanges']['Range'];
                     $buyminimum=$itemInfo['FirstLotQuantity'];
                    if(isset($ranges)){
                        $price_minqty_arr=[];                         
                        foreach($ranges as $range){
                        $batch_price=$range['Price']['ConvertedPriceWithoutSign'];
                        $batch_price=$batch_price + ($batch_price * $service_fee) + ($batch_price * $payment_gateway_fee) + ($batch_price * $exchange_rate);
                        $lot_price=number_format($batch_price, 2);
                        $minqty=$range['MinQuantity'];
                        $price_minqty_arr += [ $minqty => $lot_price ];
                        
                             
                         }
                        // print_r($price_minqty_arr);
                        
                        
                       
                        echo "<table border='2'>";
                    echo "<tr>";
                    echo "<td>Prices</td>";
                    foreach($ranges as $range){
                        $batch_price=$range['Price']['ConvertedPriceWithoutSign'];
                        $batch_price=$batch_price + ($batch_price * $service_fee) + ($batch_price * $payment_gateway_fee) + ($batch_price * $exchange_rate);
                        echo "<td style='color:#ff7300; font-size:30px; font-weight: 700;'>$".number_format($batch_price, 2)."</td>";
                    }
                     echo "</tr>";
                     
                    echo "<tr>";
                    echo "<td>Range of items</td>";
                    $newrange=[];
                    foreach($ranges as $range){
                        array_push($newrange,$range['MinQuantity']);
                        // echo "<td>".$range['MinQuantity']."</td>";
                    }

                      
                         $lastvalue = $newrange[count($newrange)-1];
                         foreach($newrange as $nr){
                         $nextval=next($newrange)-1;
                       
                         if($nr == $lastvalue){
                            echo "<td>&#8805;".$nr."</td>";  
                         }
                         else{
                            echo "<td>".$nr."-".$nextval."</td>"; 
                         }
                        
                       
                    }
                     echo "</tr>";
                    echo "</table>";
                    echo  '<hr class="mt-2 mb-3">';
                    }
                    
                    ?>
       
 
                   
                    
                           

                    <h6 id="product-price" class="mb-2">
                        <span class="font-weight-bold">Price:</span>
                        <span class="formatted-price" id="formatted-price">

                            <?php



                             if(isset($data['OtapiItemFullInfo']['Promotions']['OtapiItemPromotion'][0]))
                                {
                                  $promo_price_arr = $data['OtapiItemFullInfo']['Promotions']['OtapiItemPromotion'][0];
                                }
                                else
                                {
                                  $promo_price_arr = $data['OtapiItemFullInfo']['Promotions']['OtapiItemPromotion'];  
                                }
							
                          if($data['OtapiItemFullInfo']['Promotions'] == true && !empty($promo_price_arr['Price']['ConvertedPriceWithoutSign'])){
							$promo_price = $promo_price_arr['Price']['ConvertedPriceWithoutSign'];
							 $promo_amount=$promo_price + $promo_price * $service_fee + $promo_price * $payment_gateway_fee + $promo_price * $exchange_rate; 
							 ?>
							  
							  <span class="discount-price mr-2"> $ <?php echo round(($promo_amount), 2); ?></span>
							  <del>$ <?php echo round(($original_amount), 2);?></del>
							  <?php
                           }
                           else
                           {
                                ?>
                            <span class="discount-price mr-2"> $ <?php echo round(($original_amount), 2); ?></span>
                            <?php

                           }
							

							?>
                              

                    </span>
                    </h6>
                     <label for="categoryField" class="my-1 mr-2 font-size-14"><strong>Category: </strong><?php echo getcategory($data['OtapiItemFullInfo']['CategoryId']);?></label><br/>

                    <div id="product-variations">





<?php

                    
                    $product_attributes = array();
                    $product_attributes = $data['OtapiItemFullInfo']['Attributes']['ItemAttribute'];
                   // print_r($product_attributes);
                      
									
											$variations=array();
											$nonvariations=array();
											
                      foreach($product_attributes as $proAtt)
                      {
                          
                          if($proAtt['IsConfigurator']=='true')
                          {
                              array_push($variations,$proAtt);   // combine all attribute in array which show variations
                              
                          }
                          if($proAtt['IsConfigurator']=='false')
                          {
                               array_push($nonvariations,$proAtt);   // combine all attribute in array which don't show variations
                          }
  
                      }
                       
                    // print_r($variations);
                    //  echo "<br>";
                    // print_r($nonvariations);
                    //  echo "<br>";
                      
                      function groupify($arr) {
                        $new = array();
                         foreach ($arr as $item) {
                              if (!isset($new[$item['PropertyName']])) {
                                 $new[$item['PropertyName']] = array();
                                }
                            $new[$item['PropertyName']][] = $item;
                            }
                        return $new;
                        }

                        $variationsgroup=groupify($variations);    // combine all attributes having variations in array according to variation name
                       // print_r($variationsgroup);
                       
                        $variation_ordering=array();
                         foreach($variationsgroup as $key){
                              array_push($variation_ordering,$key[0]['@attributes']['Pid']);
                             
                         }
                        // echo "<br>";
                        // echo "<br>";
                        // echo "<br>";
                        // print_r($variation_ordering);
				 
				 
				 
                    $config_items = array();
                    $config_items = $data['OtapiItemFullInfo']['ConfiguredItems']['OtapiConfiguredItem'];
                    $promoconfig=false;
                    if($data['OtapiItemFullInfo']['Promotions'] == true){
                    $promo_config_items = array();
                    if(isset($data['OtapiItemFullInfo']['Promotions']['OtapiItemPromotion'][0])){
                       $promo_config_items = $data['OtapiItemFullInfo']['Promotions']['OtapiItemPromotion'][0]['ConfiguredItems']['Item'];
                    }
                    else
                    {
                      $promo_config_items = $data['OtapiItemFullInfo']['Promotions']['OtapiItemPromotion']['ConfiguredItems']['Item'];  
                    }
                   // print_r($config_items);
                    $promoconfig=true;
                    }
                    
                    
                  if (!$config_items[0])
                  {
                     $config_items=[$config_items] ;
                  }
                  
                  if (!$promo_config_items[0])
                  {
                     $promo_config_items=[$promo_config_items] ;
                  }

                    
                    foreach($config_items as $ci){
                        $customid=array();
   
                        foreach($ci['Configurators']['ValuedConfigurator'] as $c){

                            if($c['@attributes']){
                             $customid[$c['@attributes']['Pid']] =  $c['@attributes']['Vid'];
                           // array_push(,$c['@attributes']['Pid']=>$c['@attributes']['Vid']);
                            }
                            else{
                                 $customid[$c['Pid']] =  $c['Vid'];
                                //array_push($customid,$c['Pid']=>$c['Vid']);
                            }
                        
                        }
                        $c_id='';
                         for($a=0 ; $a<count($variation_ordering) ; $a++){
                                    foreach($customid as $pid=>$vid){
                                        if($variation_ordering[$a]==$pid)
                                         $c_id=$c_id.$vid;
                                    }
                                    
                                }
                      
                        
                        if($promoconfig){
                            foreach($promo_config_items as $pci){
    
                            if($ci['Id']==$pci['Id']){
                                
                                $original1_amount=$ci['Price']['ConvertedPriceWithoutSign'];
                                $original1_amount=round(($original1_amount+ $original1_amount * $service_fee + $original1_amount * $payment_gateway_fee + $original1_amount * $exchange_rate), 2);
                                $promo1_amount=$pci['Price']['ConvertedPriceWithoutSign'];
                                $promo1_amount=round(($promo1_amount+$promo1_amount * $service_fee + $promo1_amount * $payment_gateway_fee + $promo1_amount * $exchange_rate), 2);

                           echo '<input type="hidden" id="'.$c_id.'" data-configid="'.$ci['Id'].'" data-price="'.$original1_amount.'" data-promo-price="'.$promo1_amount.'" data-quantity="'.$ci['Quantity'].'">'; 
  
                               
                            }
                            
                            }
                        }
                        else{
                            
                            $original1_amount=$ci['Price']['ConvertedPriceWithoutSign'];
                            $original1_amount=round(($original1_amount+ $original1_amount * $service_fee + $original1_amount * $payment_gateway_fee + $original1_amount * $exchange_rate), 2);

                           
                           echo '<input type="hidden" id="'.$c_id.'" data-configid="'.$ci['Id'].'" data-price="'.$original1_amount.'" data-quantity="'.$ci['Quantity'].'">';
                            
                        }
                        
                        
                    }
                    
                    
                   

?>   

   <?php
                        
               $prd_i = 1;
                       foreach($variationsgroup as $key => $val){
                        ?>
                        <div class="product-prop">
                            
                        
                            <p class="prop-name mb-1" data-prop-id="14">
                                <strong class="prop-key font-weight-bold"><?php echo $key;?></strong><span class="font-weight-bold">:</span> <small class="prop-val"></small>
                            </p>

                            <ul id="product_var_<?php echo $prd_i;?>" class="list-unstyled prop-values">

                         <?php
                        
                                                 $prd_li = 1;
                        foreach($val as $v)
                          {
                            
                            $idinur = $v['MiniImageUrl'];
                                                        if($idinur){
                            ?>
                           <li id="varimg_<?php echo $prd_li; ?>" class="varimg list-inline-item prop-value<?php if($prd_li == 1){ echo ' selected';}?>" data-prop-pid="<?php echo $v['@attributes']['Pid'];?>" data-prop-vid="<?php echo $v['@attributes']['Vid'];?>" data-name="<?php echo $v['Value'];?>" data-target="#carousel-thumb" data-slides="<?php echo $v['Vid']; ?>" onClick=variationFunction("<?php echo $v['ImageUrl'];?>") data-prop-image="<?php echo $v['ImageUrl'];?>" data-prop-key="<?php echo $key;?>">
                              <a title="<?php echo $v['Value'];?>"><img src="<?php echo $v['MiniImageUrl'];?>" alt="<?php echo $v['Value'];?>"></a>
                            </li>


                          <?php }
                                                        else{
                                                            ?>
                                                             <li class="list-inline-item prop-value <?php if($prd_li == 1){ echo 'selected';}?>" data-prop-pid="<?php echo $v['@attributes']['Pid'];?>" data-prop-vid="<?php echo $v['@attributes']['Vid'];?>" data-name="<?php echo $v['Value'];?>" data-prop-key="<?php echo $key;?>" >
                                                        <span><?php echo $v['Value'];?></span></li>
                                                    <?php   } $prd_li++;
                                                } echo '</ul></div>';  $prd_i++; }?> 
                                                
                        <div id="varquantity" class="form-inline mb-2">

                        <label for="varquantityField" class="my-1 mr-2 font-size-14"><strong>Select Quantity:</strong></label>

												<td class="varproduct-quantity">
														<div id="varproduct-quantity" class="quantity buttons_added">
															<button id="btnminus" type="button" value="-" class="minus">-</button>
																<input type="number" id="varquantityField" class="input-text qty text" step="1" min="0" max="<?php echo $data['OtapiItemFullInfo']['MasterQuantity']; ?>" name="varproductquantity" value="0" title="Qty" size="4" inputmode="numeric" onchange="javascript:change_combo_quantity();">
															<button id="btnplus" type="button" value="+" class="plus">+</button>
														</div>
												</td>
											  <p class="mb-0"><span class="varqty-ability" id="varstockqty" value="<?php echo $data['OtapiItemFullInfo']['MasterQuantity']; ?>" ><?php echo $data['OtapiItemFullInfo']['MasterQuantity']; ?></span> Is available.</p>
                    </div>
                    
                    
                    <div id="selected_combination" class="selected_combination"></div>
                    <div id="selected_combination_price" class="selected_combination_price" style="display:none"></div>
                    <div id="selected_combination_avgprice" class="selected_combination_avgprice" style="display:none"></div>


                                                <?php
                                                /*
                                                 echo "<pre>";
                                                print_r($itemInfo);
                                                $product_weight = $itemInfo['PhysicalParameters']['Weight'];
                                                $product_weight = str_replace(' ', '', $product_weight); // Replaces all spaces with hyphens.
                                               echo $product_weight = preg_replace('/[^.0-9\-]/', '', $product_weight);
                                                
                                                 $product_height = $itemInfo['PhysicalParameters']['Height'];
                                                $product_height = str_replace(' ', '', $product_height); // Replaces all spaces with hyphens.
                                              echo  $product_height = preg_replace('/[^.0-9\-]/', '', $product_height);
                                                
                                                 $product_width = $itemInfo['PhysicalParameters']['Width'];
                                                $product_width = str_replace(' ', '', $product_width); // Replaces all spaces with hyphens.
                                               echo $product_width = preg_replace('/[^.0-9\-]/', '', $product_width);
                                                
                                                 $product_length = $itemInfo['PhysicalParameters']['Length'];
                                                $product_length = str_replace(' ', '', $product_length); // Replaces all spaces with hyphens.
                                              echo  $product_length = preg_replace('/[^.0-9\-]/', '', $product_length);
                                                
                                              echo  $product_class = $itemInfo['PhysicalParameters']['Class'];
                                                
                                               // if($product_weight<0.1 && $product_weight != 0){$product_weight=0.1;}
                                                $shipping_charges = get_shipping_charges($product_weight, $product_height, $product_width, $product_length, $product_class);
                                                
                                                
                                                $product_weight_display_name = $data['OtapiItemFullInfo']['ActualWeightInfo']['DisplayName'];
                                                
                                                $item_attribt = $itemInfo['Attributes']['ItemAttribute'];
                                                $rev_item_atri = array_reverse($item_attribt);
                                                $aer = array_search('weight', array_column($rev_item_atri, 'PropertyName'), true);
                                                $weight_attri = $rev_item_atri[$aer]['Value'];
                                                $weight_attri = str_replace(' ', '', $weight_attri); // Replaces all spaces with hyphens.
                                                $weight_attri = preg_replace('/[^.0-9\-]/', '', $weight_attri);
                                                
                                                //if($weight_attri<0.1 && $weight_attri != 0){$weight_attri=0.1;}
                                                
                                                if($shipping_charges == 0){
                                                   $shipping_charges = get_shipping_charges($weight_attri); 
                                                   $product_weight = $weight_attri;
                                                }
                                                */
                                                if($shipping_charges == 0){
                                       
                     
                                    						$Product_cat_id = $data['OtapiItemFullInfo']['CategoryId'];
                                    						 $rootpathdata = getcategoryrootpath($Product_cat_id);
                                    						 $rootpatharr=$rootpathdata['CategoryInfoList']['Content'] ;
                                    					
                                    						 //echo "<pre>".var_export($rootpatharr, true)."</pre>";

                                    	                 
                                    	                foreach($rootpatharr['Item'] as $skuCat){ 
                                    						 $Ali_cat_id = $skuCat['Id'];
                                    						 $cat_name = $skuCat['Name'];
                                    
                                    						$terms = get_term_by( 'name', $cat_name, 'product_cat', 'ARRAY_A' );
                                    					    $category_id = $terms['term_id'];
                                    
                                    						$k = 0;
                                    						if($category_id && $k == 0){
                                    							$shipp_weight_frm_cat = get_term_meta( $category_id, 'shipping_weight', true);
                                    							$shipp_height_frm_cat = get_term_meta( $category_id, 'shipping_weight', true);
                                    							$shipp_width_frm_cat = get_term_meta( $category_id, 'shipping_weight', true);
                                    							$shipp_length_frm_cat = get_term_meta( $category_id, 'shipping_weight', true);
                                    							$shipp_class_frm_cat = get_term_meta( $category_id, 'shipping_weight', true);
                                    							$categ_n = $cat_name;
                                    							$k++;
                                    						}
                                    
                                    					}
                                                   // if($shipp_weight_frm_cat<0.1 && $shipp_weight_frm_cat != 0){$shipp_weight_frm_cat=0.1;}	
                                                   $shipping_charges = get_shipping_charges($shipp_weight_frm_cat,$shipp_height_frm_cat,$shipp_width_frm_cat,$shipp_length_frm_cat,$shipp_class_frm_cat);
                                                   $product_weight = $shipp_weight_frm_cat;
                                                }
                                                
                                                 if($shipping_charges == 0){
                                                     
                                                     $product_weight = 0;
                                                 }
                                                
                                               // echo '<pre>';
                                          // print_r($data);
                                                ?>
                    <div id="quantity" class="form-inline mb-2">

                        <label for="quantityField" class="my-1 mr-2 font-size-14"><strong>Quantity:</strong></label>

												<td class="product-quantity">
														<div id="product-quantity" class="quantity buttons_added">
															<button type="button" value="-" class="minus">-</button>
																<input type="number" id="quantityField" class="input-text qty text" step="1" min="1" max="<?php echo $data['OtapiItemFullInfo']['MasterQuantity']; ?>" name="productquantity" value="1" title="Qty" size="4" inputmode="numeric" onchange="javascript:get_ins_cons_sku();">
															<button type="button" value="+" class="plus">+</button>
														</div>
												</td>
											  <p class="mb-0"><span class="qty-ability" id="stockqty"><?php echo $data['OtapiItemFullInfo']['MasterQuantity']; ?></span> Is available.</p>
                        
                    </div>
                    <h6 style="color:red; padding-left:80px" id="lowstock" >Stock is low. Not allowed to Buy</h6>
                      <input type="checkbox" id="product_inspection_check">
             <label for="product_inspection"> Product Inspection</label> <label data-price="0" class="additionalcostcheckbox" style="color:red;padding-left:30px" id="product_ins_amount"></label><br>
             <input type="checkbox" id="product_consolidation_check">
             <label for="product_consolidation"> Product Consolidation</label> <label data-price="0" class="additionalcostcheckbox" style="color:red;padding-left:30px"  id="product_cons_amount"></label><br>
                    <div id="aliexpress-shipping-methods">
                        <label for="shippingMethods" class="d-block font-size-14 mb-1"></label>
                        <hr>
                        <?php if($product_weight==0) {?>
                        <h5>Follow two steps to complete the order.</h5>
                        <h6>Step 1: Select services and pay for goods.</h6>
                        <h6>Step 2: Pay the shipping invoice sent to your account 5-15 days after payment goods.</h6>
                        Click <a target="_blank" href="http://www.kuaizi56.com/h5/pages/index/calculate">here</a> to get an estimate.-->
                        <?php } else { ?>
                        <p>
                            Weight for 1 quanitity is: <?php echo $product_weight; ?> Kg <br/>
                            <span class="shipping_charges" id="shipping_charges" data-shiping-cost="<?php echo $shipping_charges; ?>">Estimated Shipping Charges For 1 quanity to your IP country <?php $country_Code = ip_info("Visitor", "Country Name"); echo '<b>'.$country_Code.'</b> is : $ '.$shipping_charges; ?></span>
                            </p>
                           <p><span style="font-size:11px;">*You Can recalculate Shipping Charges on Checkout page accordingly your all products total weight and correct shipping country. </span>
                            </p> 
                            <?php }?>
                        <hr> 
                   
                    </div>
           

                    <h6 id="totalPrice" class="my-2 d-none"></h6>
									<!--	<h6 id="totalshpngPrice" class="my-2 d-none"></h6>-->

                    <div style="color:#ffffff;" class="d-sm-flex flex-cols-wide mt-2 mb-2">

                    <a id="ali243-add-cart" class="btn btn-primary mr-sm-2"><i class="fa fa-cart-plus"></i> Add to cart</a>
                    <a id="ali243-buy-now" class="btn btn-brand"><i class="fa fa-shopping-bag"></i> Buy Now</a>
                    </div>
                    <?php
                    if(isset($buyminimum)){
                     echo '<h6 style="color:red; padding-left:80px" id="minlimit" >Minimum quantity to Buy is : '. $buyminimum.'</h6>';   
                    
                    }
                    ?>
                     
                    <div id="txtHint" class="my-2"></div>
                    <div id="productloader1" class="my-2 d-none"><img src="https://borderlesscommerce.net/wp-content/uploads/2020/09/loader50px.gif" alt="borderless product loader"></div>
										<div id="productloader2" class="my-2 d-none"><img src="https://borderlesscommerce.net/wp-content/uploads/2020/09/loader50px.gif" alt="borderless product loader"></div>
								</div>
            </div>
<?php
/*
						$skuPrice = $data['skuModule']['skuPriceList'];
						foreach($skuPrice as $skuPrc){
							$skuprc_id = explode(",", $skuPrc["skuPropIds"]);
							$skuprcid = "";
							if($skuPrc["skuVal"]["isActivity"]){
							$skuActivityAmount1 = $skuPrc["skuVal"]["skuActivityAmount"];
              $skuActivityAmount = $skuActivityAmount1["value"];
						 $actSkuDisplayBulkPrice = $skuPrc["skuVal"]["actSkuMultiCurrencyBulkPrice"];


										if($skuActivityAmount >= $actSkuDisplayBulkPrice){
											$converttousd = $eur_to_usd*$skuActivityAmount;
											$finalprice = $converttousd + $servicefee + $converttousd*$paymentgatewayfee;
										}
										else{
											 $converttousd = $eur_to_usd*$skuPrc["skuVal"]["actSkuBulkCalPrice"];
											$finalprice = $converttousd + $servicefee + $converttousd*$paymentgatewayfee;
										}
									}
									else{
										$converttousd = $eur_to_usd*$skuPrc["skuVal"]["skuAmount"]["value"];
										$finalprice = $converttousd + $servicefee + $converttousd*$paymentgatewayfee;
									}

							$skuprc_val = $skuPrc["skuId"].'_'.$finalprice;


							foreach($skuprc_id as $sku_prc_id){

								$skuprcid = $skuprcid.'DJB'.$sku_prc_id;
							}


              echo '<input type="hidden" id="'.$skuprcid.'" value="'.$skuprc_val.'">';

						}
*/	
?>

        </div>
    </section>
    <section class="section container py-2">
        <div class="row">
            <div id="single-product-details" class="col-md-12 mx-auto">
              <div class="detailmodule_html">
                <div class="detail-desc-decorate-richtext">
                    <div class="panel-title">Product specifications</div>
                    <ul class="spu-list">
                
                <?php
                 foreach($nonvariations as $proAtt){
                   ?>  
                     <li class="item">
                      <?php
                       echo $proAtt['PropertyName'].' : '.$proAtt['Value'];
                        ?>
                      </li>
                  <?php     
                      
                 }
                        
                 ?>
                </ul>
                </div>
             </div>
           </div>
         </div>
    </section>
    

   

     <?php
    // http://otapi.net/OtapiWebService2.asmx/GetItemOriginalDescription?instanceKey=opendemo&language=en&itemId=624050617942
     
$link = 'http://otapi.net/OtapiWebService2.asmx/GetItemOriginalDescription?instanceKey=' . CFG_SERVICE_INSTANCEKEY
        . '&language=' . CFG_REQUEST_LANGUAGE
        . '&itemId='.PREFIX1688.$itemId;
 
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
$data= json_decode($json,true);
 
curl_close($curl);
 
if ((string)$xmlObject->ErrorCode !== 'Ok') {
    echo "Error: " . $xmlObject->ErrorDescription; die();
}
 
$itemDesc = $data['OtapiItemDescription'];


	?>
	<br>
	 <section class="section container py-2">
	     <div class="panel-title">Product Descriptions</div>
        <div class="row">
           <div id="single-product-details" class="col-md-8 mx-auto">
              <div class="detailmodule_html">
                <div class="detail-desc-decorate-richtext">
                
                <?php
                 echo $itemDesc['ItemDescription'];
                        
                 ?>
                </div>
             </div>
           </div>
         </div>
    </section>
    

<script type="text/javascript">
var gallarythumbs = document.getElementsByClassName("gallarythumb");

var myFunction = function() {
    var attribute = this.getAttribute("data-slides");
		var carouselitems = document.getElementsByClassName("carousel-item active");
		carouselitems[0].className = carouselitems[0].className.replace("active", "");
		document.getElementById(attribute).classList.add("active");
    var imgfromvariation = document.getElementById("imgfromvariation");
        if(imgfromvariation.classList.contains("active")){
         imgfromvariation.classList.remove("active");   
        }
};

for (var i = 0; i < gallarythumbs.length; i++) {
    gallarythumbs[i].addEventListener('click', myFunction, false);
}
function variationFunction(varimgurl) {
    
        var carouselitems = document.getElementsByClassName("carousel-item active");
		carouselitems[0].className = carouselitems[0].className.replace("active", "");
		var imgfromvariation = document.getElementById("imgfromvariation");
		if(imgfromvariation != null){
        imgfromvariation.children[0].setAttribute("src", varimgurl);
	    imgfromvariation.classList.add("active");
         }
         else{
            createimgdiv(varimgurl); 
         }


}

function createimgdiv(varimgurl){

    var containerdiv = document.createElement("div");
    containerdiv.id='imgfromvariation';
    containerdiv.className='carousel-item active';
    var imgelement = document.createElement("img");
    imgelement.className='d-block w-100';
    imgelement.src=varimgurl;

    containerdiv.appendChild(imgelement);
    var maindiv = document.getElementById("gallarybox");
    maindiv.appendChild(containerdiv);
    
}

function get_selected_combo(){
  var productQuantity = document.getElementById("quantityField").value;  
  var varproductQuantity = document.getElementById("varquantityField").value;
  var elementsLi = document.getElementsByClassName('list-inline-item prop-value selected');
  	if(elementsLi.length !==0)   //start of if statement checking selected elements
	{
    var sku_custom_id = "";
    var varcombination = "";
    var combo_arr=new Array();
   

	for (var i=0, len=elementsLi.length|0; i<len; i=i+1|0) {

         var prop_value = elementsLi[i].getAttribute('data-name');
         var sDataValue = elementsLi[i].getAttribute('data-prop-vid');
         var prop_key = elementsLi[i].getAttribute('data-prop-key');
         var prop_price = elementsLi[i].getAttribute('data-price');
         //var sDataValue1 = elementsLi[i].getAttribute('data-prop-pid');
         //var combine=sDataValue1+'---'.concat(sDataValue);
         sku_custom_id = sku_custom_id.concat(sDataValue);
        // varcombination = varcombination.concat(combination);
         combo_arr[prop_key] = prop_value;
         
	}
	

    var getprice=get_price_and_sku();
	combo_arr['sku'] = sku_custom_id;
	combo_arr['qty'] = varproductQuantity;
    combo_arr['price'] = getprice;
    console.log(getprice);
	return combo_arr;
	
	}
	
}


    

var complete_combo_arr=new Array();

function change_combo_quantity()
{
  
    var combo_arr=get_selected_combo();

    for (var i=0; i<complete_combo_arr.length; i++){
        if(combo_arr['sku'] == complete_combo_arr[i]['sku']){

            complete_combo_arr.splice(i, 1);
        }
      
    }
    
     if(combo_arr['qty']!=0){
     complete_combo_arr.push(combo_arr); 
     }
     
      if(complete_combo_arr.length!=0){
     set_main_quantity();
      }
	 display_selected_combo();
    show_hide_buy_button();   // called for checking minimum quantity to buy
}	 

function change_combo_selection()
{
     var combo_arr=get_selected_combo();
     var flag=0;
     for (var i=0; i<complete_combo_arr.length; i++){
        if(combo_arr['sku'] == complete_combo_arr[i]['sku']){
            var qty_number=complete_combo_arr[i]['qty'];
            document.getElementById("varquantityField").value=qty_number;
            flag=1;
            break;
        }

        if(flag==0){
           document.getElementById("varquantityField").value=0; 
        }
      
    }
     
      if(complete_combo_arr.length!=0){
     set_main_quantity();
      }

    display_selected_combo();
    
}


function set_main_quantity(){
    var totallqty=0;
    var totallprice=0;
  for (var i=0; i<complete_combo_arr.length; i++){
          totallqty=totallqty + Number(complete_combo_arr[i]['qty']);
          
           var minqty_range = <?php echo json_encode($newrange); ?>; 
	    var lot_price_minqty = <?php echo json_encode($price_minqty_arr); ?>; 
	    
	    if(minqty_range !=null && lot_price_minqty!=null){    // if price batch present
	        
          if(Number(complete_combo_arr[0]['price'])!=Number(complete_combo_arr[i]['price'])){

              totallprice= Number(totallqty) * Number(complete_combo_arr[complete_combo_arr.length-1]['price']);
          }
          else{
              totallprice=totallprice + Number(complete_combo_arr[i]['qty']) * Number(complete_combo_arr[i]['price']); 
          }
          
	    }
	    else{
	       totallprice=totallprice + Number(complete_combo_arr[i]['qty']) * Number(complete_combo_arr[i]['price']);  
	    }
         
  }

   var productQuantity = document.getElementById("quantityField").value;
  
  document.getElementById("quantityField").value=totallqty;
  totallprice=Number(totallprice).toFixed(2);
  document.getElementById("selected_combination_price").innerHTML=totallprice;
  	var fp = document.getElementById("formatted-price").childNodes;
	
	//fp[1].innerHTML="$ ".concat(totallprice);
	
	if(fp[3]){
	  fp[3].innerHTML="$ ".concat(totallprice);  
	}
	else{
	   fp[1].innerHTML="$ ".concat(totallprice); 
	}
	
    var avg_price=(totallprice/totallqty).toFixed(2);
	
	document.getElementById("selected_combination_avgprice").innerHTML=avg_price;
	
    productinspection();
    productconsolidation();
 
  
    
}

function display_selected_combo(){
       var txt="";
       txt = txt + "<table class='table table-bordered' ><thead><tr><th>Color</th><th>Size</th><th>Quantity</th></tr></thead>";
        for (var i=0; i<complete_combo_arr.length; i++)
        {
            txt = txt + "<tr>";
            for (var key in complete_combo_arr[i]) 
            {
                if(key !="sku" && key !="price")
                txt = txt + "<td>"   + complete_combo_arr[i][key] + "</td>" ;
            }
          
                   txt = txt + "<tr>";
           
        }
        txt = txt + "</table>";
    
   document.getElementById("selected_combination").innerHTML=txt;

}


function show_hide_qtyselector(){
  var qtyselector = document.getElementById("varquantity"); 
    qtyselector.style.display = "none";
}

function show_hide_mainqtyselector(){
   var qtyselector = document.getElementById("quantity"); 
    qtyselector.style.display = "none";  
}

function show_hide_buy_button(){
       var productQuantity = document.getElementById("quantityField").value;
     var buyminimum=<?php echo $buyminimum ?>;
      if(productQuantity>=buyminimum){
            document.getElementById("minlimit").style.display = "none";
            document.getElementById("ali243-add-cart").style.display = "block";
            document.getElementById("ali243-buy-now").style.display = "block";

             }
         else{
         document.getElementById("minlimit").style.display = "block";
         document.getElementById("ali243-add-cart").style.display = "none";
         document.getElementById("ali243-buy-now").style.display = "none";
            }
            

}



function get_price_and_sku()
{ 

          var productQuantity = document.getElementById("quantityField").value;
           var maxqty = document.getElementById("varstockqty").value;
       <?php echo $maxqty ?>
     var buyminimum=<?php echo $buyminimum ?>;
      if(productQuantity>=buyminimum){
            document.getElementById("minlimit").style.display = "none";
            document.getElementById("ali243-add-cart").style.display = "block";
            document.getElementById("ali243-buy-now").style.display = "block";

             }
         else{
         document.getElementById("minlimit").style.display = "block";
         document.getElementById("ali243-add-cart").style.display = "none";
         document.getElementById("ali243-buy-now").style.display = "none";
            }

	var elementsLi = document.getElementsByClassName('list-inline-item prop-value selected');
	if(elementsLi.length !==0)   //start of if statement checking selected elements
	{
	  show_hide_mainqtyselector();
    var sku_custom_id = "";

	for (var i=0, len=elementsLi.length|0; i<len; i=i+1|0) {

         var sDataValue = elementsLi[i].getAttribute('data-prop-vid');
         sku_custom_id = sku_custom_id.concat(sDataValue);
         
	}
	
    
	var productprice= document.getElementById(sku_custom_id).getAttribute("data-price");
	var productpricepromo = document.getElementById(sku_custom_id).getAttribute("data-promo-price");
    var productqty = document.getElementById(sku_custom_id).getAttribute("data-quantity");
    // var shippingcost_1 = document.getElementById("shippingMethods");
    // var shippingcost = shippingcost_1.value;
	document.getElementById("quantityField").setAttribute("max",productqty );
	
	//start code for selecting price according to batch price

	    var minqty_range = <?php echo json_encode($newrange); ?>; 
	    var lot_price_minqty = <?php echo json_encode($price_minqty_arr); ?>; 
	    
	    if(minqty_range !=null && lot_price_minqty!=null){
	        
	    
	    var low=Math.min.apply(Math,minqty_range); 
        var high=Math.max.apply(Math,minqty_range);
        var lowarray=[];
        var higharray=[];
	     for(var i=0; i<minqty_range.length; i++) {
	         
	            if(Number(productQuantity) >= Number(minqty_range[i])){
	                lowarray.push(minqty_range[i]);
	            }
	             else{
	                higharray.push(minqty_range[i]);
	            }
	         
	         
	     }
	     var below= Math.max.apply(Math,lowarray);
	     var above= Math.min.apply(Math,higharray);
	     
	     if(below == '-Infinity'){
	        below=above;
	     }
	     
	    
	  
 
         for(var minqty in lot_price_minqty) {
            if(below == minqty){
             productprice=productpricepromo=lot_price_minqty[minqty];
            }

        }
        
          if(productQuantity>=low){
            document.getElementById("minlimit").style.display = "none";
            document.getElementById("ali243-add-cart").style.display = "block";
            document.getElementById("ali243-buy-now").style.display = "block";

             }
         else{
         document.getElementById("minlimit").style.display = "block";
         document.getElementById("ali243-add-cart").style.display = "none";
         document.getElementById("ali243-buy-now").style.display = "none";
            }
        
	  }
	    
	    //end code for selecting price according to batch price
 

	//shippingcost = (Number(shippingcost) + (0.6 * Number(shippingcost))*(Number(productQuantity)-1)).toFixed(2);
	//var totalshpngPriceId = document.getElementById("totalshpngPrice");
	//var shippingcompany = shippingcost_1.options[shippingcost_1.selectedIndex].getAttribute('data-service-company');
	//totalshpngPriceId.innerHTML = 'Total shipping price: ' .concat(shippingcost).concat(" USD by ").concat(shippingcompany);
	//totalshpngPriceId.classList.remove("d-none");

	var toatalPrice = (Number(productQuantity) * Number(productprice)).toFixed(2);
	var toatalPricePromo = (Number(productQuantity) * Number(productpricepromo)).toFixed(2);
    var qa = document.getElementsByClassName("qty-ability")[0];
    var vqa = document.getElementsByClassName("varqty-ability")[0];
	var fp = document.getElementById("formatted-price").childNodes;
    qa.innerHTML=productqty;
    vqa.innerHTML=productqty;
    $("#varquantityField").prop("max", productqty);
    $('#varquantityField').on('input', function () {    
        var value = $(this).val();        
        if ((value !== '') && (value.indexOf('.') === -1)) {            
            $(this).val(Math.max(Math.min(value, productqty), 0));
        }
    });
    
      if(productqty>=5){
     document.getElementById("lowstock").style.display = "none";
     document.getElementById("ali243-add-cart").style.pointerEvents = "auto";
     document.getElementById("ali243-buy-now").style.pointerEvents = "auto";

    }
    else{
         document.getElementById("lowstock").style.display = "block";
         document.getElementById("ali243-add-cart").style.pointerEvents = "none";
         document.getElementById("ali243-buy-now").style.pointerEvents = "none";
    }
    
	fp[1].innerHTML="$ ".concat(toatalPricePromo);
	if(fp[3]){
	  fp[3].innerHTML="$ ".concat(toatalPrice);  
	}
	else{
	   fp[1].innerHTML="$ ".concat(toatalPrice); 
	}
	

	
		if(productpricepromo !==null && productpricepromo!==""){
	     return productpricepromo;
	   }
	   else{
	      return productprice; 
	   }
	
	} //end of if statement checking selected elements
	
	
	
	
	else{  //start of else statement checking selected elements
	     show_hide_qtyselector();
	
	    document.getElementById("quantityField").setAttribute("max",productqty );
	    var stockqty = document.getElementById("stockqty").innerHTML;
	    
	    	if(stockqty>=5){
         document.getElementById("lowstock").style.display = "none";
         document.getElementById("ali243-add-cart").style.pointerEvents = "auto";
         document.getElementById("ali243-buy-now").style.pointerEvents = "auto";

    }
    else{
        document.getElementById("lowstock").style.display = "block";
        document.getElementById("ali243-add-cart").style.pointerEvents = "none";
        document.getElementById("ali243-buy-now").style.pointerEvents = "none";
    }
	    
	    var original_amt = <?php echo $original_amount; ?>;
      var promo_amt = "<?php echo $promo_amount; ?>";
      
      
      	//start code for selecting price according to batch price

	    var minqty_range = <?php echo json_encode($newrange); ?>; 
	    var lot_price_minqty = <?php echo json_encode($price_minqty_arr); ?>; 
	    
	    if(minqty_range !=null && lot_price_minqty!=null){
	    
	    var low=Math.min.apply(Math,minqty_range); 
        var high=Math.max.apply(Math,minqty_range);
        var lowarray=[];
        var higharray=[];
	     for(var i=0; i<minqty_range.length; i++) {
	         
	            if(Number(productQuantity) >= Number(minqty_range[i])){
	                lowarray.push(minqty_range[i]);
	            }
	             else{
	                higharray.push(minqty_range[i]);
	            }
	         
	         
	     }
	     var below= Math.max.apply(Math,lowarray);
	     var above= Math.min.apply(Math,higharray);
	     
	     if(below == '-Infinity'){
	        below=above;
	     }
	     
	    
	  
 
         for(var minqty in lot_price_minqty) {
            if(below == minqty){
               original_amt=promo_amt=lot_price_minqty[minqty];
            }

        }
         if(productQuantity>=low){
            document.getElementById("minlimit").style.display = "none";
            document.getElementById("ali243-add-cart").style.display = "block";
            document.getElementById("ali243-buy-now").style.display = "block";

             }
         else{
         document.getElementById("minlimit").style.display = "block";
         document.getElementById("ali243-add-cart").style.display = "none";
         document.getElementById("ali243-buy-now").style.display = "none";
            }
        
	  }
	    
	    //end code for selecting price according to batch price
      
      

	    
if(promo_amt !==null && promo_amt!=="")
{

var toatalPricePromo = (Number(productQuantity) * Number(promo_amt)).toFixed(2);

}
	    
	    var toatalPrice = (Number(productQuantity) * Number(original_amt)).toFixed(2);
	

	var fp = document.getElementById("formatted-price").childNodes;
	fp[1].innerHTML="$ ".concat(toatalPricePromo);
	if(fp[3]){
	  fp[3].innerHTML="$ ".concat(toatalPrice);  
	}
	else{
	   fp[1].innerHTML="$ ".concat(toatalPrice); 
	}
	

	
		if(promo_amt !==null && promo_amt!==""){
	     return promo_amt;
	   }
	   else{
	      return original_amt; 
	   }
	    
	}   //end of else statement checking selected elements

}
/*
document.getElementById("shippingMethods").addEventListener("change", function() {

get_price_and_sku();
}); */



function productinspection(){
    
     
      var selected_combination_avgprice_val=document.getElementById("selected_combination_avgprice").innerHTML;
        if(selected_combination_avgprice_val!=""){
             getprice=Number(selected_combination_avgprice_val);
        }else
        {
             getprice=get_price_and_sku();
        }
    
    
    var productQuantity = document.getElementById("quantityField").value;

    var sf = <?php echo $service_fee; ?>;
	var er = <?php echo $exchange_rate; ?>;
	var pgf = <?php echo $payment_gateway_fee; ?>;
	var pif = <?php echo $product_inspection_fee; ?>;
	var pcf = <?php echo $product_consolidation_fee; ?>;
	var fpif = <?php echo $fixed_product_inspection_fee; ?>;
	var fpcf = <?php echo $fixed_product_consolidation_fee; ?>;
  

var actual_amount=getprice / (1+Number(sf)+Number(pgf)+Number(er)); 
	
var pro_ins_checkBox = document.getElementById("product_inspection_check");
	  if (pro_ins_checkBox.checked == true){
	     // newproductpricepromo=Number(productpricepromo) + Number(actual_amount) * Number(pif);
	      var product_inspecton_value= (Number(actual_amount) * Number(pif) * Number(productQuantity) + (Number(fpif) * Number(productQuantity))).toFixed(2);
        
        document.getElementById("product_ins_amount").innerHTML=' + Additional cost $' + product_inspecton_value;
        document.getElementById("product_ins_amount").setAttribute("data-price", product_inspecton_value);
        } 
        else{
            document.getElementById("product_ins_amount").innerHTML='';
             document.getElementById("product_ins_amount").setAttribute("data-price", 0);
        }
}

document.getElementById("product_inspection_check").addEventListener("change", productinspection);

function productconsolidation(){
    
      var selected_combination_avgprice_val=document.getElementById("selected_combination_avgprice").innerHTML;
        if(selected_combination_avgprice_val!=""){
             getprice=Number(selected_combination_avgprice_val);
        }else
        {
             getprice=get_price_and_sku();
        }
    var productQuantity = document.getElementById("quantityField").value;

    var sf = <?php echo $service_fee; ?>;
	var er = <?php echo $exchange_rate; ?>;
	var pgf = <?php echo $payment_gateway_fee; ?>;
	var pif = <?php echo $product_inspection_fee; ?>;
	var pcf = <?php echo $product_consolidation_fee; ?>;
	var fpif = <?php echo $fixed_product_inspection_fee; ?>;
	var fpcf = <?php echo $fixed_product_consolidation_fee; ?>;
  

var actual_amount=getprice / (1+Number(sf)+Number(pgf)+Number(er)); 
	
var pro_cons_checkBox = document.getElementById("product_consolidation_check");
	  if (pro_cons_checkBox.checked == true){
	     // newproductpricepromo=Number(productpricepromo) + Number(actual_amount) * Number(pif);
	      var product_consolidation_value= (Number(actual_amount) * Number(pcf) * Number(productQuantity) + (Number(fpcf) * Number(productQuantity))).toFixed(2);
        
        document.getElementById("product_cons_amount").innerHTML=' + Additional cost $' + product_consolidation_value;
        document.getElementById("product_cons_amount").setAttribute("data-price", product_consolidation_value);
        } 
        else{
            document.getElementById("product_cons_amount").innerHTML='';
            document.getElementById("product_cons_amount").setAttribute("data-price", 0);
        }
    
}
document.getElementById("product_consolidation_check").addEventListener("change", productconsolidation);


<?php 	$prd_i = 1;
	foreach($variationsgroup as $key => $val){
		?>

var var_ul_<?php echo $prd_i;?> = document.getElementById("product_var_<?php echo $prd_i;?>");
var var_ul_<?php echo $prd_i;?>_li = var_ul_<?php echo $prd_i;?>.getElementsByClassName("list-inline-item");
for (var i = 0; i < var_ul_<?php echo $prd_i;?>_li.length; i++) {
  var_ul_<?php echo $prd_i;?>_li[i].addEventListener("click", function() {
  var current = var_ul_<?php echo $prd_i;?>.getElementsByClassName("selected");
  current[0].className = current[0].className.replace("selected", "");
  this.className += " selected";
  
	get_price_and_sku();
	change_combo_selection();
	productinspection();
	productconsolidation();
	

  });
}
<?php $prd_i++; }?>

function get_ins_cons_sku()
{
get_price_and_sku();
productinspection();
productconsolidation();
};

document.getElementById("ali243-add-cart").onclick = function() {  createproduct(); };
document.getElementById("ali243-buy-now").onclick = function() { createproduct(); window.location.href = "https://borderlesscommerce.net/checkout/";};


function createproduct() {
    
     var phy_param='<?php echo $PhysicalParameters ?>';
    
  var conso_ins=[];  
document.getElementById("productloader1").classList.remove("d-none");

var pro_ins_checkBox = document.getElementById("product_inspection_check");
	  if (pro_ins_checkBox.checked == true){
	      conso_ins.push(" Selected for Product Inspection ");
	  }
var pro_cons_checkBox = document.getElementById("product_consolidation_check");
	  if (pro_cons_checkBox.checked == true){
	      conso_ins.push(" Selected for Product Consolidation ");
	  }

var elementsLi = document.getElementsByClassName('list-inline-item prop-value selected');
if(elementsLi.length !==0) 
{  //start of if statement checking selected elements
  var sku_custom_id = "";
    var txt = "";
	var imagelink;
  	var propattribute="";

for (var i=0, len=elementsLi.length|0; i<len; i=i+1|0) {
       var sDataValue = elementsLi[i].getAttribute('data-prop-vid');
       sku_custom_id = sku_custom_id.concat(sDataValue);
       var propkey = elementsLi[i].getAttribute('data-prop-key');
       var propname = elementsLi[i].getAttribute('data-name');
       propattribute=propattribute + propkey + "=" +propname + "<br>";   // This propattribute is redifined below for multiple attributes selection
       
        var imgnewurl=elementsLi[i].getAttribute('data-prop-image');
         if(imgnewurl!=null){
            linkforimg=imgnewurl; 
         }
         else{
             linkforimg="";
         }


}

    var pro_ins_val= document.getElementById("product_ins_amount").getAttribute('data-price');
    var pro_cons_val= document.getElementById("product_cons_amount").getAttribute('data-price');
	var productprice= document.getElementById(sku_custom_id).getAttribute("data-price");
	var productpricepromo = document.getElementById(sku_custom_id).getAttribute("data-promo-price");
    // var shippingcost_1 = document.getElementById("shippingMethods");
    // var shippingcost = shippingcost_1.value;
     var productQuantity = document.getElementById("quantityField").value;
	//shippingcost = (Number(shippingcost) + (0.6 * Number(shippingcost))*(Number(productQuantity)-1)).toFixed(2);
	//var totalshpngPriceId = document.getElementById("totalshpngPrice");
	//var shippingcompany = shippingcost_1.options[shippingcost_1.selectedIndex].getAttribute('data-service-company');

    var producttitle = document.getElementById('product-title');
     var producttitle1 = producttitle.textContent;
    var producttitle2 = producttitle1.replace(/[^a-zA-Z0-9 ]/g, '');
    
    
    	//start code for selecting price according to batch price

	    var minqty_range = <?php echo json_encode($newrange); ?>; 
	    var lot_price_minqty = <?php echo json_encode($price_minqty_arr); ?>; 
	    /*
	    if(minqty_range !=null && lot_price_minqty!=null){
	    
	    var low=Math.min.apply(Math,minqty_range); 
        var high=Math.max.apply(Math,minqty_range);
        var lowarray=[];
        var higharray=[];
	     for(var i=0; i<minqty_range.length; i++) {
	         
	            if(Number(productQuantity) >= Number(minqty_range[i])){
	                lowarray.push(minqty_range[i]);
	            }
	             else{
	                higharray.push(minqty_range[i]);
	            }
	         
	         
	     }
	     var below= Math.max.apply(Math,lowarray);
	     var above= Math.min.apply(Math,higharray);
	     
	     if(below == '-Infinity'){
	        below=above;
	     }
	     
	    
	  
 
         for(var minqty in lot_price_minqty) {
            if(below == minqty){
               productprice=productpricepromo=lot_price_minqty[minqty];
            }

        }
        
	    }
	    
	    //end code for selecting price according to batch price
	    
	  */  

   // var toatalPrice = (Number(shippingcost) + (Number(productQuantity) * Number(productprice))).toFixed(2);
     var toatalPrice = (Number(productQuantity) * Number(productprice) + Number(pro_ins_val) + Number(pro_cons_val) ).toFixed(2);
	
	if(productpricepromo !==null && productpricepromo!==""){
	    //	var toatalPrice = (Number(shippingcost) + (Number(productQuantity) * Number(productpricepromo))).toFixed(2);
	    	var toatalPrice = (Number(productQuantity) * Number(productpricepromo) + Number(pro_ins_val) + Number(pro_cons_val)).toFixed(2);
	}
	
		 //  if(minqty_range !=null && lot_price_minqty!=null){
	      propattribute="";
        for (var i=0; i<complete_combo_arr.length; i++){
             propattribute = propattribute + "{";
            for (var key in complete_combo_arr[i]) {
                if(key !="sku" && key !="price"){
                    
                    if(key!='qty'){
                      propattribute = propattribute + '"'+key +'": "' + complete_combo_arr[i][key]+'" ,';    //creating attribute in json format  
                    }
                    else{
                      propattribute = propattribute + '"'+key +'": "' + complete_combo_arr[i][key]+'"';    //creating attribute in json format  
                    }
                    
                
                }
 
            }
                         if(i!=(complete_combo_arr.length)-1){
                           propattribute = propattribute + "},";  
                         }
                         else{
                            propattribute = propattribute + "}"; 
                         }
                          
           
        }
    
    var price_combo=document.getElementById("selected_combination_price").innerHTML;
    
    var toatalPrice = (Number(price_combo) + Number(pro_ins_val) + Number(pro_cons_val) ).toFixed(2);
      
	 //  }


// var txt = txt + "<br/> SKU: " + sku_custom_id + "<br/> Ship by: " + shippingcompany;
  var txt = txt + "<br/> SKU: " + sku_custom_id;

  if(linkforimg!=""){
  imagelink=linkforimg;  
  }
  else{
    if(imagelink==null ){ imagelink = "<?php echo $itemInfo['MainPictureUrl'];?>";}  
  }

       var productlink = "<?php echo $_GET['url']; ?>"; 
       var weight_attri = "<?php echo $product_weight; ?>";
      var str = "price=" + toatalPrice + "&quantity=" + productQuantity + "&title=" + producttitle2 + "&link=" + productlink + "&content=" + txt + "&image=" + imagelink + "&attributes=" + propattribute + "&consoins=" + conso_ins+ "&physical="+ phy_param + "&weight="+ weight_attri;
   //alert(str);   
} //end of if statement checking selected elements


else{ //start of else statement checking selected elements
    var sku_custom_id=<?php echo $itemId; ?>;
    var txt = "";
	var imagelink;

    var pro_ins_val= document.getElementById("product_ins_amount").getAttribute('data-price');
    var pro_cons_val= document.getElementById("product_cons_amount").getAttribute('data-price');
    // var shippingcost_1 = document.getElementById("shippingMethods");
    // var shippingcost = shippingcost_1.value;
     var productQuantity = document.getElementById("quantityField").value;
	    
	    var original_amt = <?php echo $original_amount; ?>;
	    var promo_amt ="<?php echo $promo_amount; ?>";
	    
	    
	    	//start code for selecting price according to batch price

	    var minqty_range = <?php echo json_encode($newrange); ?>; 
	    var lot_price_minqty = <?php echo json_encode($price_minqty_arr); ?>; 
	    
	    if(minqty_range !=null && lot_price_minqty!=null){
	    
	    var low=Math.min.apply(Math,minqty_range); 
        var high=Math.max.apply(Math,minqty_range);
        var lowarray=[];
        var higharray=[];
	     for(var i=0; i<minqty_range.length; i++) {
	         
	            if(Number(productQuantity) >= Number(minqty_range[i])){
	                lowarray.push(minqty_range[i]);
	            }
	             else{
	                higharray.push(minqty_range[i]);
	            }
	         
	         
	     }
	     var below= Math.max.apply(Math,lowarray);
	     var above= Math.min.apply(Math,higharray);
	     
	     if(below == '-Infinity'){
	        below=above;
	     }
	     
	    
	  
 
         for(var minqty in lot_price_minqty) {
            if(below == minqty){
               original_amt=promo_amt=lot_price_minqty[minqty];
            }

        }
        
	    }
	    
	    //end code for selecting price according to batch price
	    

	//shippingcost = (Number(shippingcost) + (0.6 * Number(shippingcost))*(Number(productQuantity)-1)).toFixed(2);
	//var totalshpngPriceId = document.getElementById("totalshpngPrice");
	//var shippingcompany = shippingcost_1.options[shippingcost_1.selectedIndex].getAttribute('data-service-company');

    var producttitle = document.getElementById('product-title');
        var producttitle1 = producttitle.textContent;
    var producttitle2 = producttitle1.replace(/[^a-zA-Z0-9 ]/g, '');
   // var toatalPrice = (Number(shippingcost) + (Number(productQuantity) * Number(productprice))).toFixed(2);
     var toatalPrice = (Number(productQuantity) * Number(original_amt) + Number(pro_ins_val) + Number(pro_cons_val) ).toFixed(2);
    

if(promo_amt !==null && promo_amt!==""){
  var toatalPrice = (Number(productQuantity) * Number(promo_amt) + Number(pro_ins_val) + Number(pro_cons_val)).toFixed(2);
      }
	
	


// var txt = txt + "<br/> SKU: " + sku_custom_id + "<br/> Ship by: " + shippingcompany;
  var txt = txt + "<br/> SKU: " + sku_custom_id;

		if(imagelink==null ){ imagelink = "<?php echo $itemInfo['MainPictureUrl'];?>";}

       var productlink = "<?php echo $_GET['url']; ?>";
      
       var weight_attri = "<?php echo $product_weight; ?>";

      var str = "price=" + toatalPrice + "&quantity=" + productQuantity + "&title=" + producttitle2 + "&link=" + productlink + "&content=" + txt + "&image=" + imagelink + "&consoins=" + conso_ins + "&physical="+ phy_param + "&weight="+ weight_attri;  

//alert(str);
} // end of else statement checking selected elements




       var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
								document.getElementById("productloader1").classList.add("d-none");
            }
        }
        // xmlhttp.open("GET", "https://borderlesscommerce.net/saveproduct.php?"+str, true);
        xmlhttp.open('POST', "https://borderlesscommerce.net/saveproduct.php", true);
        xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlhttp.send(str);
        // xmlhttp.send();


			}
jQuery(window).on('load', function() {
    get_price_and_sku();
})			


</script>


<?php
}

else

{
  if(!isset($_GET['from'])){
    $frameposition=0;    
   }
   else{
      $frameposition=$_GET['from']+1; 
   }   
   
   
    // Start uploading file to oapi and get image url from their
   
function GetFileUploadUrl($filename)
{
  
    $link = 'http://otapi.net/service/GetFileUploadUrl?instanceKey=' . CFG_SERVICE_INSTANCEKEY
        . '&language=' . CFG_REQUEST_LANGUAGE
        . '&fileName=' . $filename
        . '&fileType=image';
    
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
 
    if ((string)$xmlObject->ErrorCode !== 'Ok') 
    {
   echo "Error: " . $xmlObject->ErrorDescription; die();
     }

    $FileId=$data['Result']['Id'];

    return $FileId;

}

function uploadimage($filetmpname,$filetype,$filename,$uploadurl)
  {
    $ch= curl_init();
    //$cfile=new CURLFile($_FILES['fileToUpload']['tmp_name'],$_FILES['fileToUpload']['type'],$_FILES['fileToUpload']['name']);
    $cfile=new CURLFile($filetmpname,$filetype,$filename);
    $data = array("myimage" => $cfile);
    curl_setopt($ch,CURLOPT_URL, $uploadurl);
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    if($response == true){
    //echo "file posted";
    }
    else
    {
    echo "Error: ". curl_error($ch);
    }
    curl_close($ch);

}

function GetFileInfo($fileid)
{
   //http://otapi.net/OtapiWebService2.asmx/GetFileInfo?language=ru&fileId=e6a05dcd-d345-eb11-80c4-f409ed584015&instanceKey=opendemo
    $link = 'http://otapi.net/OtapiWebService2.asmx/GetFileInfo?instanceKey=' . CFG_SERVICE_INSTANCEKEY
        . '&language=' . CFG_REQUEST_LANGUAGE
        . '&fileId=' . $fileid;
        
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
 
    if ((string)$xmlObject->ErrorCode !== 'Ok') 
    {
   echo "Error: " . $xmlObject->ErrorDescription; die();
     }

    $FinalImageUrl=$data['Result']['Url'];

    return $FinalImageUrl;

}
   
if (isset($_FILES['fileToUpload']['name']) && $_FILES['fileToUpload']['name']!="") 
{
   
    $filetmpname=$_FILES['fileToUpload']['tmp_name'];
    $filetype=$_FILES['fileToUpload']['type'];
    $filename=$_FILES['fileToUpload']['name'];
    //$fileid=GetFileUploadUrl($filename); // old
    $filename = str_replace(' ', '_', $filename); // new rsw
    $fileid=GetFileUploadUrl($filename);  // new rsw
    $uploadurl="http://files.otapi.net/upload?fileId=".$fileid;
    uploadimage($filetmpname,$filetype,$filename,$uploadurl);
    $finalimageurl=GetFileInfo($fileid);
    $_SESSION['imgurl']=$finalimageurl;

}
   
    // End uploading file to oapi and get image url from their
    
   $price_order = empty($_GET['price'])?'':$_GET['price'];
   $price_range_min = empty($_GET['price_range_min'])?'': $_GET['price_range_min'];
   $price_range_max = empty($_GET['price_range_max'])?'': $_GET['price_range_max'];

   $price_range_min_api_var = empty($price_range_min)?'' : '<MinPrice>'.$price_range_min.'</MinPrice>';
   $price_range_max_api_var = empty($price_range_max)?'' : '<MaxPrice>'.$price_range_max.'</MaxPrice>';
   $price_order_api_var = empty($price_order)?'':'<OrderBy>Price:'.$price_order.'</OrderBy>';
    
     if(isset($_SESSION['imgurl']) && $url==""){
    
    $xmlp='<SearchItemsParameters><Provider>Alibaba1688</Provider>'.$price_range_min_api_var.$price_range_max_api_var.$price_order_api_var.'<ImageUrl>'.$_SESSION['imgurl'].'</ImageUrl></SearchItemsParameters>';
    // echo "<textarea>$xmlp</textarea>";
    
    //   $xmlp='<SearchItemsParameters><Provider>Alibaba1688</Provider><ImageUrl>'.$_SESSION['imgurl'].'</ImageUrl></SearchItemsParameters>';
//$xmlpl = str_replace("<","%3C",$xmlp);
//$xmlpr = str_replace(">","%3E",$xmlpl);
//$xmlps = str_replace("/","%2F",$xmlpr);
//$xmlpc = str_replace(":","%3A",$xmlps);
if(empty($price_range_min) && empty($price_range_max)) {
    $link = 'http://otapi.net/service/BatchSearchItemsFrame?instanceKey=' . CFG_SERVICE_INSTANCEKEY
        . '&language=' . CFG_REQUEST_LANGUAGE
        . '&sessionId=' . CFG_SESSIONID
        . '&xmlParameters=' . $xmlp
        . '&framePosition=' . $frameposition
        . '&frameSize=' . CFG_FRAMESIZE
        . '&blockList=' . CFG_BLOCKLIST;
}else{
    $link = 'http://otapi.net/service/BatchSearchItemsFrame?instanceKey=' . CFG_SERVICE_INSTANCEKEY
        . '&language=' . CFG_REQUEST_LANGUAGE
        . '&sessionId=' . CFG_SESSIONID
        . '&xmlParameters=' . $xmlp
        . '&framePosition=' . $frameposition
        . '&frameSize=' . CFG_FRAMESIZE_RSW
        . '&blockList=' . CFG_BLOCKLIST;

}        
        
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $link);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HEADER, 0);
 
$result = curl_exec($curl);

if ($result === FALSE) {
    echo "cURL Error: " . curl_error($curl);
}
$xmlObject = simplexml_load_string($result);


$json = json_encode($xmlObject);
$data_s = json_decode($json,true);
 
curl_close($curl);
if ((string)$xmlObject->ErrorCode !== 'Ok') {
    echo "Errors: " . $xmlObject->ErrorDescription;
              }
           
       
   }  // end of if session variable set
   
   else{
       


       
       $url1 = str_replace(" ","%20",$url);
// $xmlp='<SearchItemsParameters><Provider>Alibaba1688</Provider><SearchMethod>Extended</SearchMethod><IsClearItemTitles>false</IsClearItemTitles><ItemTitle>'.$url1.'</ItemTitle></SearchItemsParameters>';  // comment by rsw
$xmlp='<SearchItemsParameters><Provider>Alibaba1688</Provider><SearchMethod>Extended</SearchMethod><IsClearItemTitles>false</IsClearItemTitles><ItemTitle>'.$url1.'</ItemTitle>'.$price_order_api_var.''.$price_range_min_api_var.''.$price_range_max_api_var.'</SearchItemsParameters>';
$xmlpl = str_replace("<","%3c",$xmlp);
$xmlpr = str_replace(">","%3e",$xmlpl);
$link = 'http://otapi.net/OtapiWebService2.asmx/BatchSearchItemsFrame?instanceKey=' . CFG_SERVICE_INSTANCEKEY
        . '&language=' . CFG_REQUEST_LANGUAGE
        . '&sessionId=' . CFG_SESSIONID
        . '&xmlParameters=' . $xmlpr
        . '&framePosition=' . $frameposition
        . '&frameSize=' . CFG_FRAMESIZE
        . '&blockList=' . CFG_BLOCKLIST;
 
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
$data_s = json_decode($json,true);
 
curl_close($curl);
 
if ((string)$xmlObject->ErrorCode !== 'Ok') {
    echo "Error: " . $xmlObject->ErrorDescription; die();
}
       
   }   // end of else session variable set
    

 



	?>

	<style>.b{ width: 95px; display: block; float: left;} .filter_select{ width:38%; display:inline-block; }  .price_order_input { margin-right: 20px; } .filter_common{ margin: 10px 0px ;}
        @media only screen and (min-width: 320px) and (max-width: 767px) {
            .filter_common input[type="number"] {width: 100%;}
        }</style>
	<div class="archive-products">

	<h6> You Search for Keyword: <?php echo $url; ?></h6>

	<div class="wpb_wrapper vc_column-inner">
		<div class="wpb_text_column wpb_content_element ">
			<div class="wpb_wrapper">
				<div class="vc_wp_search wpb_content_element">
	<div class="widget widget_search">
	<form id="searchform" class="searchform" action="https://borderlesscommerce.net/1688-to-borderless-commerce/" method="get">
	<div class="input-group">
		<input id="url" class="form-control" autocomplete="off" name="url" type="text" placeholder="Enter the 1688.com unique product link here"><br>
	<span class="input-group-append"><br>
	<button class="btn btn-dark p-2" type="submit"><i class="fas fa-search m-2"></i></button><br>
	</span></div>
	</form>
	</div>
	</div>

			</div>
		</div>
	</div>


<div>
    <h3>Filters</h3>
        <form action="https://borderlesscommerce.net/1688-to-borderless-commerce/" method="get">
            <input type="hidden" name="url" value="<?php echo $url;?>">
    
            <div class="row">
                <div class="col-md-6">
                    <div class='filter_common'>
                        <b class='b'>Price Range:</b>
                        <input type="number" name="price_range_min" placeholder="Min price" value="<?php echo $price_range_min; ?>"> to
                        <input type="number" name="price_range_max" placeholder="Max price"  value="<?php echo $price_range_max; ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class='filter_common'>
                        <b class='b'>Price:</b>
                        <span class='price_order_input'> <input type="radio" name="price" value="Asc" <?php if(isset($_GET['price']) && $_GET['price'] == 'Asc'){ echo "checked"; } ?> >  Low to High </span>
                        <span class='price_order_input'> <input type="radio" name="price" value="Desc" <?php if(isset($_GET['price']) && $_GET['price'] == 'Desc'){ echo "checked"; }?>  > High to Low </span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class='filter_common'>
                        <input type="submit" class="d-block w-100" name="" value="filter">
                    </div>
                </div>
            </div>
        </form>
</div>

			<ul class="products products-container grid pcols-lg-4 pcols-md-3 pcols-xs-3 pcols-ls-2 pwidth-lg-4 pwidth-md-3 pwidth-xs-2 pwidth-ls-1" data-product_layout="product-outimage_aq_onimage">

				<?php
         $maxpagecount=$data_s['Result']['Items']['MaximumPageCount'];
         $maxitemcount=CFG_FRAMESIZE * $maxpagecount;
         $pageperview=10;
         $itemperview=$pageperview * CFG_FRAMESIZE;    //200
         $totalview=$maxpagecount/$pageperview;        //50
         $view_number=floor($_GET['from']/$itemperview) *200;
         $increment_number=floor($_GET['from']/$itemperview) *10;
                    $item_list = array();
                    $item_list = $data_s['Result']['Items']['Items']['Content']['Item'];
                    $from = isset($_GET['from']) ? $_GET['from'] : 0;
                    $totalitem=count($item_list);
                    $itemperpage=20;
                    //$numberOfPages = ceil($totalitem / $itemperpage);
                   // $pagedata = array_slice($item_list,$_GET['from'], $itemperpage, true);
                   
                    if($price_order != '') {
                        if($price_order == 'Desc') {
                            usort($item_list, function ($a, $b) {
                                 return ($b['Price']['ConvertedPriceWithoutSign'] <=> $a['Price']['ConvertedPriceWithoutSign']);
                            });
                        }else{
                            usort($item_list, function ($a, $b) {
                                 return ($a['Price']['ConvertedPriceWithoutSign'] <=> $b['Price']['ConvertedPriceWithoutSign']);
                            });
                        }
                    }                   
                    
                    $show_hide = 'hide_pagination';
                    
			     	foreach ($item_list as $itml) { $show_hide = '';

              if($itml['ErrorCode']!="NotFound"){
                  
                if(!empty($price_range_max) || !empty($price_range_min)){   
                    if( !empty($price_range_max) && $itml['Price']['ConvertedPriceWithoutSign']  > $price_range_max ) { continue; }
                    if( !empty($price_range_min) && $itml['Price']['ConvertedPriceWithoutSign']  < $price_range_min ) { continue; }
                }
				?>

	<li class="product-col product-outimage_aq_onimage product type-product post-3415 status-publish first instock has-post-thumbnail shipping-taxable purchasable product-type-simple">
	<div class="product-inner">

		<div class="product-image">

    <a href="https://borderlesscommerce.net/1688-to-borderless-commerce/?url=https%3A%2F%2Fdetail.1688.com%2Foffer%2F<?php echo trim($itml['Id'],"abb-"); ?>.html">
				<div class="inner"><img width="" src="<?php echo $itml['MainPictureUrl'];?>" class="external-img wp-post-image " alt="<?php echo $itml['Title'];?>"></div>		</a>
				<div class="links-on-image">
				<div class="add-links-wrap">
		<div class="add-links clearfix">
    <a href="https://borderlesscommerce.net/1688-to-borderless-commerce/?url=https%3A%2F%2Fdetail.1688.com%2Foffer%2F<?php echo trim($itml['Id'],"abb-"); ?>.html" data-quantity="1" class="viewcart-style-2 button product_type_simple add_to_cart_button ajax_add_to_cart"   aria-label="<?php echo $itml['Title'];?>" rel="nofollow">Add to cart</a>
			</div>
		</div>
			</div>
			</div>

		<div class="product-content">
			<span class="category-list"></span>
				<a class="product-loop-title" href="">
		<h3 class="woocommerce-loop-product__title"><?php echo $itml['Title'];?></h3>	</a>



		<span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $itml['Price']['ConvertedPriceWithoutSign'];?></span></span>

				</div>
	</div>
	</li>

		<?php
              }
			}
//$from  = $_GET['from'];
$from_n = $from+$itemperpage;
if($from>$itemperpage)
{
	$from_p = $from-$itemperpage;
}
else{
		$from_p = 0;
}

/***************** new url logic *********************/
    
       $url_parameter = empty($url)?'': '&url='.$url;
       $price_order_URL = empty($price_order)?'': '&price='.$price_order;
       $price_min_URL = empty($price_range_min)?'': '&price_range_min='.$price_range_min;
       $price_max_URL = empty($price_range_max)?'': '&price_range_max='.$price_range_max;

    $pagination_parameters = $url_parameter.$price_order_URL.$price_min_URL.$price_max_URL;

$paginationURL = 'https://borderlesscommerce.net/1688-to-borderless-commerce/?from='.$from.$pagination_parameters;
$paginationURL_n = 'https://borderlesscommerce.net/1688-to-borderless-commerce/?from='.$from_n.$pagination_parameters;
$paginationURL_p = 'https://borderlesscommerce.net/1688-to-borderless-commerce/?from='.$from_p.$pagination_parameters;


/***************** new url logic *******************/



// $paginationURL = 'https://borderlesscommerce.net/1688-to-borderless-commerce/?from='.$from.'&url='.$url;
// $paginationURL_n = 'https://borderlesscommerce.net/1688-to-borderless-commerce/?from='.$from_n.'&url='.$url;
// $paginationURL_p = 'https://borderlesscommerce.net/1688-to-borderless-commerce/?from='.$from_p.'&url='.$url;
	 ?>

</ul>
    <div class="<?php echo $show_hide; ?>">
		<div class="pagination">

		  <a href="<?php echo $paginationURL_p;?>">&laquo;</a>
			<?php
				$from1 = 0+$view_number;
                
                
        for($i=1 + $increment_number;$i<$pageperview +1 +$increment_number;$i++){
          if($from1 == $_GET['from']){
          $class = 'active';
        }
    //   echo	'<a href="https://borderlesscommerce.net/1688-to-borderless-commerce/?from='.$from1.'&url='.$url.'" class="'.$class.'">'.$i.'</a>';
      echo	'<a href="https://borderlesscommerce.net/1688-to-borderless-commerce/?from='.$from1.''.$pagination_parameters.'" class="'.$class.'">'.$i.'</a>';
	$from1 = $from1+$itemperpage;
	$class = ' ';
			}
			?>
		  <a href="<?php echo $paginationURL_n;?>">&raquo;</a>
		</div>
    </div>
</div>

<?php
}



get_footer(); ?>