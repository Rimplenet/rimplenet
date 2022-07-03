(function( $ ) {
	'use strict';
let canSend = []
  const checkempty = (input = []) => {
    canSend = [];
    input.forEach((item) => {
      if (item.val() == "") {
        item.addClass('error').removeClass('success');
        canSend.push(false);
      } else {
        item.addClass('success').removeClass('error')
        canSend.push(true);
      }
    });
  };

  let amount = $('#transfer-amount')
  let wallet = $('#transfer-wallet')
  let user = $('#transfer-user')

  $('.transfer-form').on('submit', function(e){
    let form = $(this)
    e.preventDefault();
    checkempty([amount, wallet, user])
    if (!canSend.includes(false)) {
      // $(form).submit();
      document.querySelector('.transfer-form').submit()
    }
  })


})( jQuery );
