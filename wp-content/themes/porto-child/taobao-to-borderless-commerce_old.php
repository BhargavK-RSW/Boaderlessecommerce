<?php

/* Template Name: Taobao to Borderless Commerce */

get_header();
$provider=$_GET['api-url'];

$_SESSION['provider'] = $provider;


define('CFG_SERVICE_INSTANCEKEY', 'opendemo');
define('CFG_REQUEST_LANGUAGE', 'en');
define('CFG_SESSIONID', '');
define('CFG_FRAMEPOSITION', '0');
define('CFG_FRAMESIZE', '20');
define('CFG_BLOCKLIST', '');

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



$url = $_GET['url'];

//https://detail.tmall.com/item.htm?id=528555529970
//$link = "https://world.taobao.com/item/616606689587.htm?spm=a21wu.10013406-cat-tw.0.0.28e25116D0Opvd";

//$link ="https://world.taobao.com/item/623346006409.htm?spm=a21wu.10013406-cat-tw.0.0.28e25116D0Opvd";

// Validate url
if (filter_var($url, FILTER_VALIDATE_URL)) {

$str2 = explode(".htm",$url);
$str3 = explode("item/",$str2[0]);
$itemId_taobao = $str3[1];

$a=explode("&",$url);
$b=explode("id=",$a[0]); 
$itemId_tmall = $b[1]; 


if (isset($itemId_taobao)){
 $itemId=$itemId_taobao;   
}
else if(isset($itemId_tmall))
{
  $itemId=$itemId_tmall;   
}
else{
  $itemId="";  
}


if(!isset($itemId) ||  $itemId==""){
    
echo "<script>
alert('Error: Please input correct Taobao product link.');
window.location.href='http://borderlesscommerce.net';
</script>";

}

$link = 'http://otapi.net/OtapiWebService2.asmx/GetItemFullInfoWithPromotions?instanceKey=' . CFG_SERVICE_INSTANCEKEY
        . '&language=' . CFG_REQUEST_LANGUAGE
        . '&itemId=' . $itemId;
        
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
<h2></h2>
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

                                          $imageModule = array();
                                          $imageModule = $data['OtapiItemFullInfo']['Pictures']['ItemPicture'];


																					$gi=1;
                        if(is_array($imageModule)){

                        foreach($imageModule as $imagegallary){
                      

                          	?>

                        <div id="img_<?php echo $gi; ?>" class="carousel-item<?php if($gi==1){ echo " active";}?>">
                          <img class="d-block w-100" src="<?php echo $imagegallary['Url']; ?>">
                        </div>

                        <?php $gi++; } } ?>

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
										 if(is_array($imageModule)){

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

                <div class="col-md-7 py-4 bg-gray" id="product-details">
                    <h5 id="product-title" data-src-link="<?php echo $url;?>"><?php echo $itemInfo['Title'] ?></h5>
 
                    <hr class="mt-2 mb-3">

                    <h6 id="product-price" class="mb-2">
                        <span class="font-weight-bold">Price:</span>
                        <span class="formatted-price" id="formatted-price">

							<?php
							  
							  $original_price = $data['OtapiItemFullInfo']['Price']['ConvertedPriceWithoutSign'];
                             $ali243fee = get_field( "per_product_fees", 143 );
							 $ali243comission = get_field( "commission_on_aliexpress_product", 143 );
							 $ali243comission = $ali243comission/100;
							 $original_amount=$original_price + $ali243fee;
							
                           if($data['OtapiItemFullInfo']['Promotions'] == true && !empty($data['OtapiItemFullInfo']['Promotions']['OtapiItemPromotion']['Price']['ConvertedPriceWithoutSign'])){
							$promo_price = $data['OtapiItemFullInfo']['Promotions']['OtapiItemPromotion']['Price']['ConvertedPriceWithoutSign'];
							 $promo_amount=$promo_price + $ali243fee;  
							 ?>
							  
							  <span class="discount-price mr-2"> $ <?php echo round(($promo_amount+$promo_amount*$ali243comission), 2); ?></span>
							  <del>$ <?php echo round(($original_amount+$original_amount*$ali243comission), 2);?></del>
							  <?php
                           }
                           else
                           {
                                ?>
                            <span class="discount-price mr-2"> $ <?php echo round(($original_amount+$original_amount*$ali243comission), 2); ?></span>
                            <?php

                           }
							

							?>
                              


                    </h6>
                     <label for="categoryField" class="my-1 mr-2 font-size-14"><strong>Category: </strong><?php echo getcategory($data['OtapiItemFullInfo']['CategoryId']);?></label><br/>

                    <div id="product-variations">



<?php


						$Product_cat_id = $data['OtapiItemFullInfo']['CategoryId'];
						 $cat_name = getcategory($Product_cat_id);
						$terms = get_term_by( 'name', $cat_name, 'taobao_product_cat', 'ARRAY_A' );
						 $category_id = $terms['term_id'];

							$shipp_cost = get_term_meta( $category_id, 'taobao_shipping_cost', true);
							$shipp_deliver_time = get_term_meta( $category_id, 'taobao_delivery_time_in_days', true);
							$categ_n = $cat_name;

	             if(empty($shipp_cost) || $shipp_cost < 1){
					 $shipp_cost = 10;
					 $shipp_deliver_time  = "40-50";
				 }
				 
				 
			 
				  
                    
                    $product_attributes = array();
                    $product_attributes = $data['OtapiItemFullInfo']['Attributes']['ItemAttribute'];
                   // print_r($product_attributes);
                      
									
											$variations=array();
											$nonvariations=array();
											
                      foreach($product_attributes as $proAtt)
                      {
                          
                          if($proAtt['IsConfigurator']=='true')
                          {
                              array_push($variations,$proAtt);
                          }
                          if($proAtt['IsConfigurator']=='false')
                          {
                               array_push($nonvariations,$proAtt);
                          }
  
                      }
                       
                      //print_r($variations);
                      
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

                        $variationsgroup=groupify($variations);
                       // print_r($variationsgroup);
                       
                        $variation_ordering=array();
                         foreach($variationsgroup as $key){
                              array_push($variation_ordering,$key[0]['@attributes']['Pid']);
                             
                         }
				 
				 
				 
                    $config_items = array();
                    $config_items = $data['OtapiItemFullInfo']['ConfiguredItems']['OtapiConfiguredItem'];
                    $promoconfig=false;
                    if($data['OtapiItemFullInfo']['Promotions'] == true){
                    $promo_config_items = array();
                    $promo_config_items = $data['OtapiItemFullInfo']['Promotions']['OtapiItemPromotion']['ConfiguredItems']['Item'];
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
                                
                                $original1_amount=$ci['Price']['ConvertedPriceWithoutSign'] + $ali243fee;
                                $original1_amount=round(($original1_amount+$original1_amount*$ali243comission), 2);
                                $promo1_amount=$pci['Price']['ConvertedPriceWithoutSign'] + $ali243fee;
                                $promo1_amount=round(($promo1_amount+$promo1_amount*$ali243comission), 2);

                           echo '<input type="hidden" id="'.$c_id.'" data-price="'.$original1_amount.'" data-promo-price="'.$promo1_amount.'" data-quantity="'.$ci['Quantity'].'">'; 
  
                               
                            }
                            
                            }
                        }
                        else{
                            
                            $original1_amount=$ci['Price']['ConvertedPriceWithoutSign'] + $ali243fee;
                            $original1_amount=round(($original1_amount+$original1_amount*$ali243comission), 2);

                           
                           echo '<input type="hidden" id="'.$c_id.'" data-price="'.$original1_amount.'" data-quantity="'.$ci['Quantity'].'">';
                            
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
                            <li class="list-inline-item prop-value<?php if($prd_li == 1){ echo ' selected';}?>" data-prop-pid="<?php echo $v['@attributes']['Pid'];?>" data-prop-vid="<?php echo $v['@attributes']['Vid'];?>" data-name="<?php echo $v['Value'];?>" data-target="#carousel-thumb" data-slides="<?php echo $v['Vid']; ?>" data-prop-image="<?php echo $v['MiniImageUrl'];?>">
                              <a title="<?php echo $v['Value'];?>"><img src="<?php echo $v['MiniImageUrl'];?>" alt="<?php echo $v['Value'];?>"></a>
                            </li>


                          <?php }
                                                        else{
                                                            ?>
                                                            <li class="list-inline-item prop-value <?php if($prd_li == 1){ echo 'selected';}?>" data-prop-pid="<?php echo $v['@attributes']['Pid'];?>" data-prop-vid="<?php echo $v['@attributes']['Vid'];?>" data-name="<?php echo $v['Value'];?>" >
                                                        <span><?php echo $v['Value'];?></span></li>
                                                    <?php   } $prd_li++;
                                                } echo '</ul></div>';  $prd_i++; }?> 
                                                


                    <div id="quantity" class="form-inline mb-2">

                        <label for="quantityField" class="my-1 mr-2 font-size-14"><strong>Quantity:</strong></label>

												<td class="product-quantity">
														<div id="product-quantity" class="quantity buttons_added">
															<button type="button" value="-" class="minus">-</button>
																<input type="number" id="quantityField" class="input-text qty text" step="1" min="1" max="<?php echo $data['OtapiItemFullInfo']['MasterQuantity']; ?>" name="productquantity" value="1" title="Qty" size="4" inputmode="numeric" onchange="javascript:get_price_and_sku();">
															<button type="button" value="+" class="plus">+</button>
														</div>
												</td>
											  <p class="mb-0"><span class="qty-ability"><?php echo $data['OtapiItemFullInfo']['MasterQuantity']; ?></span> Is available.</p>
                    </div>
                    <div id="aliexpress-shipping-methods">
                        <label for="shippingMethods" class="d-block font-size-14 mb-1"><strong>Delivery methods:</strong></label>

                        <select id="shippingMethods" class="custom-select">

													<option value="<?php echo $shipp_cost; ?>" data-service-name="Borderless Commerce Shipping" data-service-company="Borderless Commerce Shipping Company"> Shipping Company (Time: <?php echo $shipp_deliver_time;?> Days) - <?php echo $shipp_cost; ?> USD</option>

													<?php
													/*
													foreach($shipping_data as $shpng_data){
														$shpngcost = $shpng_data['freightAmount']['value'];
                          $totalshpng = $shpngcost+$shpngcost*0.1;

														?>

													<option value="<?php echo $totalshpng;?>" data-service-name="<?php echo $shpng_data['serviceName'];?>" data-service-company="<?php echo $shpng_data['company'];?>"> <?php echo $shpng_data["company"].' (Temps: '.$shpng_data["time"].' Journées) - '.$totalshpng; ?> USD</option>

												<?php } */?>
												</select>
                    </div>

                    <h6 id="totalPrice" class="my-2 d-none"></h6>
										<h6 id="totalshpngPrice" class="my-2 d-none"></h6>

                    <div style="color:#ffffff;" class="d-sm-flex flex-cols-wide mt-2 mb-2">

                    <a id="ali243-add-cart" class="btn btn-primary mr-sm-2"><i class="fa fa-cart-plus"></i> Add to cart</a>
                    <a id="ali243-buy-now" class="btn btn-brand"><i class="fa fa-shopping-bag"></i> Buy Now</a>
                    </div>
                    <div id="txtHint" class="my-2"></div>
                    <div id="productloader1" class="my-2 d-none"><img src="http://ali243.com/wp-content/uploads/2020/06/loader50px.gif" alt="ali243 product loader"></div>
										<div id="productloader2" class="my-2 d-none"><img src="http://ali243.com/wp-content/uploads/2020/06/loader50px.gif" alt="ali243 product loader"></div>
								</div>
            </div>
