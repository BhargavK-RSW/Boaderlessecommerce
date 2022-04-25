<?php
/* Template Name: Currency_Exchange */
get_header();

?>

<div class="hero-content py-5" style="height: auto !important;">
                <div class="container" style="height: auto !important;">
           
					<?php 
					if($_GET["youSend"] && $_GET["youSendCurrency"] && $_GET["sender-payment-method"] && $_GET["recipientCurrency"])
					{
			$yousendcurrency = $_GET["youSendCurrency"];
			$yougetcurrency = $_GET["recipientCurrency"];
			$sendamount = $_GET["youSend"];
$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://currency-converter5.p.rapidapi.com/currency/convert?format=json&from=".$yousendcurrency."&to=".$yougetcurrency."&amount=".$sendamount,
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
						if($yougetcurrency == "USD"){
							
					$exchangerate = $responserates['USD']['rate'];
					$totalamount = $responserates['USD']['rate_for_amount'];
							
						}
						else{
								$exchangerate = $responserates['CNY']['rate'];
					            $totalamount = $responserates['CNY']['rate_for_amount'];
						}
							
					?>
				
	   <div class="row justify-content-center">
                        <div class="col-md-12 col-lg-8 mt-5" id="remiFlex" style="display: block;">
                            <div class="bg-white rounded text-dark" id="currencyexchange">
                               <!-- <h3 class="text-3 px-4 mb-0 pt-5 pb-2 rounded-top text-info text-center">
									Sending Amount: <span id="sicurrencyF"><?php echo $_GET["youSend"];?></span>&nbsp;<span id="sicountryF"><?php echo $_GET["youSendCurrency"];?></span> 
									
								</h3>-->
                                <div class="text-center pt-1 icon-money-div">
									<div class="icon-money mx-auto my-0" style="height:50px;width:50px;"><svg style="width:100%;height:100%;" fill="#52489c" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
										<linearGradient id="New_Gradient_Swatch_18" gradientUnits="userSpaceOnUse" x1="291.588" x2="399.859" y1="463.081" y2="354.81">
											<stop offset="0" stop-color="#f5c2c2"></stop><stop offset="1" stop-color="#fae1e1"></stop></linearGradient>
										<linearGradient id="New_Gradient_Swatch_8" gradientUnits="userSpaceOnUse" x1="100" x2="352.024" y1="500" y2="247.976"><stop offset="0" stop-color="#f7d1d1"></stop><stop offset="1" stop-color="#fcf0f0"></stop></linearGradient>
										<linearGradient id="New_Gradient_Swatch_21" gradientUnits="userSpaceOnUse" x1="268.118" x2="403.882" y1="195.882" y2="60.118">
											<stop offset="0" stop-color="#52489c"></stop><stop offset="1" stop-color="#52489c"></stop></linearGradient>
										<g id="_16-recieve" data-name="16-recieve">
											<g id="linear_color" data-name="linear color">
												<g id="color"><path d="m435 390s-20-23-47-9c-44.917 23.291-59 41-59 41h-66.662l35.835 47.666 56.827.334z" fill="url(#New_Gradient_Swatch_18)"></path><path d="m449 383c-44.917 23.291-59 41-59 41l-86-8-80-64s47.762 15.058 79 22c45 10 53-41 53-41l-132-37h-208v120h128l152 64 120-8 80-80s-20-23-47-9z" fill="url(#New_Gradient_Swatch_8)"></path></g><circle cx="336" cy="128" fill="url(#New_Gradient_Swatch_21)" r="96"></circle><path d="m444.4 374.122c-2.59 1.343-5.071 2.668-7.47 3.976-11.128-8.977-31.176-17.565-53.531-5.975-29.356 15.226-46.155 28.247-54.819 36.12l-20.669-1.923-37.955-30.364c10.586 2.9 21.427 5.706 30.878 7.806a61.967 61.967 0 0 0 13.445 1.551 45.716 45.716 0 0 0 26.3-7.931c20.569-14.021 25.117-41.662 25.3-42.832a10 10 0 0 0 -7.18-11.179l-132-37a10.009 10.009 0 0 0 -2.699-.371h-208a10 10 0 0 0 0 20h206.625l121.164 33.963c-2.353 6.68-6.778 15.685-14.548 20.942-6.5 4.4-14.373 5.488-24.072 3.333-30.447-6.765-77.689-21.626-78.162-21.775-10.5-3.218-17.767 10.422-9.254 17.346l80 64a10 10 0 0 0 5.321 2.148l28.109 2.615 57.891 5.385a9.859 9.859 0 0 0 8.7-3.673c.135-.165 13.994-16.717 55.824-38.406a27.06 27.06 0 0 1 27.567.81l-69.584 69.584-113.881 7.592-149.82-63.081a10.017 10.017 0 0 0 -3.88-.783h-128a10 10 0 0 0 0 20h125.98l150.14 63.217a10.013 10.013 0 0 0 3.88.783c.222 0 .443-.008.665-.022l120-8a10 10 0 0 0 6.406-2.907l80-80a10 10 0 0 0 .475-13.633c-8.703-10.008-32.267-25.253-59.146-11.316zm-58.34 39.468-28.643-2.664a306.1 306.1 0 0 1 35.183-21.048c10.158-5.269 18.912-3.546 25.124-.515-16.953 10.748-26.79 19.4-31.664 24.227z"></path><path d="m336 234a106 106 0 1 0 -106-106 106.12 106.12 0 0 0 106 106zm0-192a86 86 0 1 1 -86 86 86.1 86.1 0 0 1 86-86z"></path><path fill="#fff" d="m327 138h17a14.015 14.015 0 0 1 14 14v1a14.015 14.015 0 0 1 -14 14h-25.209a5.507 5.507 0 0 1 -5.507-5.609l.019-1.038a10 10 0 0 0 -20-.358l-.019 1.036a25.509 25.509 0 0 0 25.507 25.969h7.209v6a10 10 0 0 0 20 0v-6.063a34.041 34.041 0 0 0 32-33.937v-1a34.038 34.038 0 0 0 -34-34h-17a14 14 0 0 1 0-28h24.811a5.508 5.508 0 0 1 5.5 5.744 10 10 0 0 0 19.982.86 25.508 25.508 0 0 0 -25.482-26.604h-5.811v-6a10 10 0 0 0 -20 0v6.025a33.995 33.995 0 0 0 1 67.975z"></path><path d="m439.071 280.929a10 10 0 0 0 -14.142 14.142l32 32a10 10 0 0 0 14.142 0l32-32a10 10 0 0 0 -14.142-14.142l-14.929 14.928v-55.857a10 10 0 0 0 -20 0v55.857z"></path><path d="m464 210a10 10 0 0 0 10-10v-8a10 10 0 0 0 -20 0v8a10 10 0 0 0 10 10z"></path><path d="m152.929 151.071a10 10 0 0 0 14.142 0l32-32a10 10 0 0 0 -14.142-14.142l-14.929 14.928v-55.857a10 10 0 0 0 -20 0v55.857l-14.929-14.928a10 10 0 0 0 -14.142 14.142z"></path><path d="m160 34a10 10 0 0 0 10-10v-8a10 10 0 0 0 -20 0v8a10 10 0 0 0 10 10z"></path><path d="m56.929 247.071a10 10 0 0 0 14.142 0l32-32a10 10 0 0 0 -14.142-14.142l-14.929 14.928v-55.857a10 10 0 0 0 -20 0v55.857l-14.929-14.928a10 10 0 0 0 -14.142 14.142z"></path><path d="m64 130a10 10 0 0 0 10-10v-8a10 10 0 0 0 -20 0v8a10 10 0 0 0 10 10z"></path></g></g></svg></div></div>
                                
								<!--<h3 class="text-18 px-4 mb-0 pb-2 text-center text-success"><span id="total-recipient-get" class="sicurrencyT"><?php echo round($_GET["youSend"]/$exchangerate,2); ?></span>&nbsp;<span id="recipient-country" class="sicountryT"><?php echo $_GET["youSendCurrency"];?></span></h3>-->
				
							
            <p class="text-muted px-4 text-center mx-0 my-0 pb-2 text-3">
				The current exchange rate is
							 <span class="font-weight-500"><span id="fromValueiFlex">1</span> <span id="fromCurrencyiFlex"><?php echo $_GET["youSendCurrency"];?> = </span>
                                        <span id="toValueiFlex"><?php echo round($exchangerate,2); ?></span> <span id="toCurrencyiFlex"><?php echo $_GET["recipientCurrency"];?></span></span>
                                </p>
                                <hr class=" mx-0 my-0 pb-2">
         <!--                       <p class="pb-2  mx-0 my-0 px-4 font-weight-500">Our Fees <span class="text-danger">(</span><span class="text-danger" id="total-fees">8</span><span id="cur-sym-d" class="text-danger"></span>&nbsp;<span class="text-danger" id="feeType">%)</span><span class="text-3 float-right text-danger"> <span class="text-danger" id="total-fees-amount">-->
									
									<?php $youSend=$_GET["youSend"]; 
									$admin_currency_exchange_charge = (int)get_option('currency_exchange_charge') / 100; 
									$comision = round($admin_currency_exchange_charge*$youSend/$exchangerate); ?>
									
									<!--</span>&nbsp;<span id="total-fees-amount-cur" class="text-danger"><?php echo $_GET["youSendCurrency"];?></span></span>-->
         <!--                       </p>-->
                                <p class="font-weight-500 pb-2  mx-0 my-0 px-4">Recipient will Get
                                    <span class="text-3 float-right text-success"><span id="total-recipient-get-2"><?php echo round($_GET["youSend"],2); ?></span>&nbsp;<span id="recipient-country-2"><?php echo $_GET["recipientCurrency"];?></span></span>
                                </p>
                                <p class="font-weight-500 pb-2  mx-0 my-0 rounded-bottom-0 px-4">Total To Pay
                                    <span class="text-3 float-right text-primary"><span id="total-to-pay"><?php echo round($_GET["youSend"]/$exchangerate)+$comision; ?></span>&nbsp;<span id="total-to-pay-cur"><?php echo $_GET["youSendCurrency"];?></span></span>
                                </p>
                                <div class="pb-4 mx-4 d-flex flex-column flex-sm-row justify-content-sm-between">
                                
                         <button class="btn btn-primary btn-block" id="continueBtn">Next</button>
                                </div>
                            </div>
							
							<div id="customerinfo" style="display:none;">
							
								<?php if(is_user_logged_in()){
						?>
					
								<div class="tab-content p-4 bg-white rounded-bottom">
                                <div class="tab-pane fade active show" role="tabpanel" aria-labelledby="send-money-tab">
                        <form>
							
							<input type="hidden" id="usdamount" value="<?php echo round($_GET["youSend"]/$exchangerate)+$comision; ?>">
							<input type="hidden" id="cnyamount" value="<?php echo round($_GET["youSend"]) ?>">
                                        <div class="form-group">
                                            <label for="cName">WeChat or Alipay Recipient’s name (Type in Chinese if it’s a Chinese name)</label>
                                            <div class="input-group"> 
                                                <input type="text" class="form-control" id="cName" name="cName" value="" placeholder="王利">
                                            </div>
                                           
                                        </div>

                                        <div class="form-group">
                                            <label for="cEmail">Your Email</label>
                                            <div>
                                                <input type="email" id="cEmail" name="cEmail" placeholder="john@hotmail.com" class="form-control" required />
                                        
                   </div>
                                        </div>
							
							 <div class="form-group">
                                            <label for="cAlipay">Alipay or WeChat ID </label>
                                            <div>
                                                <input type="text" id="cAlipay" name="cAlipay" placeholder="138-0277-3655 or borderlesscommerce" class="form-control" required />
                                        
                   </div>
                                        </div>
                              
                                        <div class="form-group">
                                            <label for="cMobile">Alipay or WeChat Mobile Phone Number</label>
                                            <div><input type="tel" id="cMobile" name="cMobile" placeholder="138-0277-3655" class="form-control" required />  </div>
                                        </div>
                    
							<input class="btn btn-primary btn-block" id="submitbtn" type="button" value="Send Money">
                                    </form>
                                </div>
                           
                            </div>
								
								<?php }
						else{
							echo do_shortcode("[woocommerce_my_account]");
						}
						 ?>
							</div>
							
							
                        </div>
                    </div>				
					
	<script>
		
		jQuery("#continueBtn").click(function(){
		 // $("#currencyexchange").hide();
			 jQuery("#customerinfo").show();
			jQuery("#continueBtn").hide();
			
		});			
					
	</script>				
	<?php
    } else  {
?>
					
	
                    <div class="row justify-content-center">
                        <div class="col-md-12 col-lg-8 my-auto">
                            <h2 class="text-7 px-4 mb-0 pt-4 pb-2 rounded-top bg-white text-center">Borderless Commerce Currency Converter</h2>
                            <ul class="nav nav-tabs nav-justified bg-white style-6" role="tablist">
                               
                            </ul>
                            <div class="tab-content p-4 bg-white rounded-bottom">
                                <div class="tab-pane fade active show" role="tabpanel" aria-labelledby="send-money-tab">
                        <form action="https://borderlesscommerce.net/currency-exchange" method="GET">
                                        <div class="form-group">
                                            <label for="youSend">How much RMB do you need?</label>
                                            <div class="input-group" id="youSend-int"> 
                                                <input type="number" min="115" max="3000" class="form-control" id="youSend" name="youSend" value="" placeholder="0.00">
                                            </div>
                                           
                                        </div>

                                        <div class="form-group">
                                            <label for="youSendCurrency">From Currency</label>
                                            <div>
                                                <select id="youSendCurrency" name="youSendCurrency" class="selectpicker form-control bg-transparent" required>
                                        
                     <option selected="selected"  value="USD">USD</option>
					 <!-- <option  value="CNY">CNY</option> -->
                                             
                                            </select></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="paymentmethod">Sender Payment Method</label>
                                        
                                                
                                                <div ><select id="paymentmethod" name="sender-payment-method" class="selectpicker form-control bg-transparent" required>
                                                  >
                                                    <!--<option data-icon="fab fa-paypal" value="iFPaypal">Paypal
                                                    </option>-->
                                                  
                                                    <option data-icon="fa fa-credit-card" value="iFStripe">
                                                        Visa/MasterCard</option><!--
                                                    <option data-icon="fab fa-alipay" value="iFAlipay">Alipay
                                                    </option>
                                                    <option data-icon="fab fa-weixin" value="iFWechat">WeChat
                                                    </option> -->

                                                </select>
                                              </div>
                                           
                                        </div>
							 <div class="form-group">
                                            <label for="paymentrecmethod">Payment Receiving Method</label>
                                        
                                                
                                                <div ><select id="paymentrecmethod" name="receive-payment-method" class="selectpicker form-control bg-transparent" required>
                                                  >
                                                   
                              <option selected="selected" data-icon="fab fa-alipay" value="iFAlipay">Alipay
                                                    </option>
                                                    
                                <option data-icon="fab fa-weixin" value="iFWechat">WeChat
                                                    </option>
                                                </select>
                                              </div>
                                           
                                        </div>
                                        <div class="form-group">
                                            <label for="recipientCurrency">To Currency</label>
                                            <div><select id="recipientCurrency" name="recipientCurrency" class="selectpicker form-control bg-transparent" required>

                                  <option selected="selected" value="CNY">CNY</option>
                                   <!--<option value="USD">USD</option> -->
                                                   
                                            </select>
                                            </div>
                                        </div>
                    
							<input class="btn btn-primary btn-block" type="submit" value="Please Click Here to Calculate">
                                    </form>
                                </div>
                           
                            </div>
                        </div>
                    </div>
			<?php } ?>		
					
                </div>
            </div>
  <script>
