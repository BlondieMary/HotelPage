(function($) {
  $(document).on('submit', 'form.searchForm', function(e) {
    // Stop default form
    e.preventDefault();
    
    // Get form inputs
    const formData = $(this).serialize();
    
    // Ajax request
    $.ajax(
      'http://hotel.collegelink.localhost/public/ajax/search_results.php',
      {
        type: "GET",
        dataType: "html",
        data: formData,
      }).done(function(result) {
        // Clear results container
        $('#search-results-container').html('');
        
        // Append results in the results container
        $('#search-results-container').append(result);
        history.pushState({}, '', 'http://hotel.collegelink.localhost/public/index.php?'+formData);
      });
  });
})(jQuery);