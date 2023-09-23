(function( $ ) {
	'use strict';
	  
   	$( window ).load(function() {
   	   $('.rimplenet_click_to_copy').on('click',function(e){
        RimplenetCopyText(this);

       }); 
   	});
   	
    
function RimplenetCopyText(element) {
 $(element).focus();
 $(element).select();
 document.execCommand('copy');




 if($(element).val()) {
 	alert("Copied: " + $(element).val());
 }
  else{
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
	 * All of the code for your admin-facing JavaScript source
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
