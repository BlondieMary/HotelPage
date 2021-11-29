(function($) {
  $(document).on('submit', 'form.favoriteForm', function(e) {
    // Stop default form
    e.preventDefault();
    
    // Get form inputs
    const formData = $(this).serialize();
    
    // Ajax request
    $.ajax(
      'http://hotel.collegelink.localhost/public/ajax/room_favorite.php',
      {
        type: "POST",
        dataType: "json",
        data: formData,
      }).done(function(result) {
        if (result.status) {
          $('input[name=is_favorite]').val(result.is_favorite ? 1 : 0);
          $('.fa-heart').toggleClass('selected', result.is_favorite);
        }
        else {
          console.log("error");
          $('.fa-heart').toggleClass('selected', !result.is_favorite);
        }
      });
  });

  $(document).on('submit', 'form.reviewForm', function(e) {
    // Stop default form
    e.preventDefault();

    // Get form inputs
    const formData = $(this).serialize();
    
    // ajax request
    $.ajax(
      'http://hotel.collegelink.localhost/public/ajax/room_review.php',
      {
        type: "POST",
        dataType: "html",
        data: formData,
      }).done(function(result) {
        //append results conatainer
        $('#room-reviews').append(result);
        $('#reviewForm').trigger('reset');
        $('#fastar1').css("color", "gray");
        $('#fastar2').css("color", "gray");
        $('#fastar3').css("color", "gray");
        $('#fastar4').css("color", "gray");
        $('#fastar5').css("color", "gray");
        $("#btn").attr("disabled", true);
        $("#btn").css("background","gray");
      });
  });
})(jQuery);

$(document).on('submit', 'form.bookingsDates', function(e) {
  // Stop default form
  e.preventDefault();
  
  // Get form inputs
  const formData = $(this).serialize();
  
  // Ajax request
  $.ajax(
    'http://hotel.collegelink.localhost/public/ajax/booking_results.php',
    {
      type: "GET",
      dataType: "html",
      data: formData,
    }).done(function(result) {
      console.log(formData);
      $('.room-booking').html('');
      $('.room-booking').append(result);
      history.pushState({}, '', 'http://hotel.collegelink.localhost/public/room.php?'+formData);
    });
});