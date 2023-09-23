(function( $ ) {
	'use strict';
  let rt_dir = $('#__rt_dir').val()

  let baseUrl = window.location.origin
  
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

  console.log('rimplenet_transfers_ajax_object.ajaxurl');

  $('input.user-transfer').on('input', function(){
    let value = $(this).val()
    let bal = $(this).parent().find('.wallet_balance')
    bal.text($(this).val())
    $.ajax({
      url: `${rimplenet_transfers_ajax_object.ajaxurl}transfers/assets/php/helpers.php?get_user`,
      method: 'get'
    })
  })




})( jQuery );
