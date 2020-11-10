$(document).on('ready', function () {
  $(document).on('click', '.training-get-description', function (e) {
    e.preventDefault();
    $.ajax(trainingArticlesController, {
      data: {
        'ajax': 1,
        'action': 'getDescription',
        'id_training_article': $(this).attr('data-id-training-article'),
      },
      success: function (response) {
        alert(response)
      }
    })
  })
})