<?php
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
											$finalprice = $converttousd + $ali243fee + $converttousd*$ali243comission;
										}
										else{
											 $converttousd = $eur_to_usd*$skuPrc["skuVal"]["actSkuBulkCalPrice"];
											$finalprice = $converttousd + $ali243fee + $converttousd*$ali243comission;
										}
									}
									else{
										$converttousd = $eur_to_usd*$skuPrc["skuVal"]["skuAmount"]["value"];
										$finalprice = $converttousd + $ali243fee + $converttousd*$ali243comission;
									}

							$skuprc_val = $skuPrc["skuId"].'_'.$finalprice;


							foreach($skuprc_id as $sku_prc_id){

								$skuprcid = $skuprcid.'DJB'.$sku_prc_id;
							}


              echo '<input type="hidden" id="'.$skuprcid.'" value="'.$skuprc_val.'">';

							}

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
        . '&itemId=' . $itemId;
 
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

};

for (var i = 0; i < gallarythumbs.length; i++) {
    gallarythumbs[i].addEventListener('click', myFunction, false);
}

function get_price_and_sku()
{
	var elementsLi = document.getElementsByClassName('list-inline-item prop-value selected');
  var sku_custom_id = "";

	for (var i=0, len=elementsLi.length|0; i<len; i=i+1|0) {

         var sDataValue = elementsLi[i].getAttribute('data-prop-vid');
         sku_custom_id = sku_custom_id.concat(sDataValue);

	}
	console.log(sku_custom_id);


	var productprice= document.getElementById(sku_custom_id).getAttribute("data-price");
	var productpricepromo = document.getElementById(sku_custom_id).getAttribute("data-promo-price");
    var productqty = document.getElementById(sku_custom_id).getAttribute("data-quantity");
     var shippingcost_1 = document.getElementById("shippingMethods");
     var shippingcost = shippingcost_1.value;
	var productQuantity = document.getElementById("quantityField").value;
	document.getElementById("quantityField").setAttribute("max",productqty );

	shippingcost = (Number(shippingcost) + (0.6 * Number(shippingcost))*(Number(productQuantity)-1)).toFixed(2);
	var totalshpngPriceId = document.getElementById("totalshpngPrice");
	var shippingcompany = shippingcost_1.options[shippingcost_1.selectedIndex].getAttribute('data-service-company');
	totalshpngPriceId.innerHTML = 'Total shipping price: ' .concat(shippingcost).concat(" USD by ").concat(shippingcompany);
	totalshpngPriceId.classList.remove("d-none");

	var toatalPrice = (Number(productQuantity) * Number(productprice)).toFixed(2);
	var toatalPricePromo = (Number(productQuantity) * Number(productpricepromo)).toFixed(2);
    var qa = document.getElementsByClassName("qty-ability")[0];
	var fp = document.getElementById("formatted-price").childNodes;
    qa.innerHTML=productqty;
	//totalPriceId.innerHTML = "$ ".concat(toatalPrice);
		console.log(fp[0]);
	console.log(fp[1]);
	fp[1].innerHTML="$ ".concat(toatalPricePromo);
	if(fp[3]){
	  fp[3].innerHTML="$ ".concat(toatalPrice);  
	}
	else{
	   fp[1].innerHTML="$ ".concat(toatalPrice); 
	}
//	totalPriceId.classList.remove("d-none");

}

