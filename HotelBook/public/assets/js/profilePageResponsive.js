document.addEventListener("DOMContentLoaded", () => {

  // Favorite button
  var favClass = document.querySelector(".favorite-bar");
  let favClassFlagg = true;
  const $btn_favorite_button = document.querySelector(".favorite-responsive-button");

  $btn_favorite_button.addEventListener("click", (e) => {
    favClassFlagg = !favClassFlagg;
    
    if (favClassFlagg === true) {
      favClass.className = " favorite-bar-hidden";
    }
    else {
      favClass.className = "favorite-bar-vissibled";
    }
    console.log(favClass);
  });

  // Review button
  var revClass = document.querySelector(".review-bar");
  let revClassFlagg = true;
  const $btn_review_button = document.querySelector(".review-responsive-button");

  $btn_review_button.addEventListener("click", (e) => {
    revClassFlagg = !revClassFlagg;
    if (revClassFlagg === true) {
      revClass.className = " review-bar-hidden";
    }
    else {
      revClass.className = "review-bar-vissibled";
    }
    console.log(favClass);
  });

  window.addEventListener('resize', () => {
    var width = window.screen.availWidth;
    if (width < 480) {
      favClass.className = "favorite-bar-hidden";
      revClass.className = " review-bar-hidden";
    }
    else {
      favClass.className = "favorite-bar";
      revClass.className = " review-bar";
      let revClassFlagg = true;
      let favClassFlagg = true;
    }});

    favClass.className = "favorite-bar";
    revClass.className = " review-bar";
});