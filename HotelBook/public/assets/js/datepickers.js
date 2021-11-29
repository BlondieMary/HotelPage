document.addEventListener("DOMContentLoaded", () => {
  let cHECKINDATE = null;
  let cHECKOUTDATE = null;
  let minDate
  const $Searchbtn = document.querySelector("#submitButton");
  const $city = document.querySelector("#City_option");
  $Searchbtn.disabled = true;

  // Flag: will be True ONLY IF check-in date and check-out date are in the right cronological order 
  let VALID_DATE = false;

  $('#submitButton').css("background-color", "gray");

  changebtn = () => {
    $("#submitButton").mouseout(function() {
      $("#submitButton").css("background-color", "#ff764b");
    });

    $('#submitButton').css("background-color", "#ff764b");
    
    $("#submitButton").mouseover(function() {
      $("#submitButton").css("background-color", " #a20d01");
    });
  }

  defaultDateStyle = () => {
    $('#check_in_date').css("border-color", "#f1eaea");
    $('#check_in_date').css("color", "#000");
    $('#check_out_date').css("border-color", "#f1eaea");
    $('#check_out_date').css("color", "#000;");
  }
  
  $(function() {
    $("#check_in_date").datepicker({
      minDate: 0,
      dateFormat: "dd-mm-yy",
      onSelect: function() {
        var checkindate = $(this).val();
        var splitted = checkindate.split('-');
        cHECKINDATE = new Date(splitted[2], splitted[1]-1, splitted[0], 0, 0, 0);
        if (cHECKOUTDATE != null) {
          if (cHECKOUTDATE < cHECKINDATE) {
            $('#check_in_date').css("border-color", "red");
            $('#check_in_date').css("color", "red");
            $('#submitButton').css("background-color", "gray");
            $Searchbtn.disabled = true;
          }
          else {
            VALID_DATE = true;
            defaultDateStyle();
            if ($("#City_option").val() === "City") {
              $Searchbtn.disabled = true;
            }
            else {
              changebtn();
              $Searchbtn.disabled = false;
            }
          }
        }
        else {
          $Searchbtn.disabled = true;
          VALID_DATE = false;
        }
      }
    });
  });

  $("#check_in_date").on("change", function() {
    var selected = $(this).val();
    if (selected === "" || selected === null) {
      cHECKINDATE=null;
      VALID_DATE = false;
      $Searchbtn.disabled = true;
    }
  });
  
  $(function() {
    $("#check_out_date").datepicker({
      minDate:2,
      dateFormat: "dd-mm-yy",
      onSelect: function() {
        var checkoutdate = $(this).val();
        var splitted = checkoutdate.split('-');
        cHECKOUTDATE = new Date(splitted[2], splitted[1]-1, splitted[0], 0, 0, 0);
        if (cHECKINDATE != null){
          if (cHECKOUTDATE < cHECKINDATE){
            $('#check_out_date').css("border-color", "red");
            $('#check_out_date').css("color", "red");
            $('#submitButton').css("background-color", "gray");
            $Searchbtn.disabled = true;
          }
          else {
            VALID_DATE = true;
            defaultDateStyle();
            if ($("#City_option").val() === "City") {
                $Searchbtn.disabled = true;
            }
            else {
              changebtn();
              $Searchbtn.disabled = false;
            }
          }
        }
        else {
          $Searchbtn.disabled = true;
          VALID_DATE = false;
        }
      }
    });
  });
  
  $("#check_out_date").on("change",function() {
    var selected = $(this).val();
      if (selected === "" || selected === null) {
        cHECKOUTDATE=null;
        VALID_DATE = false;
        $Searchbtn.disabled = true;
      }
  });
  
  $( "#City_option" ).change(function(e) {
    if ($("#City_option").val() === "City") {
        $Searchbtn.disabled = true;
        $('#submitButton').css("background-color", "gray");
    }
    else if (VALID_DATE === true) {
      $Searchbtn.disabled = false;
      defaultDateStyle();
      changebtn();
    }
  });
});