document.getElementById("submitbtn").onclick = function() {  createproduct(); };

function createproduct() {

//document.getElementById("productloader1").classList.remove("d-none");

//document.getElementById("submitbtn").hide();
	
  var toatalUSDPrice = document.getElementById("usdamount").value;
  var toatalCNYPrice = document.getElementById("cnyamount").value;
  var customerName = document.getElementById("cName").value;
  var customerMobile = document.getElementById("cMobile").value;
  var customerAlipay = document.getElementById("cAlipay").value;
 //var currentdate = new Date(); 
   // var datetime = "On: " + currentdate.getDate() + "/"
   //             + (currentdate.getMonth()+1)  + "/" 
   //             + currentdate.getFullYear() + " @ "  
    //            + currentdate.getHours() + ":"  
    //            + currentdate.getMinutes() + ":" 
    //            + currentdate.getSeconds();
	
      // var producttitle = "Order for USD "+toatalUSDPrice+" to CNY "+toatalCNYPrice;

      var str = "price=<?php echo $youSend;?>&totalusd=" + toatalUSDPrice + "&totalcny=" + toatalCNYPrice + "&customerName=" + customerName + "&customerMobile=" + customerMobile + "&customerAlipay=" + customerAlipay;

       var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //document.getElementById("txtHint").innerHTML = this.responseText;
				//document.getElementById("productloader1").classList.add("d-none");
				window.location.href = "https://borderlesscommerce.net/checkout/"; 
            }
			alert(this.responseText);
        }
        xmlhttp.open("GET", "https://borderlesscommerce.net/currency_save_product.php?"+str, true);
        xmlhttp.send();
	}
</script>      
<?php
get_footer();
?>