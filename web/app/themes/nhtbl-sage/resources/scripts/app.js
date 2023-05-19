import domReady from '@roots/sage/client/dom-ready'
import Alpine from 'alpinejs'

window.Alpine = Alpine

/**
 * Application entrypoint
 */
domReady(async () => {
  Alpine.start()

  let containers = document.querySelectorAll(".scroll-container");

  if (containers.length > 0) {
    containers.forEach(function(nav) {
      let idx;
      let acceleration = 1;
      let scrollSpeed = 2;
      let maxScrollSpeed = 20;
      let accelerationFactor = 0.05; // Adjust this value to control the acceleration sensitivity
      let isMouseInside = false;
      let mousePos = { x: 0, y: 0 };

      nav.addEventListener("mouseenter", () => {
        isMouseInside = true;
        nav.style.overflowY = "scroll";
      });

      nav.addEventListener("mouseleave", () => {
        isMouseInside = false;
        resetScroll();
        nav.style.overflowY = "hidden";
      });

      function resetScroll() {
        acceleration = 1;
        scrollSpeed = 2;
      }

      nav.addEventListener("mousemove", (event) => {
        resetScroll();
        let rect = nav.getBoundingClientRect();
        mousePos = {
          x: event.clientX - rect.left,
          y: event.clientY - rect.top
        };

        let containerCenterX = rect.width / 2;
        let mouseOffsetX = mousePos.x - containerCenterX;
        let distanceFromCenter = Math.abs(mouseOffsetX);

        let accelerationMultiplier = 1 + distanceFromCenter * accelerationFactor;
        scrollSpeed = Math.min(maxScrollSpeed, scrollSpeed * accelerationMultiplier);

        let scrollDirection = mouseOffsetX < 0 ? -1 : 1;
        nav.scrollLeft += scrollDirection * scrollSpeed;
      });

      idx = setInterval(updateScroll, 5);
      
      function updateScroll() {
        if (isMouseInside) {
          // No need to update scroll position here
        }
      }
    });
  }
  // ...
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);
