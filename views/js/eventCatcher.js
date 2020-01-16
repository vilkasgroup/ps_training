$(document).ready(function() {
  console.log('hello world');

  prestashop.on('updateCart', (event) => {
    console.log(event);
  });
});
