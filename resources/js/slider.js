
(()=>{
return;
    let area = '';

    if (window.screen.width <=  768) {
        area = '.images-area--base';
    } else if (window.screen.width <=  1024) {
        area = '.images-area--m';
    } else if (window.screen.width <=  1280) {
        area = '.images-area--lg';
    } else {
        area = '.images-area--xl';
    }

        // Images Area Images
    let imagesAreaImages = document.querySelectorAll(area+' img');
    // Images Area First Image
    let imagesAreaFirstImage = document.querySelector(area+' .firstImage');

    // Current Image Count
    let currentImageCount = 1;

    // Slider Controler Function
    let sliderController;


// Slider Controler Function
(sliderController = function (){

  // The currentImageCount Minus One
  let currentImageMinusOne = currentImageCount - 1;

  let width = window.screen.width;
  // Move The images Area First Image
  imagesAreaFirstImage.style.marginLeft = `-${width * currentImageMinusOne}px`;
})();

// Move Slider Image Every 3 Second 
setInterval(() => {
  // If The Current Image Count Not Equle imagesAreaImages.length
  if(currentImageCount != imagesAreaImages.length){
    // Plus One
    currentImageCount++;
    // Call Function sliderController();
    sliderController();
  }else{ // else
    // Make currentImageCount Equle 1
    currentImageCount = 1;
    // Call Function sliderController();
    sliderController();
  };
}, 3000);
})();