document.getElementById("shippingMethods").addEventListener("change", function() {

get_price_and_sku();
});

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
  });
}
<?php $prd_i++; }?>


document.getElementById("ali243-add-cart").onclick = function() {  createproduct(); };
document.getElementById("ali243-buy-now").onclick = function() { createproduct(); window.location.href = "http://borderlesscommerce.net/checkout/";};


function createproduct() {
    
    console.log('createproductclicked');

document.getElementById("productloader1").classList.remove("d-none");

var elementsLi = document.getElementsByClassName('list-inline-item prop-value selected');
  var sku_custom_id = "";
    var txt = "";
	var imagelink;

	for (var i=0, len=elementsLi.length|0; i<len; i=i+1|0) {
	    console.log(len);

         var sDataValue = elementsLi[i].getAttribute('data-prop-vid');
         console.log(sDataValue);
         sku_custom_id = sku_custom_id.concat(sDataValue);
         console.log(sku_custom_id);


	}


	var productprice= document.getElementById(sku_custom_id).getAttribute("data-price");
	var productpricepromo = document.getElementById(sku_custom_id).getAttribute("data-promo-price");
	console.log(productprice);
	console.log(productpricepromo);
     var shippingcost_1 = document.getElementById("shippingMethods");
     var shippingcost = shippingcost_1.value;
     var productQuantity = document.getElementById("quantityField").value;
	shippingcost = (Number(shippingcost) + (0.6 * Number(shippingcost))*(Number(productQuantity)-1)).toFixed(2);
	var totalshpngPriceId = document.getElementById("totalshpngPrice");
	var shippingcompany = shippingcost_1.options[shippingcost_1.selectedIndex].getAttribute('data-service-company');

    var producttitle = document.getElementById('product-title');
    
    var toatalPrice = (Number(shippingcost) + (Number(productQuantity) * Number(productprice))).toFixed(2);
	
	if(productpricepromo !==null && productpricepromo!==""){
	    	var toatalPrice = (Number(shippingcost) + (Number(productQuantity) * Number(productpricepromo))).toFixed(2);
	}


 var txt = txt + "<br/> SKU: " + sku_custom_id + "<br/> Ship by: " + shippingcompany;
  //totalPriceId.innerHTML = "$ ".concat(toatalPrice);
  //totalPriceId.classList.remove("d-none");

			 //let imagelink = document.querySelectorAll('div#product-variations li.selected img').src;

		if(imagelink==null ){ imagelink = "<?php echo $itemInfo['MainPictureUrl'];?>";}

       var productlink = "<?php echo $_GET['url']; ?>";
       console.log(toatalPrice);

      var str = "price=" + toatalPrice + "&title=" + producttitle.textContent + "&link=" + productlink + "&content=" + txt + "&image=" + imagelink + "&quantity=" + productQuantity;

       var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
								document.getElementById("productloader1").classList.add("d-none");
            }
        }
        xmlhttp.open("GET", "http://borderlesscommerce.net/saveproduct.php?"+str, true);
        xmlhttp.send();


			}


