document.addEventListener("DOMContentLoaded", () => {
  const $star5 = document.querySelector("#fastar5");
  const $star4 = document.querySelector("#fastar4");
  const $star3 = document.querySelector("#fastar3");
  const $star2 = document.querySelector("#fastar2");
  const $star1 = document.querySelector("#fastar1");
  const $sudmitButton = document.querySelector("#btn");

  $sudmitButton.disabled =true;
  $sudmitButton.style.background = "gray";
  $sudmitButton.addEventListener("click", (e) => {
    $sudmitButton.style.cursor = "progress";
  });

  function enablebutton() {
    $sudmitButton.disabled =false;
    $sudmitButton.style.background = "#ff3d01";
    
    $sudmitButton.addEventListener("mouseover", (e) => {
      $sudmitButton.style.background = "#8d1313";
    });

    $sudmitButton.addEventListener("mouseout", (e) => {
      $sudmitButton.style.background = "#ff3d01";
    });
  }

  $star5.addEventListener("click", (e) => {
    $star5.style.color = "#ef2222";
    $star4.style.color = "#ef2222";
    $star3.style.color = "#ef2222";
    $star2.style.color = "#ef2222";
    $star1.style.color = "#ef2222";
    enablebutton();
  });

  $star4.addEventListener("click", (e) => {
    enablebutton();
    $star5.style.color = "gray";
    $star4.style.color = "#ef2222";
    $star3.style.color = "#ef2222";
    $star2.style.color = "#ef2222";
    $star1.style.color = "#ef2222";
  });

  $star3.addEventListener("click", (e) => {
    $star5.style.color = "gray";
    $star4.style.color = "gray";
    $star3.style.color = "#ef2222";
    $star2.style.color = "#ef2222";
    $star1.style.color = "#ef2222";
    enablebutton();
  });

  $star2.addEventListener("click", (e) => {
    $star5.style.color = "gray";
    $star4.style.color = "gray";
    $star3.style.color = "gray";
    $star2.style.color = "#ef2222";
    $star1.style.color = "#ef2222";
    enablebutton();
  });

  $star1.addEventListener("click", (e) => {
    $star5.style.color = "gray";
    $star4.style.color = "gray";
    $star3.style.color = "gray";
    $star2.style.color = "gray";
    $star1.style.color = "#ef2222";
    enablebutton();
  });
});