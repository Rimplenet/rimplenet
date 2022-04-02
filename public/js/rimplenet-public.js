(function( $ ) {
	'use strict';
    
	jQuery(document).ready(function( $ ) {

	
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
    	
	var referral = urlParams.get('rimplenet-ref');
	if(referral) {
	   setCookie("rimplenet_referrer_sponsor", referral, 1) ;
	   //alert(referral);
	}
	
	
	if(getCookie("rimplenet_referrer_sponsor")){
	 referral = getCookie("rimplenet_referrer_sponsor");
	 //alert(referral);
	}
	
	//For woocommerce registration form
	$('#rimplenet_referrer_sponsor').val(referral);
	$('#rimplenet_referrer_sponsor').on("keyup", function(){ 
			
	    //referral = getCookie("rimplenet_referrer_sponsor");
	   document.cookie = "rimplenet_referrer_sponsor=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";//delete cookie - rimplenet_referrer_sponsor
	    var referral = $('#rimplenet_referrer_sponsor').val();
		setCookie("rimplenet_referrer_sponsor", referral, 1);
		//alert(getCookie("rimplenet_referrer_sponsor"));
	
	});
	
	//For Ultimate Membership Plugin Registration form
	$('[data-key~="rimplenet_referrer_sponsor"]').val(referral);
	$('[data-key~="rimplenet_referrer_sponsor"]').on("keyup", function(){ 
	    //referral = getCookie("rimplenet_referrer_sponsor");
	   
	   document.cookie = "rimplenet_referrer_sponsor=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";//delete cookie - rimplenet_referrer_sponsor
	   var referral = $('[data-key~="rimplenet_referrer_sponsor"]').val();
	   setCookie("rimplenet_referrer_sponsor", referral, 1);
		//alert(getCookie("rimplenet_referrer_sponsor"));
	
	});
	
	
	    
	});
	
 
   	$('.rimplenet_click_to_copy').on('click',function(e){

        RimplenetCopyText(this);

    });
    
    window.RimplenetCopyText = function RimplenetCopyText(element) {
        
    
     if($(element).val()) {
        $(element).focus();
        $(element).select();
        document.execCommand('copy');
     	alert("Copied: " + $(element).val());
     }
     else if(typeof element==='object' && element!==null){
          var $temp = $("<input>");
		  $("body").append($temp);
		  $temp.val($(element).text()).select();
		  document.execCommand("copy");
		  $temp.remove();
     	  alert("Copied: " + $(element).html());
     	  $(element).focus();
    	  $(element).select();
     }

    else{
          var element = $(element); 
      	  var $temp = $("<input>");
		  $("body").append($temp);
		  $temp.val($(element).text()).select();
		  document.execCommand("copy");
		  $temp.remove();
     	  alert("Copied: " + $(element).html());
     	  $(element).focus();
    	  $(element).select();

     }
    }

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 


})( jQuery );

function ConvertRimplenetAmount(amount, wallet_from, wallet_to){
    var value_from_wallet_to_base_wallet = rimplenet_wallets[wallet_from]["value_1_to_base_cur"];
    var value_to_wallet_to_base_wallet = rimplenet_wallets[wallet_to]["value_1_to_base_cur"]; 
                 
    amount_to_base_wallet = amount * value_from_wallet_to_base_wallet; // convert the amt (in wallet_from) to website base cur value
    amount_to_wallet_to = amount_to_base_wallet / value_to_wallet_to_base_wallet; // convert from website base cur value TO provided WALLET_TO
                
    amt_converted = amount_to_wallet_to;
                 
    return amt_converted;
}

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

var coll = document.getElementsByClassName("rimple-net-collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  });
}