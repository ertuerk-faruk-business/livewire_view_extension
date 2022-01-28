/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************!*\
  !*** ./resources/js/lve.js ***!
  \*****************************/
/** Livewire View Extension UpdateBrowserHistory */
document.addEventListener('DOMContentLoaded', function () {
  window.livewire.on('lveUrlChanges', function (param) {
    if (!param) {
      history.pushState(null, null, "".concat(document.location.pathname));
      return;
    }

    history.pushState(null, null, "".concat(document.location.pathname, "?").concat(param));
  });
});
/******/ })()
;