<?php

/* Template Name: PinDuoDuo */

get_header();
$SITE_URL = get_site_url();
$RMB_2_USD_RATE = 6.37;


$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://currency-converter5.p.rapidapi.com/currency/convert?format=json&from=USD&to=CNY&amount=1",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "x-rapidapi-host: currency-converter5.p.rapidapi.com",
        "x-rapidapi-key: 89b301d303mshe4450617c8e1053p16a702jsnc6088edc3ad1"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $data = json_decode($response,true);
    $responserates = $data['rates'];
}              

if($yougetcurrency == "USD") {
    $exchangerate = $responserates['USD']['rate'];
} else {
    $exchangerate = $responserates['CNY']['rate'];
}
 $RMB_2_USD_RATE =  round($exchangerate, 2 );

if(isset($_GET['gid'])){
    // echo "Product detail";
    
    $curl = curl_init();
    $gid = $_GET['gid'];
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://pinduoduo-data-service.p.rapidapi.com/Good/GoodsDetail.ashx?goods_id=$gid",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: pinduoduo-data-service.p.rapidapi.com",
            "x-rapidapi-key: 3b4ed8d56cmsh8ec359580268da1p1e5eebjsn14a8411ac21f"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $data = json_decode(json_decode($response)->ret_body);
    }

?>  
<style type="text/css">
    .selected{ border: 1px solid black; }
