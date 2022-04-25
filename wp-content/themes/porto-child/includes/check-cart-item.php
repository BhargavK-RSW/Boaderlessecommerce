<?php

add_action( 'woocommerce_before_calculate_totals', 'init_check_cart_item_stock' );
function init_check_cart_item_stock(){
    define('CFG_SERVICE_INSTANCEKEY', '9e060d6f-44fc-4864-9078-0ad08b14e99d');
    define('CFG_REQUEST_LANGUAGE', 'en');
    define('CFG_SESSIONID', '');
    define('CFG_BLOCKLIST', '');
    define('CFG_FRAMESIZE', '20'); 

    if(!function_exists('getvariationarray')){
        function getvariationarray($des){
            $text=stristr($des,"{");
            $text2=strip_tags($text);
            $json_arr = json_decode($text2, true);
            return $json_arr;
        }
    }
    if(!function_exists('getitemid')){
        function getitemid($des){
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
    }
    if(!function_exists('getstockavailablity')){
        function getstockavailablity($cartarr, $actualarr){
            $stockavail=true;
            $cart_arr=$cartarr;
            $actual_arr=$actualarr;
            $priceorigin= 0;
            foreach ($cart_arr['att'] as $key=>$value){
                foreach ($actual_arr as $keyy =>$valuee){
                    $qtyarry=array_diff_assoc($valuee, $value);
                    if (array_key_exists("qty",$qtyarry) && sizeof($qtyarry)==2){

                        $qty_original=$qtyarry['qty'];
                        $priceorigin=$qtyarry['oprice'];
                    }
                    $qtyarry2=array_diff_assoc($value, $valuee);

                    if (array_key_exists("qty",$qtyarry2) && sizeof($qtyarry2)==2){
                        $qty_cart= $qtyarry2['qty'];
                    }
                    if($qty_original>$qty_cart){
                        $stockavail=true;
                    }
                    else{
                        $stockavail=false;
                    }
                }
            }
            return ['stockavail'=>$stockavail, 'priceorigin'=>$priceorigin];
        }
    }
    if(!function_exists('getitemstockavailablearray')){
        function getitemstockavailablearray($itemId){
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
            $oriprice = $data['OtapiItemFullInfo']['Price']['ConvertedPriceWithoutSign'];
            $arr=array();
            if($ConfiguredItems!="" || $ConfiguredItems!=NULL )
            {
                if (array_key_exists("Quantity",$ConfiguredItems)){
                    $ci= $ConfiguredItems;
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
                    $newarr['qty']=$ci['Quantity'];
                    $newarr['oprice']=$ci['Price']['ConvertedPriceWithoutSign'];
                    array_push($arr,$newarr);
                }
                else{
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
                        $newarr['qty']=$ci['Quantity'];
                        $newarr['oprice']=$ci['Price']['ConvertedPriceWithoutSign'];
                        array_push($arr,$newarr);
                    }
                }   
            }
            else{
                array_push($arr,$MasterQuantity);
                array_push($arr,$oriprice);
            }
            return $arr;
        }
    }
    if(!function_exists('getcartitems')){
        function getcartitems(){
            global $woocommerce;
            $items = $woocommerce->cart->get_cart();
            foreach($items as $item => $values) { 
                $_product =  wc_get_product( $values['data']->get_id());
                $qty_in_cart=$values['quantity'];
                $desc= $_product->get_description();
                $cartitemvararray=getvariationarray($desc);
                $itemid=getitemid($desc);
                if($itemid!=""){
                    $actualitemvararray=getitemstockavailablearray($itemid);
                    if(sizeof($cartitemvararray['att'])==0){
                        $stockcount=$actualitemvararray[0]; 
                  
                        if($stockcount>$qty_in_cart){
                            $isstock=true; 
                        }
                        else{
                            $isstock=false;
                        }
                    }
                    else{
                        $cartitemvararray['att'][0]['qty'] = $qty_in_cart;
                        $stockandprice=getstockavailablity($cartitemvararray, $actualitemvararray);
                        $isstock= $stockandprice['stockavail'];
                        $original_price = $stockandprice['priceorigin'];
                        $service_fee = get_field( "service_fees", 143 )/100;
                        $payment_gateway_fee = get_field( "payment_gateway_fees", 143 )/100;
                        $exchange_rate = get_field( "exchange_rate", 143 )/100;
                        $product_inspection_fee = get_field( "product_inspection_fees", 143 )/100;
                        $product_consolidation_fee = get_field( "product_consolidation_fees", 143 )/100;
                        $fixed_product_inspection_fee = get_field( "fixed_price_product_inspection_fees", 143 );
                        $fixed_product_consolidation_fee = get_field( "fixed_price_product_consolidation_fees", 143 );
                        $original_amount=$original_price + ($original_price * $service_fee) + ($original_price * $payment_gateway_fee) + ($original_price * $exchange_rate);

                        $attributes = $values['data']->get_attributes();
                        $myprice=$values['data']->set_price($original_amount);
                    }
                    if(!$isstock){
                        $url = site_url( '/cart/', 'https' );
                        WC()->cart->remove_cart_item($item);
                        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
                        {    
                            wp_redirect($url);
                            exit;    
                        }
                        else{
                            echo "Cart quantity exceeds available quantity. your cart is empty. please add to cart again.";
                            exit;
                        }
                    }
                }
            }  
        }
    }
    getcartitems();
}  // end init_check_cart_item_stock