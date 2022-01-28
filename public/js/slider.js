/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************!*\
  !*** ./resources/js/slider.js ***!
  \********************************/
(function () {
  return;
  var area = '';

  if (window.screen.width <= 768) {
    area = '.images-area--base';
  } else if (window.screen.width <= 1024) {
    area = '.images-area--m';
  } else if (window.screen.width <= 1280) {
    area = '.images-area--lg';
  } else {
    area = '.images-area--xl';
  } // Images Area Images


  var imagesAreaImages = document.querySelectorAll(area + ' img'); // Images Area First Image

  var imagesAreaFirstImage = document.querySelector(area + ' .firstImage'); // Current Image Count

  var currentImageCount = 1; // Slider Controler Function

  var sliderController; // Slider Controler Function

  (sliderController = function sliderController() {
    // The currentImageCount Minus One
    var currentImageMinusOne = currentImageCount - 1;
    var width = window.screen.width; // Move The images Area First Image

    imagesAreaFirstImage.style.marginLeft = "-".concat(width * currentImageMinusOne, "px");
  })(); // Move Slider Image Every 3 Second 

  setInterval(function () {
    // If The Current Image Count Not Equle imagesAreaImages.length
    if (currentImageCount != imagesAreaImages.length) {
      // Plus One
      currentImageCount++; // Call Function sliderController();

      sliderController();
    } else {
      // else
      // Make currentImageCount Equle 1
      currentImageCount = 1; // Call Function sliderController();

      sliderController();
    }

    ;
  }, 3000);
})();
/******/ })()
;