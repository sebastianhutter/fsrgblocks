/******/ (() => { // webpackBootstrap
/*!********************************************!*\
  !*** ./src/next-tour-dates-slider/view.js ***!
  \********************************************/
/**
 * Use this file for JavaScript code that you want to run in the front-end
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 *
 * If you're not making any changes to this file because your project doesn't need any
 * JavaScript running in the front-end, then you should delete this file and remove
 * the `viewScript` property from `block.json`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#view-script
 */

const swiper = new Swiper('.swiper', {
  // Optional parameters
  direction: 'horizontal',
  loop: true,
  slidesPerView: "auto",
  centeredSlides: true,
  spaceBetween: 10,
  // TODO: enable after css testing is done.
  // autoplay: {
  // 	delay: 5000,
  // },

  // If we need pagination
  pagination: {
    el: ".swiper-pagination",
    clickable: true
  },
  mousewheel: false,
  keyboard: false
});
/******/ })()
;
//# sourceMappingURL=view.js.map