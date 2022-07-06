(function( $ ) {
	'use strict';
  let rt_dir = $('#__rt_dir').val()
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

  $('#transfer-wallet').on('change', function(){
    let selected = $(this).find(':selected')
    $('span.wallet_symbol').text(selected.data('symbol'))
  })

  $('input.user-transfer').on('input', function(){
    let bal = $(this).parent().find('.wallet_balance')
    
    bal.text($(this).val())
  })



})( jQuery );
