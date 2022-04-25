<?php /* Template Name: AliExpress2 */ 

get_header(); 


if($_GET['url']){
//$url = 'https://www.ali2bd.com/details?p='.$_GET['url'];
$url = $_GET['url'];
    
include('/home/ali243/public_html/aliexpress/simplehtmldom/simple_html_dom.php');
$html = file_get_html($url);
 $html1 = $html->find('head');
echo $html1[0];

$file = file_get_contents($url);
//$html1 = $html->find('div[class="product-info"]');
$body = $html->find('body');

echo $body[0];
}


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

}

}

?>



<script type="text/javascript">
if (window.addEventListener) { // Mozilla, Netscape, Firefox
    window.addEventListener('load', WindowLoad, false);
} else if (window.attachEvent) { // IE
    window.attachEvent('onload', WindowLoad);
}

document.getElementById("ali243-buy-now").onclick = function() {myFunction()};

function ali243addcart() {


 var elements = document.getElementsByClassName('sku-property');

var result = 0;


  var elementsLi = document.getElementsByClassName('sku-property-item selected');
  for (var i=0, len=elementsLi.length|0; i<len; i=i+1|0) {
    result++;
}
  var txt = "";
  
  for(var i =0; i < elements.length; i++)
  { 
  
  
    var c = elements[i].children;
    

     txt = txt + "<br>" + c[0].textContent + "<br>";
    
  }
  
  if(i!=result){
   alert("Please select all attributes");
  }
  else{
      
       var productprice = document.getElementsByClassName('product-price-value');
       var producttitle = document.getElementsByClassName('product-title');
      var imagelink = document.getElementsByClassName('magnifier-image');
       var productlink = "<?php echo $url; ?>";
      var quantity = $('.next-input :input').val();
      
      var str = "price=" + productprice[0].textContent + "&title=" + producttitle[0].textContent + "&link=" + productlink + "&content=" + txt + "&image=" + imagelink[0].src + "&quantity=" + quantity;
      
      
       var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "http://ali243.com/saveproduct.php?"+str, true);
        xmlhttp.send(); 
  }
 
 
 
}
  
  function ali243buynow() {


 var elements = document.getElementsByClassName('sku-property');

var result = 0;


  var elementsLi = document.getElementsByClassName('sku-property-item selected');
  for (var i=0, len=elementsLi.length|0; i<len; i=i+1|0) {
    result++;
}
  var txt = "";
  
  for(var i =0; i < elements.length; i++)
  { 
  
  
    var c = elements[i].children;
    

     txt = txt + "<br>" + c[0].textContent + "<br>";
    
  }
  
  if(i!=result){
   alert("Please select all attributes");
  }
  else{
      
       var productprice = document.getElementsByClassName('product-price-value');
       var producttitle = document.getElementsByClassName('product-title');
      var imagelink = document.getElementsByClassName('magnifier-image');
       var productlink = "<?php echo $url; ?>";
      var quantity = $('.next-input :input').val();
      
      var str = "price=" + productprice[0].textContent + "&title=" + producttitle[0].textContent + "&link=" + productlink + "&content=" + txt + "&image=" + imagelink[0].src + "&quantity=" + quantity;
      
      
       var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                window.location.href = "http://www.ali243.com/cart";
                //document.getElementById("txtHint").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "http://ali243.com/saveproduct.php?"+str, true);
        xmlhttp.send(); 
  }
 
 
 
}
  
function WindowLoad(event) {
   
    var buttonhtml = '<a class="single_add_to_cart_button button alt" id="ali243-buy-now" onclick="ali243buynow()">Buy now</a><a class="single_add_to_cart_button button alt" id="ali243-add-cart" onclick="ali243addcart()">Add to Cart</a><div id="txtHint"></div>';
  
   var x = document.getElementsByClassName("buyer-pretection");
  x[0].innerHTML = buttonhtml;
  
    
  
         /*     
              var elements1 = document.getElementsByClassName('sku-property');
              
              for(var i =0; i < elements1.length; i++)
              { 
                
              
                var c1 = elements1[i].children;
                var str = c1[0].textContent;
                var strLower = str.toLowerCase();
                
                var newstrng = strLower.replace(/\s/g, '');
     
            // if(strLower == 'expédié de:' || strLower == 'shipped from:' )
            //{
              alert(newstrng);
              c1.remove();
                 elements1[i].parentNode.removeChild(elements1[i]);
            // } 
                  
              }
*/

}
</script>
<?php get_footer(); ?>