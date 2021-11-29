(function($) {
  $(document).on('submit', 'form.listForm', function(e){
  // Stop default form
  e.preventDefault();
  
  // Get form inputs
  const formData = $(this).serialize();
  // Ajax request
  $.ajax(
    'http://hotel.collegelink.localhost/public/ajax/index_list_result.php',
    {
      type: "GET",
      dataType: "html",
      data: formData,
    }).done(function(result) {
      // Clear results container
      $('#container-search-result').html('');
      $('#container-search-result').css("display", "block");
      $(".footer-index").css("display", "none");
      
      //Append results to the results container
      $('#container-search-result').append(result);
      
      history.pushState({}, '', 'http://hotel.collegelink.localhost/public/index.php?'+formData);
      $("html, body").animate({
        scrollTop: $(".container:nth-child(2)").offset().top
      }, 1000);
    });
  });
})(jQuery);

// This function is used to get a specific parameter from the URL String
function getQueryParam(param, defaultValue = undefined) {
  location.search.substr(1)
                 .split("&")
                 .some(function(item) {
                    return item.split("=")[0] == param && (defaultValue = item.split("=")[1], true)
                  })
    return defaultValue
}

// For Load and Reload events, to fetch data if URL parameters are present
(function($) {
  $(window).bind('load', function(e) {
    // Get parameters from URL string
    var city = getQueryParam("city");
    var room = getQueryParam("room");
    var checkInDate = getQueryParam("check_in_date");
    var checkOutDate = getQueryParam("check_out_date");
    
    // If URL contains the parameters, make an ajax call to fetch data based on those parameters
    if (city != undefined && checkInDate != undefined && checkOutDate != undefined ) {
      // Stop default form
      e.preventDefault();

      // Get form inputs
      const formData = location.search.substr(1);

      // Ajax request
      $.ajax(
        'http://hotel.collegelink.localhost/public/ajax/index_list_result.php',
        {
          type: "GET",
          dataType: "html",
          data: formData,
        }).done(function(result) {
          // Clear results container
          $('#container-search-result').html('');
          $('#container-search-result').css("display", "block");

          // Append results to the container
          $('#container-search-result').append(result);
          $(".footer-index").css("display", "none");
          history.pushState({}, '', 'http://hotel.collegelink.localhost/public/index.php?'+formData);
          $("html, body").animate({
             scrollTop: $(".container:nth-child(2)").offset().top
          }, 1000);
        });
    }
    else {
      $('#container-search-result').html('');
      $('#container-search-result').css("display", "none");
    }
  });
})(jQuery);