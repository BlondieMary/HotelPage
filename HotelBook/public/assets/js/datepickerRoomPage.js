document.addEventListener("DOMContentLoaded", () => {
  let cHECKINDATE = null;
  let cHECKOUTDATE = null;

  // Flag: will be True ONLY IF check-in date and check-out date are in the right cronological order 
  let VALID_DATE = false;
  
  const $Searchbtn = document.querySelector("#check_dates");
  const $checkOutDate = document.querySelector("#check_out_date");
  const $checkInDate = document.querySelector("#check_in_date");

  $Searchbtn.disabled = false;

  changebtn = () => {
    $Searchbtn.addEventListener("mouseenter", function() {
      $Searchbtn.style.backgroundColor = "#a20d01";
    });

    $Searchbtn.addEventListener("mouseleave", function() {
      $Searchbtn.style.backgroundColor = "red";
    });
  };

  $(function() {
    $("#check_in_date").datepicker({
      minDate: 0,
      dateFormat: "dd-mm-yy",
      onSelect: function() {
        var checkindate = $(this).val();
        var splitted = checkindate.split('-');
        cHECKINDATE = new Date(splitted[2], splitted[1]-1, splitted[0], 0, 0, 0);
        if (cHECKOUTDATE < cHECKINDATE) {
          $Searchbtn.style.backgroundColor = "gray";
          $Searchbtn.disabled = true;
        }
        else {
          VALID_DATE = true;
          changebtn();
          $Searchbtn.disabled = false;
          $Searchbtn.style.backgroundColor = "red";
        }
      }
    });
  });

  $(function() {
    $("#check_out_date").datepicker({
      minDate:2,
      dateFormat: "dd-mm-yy",
      onSelect: function() {
        var checkoutdate = $(this).val();
        var splitted = checkoutdate.split('-');
        cHECKOUTDATE = new Date(splitted[2], splitted[1]-1, splitted[0], 0, 0, 0);
        if (cHECKOUTDATE < cHECKINDATE) {
          $Searchbtn.style.backgroundColor = "gray";
          $Searchbtn.disabled = true;
        }
        else {
          VALID_DATE = true;
          changebtn();
          $Searchbtn.disabled = false;
          $Searchbtn.style.backgroundColor = "red";
        }
      }
    });
  });
});