<?php /* Template Name: AliExpress3 */

get_header();

$url = $_GET['url'];
$from = 0;
// Validate url
if (filter_var($url, FILTER_VALIDATE_URL)) {

$IDfromurl = explode('item/', $url);
$IDfromurl1 = explode('.html', $IDfromurl[1]);

if(!empty($IDfromurl1[0])){

$curlopturl = 'https://ali-express1.p.rapidapi.com/product/'.$IDfromurl1[0].'?language=fr';
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
		"x-rapidapi-key: b061357a12mshdf418c9fe6573efp156a0ejsn68d37fb0072f"
	),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
}

else {

$data = json_decode($response,true);

$title = $data['pageModule']['title'];
$descriptionurl = $data['descriptionModule']['descriptionUrl'];

$description = $data['pageModule']['description'].'<br />'.file_get_contents($descriptionurl);

/*
$curlopturl1 = 'https://ali-express1.p.rapidapi.com/shipping/'.$IDfromurl1[0].'?destination_country=CD&language=fr';

$curl1 = curl_init();

curl_setopt_array($curl1, array(
	CURLOPT_URL => $curlopturl1,
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

	 $shipping_response = curl_exec($curl1);

$err1 = curl_error($curl1);

curl_close($curl1);

if ($err1) {
	echo "cURL Error #:" . $err1;
} else {

	 $shipping_data_jsn = json_decode($shipping_response,true);
	 $shipping_data = $shipping_data_jsn['body']['freightResult'];

}

*/
}
}
else{
	echo '<h2>Veuillez saisir le lien de produit aliexpress correct <a href="http://www.ali243.com">Retourner</h2>';
}

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
                                          $imageModule = $data['imageModule']['imagePathList'];


																					$gi=1;
                        if(is_array($imageModule)){

                        foreach($imageModule as $imagegallary){

                        $idinurl = explode('/', $imagegallary);
                          	?>

                        <div id="<?php echo $idinurl[4]; ?>" class="carousel-item<?php if($gi==1){ echo " active";}?>">
                          <img class="d-block w-100" src="<?php echo $imagegallary; ?>">
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
										 if(is_array($imageModule)){

										 foreach($imageModule as $imagegallary){
											 $idinur = explode('/', $imagegallary);
												?>

										 <li data-target="#carousel-thumb" data-slides="<?php echo $idinur[4]; ?>" class="gallarythumb">
											 <img class="d-block w-100" src="<?php echo $imagegallary; ?>" class="img-fluid">
											</li>

										 <?php  } } ?>

									</ul>

								</div></div></div>
                  <!--/.Carousel Wrapper-->



						 <!--/.Controls-->

                </div>

                <div class="col-md-7 py-4 bg-gray" id="product-details">
                    <h5 id="product-title" data-src-link="<?php echo $url;?>"><?php echo $title;?></h5>
                    <p class="mb-0 product-evaluation">
                      <span><?php echo $data['titleModule']['tradeCount']; ?>  Ordres</span>
                      <span><?php echo $data['titleModule']['feedbackRating']['averageStar']; ?>  Évaluations</span>
                    </p>
                    <hr class="mt-2 mb-3">
                    <h6 id="product-price" class="mb-2">
                        <span class="font-weight-bold">Prix:</span>
                        <span class="formatted-price" id="formatted-price">

							<?php

							$price = $data['priceModule'];
               $ali243fee = get_field( "per_product_fees", 143 );
							 $ali243comission = get_field( "commission_on_aliexpress_product", 143 );
							 $ali243comission = $ali243comission/100;
							 $minActivityAmount = $ali243fee+$eur_to_usd*$price['minActivityAmount']['value'];
							 $maxActivityAmount = $ali243fee+$eur_to_usd*$price['maxActivityAmount']['value'];
							 $minAmount = $ali243fee+$eur_to_usd*$price['minAmount']['value'];
							 $maxAmount = $ali243fee+$eur_to_usd*$price['maxAmount']['value'];
							//if($price['discountPromotion'] == true || $minActivityAmount<$minAmount){
								if($price['discountPromotion'] == true || !empty($price['minActivityAmount']['value'])){
							?>
                                  <span class="discount-price mr-2">USD <?php echo round(($minActivityAmount+($minActivityAmount*$ali243comission)), 2) .'- '.round(($maxActivityAmount+$maxActivityAmount*$ali243comission), 2); //$data['priceModule']['formatedActivityPrice']; ?></span>
                                <span class="discount-sku-price mr-2">USD <?php echo round(($minActivityAmount+$minActivityAmount*$ali243comission), 2) .'- '.round(($maxActivityAmount+$maxActivityAmount*$ali243comission), 2); //$data['priceModule']['formatedActivityPrice']; ?></span>
                                <small>
                                    <del class="original-sku-price">USD <?php echo round(($minAmount+$minAmount*$ali243comission), 2) .'- '.round(($maxAmount+$maxAmount*$ali243comission), 2); ?></del>
                                    <del class="original-price">USD <?php echo round(($minAmount+$minAmount*$ali243comission), 2) .'- '.round(($maxAmount+$maxAmount*$ali243comission), 2); ?></del>
                                </small>
                                <span class="badge badge-pale badge-danger discount-amount">-<?php echo $data['priceModule']['discount']; ?>%</span>
                                                    </span>
								<?php }
								else{
									?>
									<span class="discount-price mr-2">USD <?php echo round(($minAmount+$minAmount*$ali243comission), 2) .'- '.round(($maxAmount+$maxAmount*$ali243comission), 2); //$data['priceModule']['formatedActivityPrice']; ?></span>
								<span class="discount-sku-price mr-2">USD <?php echo round(($minAmount+$minAmount*$ali243comission), 2) .'- '.round(($maxAmount+$maxAmount*$ali243comission), 2); //$data['priceModule']['formatedActivityPrice']; ?></span>


									<?php
								}
								?>
                    </h6>

                    <div id="product-variations">



<?php

	                  $skucategory = $data['crossLinkModule']['breadCrumbPathList'];
	                  $reversed = array_reverse($skucategory);

	                foreach($reversed as $skuCat){
						 $Ali_cat_id = $skuCat['cateId'];
						 $cat_name = $skuCat['name'];

						$terms = get_term_by( 'name', $cat_name, 'product_cat', 'ARRAY_A' );
						 $category_id = $terms['term_id'];

						$k = 0;
						if($category_id && $k == 0){
							$shipp_cost = get_term_meta( $category_id, 'shipping_cost', true);
							$shipp_deliver_time = get_term_meta( $category_id, 'delivery_time_in_days', true);
							$categ_n = $cat_name;
							$k++;
						}

					}

	             if(empty($shipp_cost) || $shipp_cost < 1){
					 $shipp_cost = 10;
					 $shipp_deliver_time  = "40-50";
				 }


                      $skuModule = $data['skuModule']['productSKUPropertyList'];
                      $product_attributes = array();
											$prd_i = 1;
                      foreach($skuModule as $skuMod){
                        ?>
                        <div class="product-prop">
							 <label for="categoryField" class="my-1 mr-2 font-size-14"><strong>Catégorie:</strong><?php echo $categ_n;?></label><br/>
                            <p class="prop-name mb-1" data-prop-id="14">
                                <strong class="prop-key font-weight-bold"><?php echo $skuMod['skuPropertyName'];?></strong><span class="font-weight-bold">:</span> <small class="prop-val"></small>
                            </p>

                            <ul id="product_var_<?php echo $prd_i;?>" class="list-unstyled prop-values">

                         <?php
												 $prd_li = 1;
                        foreach($skuMod['skuPropertyValues'] as $skuModkeyv)
                          {
                            $idinur = explode('/', $skuModkeyv['skuPropertyImagePath']);
														if($idinur[4]){
                            ?>
                            <li class="list-inline-item prop-value<?php if($prd_li == 1){ echo ' selected';}?>" data-prop-value-id="<?php echo $skuModkeyv['propertyValueId'];?>" data-name="<?php echo $skuModkeyv['propertyValueDisplayName'];?>" data-target="#carousel-thumb" data-slides="<?php echo $idinur[4]; ?>" data-prop-image="<?php echo $skuModkeyv['skuPropertyImagePath'];?>">
                              <a title="<?php echo $skuModkeyv['propertyValueDisplayName'];?>"><img src="<?php echo $skuModkeyv['skuPropertyImageSummPath'];?>" alt="<?php echo $skuModkeyv['propertyValueDisplayName'];?>"></a>
                            </li>


                          <?php }
														else{
															?>
															<li class="list-inline-item prop-value <?php if($prd_li == 1){ echo 'selected';}?>" data-prop-value-id="<?php echo $skuModkeyv['propertyValueId'];?>" data-name="<?php echo $skuModkeyv['propertyValueDisplayName'];?>">
																	<span><?php echo $skuModkeyv['propertyValueDisplayName'];?></span></li>
													<?php	} $prd_li++;
												} echo '</ul></div>';  $prd_i++; }?>


                    <div id="quantity" class="form-inline mb-2">

                        <label for="quantityField" class="my-1 mr-2 font-size-14"><strong>Quantité:</strong></label>

												<td class="product-quantity">
														<div id="product-quantity" class="quantity buttons_added">
															<button type="button" value="-" class="minus">-</button>
																<input type="number" id="quantityField" class="input-text qty text" step="1" min="1" max="<?php echo $data['actionModule']['totalAvailQuantity']; ?>" name="productquantity" value="1" title="Qty" size="4" inputmode="numeric" onchange="javascript:get_price_and_sku();">
															<button type="button" value="+" class="plus">+</button>
														</div>
												</td>
											  <p class="mb-0"><span class="qty-ability"><?php echo $data['actionModule']['totalAvailQuantity']; ?></span> Est disponible.</p>
                    </div>
                    <div id="aliexpress-shipping-methods">
                        <label for="shippingMethods" class="d-block font-size-14 mb-1"><strong>méthodes de livraison:</strong></label>

                        <select id="shippingMethods" class="custom-select">

													<option value="<?php echo $shipp_cost; ?>" data-service-name="Ali243 Shipping" data-service-company="Ali243 Shipping Company"> Ali243 Shipping Company (Temps: <?php echo $shipp_deliver_time;?> Journées) - <?php echo $shipp_cost; ?> USD</option>

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
            <div id="single-product-details" class="col-md-8 mx-auto">
              <div class="detailmodule_html">
                <div class="detail-desc-decorate-richtext">
                <?php echo $description; ?>

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
  var sku_price = "";

	for (var i=0, len=elementsLi.length|0; i<len; i=i+1|0) {

         var sDataValue = elementsLi[i].getAttribute('data-prop-value-id');
         sku_price = sku_price.concat("DJB").concat(sDataValue);


	}
	if(sku_price == ""){
		sku_price = 'DJB';
	}


	var productprice_1 = document.getElementById(sku_price).value;
	var productpriceandsku = productprice_1.split("_");
	var productsku = productpriceandsku[0];
	var productprice = productpriceandsku[1];
	var shippingcost_1 = document.getElementById("shippingMethods");
  var shippingcost = shippingcost_1.value;
	var productQuantity = document.getElementById("quantityField").value;


	shippingcost = (Number(shippingcost) + (0.6 * Number(shippingcost))*(Number(productQuantity)-1)).toFixed(2);
	var totalshpngPriceId = document.getElementById("totalshpngPrice");
	var shippingcompany = shippingcost_1.options[shippingcost_1.selectedIndex].getAttribute('data-service-company');
	totalshpngPriceId.innerHTML = 'Prix total d`expédition: ' .concat(shippingcost).concat(" USD by ").concat(shippingcompany);
	totalshpngPriceId.classList.remove("d-none");

	var toatalPrice = (Number(productQuantity) * Number(productprice)).toFixed(2);
	var totalPriceId = document.getElementById("formatted-price");
	totalPriceId.innerHTML = "$ ".concat(toatalPrice);
	totalPriceId.classList.remove("d-none");

}

document.getElementById("shippingMethods").addEventListener("change", function() {

get_price_and_sku();
});

<?php 	$prd_i = 1;
	foreach($skuModule as $skuMod){
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
document.getElementById("ali243-buy-now").onclick = function() { createproduct(); window.location.href = "http://ali243.com/checkout/";};

function createproduct() {

document.getElementById("productloader1").classList.remove("d-none");

	var elementsLi = document.getElementsByClassName('list-inline-item prop-value selected');
  var sku_price = "";
  var txt = "";
	var imagelink;
  for (var i=0, len=elementsLi.length|0; i<len; i=i+1|0) {

 				var sDataValue = elementsLi[i].getAttribute('data-prop-value-id');
				var imgsrc = elementsLi[i].getAttribute('data-prop-image');
				var txt = txt + "<br>" + elementsLi[i].getAttribute('data-name') + "-" + sDataValue;
 				sku_price = sku_price.concat("DJB").concat(sDataValue);

				if(imgsrc !== ""){ imagelink = imgsrc;}
  }
	if(sku_price == ""){
		sku_price = 'DJB';
	}

  var productprice_1 = document.getElementById(sku_price).value;
  var productpriceandsku = productprice_1.split("_");
  var productsku = productpriceandsku[0];
  var productprice = productpriceandsku[1];
  var shippingcost_1 = document.getElementById("shippingMethods");
  var shippingcost = shippingcost_1.value;
  var productQuantity = document.getElementById("quantityField").value;

  shippingcost = (Number(shippingcost) + (0.6 * Number(shippingcost))*(Number(productQuantity)-1)).toFixed(2);
  var shippingcompany = shippingcost_1.options[shippingcost_1.selectedIndex].getAttribute('data-service-company');

  var toatalPrice = (Number(shippingcost) + (Number(productQuantity) * Number(productprice))).toFixed(2);

 var txt = txt + "<br/> SKU: " + productsku + "<br/> Ship by: " + shippingcompany;
  //totalPriceId.innerHTML = "$ ".concat(toatalPrice);
  //totalPriceId.classList.remove("d-none");

       var productprice = document.getElementsByClassName('product-price-value');
       var producttitle = document.getElementById('product-title');
			 //let imagelink = document.querySelectorAll('div#product-variations li.selected img').src;

		if(imagelink==null ){ imagelink = "<?php echo $data['pageModule']['imagePath'];?>";}

       var productlink = "<?php echo $_GET['url']; ?>";

      var str = "price=" + toatalPrice + "&title=" + producttitle.textContent + "&link=" + productlink + "&content=" + txt + "&image=" + imagelink + "&quantity=" + productQuantity;

       var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
								document.getElementById("productloader1").classList.add("d-none");
            }
        }
        xmlhttp.open("GET", "http://ali243.com/saveproduct.php?"+str, true);
        xmlhttp.send();


			}


