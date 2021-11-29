var y = document.querySelector(".hotel-search");
let flag = true;

const $btn_form_search = document.querySelector(".filter-search");
$btn_form_search.addEventListener("click", (e) => {
  flag = !flag;
  
  if (flag === true) {
    y.className = " hidden";
  }
  else {
    y.className = " vissibled";
  }
});