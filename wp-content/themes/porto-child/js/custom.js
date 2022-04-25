
    
jQuery(document).ready(function(){
    
 jQuery('#billing_country option:contains(China)').remove();
 jQuery('#shipping_country option:not(:contains(China))').remove();
 
// $("#billing_country option[value='CN']").remove();

});