</script>


<?php
}

else

{
    
   
$url1 = str_replace(" ","%20",$url);
$xmlp='<SearchItemsParameters><Provider>Taobao</Provider><SearchMethod>Extended</SearchMethod><IsClearItemTitles>false</IsClearItemTitles><ItemTitle>'.$url1.'</ItemTitle><UseOptimalFrameSize>true</UseOptimalFrameSize></SearchItemsParameters>';
$xmlpl = str_replace("<","%3c",$xmlp);
$xmlpr = str_replace(">","%3e",$xmlpl);
$link = 'http://otapi.net/OtapiWebService2.asmx/BatchSearchItemsFrame?instanceKey=' . CFG_SERVICE_INSTANCEKEY
        . '&language=' . CFG_REQUEST_LANGUAGE
        . '&sessionId=' . CFG_SESSIONID
        . '&xmlParameters=' . $xmlpr
        . '&framePosition=' . CFG_FRAMEPOSITION
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
 
$itemInfo = $xmlObject->Result->Item;


	?>

	<div class="archive-products">

	<h6> You Search for Keyword: <?php echo $url; ?></h6>

	<div class="wpb_wrapper vc_column-inner">
		<div class="wpb_text_column wpb_content_element ">
			<div class="wpb_wrapper">
				<div class="vc_wp_search wpb_content_element">
	<div class="widget widget_search">
	<form id="searchform" class="searchform" action="http://borderlesscommerce.net/taobao-to-borderless-commerce/" method="get">
	<div class="input-group">
		<input id="url" class="form-control" autocomplete="off" name="url" type="text" placeholder="Enter the Taobao unique product link here"><br>
	<span class="input-group-append"><br>
	<button class="btn btn-dark p-2" type="submit"><i class="fas fa-search m-2"></i></button><br>
	</span></div>
	</form>
	</div>
	</div>

			</div>
		</div>
	</div>



			<ul class="products products-container grid pcols-lg-4 pcols-md-3 pcols-xs-3 pcols-ls-2 pwidth-lg-4 pwidth-md-3 pwidth-xs-2 pwidth-ls-1" data-product_layout="product-outimage_aq_onimage">

				<?php
                    $item_list = array();
                    $item_list = $data_s['Result']['Items']['Items']['Content']['Item'];
                    $from = isset($_GET['from']) ? $_GET['from'] : 0;
                    $totalitem=count($item_list);
                    $itemperpage=20;
                    $numberOfPages = ceil($totalitem / $itemperpage);
                    $pagedata = array_slice($item_list,$_GET['from'], $itemperpage, true);
                    
			     	foreach ($pagedata as $itml) {

                 	

				?>

	<li class="product-col product-outimage_aq_onimage product type-product post-3415 status-publish first instock has-post-thumbnail shipping-taxable purchasable product-type-simple">
	<div class="product-inner">

		<div class="product-image">

			<a href="http://borderlesscommerce.net/taobao-to-borderless-commerce/?url=https%3A%2F%2Fworld.taobao.com%2Fitem%2F<?php echo $itml['Id']; ?>.htm%3Fspm%3Da21wu.10013406-cat-tw.0.0.28e25116D0Opvd">
				<div class="inner"><img width="" src="<?php echo $itml['MainPictureUrl'];?>" class="external-img wp-post-image " alt="<?php echo $itml['Title'];?>"></div>		</a>
				<div class="links-on-image">
				<div class="add-links-wrap">
		<div class="add-links clearfix">
			<a href="" data-quantity="1" class="viewcart-style-2 button product_type_simple add_to_cart_button ajax_add_to_cart"   aria-label="<?php echo $itml['Title'];?>" rel="nofollow">Add to cart</a>
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
//$from  = $_GET['from'];
$from_n = $from+$itemperpage;
if($from>$itemperpage)
{
	$from_p = $from-$itemperpage;
}
else{
		$from_p = 0;
}

			$paginationURL = 'http://borderlesscommerce.net/taobao-to-borderless-commerce/?from='.$from.'&url='.$url;
			$paginationURL_n = 'http://borderlesscommerce.net/taobao-to-borderless-commerce/?from='.$from_n.'&url='.$url;
			$paginationURL_p = 'http://borderlesscommerce.net/taobao-to-borderless-commerce/?from='.$from_p.'&url='.$url;
	 ?>

</ul>
		<div class="pagination">

		  <a href="<?php echo $paginationURL_p;?>">&laquo;</a>
			<?php
				$from1 = 0;
                
                
			for($i=1;$i<$numberOfPages+1;$i++){
				if($from1 == $_GET['from']){
				$class = 'active';
			}
			echo	'<a href="http://borderlesscommerce.net/taobao-to-borderless-commerce/?from='.$from1.'&url='.$url.'" class="'.$class.'">'.$i.'</a>';
	$from1 = $from1+$itemperpage;
	$class = ' ';
			}
			?>
		  <a href="<?php echo $paginationURL_n;?>">&raquo;</a>
		</div>

</div>

<?php
}



get_footer(); ?>
