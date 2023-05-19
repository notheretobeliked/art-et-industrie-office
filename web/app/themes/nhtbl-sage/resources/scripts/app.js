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
      let accelerationInterval = 500;
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
        clearInterval(idx);
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
      });

      function updateScroll() {
        if (isMouseInside) {
          if (mousePos.x < nav.offsetWidth / 2) {
            nav.scrollLeft -= scrollSpeed;
          } else {
            nav.scrollLeft += scrollSpeed;
          }

          if (scrollSpeed < maxScrollSpeed) {
            acceleration += 1;
            if (acceleration >= accelerationInterval) {
              scrollSpeed += 1;
              acceleration = 1;
            }
          }
        }
      }

      setInterval(updateScroll, 5);
    });
  }
  // ...
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);