</style>
    <section id="single-product-app">
        <div class="container">
            <div class="row">
                <div class="col-md-5 mb-7 mb-md-0">
                    <div class="product-images images">
                        <div class="container my-4">
                        <hr class="my-4">
                            <div id="carousel-thumb" class="carousel carousel-fade carousel-thumbnails" data-ride="carousel">
                                <div class="carousel-inner" id="gallarybox" role="listbox">
                                    <?php
                                            $data = json_decode(json_decode($response)->ret_body);
                                            $imageModule = $data->sku;
                                            $c = 0;
                                            if($data->gallery){
                                                $c = count($data->gallery);
                                            }
                                            for($i = 0; $i < $c; $i++)
                                            {
                                                $URL = $data->gallery[$i]->url;
                                            }
                                            $gi=1;
                                            if(isset($imageModule[0]))
                                            {
                                                foreach($imageModule as $imagegallary)
                                                {
                    
                                    ?>
                    
                                                    <div id="img_<?php echo $gi; ?>" class="carousel-item<?php if($gi==1){ echo " active";}?>">
                                                    <img class="d-block w-100" id="imageModel" value="<?php echo $imagegallary->thumb_url; ?>"  src="<?php echo $imagegallary->thumb_url; ?>">
                                                    </div>
                                    <?php 
                                                    $gi++;
                                                } 
                                            } 
                                            else
                                            {
                                    ?>
                                                <div id="img_<?php echo $gi; ?>" class="carousel-item<?php if($gi==1){ echo " active";}?>">
                                                    <img class="d-block w-100" id="imageModel" value="<?php echo $imagegallary->thumb_url; ?>"  src="<?php echo $imagegallary->thumb_url; ?>">
                                                </div>
                                    <?php
                                            }
                                    ?>
                                </div>
                                <a class="carousel-control-prev" href="#carousel-thumb" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                                <ul class="gallarythumbs">
                                    <?php
                                        $gi=1;
                                        if(isset($imageModule[0]))
                                        {

                                            foreach($imageModule as $imagegallary)
                                            {
                                    ?>
                                                <li data-target="#carousel-thumb" data-slides="img_<?php echo $gi; ?>" class="gallarythumb">
                                                    <img class="d-block w-100" src="<?php echo $imagegallary->thumb_url;; ?>" class="img-fluid">
                                                </li>
                                    <?php 
                                                $gi++; 
                                            } 
                                        } 
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-7 py-4 bg-gray" id="product-details">
                    <h5 id="product-title" data-src-link="https://world.taobao.com/item/621454639920.htm?spm=a21wu.10013406-cat-tw.0.0.28e25116D0Opvd"><?php echo $data->goods_name; ?></h5>
                    <hr class="mt-2 mb-3">
                    <h6 id="product-price" class="mb-2">
                        <span class="font-weight-bold">Price:</span>
                        <span class="formatted-price" id="formatted-price">
                            <?php 
                            $min_group_price = substr($data->min_group_price, 0, -2); // min_normal_price
                            $USD_price = $min_group_price / $RMB_2_USD_RATE;
                            $USD_price = $USD_price + ($USD_price * 10) /100;
                            $formatted_price = format_number($USD_price);
                            ?>
                            <span class="discount-price mr-2" id="total_price" value='<?php echo $formatted_price ?>'> $ <?php echo $formatted_price; ?></span>
                            <p>Direct price <?= $data->min_group_price;?></p>
                        </span>
                    </h6>
                    <div class="product-prop">              
                            <p class="prop-name mb-1" data-prop-id="14">
                                <strong class="prop-key font-weight-bold">Color</strong><span class="font-weight-bold">:</span> <small class="prop-val"></small>
                            </p>

                            <ul id="product_var_<?php echo $prd_i;?>" class="list-unstyled prop-values">
                            <?php
                                $prd_li = 1;
                                // echo "<pre>";print_r($imageModule);echo "</pre>";
                                foreach($imageModule as $v)
                                {
                                    $sku_id     = $v->sku_id;

                                    $group_price = (int) substr($v->group_price, 0, -2);  // normal_price
                                    $USD_price =  $group_price / $RMB_2_USD_RATE;
                                    $USD_price = $USD_price + ($USD_price * 10) /100;
                                    $formatted_price = format_number($USD_price);                                    
                                    
                            ?>
                                    <li id="sub_quantity_<?php echo $v->sku_id; ?>" class="varimg list-inline-item prop-value<?php if($prd_li == 1){ echo ' selected';}?>" data-prop-vid="<?php echo $v->sku_id;?>" data-slides="<?php echo $v->sku_id; ?>" data-target="#carousel-thumb" onClick=variationFunction("<?php echo $v->thumb_url;?>","<?php echo $v->quantity;?>","<?php echo $formatted_price;?>","<?php echo $sku_id;?>")  data-prop-image="<?php echo $v->thumb_url; ?>">
                                        <img  src="<?php echo $v->thumb_url;?>" height="50px" width="50px">
                                    </li>
                            <?php
                                    $prd_li++; 
                                }
                            ?>
                    </div>

                    <div id="product-variations">
                        <div id="quantity" class="form-inline mb-2">
                            <label for="quantityField" class="my-1 mr-2 font-size-14">
                                <strong>Quantity:</strong>
                            </label>
                            <div id="product-quantity" class="quantity buttons_added">
                                <button type="button" value="-" class="minus">-</button>
                                <input type="number" id="quantityField" class="input-text qty text onkeypress" step="1" min="0" max="<?php echo $data->quantity; ?>" name="productquantity" value="0" title="Qty" size="4" inputmode="numeric" onchange="get_ins_cons_sku();">
                                <button type="button" value="+" class="plus">+</button>
                            </div>
                            <p class="mb-0">
                                <span class="qty-ability" id="stockqty"><?php echo $data->quantity; ?></span> Is available.
                            </p>
                            <br>
                        </div>


                    <div id="selected_combination" class="selected_combination"></div>
                    <div id="selected_combination_price" class="selected_combination_price" style="display:none"></div>
                    <div id="selected_combination_avgprice" class="selected_combination_avgprice" style="display:none"></div>


                        <h6 style="color:red; padding-left:80px; display: none;" id="lowstock" >Stock is low. Not allowed to Buy</h6>
                        <div id="aliexpress-shipping-methods">
                            <label for="shippingMethods" class="d-block font-size-14 mb-1"></label>
                            <hr>
                            <h5>Follow two steps to complete the order.</h5>
                            <h6>Step 1: Select services and pay for goods.</h6>
                            <h6>Step 2: Pay the shipping invoice sent to your account 5-15 days after payment goods.</h6> Click <a target="_blank" href="http://www.kuaizi56.com/h5/pages/index/calculate">here</a> to get an estimate.
                            <hr>
                        </div>
                        <h6 id="totalPrice" class="my-2 d-none"></h6>
                        <!--    <h6 id="totalshpngPrice" class="my-2 d-none"></h6>-->
                        <div style="color:#ffffff;" class="d-sm-flex flex-cols-wide mt-2 mb-2">
                            <a id="ali243-add-cart" class="btn btn-primary mr-sm-2">
                                <i class="fas fa-cart-plus"></i> Add to cart </a>
                            <a id="ali243-buy-now" class="btn btn-brand">
                                <i class="fas fa-shopping-bag"></i> Buy Now </a>
                        </div>
                        <div id="txtHint" class="my-2"></div>
                        <div id="productloader1" class="my-2 d-none">
                            <img src="https://borderlesscommerce.net/wp-content/uploads/2020/09/loader50px.gif" alt="borderless product loader">
                        </div>
                        <div id="productloader2" class="my-2 d-none">
                            <img src="https://borderlesscommerce.net/wp-content/uploads/2020/09/loader50px.gif" alt="borderless product loader">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.getElementById("ali243-add-cart").onclick = function() {  createproduct(); };
        document.getElementById("ali243-buy-now").onclick = function() { createproduct(); window.location.href = "https://borderlesscommerce.net/checkout/";};

        function createproduct() 
        {           
            var toatalPrice =  document.getElementById("total_price").getAttribute('value');
            var producttitle = document.getElementById("product-title");
            var producttitle1 = producttitle.textContent;
            var producttitle2 = producttitle1.replace(/[^a-zA-Z0-9 ]/g, '');
            var productQuantity = document.getElementById("quantityField").value;
            var weight_attri ="<?php echo $product_weight; ?>";
            var sku_custom_id = <?php echo $_GET['gid']; ?>;
            var txt = "";
            var txt = txt + "<br/> SKU: " + sku_custom_id;
            var imagelink = document.getElementById("imageModel").getAttribute('value');
            var productlink = "<?php echo $URL; ?>";

            propattribute = JSON.stringify(complete_combo_arr );

            var str = 'price=' + toatalPrice + '&quantity=' + productQuantity + '&title=' + producttitle2 + '&link=' + productlink + '&content=' + txt + '&image=' + imagelink + '&attributes=' + propattribute + '&weight='+ weight_attri;


            document.getElementById("productloader1").classList.remove("d-none");


            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (this.readyState == 4 && this.status == 200) 
                {
                    document.getElementById("txtHint").innerHTML = this.responseText;
                    document.getElementById("productloader1").classList.add("d-none");
                }
            }
            xmlhttp.open('POST', "https://borderlesscommerce.net/pddsaveproduct.php", true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send(str);
        

        }
    </script>
