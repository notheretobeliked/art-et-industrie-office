import domReady from '@roots/sage/client/dom-ready'
import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'
import {setCookie, getCookie} from './qualityswitcher.js'

window.Alpine = Alpine
Alpine.plugin(collapse)

window.getCookie = getCookie
window.setCookie = setCookie

/**
 * Application entrypoint
 */

domReady(async () => {
  document.addEventListener('alpine:init', () => {
    Alpine.store('utils', {
      setCookie: (name, value, days) => window.setCookie(name, value, days),
      getCookie: (name, value, days) => window.getCookie(name, value, days),
    })
    Alpine.store('quality', {
      qualitySwitch: window.getCookie('qualitySwitch') || 'webp-low',
    })
  })

  Alpine.start()

  // Get all gallery wrappers
  let galleryWrappers = document.querySelectorAll('.galleryWrapper')

  if (galleryWrappers.length) {
    // There are galleries on the page
    galleryWrappers.forEach((wrapper) => {
      let galleryContainer = wrapper.querySelector('.galleryContainer')
      let prevBtn = wrapper.querySelector('.prevBtn')
      let nextBtn = wrapper.querySelector('.nextBtn')

      // Check if galleryContainer exists
      if (galleryContainer) {
        let children = Array.from(
          galleryContainer.getElementsByClassName('snap-start'),
        )
        let currentIndex = 0

        function adjustIndex(isNext) {
          if (isNext) {
            if (currentIndex < children.length - 1) {
              currentIndex++
            }
          } else {
            if (currentIndex > 0) {
              currentIndex--
            }
          }
          galleryContainer.scroll({
            top: 0,
            left: children[currentIndex].offsetLeft,
            behavior: 'smooth',
          })
        }

        prevBtn.addEventListener('click', function () {
          adjustIndex(false)
        })

        nextBtn.addEventListener('click', function () {
          adjustIndex(true)
        })
      }
    })
  } 

  let containers = document.querySelectorAll('.scroll-container')

  if (containers.length > 0) {
    containers.forEach(function (nav) {
      let idx
      let acceleration = 1
      let scrollSpeed = 1.2
      let maxScrollSpeed = 4
      let accelerationFactor = 0.3 // Adjust this value to control the acceleration sensitivity
      let isMouseInside = false
      let mousePos = {x: 0, y: 0}

      nav.addEventListener('mouseenter', () => {
        isMouseInside = true
      })

      nav.addEventListener('mouseleave', () => {
        isMouseInside = false
        resetScroll()
      })

      function resetScroll() {
        acceleration = 1
        scrollSpeed = 1.3
      }

      let isMouseMoving = false // Flag to track mouse movement

      nav.addEventListener('mousemove', (event) => {
        resetScroll()
        let rect = nav.getBoundingClientRect()
        mousePos = {
          x: event.clientX - rect.left,
          y: event.clientY - rect.top,
        }

        // Update the mouse movement flag
        isMouseMoving = true

        let containerCenterX = rect.width / 2
        let mouseOffsetX = mousePos.x - containerCenterX
        let distanceFromCenter = Math.abs(mouseOffsetX)

        let accelerationMultiplier = 1 + distanceFromCenter * accelerationFactor
        scrollSpeed = Math.min(
          maxScrollSpeed,
          scrollSpeed * accelerationMultiplier,
        )

        let scrollDirection = mouseOffsetX < 0 ? -1 : 1
        nav.scrollLeft += scrollDirection * scrollSpeed
      })

      idx = setInterval(updateScroll, 5)

      function updateScroll() {
        if (isMouseInside) {
          let rect = nav.getBoundingClientRect()
          let containerCenterX = rect.width / 2
          let mouseOffsetX = mousePos.x - containerCenterX

          // Calculate the scroll speed based on the mouse offset
          let scrollSpeed = 0

          if (mouseOffsetX > 0) {
            // Calculate the speed based on the distance from the center
            scrollSpeed = Math.min(
              maxScrollSpeed,
              mouseOffsetX * accelerationFactor,
            )
          } else if (mouseOffsetX < 0) {
            // Calculate the speed based on the distance from the center (negative)
            scrollSpeed = Math.max(
              -maxScrollSpeed,
              mouseOffsetX * accelerationFactor,
            )
          }

          // Scroll the container with the calculated speed
          nav.scrollLeft += scrollSpeed
        }
      }

      // Function to map a value from one range to another
      function mapRange(value, inMin, inMax, outMin, outMax) {
        return ((value - inMin) * (outMax - outMin)) / (inMax - inMin) + outMin
      }
    })
  }
})

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error)