</script>


<?php
}

else

{
 $url1 = str_replace(" ","%20",$url);;
$curl1 = curl_init();
$curlopturl1 = 'https://ali-express1.p.rapidapi.com/search?from=0&query='.$url1;
curl_setopt_array($curl1, array(
	CURLOPT_URL => $curlopturl1,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => array(
		"x-rapidapi-host: ali-express1.p.rapidapi.com",
		"x-rapidapi-key: b061357a12mshdf418c9fe6573efp156a0ejsn68d37fb0072f"
	),
));

$response_s = curl_exec($curl1);
$err1 = curl_error($curl1);

curl_close($curl1);

if ($err1) {
	echo "cURL Error #:" . $err1;
} else {

	$data_s = json_decode($response_s,true);


	?>

	<div class="archive-products">

	<h6> You Search for Keyword: <?php echo $url; ?></h6>

	<div class="wpb_wrapper vc_column-inner">
		<div class="wpb_text_column wpb_content_element ">
			<div class="wpb_wrapper">
				<div class="vc_wp_search wpb_content_element">
	<div class="widget widget_search">
	<form id="searchform" class="searchform" action="http://ali243.com/ali-to-ali243/" method="get">
	<div class="input-group">
		<input id="url" class="form-control" autocomplete="off" name="url" type="text" placeholder="Enter the Aliexpress unique product link here"><br>
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

				foreach ($data_s as $data_e) {


				$data_p = $data_e['productElements'];

				?>

	<li class="product-col product-outimage_aq_onimage product type-product post-3415 status-publish first instock has-post-thumbnail shipping-taxable purchasable product-type-simple">
	<div class="product-inner">

		<div class="product-image">

			<a href="http://ali243.com/ali-to-ali243/?url=https%3A%2F%2Fwww.aliexpress.com%2Fitem%2F<?php echo $data_e['productId'];?>.html">
				<div class="inner"><img width="" src="<?php echo $data_p['image']['imgUrl'];?>" class="external-img wp-post-image " alt="<?php echo $data_p['title']['title'];?>"></div>		</a>
				<div class="links-on-image">
				<div class="add-links-wrap">
		<div class="add-links clearfix">
			<a href="http://ali243.com/ali-to-ali243/?url=https%3A%2F%2Fwww.aliexpress.com%2Fitem%2F<?php echo $data_e['productId'];?>.html" data-quantity="1" class="viewcart-style-2 button product_type_simple add_to_cart_button ajax_add_to_cart"   aria-label="<?php echo $data_p['title']['title'];?>" rel="nofollow">Add to cart</a>
			</div>
		</div>
			</div>
			</div>

		<div class="product-content">
			<span class="category-list"></span>
				<a class="product-loop-title" href="http://ali243.com/ali-to-ali243/?url=https%3A%2F%2Fwww.aliexpress.com%2Fitem%2F<?php echo $data_e['productId'];?>.html">
		<h3 class="woocommerce-loop-product__title"><?php echo $data_p['title']['title'];?></h3>	</a>



	<div class="rating-wrap">
		<div class="rating-content"><div class="star-rating" title="" data-original-title="<?php echo $data_p['evaluation']['starRating'];?>"><span style="width:<?php echo $rating = $data_p['evaluation']['starRating']/5*100;?>%"><strong class="rating"><?php echo $data_p['evaluation']['starRating'];?></strong> out of 5</span></div></div>
	</div>


		<span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $data_p['price']['sell_price']['value'];?></span></span>

				</div>
	</div>
	</li>

		<?php

			}
$from  = $_GET['from'];
$from_n = $from+20;
if($from>20)
{
	$from_p = $from-20;
}
else{
		$from_p = 0;
}

			$paginationURL = 'http://ali243.com/ali-to-ali243/?from='.$from.'&url='.$url;
			$paginationURL_n = 'http://ali243.com/ali-to-ali243/?from='.$from_n.'&url='.$url;
			$paginationURL_p = 'http://ali243.com/ali-to-ali243/?from='.$from_p.'&url='.$url;
	 ?>

</ul>
		<div class="pagination">

		  <a href="<?php echo $paginationURL_p;?>">&laquo;</a>
			<?php
				$from1 = 0;

			for($i=1;$i<11;$i++){
				if($from1 == $_GET['from']){
				$class = 'active';
			}
			echo	'<a href="http://ali243.com/ali-to-ali243/?from='.$from1.'&url='.$url.'" class="'.$class.'">'.$i.'</a>';
	$from1 = $from1+20;
	$class = ' ';
			}
			?>
		  <a href="<?php echo $paginationURL_n;?>">&raquo;</a>
		</div>

</div>

<?php
}

}

get_footer(); ?>