<?php
}else{

    if(isset($_GET['url']) || isset($_POST['api-url']))
    {
        if(isset($_GET['url']))
        {
            $url = $_GET['url'];
        }else if(isset($_POST['api-url'])){
            $url = $_POST['url'];
        }
        $from =  $_GET['from'] ? $_GET['from'] : 1;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://pinduoduo-data-service.p.rapidapi.com/Search/GoodsSearchKeyword.ashx",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "keyword=$url&page_num=$from&page_size=10&sort=2",
            CURLOPT_HTTPHEADER => [
                "content-type: application/x-www-form-urlencoded",
                "x-rapidapi-host: pinduoduo-data-service.p.rapidapi.com",
                "x-rapidapi-key: 3b4ed8d56cmsh8ec359580268da1p1e5eebjsn14a8411ac21f"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err)   {
            echo "cURL Error #:" . $err;
        } else {
            $item_list = json_decode(json_decode($response)->ret_body);
        }
    }


    ?>

    <style> 
        .b{width: 95px;display: block;  float: left;}.price_order_input { margin-right: 20px; } .filter_common{ margin: 10px 0px ;}
        @media only screen and (min-width: 320px) and (max-width: 767px) {
                .filter_common input[type="number"] {width: 100%;}
            }
    </style>
        <div class="archive-products">

        <h6> You Search for Keyword: <?php echo $url; ?></h6>

        <div class="wpb_wrapper vc_column-inner">
            <div class="wpb_text_column wpb_content_element ">
                <div class="wpb_wrapper">
                    <div class="vc_wp_search wpb_content_element">
        <div class="widget widget_search">
        <form id="searchform" class="searchform" action="https://borderlesscommerce.net/pinduoduo/" method="get">
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

    <div>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<!--
         <h3>Filters</h3>
                <form action="https://borderlesscommerce.net/pinduoduo/" method="get">
                <input type="hidden" name="url" value="<?php echo $url;?>">
                <div class="row">
                <div class="col-md-6">
                    <div class='filter_common'>
                        <b class='b'>Brand</b>
                        <select name='BrandId' class='filter_select'>
                            <option value='' selected disabled>Type to search brand</option>                    
                            <?php
                            foreach ($brand_list as $brand) {
                                if(!empty($brand['ExternalId'])  && $brand['ProviderType']  == 'Taobao' ) {
                                    if(isset($brand_id) && $brand_id == $brand['ExternalId']){
                                        echo "<option value='".$brand['ExternalId']."' selected>".$brand['Name']."</option>";
                                    }else{
                                        echo "<option value='".$brand['ExternalId']."'>".$brand['Name']."</option>";
                                    }
                                }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class='filter_common'>
                        <b class='b'>Category</b>
                        <select name='CatId' class='filter_select'>
                            <option value='' selected disabled> Type to search category</option>
                            <?php
                            foreach ($category_list as $category) {
                                if(!empty($category['Id']) && $category['ProviderType'] == 'Taobao' ) {
                                    if(isset($cat_id) && $cat_id == $category['Id']){
                                        echo "<option value='".$category['Id']."' selected>".$category['Name']."</option>";
                                    }else{
                                        echo "<option value='".$category['Id']."'>".$category['Name']."</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class='filter_common'>
                        <b class='b'>Price Range </b>
                        </span><input type="number" name="price_range_min" placeholder="Minimum price" value="<?php echo $price_range_min; ?>"> to
                        <input type="number" name="price_range_max" placeholder="Maximum price"  value="<?php echo $price_range_max; ?>">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class='filter_common'>
                        <b class='b'> Price: </b>
                        <span class='price_order_input'> <input type="radio" name="price" value="Asc" <?php if(isset($_GET['price']) && $_GET['price'] == 'Asc'){ echo "checked"; } ?> >  Low to High  </span>
                        <span class='price_range_input'> <input type="radio" name="price" value="Desc" <?php if(isset($_GET['price']) && $_GET['price'] == 'Desc'){ echo "checked"; }?>  > High to Low </span>
                    </div>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4"><input type="submit" name="" value="filter" class="d-block w-100"></div>
                <div class="col-md-4"></div>
            </div>
            </form>
    </div>
-->
                <ul class="products products-container grid pcols-lg-4 pcols-md-3 pcols-xs-3 pcols-ls-2 pwidth-lg-4 pwidth-md-3 pwidth-xs-2 pwidth-ls-1" data-product_layout="product-outimage_aq_onimage">
    <?php
        foreach ($item_list->goods_list as $itml) { $show_hide = '';

                            if(!empty($itml)){

                            if(!empty($price_range_max) || !empty($price_range_min)){   
                                if( !empty($price_range_max) && $itml['Price']['ConvertedPriceWithoutSign']  > $price_range_max ) { continue; }
                                if( !empty($price_range_min) && $itml['Price']['ConvertedPriceWithoutSign']  < $price_range_min ) { continue; }
                            }
    ?>

        <li class="product-col product-outimage_aq_onimage product type-product post-3415 status-publish first instock has-post-thumbnail shipping-taxable purchasable product-type-simple">
        <div class="product-inner">

            <div class="product-image">
       
                <a href="https://borderlesscommerce.net/pinduoduo/?gid=<?php echo $itml->goods_id; ?>">
                    <div class="inner"><img width="" src="<?php echo $itml->goods_image_url;?>" class="external-img wp-post-image " alt="<?php echo $itml->goods_name;?>"></div>        </a>
                    <div class="links-on-image">
                    <div class="add-links-wrap">
            <div class="add-links clearfix">
                <a href="" data-quantity="1" class="viewcart-style-2 button product_type_simple add_to_cart_button ajax_add_to_cart"   aria-label="<?php echo $itml->goods_name;?>" rel="nofollow">Add to cart</a>
                </div>
            </div>
                </div>
                </div>

            <div class="product-content">
                <span class="category-list"></span>
                    <a class="product-loop-title" href="">
            <h3 class="woocommerce-loop-product__title"><?php echo $itml->goods_name;?></h3>    </a>


            <?php 

                $min_group_price = substr($itml->min_group_price, 0, -2); // min_normal_price
                $USD_price = $min_group_price / $RMB_2_USD_RATE; 
                $USD_price = $USD_price + ($USD_price * 10) /100;
                $formatted_price = format_number($USD_price);                
            ?>

            <span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $formatted_price;?></span></span>
            <p>Direct price <?= $itml->min_group_price;?></p>
                    </div>
        </div>
        </li>

            <?php
                        }
                }
    $increment_number = 5;
    $itemperpage = 20;
    $from_n = $from+$itemperpage;
    if($from > $itemperpage)
    {
        $from_p = $from-$itemperpage;
    }   else {
        $from_p = 0;
    }

    $from_p = "?from=$from_p&url=$url";
    $from_n = "?from=$from_n&url=$url";

    $number_of_box = 5;
    if($from <= $number_of_box ) {
     $start = 1; 
     $end = $from+$number_of_box;
    } else {
      $start = $from - $number_of_box;
      $end = $from + $number_of_box;
    }
    ?>

    </ul>
        <div class="<?php echo $show_hide; ?>">
            <div class="pagination">

              <a href="<?php echo $from_p;?>">&laquo;</a>
                <?php
                for($i=$start;$i<=$end;$i++){
                    if($i == $from){
                    $class = 'active';
                }
                echo    '<a href="'.$SITE_URL.'/pinduoduo/?from='.$i.'&url='.$url.'" class="'.$class.'">'.$i.'</a>';
                $from1 = $from1+$itemperpage;
                $class = '';
                }
                ?>
              <a href="<?php echo $from_n;?>">&raquo;</a>
            </div>
        </div>
    </div>
    <?php } // else of isser get    
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

            <script> 
                $(document).ready(function(){   $('.filter_select').selectize({ sortField: 'text' });  })
            </script>
            <script type="text/javascript">
                var gallarythumbs = document.getElementsByClassName("gallarythumb");
            var myFunction = function() 
            {
                var attribute = this.getAttribute("data-slides");

                var carouselitems = document.getElementsByClassName("carousel-item active");                
                carouselitems[0].className = carouselitems[0].className.replace("active", "");
                document.getElementById(attribute).classList.add("active");
                var imgfromvariation = document.getElementById("imgfromvariation"); 

                if(imgfromvariation.classList.contains("active"))
                {
                    imgfromvariation.classList.remove("active");   
                }
            };
            for (var i = 0; i < gallarythumbs.length; i++) {
                gallarythumbs[i].addEventListener('click', myFunction, false);
            }
            
        var complete_combo_arr = new Array();

    function create_combo() {
        var element = document.getElementsByClassName('list-inline-item prop-value selected');

        var qty = document.getElementById('quantityField').value;
        var id = element[0].id;
        var img = element[0].dataset['propImage'];
        var price_into_qty = document.getElementById('total_price')
        var individualprice =  document.getElementById("total_price").getAttribute('value');            

        var item = { 'selected_item_id': id, 'selected_item_img':img, 'Quantity':qty, 'selected_item_price': individualprice };

        /*  OLD way to delete not work
        if (complete_combo_arr.filter(item => item.selected_item_id === id).length ) {
            complete_combo_arr.splice(complete_combo_arr.indexOf(item), 1);  //deleting
        }*/

        complete_combo_arr = complete_combo_arr.filter(function( obj ) {
            return obj.selected_item_id !== id;
        });

        if(qty != 0){
            complete_combo_arr.push(item);
        }
        display_selected_combo();
    }



            function variationFunction(varimgurl,quantity,price,sku_id) 
            {   
                $(".varimg").removeClass('selected');
                $("#sub_quantity_"+sku_id).addClass('selected');

                for (var k=0; k<complete_combo_arr.length; k++){
                    if(complete_combo_arr[k]['selected_item_id'] == 'sub_quantity_'+sku_id) {
                        document.getElementById('quantityField').value = complete_combo_arr[k]['Quantity'];
                        break;
                    }else{
                        document.getElementById('quantityField').value = 0;
                    }
                }


                    $("#stockqty").text(quantity);
                    $("#total_price").text('$ '+''+price);
                    document.getElementById("total_price").setAttribute("value",price );
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
            var stockqty = document.getElementById("stockqty").innerHTML;
            document.getElementsByClassName('onkeypress')[0].addEventListener("keyup", function(e) {
                
                if (e.target.value > stockqty) 
                {
                    this.value = stockqty;
                } else if (e.target.value.length && e.target.value <= 0) {
                    this.value = 1;
                }
            });
                function get_ins_cons_sku()
                {
                    get_price_and_sku();
                }
               function get_price_and_sku()
                {
                    var elementsLi = document.getElementsByClassName('list-inline-item prop-value selected');
                    if(elementsLi.length ==0)   //start of if statement checking selected elements
                    {
                        var sku_custom_id = "";
                        for (var i=0, len=elementsLi.length|0; i<len; i=i+1|0)
                        {
                            var sDataValue = elementsLi[i].getAttribute('data-prop-vid');
                            sku_custom_id = sku_custom_id.concat(sDataValue);
                        }
                        var productprice= document.getElementById(sku_custom_id).getAttribute("data-price");
                        var productpricepromo = document.getElementById(sku_custom_id).getAttribute("data-promo-price");
                        var productqty = document.getElementById(sku_custom_id).getAttribute("data-quantity");
                        var productQuantity = document.getElementById("quantityField").value;
                        document.getElementById("quantityField").setAttribute("max",productqty );
                    
                        var toatalPrice = (Number(productQuantity) * Number(productprice)).toFixed(2);
                        var toatalPricePromo = (Number(productQuantity) * Number(productpricepromo)).toFixed(2);
                        var qa = document.getElementsByClassName("qty-ability")[0];
                        var fp = document.getElementById("formatted-price").childNodes;
                        qa.innerHTML=productqty;
                    
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
                        if(fp[3])
                        {
                            fp[3].innerHTML="$ ".concat(toatalPrice);  
                        }
                        else{
                            fp[1].innerHTML="$ ".concat(toatalPrice); 
                        }
                        if(productpricepromo !==null && productpricepromo!=="")
                        {
                            return productpricepromo;
                        }
                        else{
                            return productprice; 
                        }
                    
                    }
                    else{
                        var productQuantity = document.getElementById("quantityField").value;
                        var stockqty = document.getElementById("stockqty").innerHTML;                       
                        
                        if(parseInt(productQuantity) <= parseInt(stockqty))
                        {
                            create_combo();  // call your selection table

                            document.getElementById("lowstock").style.display = "none";
                            document.getElementById("ali243-add-cart").style.pointerEvents = "auto";
                            document.getElementById("ali243-buy-now").style.pointerEvents = "auto";
                            var RMB_2_USD_RATE = "<?php echo $RMB_2_USD_RATE; ?>";
                            var original_amt = "<?php echo substr($data->min_group_price,0,-2); // min_normal_price ?>";
                            var promo_amt = "<?php echo $promo_amount; ?>";
                            var price = document.getElementById("total_price").getAttribute('value');
                            document.getElementById("total_price").setAttribute('value',price);

                            original_amt =   original_amt / RMB_2_USD_RATE; // RMB to USD
                            original_amt = original_amt+ (original_amt * 10)/ 100;
                            original_amt =  parseFloat(original_amt).toFixed(2);
                            console.log(original_amt);


                            if(promo_amt !==null && promo_amt!=="")
                            {
                                var toatalPricePromo = (Number(productQuantity) * Number(promo_amt)).toFixed(2);
                            }
                            var toatalPrice = (Number(productQuantity) * Number(price)).toFixed(2);
                            //document.getElementById("total_price").setAttribute('value',toatalPrice);

                            var fp = document.getElementById("formatted-price").childNodes;
                            fp[1].innerHTML="$ ".concat(toatalPricePromo);
                            if(fp[3])
                            {
                                fp[3].innerHTML="$ ".concat(toatalPrice);  
                            }
                            else{
                                fp[1].innerHTML="$ ".concat(toatalPrice); 
                            }
                            if(promo_amt !==null && promo_amt!=="")
                            {
                                return promo_amt;
                            }
                            else{
                                return original_amt; 
                            }
                        }
                        else{
                            console.log('else block');
                            var price = document.getElementById("total_price").getAttribute('value');
                            
                            document.getElementById("quantityField").value = 1;
                            var original_amt = "<?php echo $data->min_group_price; // min_normal_price?>";
                            var promo_amt = "<?php echo $promo_amount; ?>";
                            document.getElementById("total_price").setAttribute('value',price);
                            if(promo_amt !==null && promo_amt!=="")
                            {
                                var toatalPricePromo = (Number(productQuantity) * Number(promo_amt)).toFixed(2);
                            }
                            var fp = document.getElementById("formatted-price").childNodes;
                            fp[1].innerHTML="$ ".concat(toatalPricePromo);
                            if(fp[3])
                            {
                                fp[3].innerHTML="$ ".concat(price);  
                            }
                            else{
                                fp[1].innerHTML="$ ".concat(price); 
                            }
                            if(promo_amt !==null && promo_amt!=="")
                            {
                                return promo_amt;
                            }
                            else{
                                return price; 
                            }
                            document.getElementById("lowstock").style.display = "block";
                            document.getElementById("ali243-add-cart").style.pointerEvents = "none";
                            document.getElementById("ali243-buy-now").style.pointerEvents = "none";
                        }
                    }
                }

                function display_selected_combo(){
   
                       var txt="";
                       txt = txt + "<table><caption>Your Selection</caption>";
                        for (var i=0; i<complete_combo_arr.length; i++){
                            txt = txt + "<tr>";
                            for (var key in complete_combo_arr[i]) {
                                if(key =="selected_item_img"){
                                    txt = txt + "<td> <img src='"+complete_combo_arr[i][key]+"' width='80px'></img></td>" ;
                                }

                                if(key =="Quantity"){
                                    txt = txt + "<td>" + key +" = " + complete_combo_arr[i][key] + "</td>" ;
                                }
                            }
                          
                                   txt = txt + "<tr>";
                           
                        }
                        txt = txt + "</table>";
                    
                   document.getElementById("selected_combination").innerHTML=txt;

                }

            </script>

<?php

get_footer(); 

function format_number($num)
{
    return number_format($num,2,'.','');
}


