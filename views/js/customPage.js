$(document).ready(function () {
  $(document).on('click', '.what-day-button', function () {
    $.ajax(trainingCustomPageUrl, {
      data: {
        action: 'getCurrentDay',
        token: prestashop.static_token,
        ajax: 1
      },
      success: function (response) {
        console.log('hello world');
        alert(response);
      }
    });
  })
});
