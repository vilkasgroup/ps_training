$(document).on('ready', function () {
  prestashop.on('updateCart', (event) => {
    console.log(event);
  })

  $(document).on('click', '.training-add-to-wishlist', function () {
    $.ajax(trainingAjaxController, {
      data: {
        'ajax': 1,
        'action': 'addToWishList',
        'id_product': $(this).attr('data-id-product'),
        'token': prestashop.static_token,
      },
      success: function (response) {
        alert(response)
      }
    })
  })
})
