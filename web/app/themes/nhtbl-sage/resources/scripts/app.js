import domReady from '@roots/sage/client/dom-ready'
import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'
import {setCookie, getCookie} from './qualityswitcher.js'

window.Alpine = Alpine
Alpine.plugin(collapse)

window.getCookie = getCookie
window.setCookie = setCookie

if (localStorage.darkMode === 'dark' || ((localStorage.darkMode  === 'system') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
  document.documentElement.classList.add('dark')
} else {
  document.documentElement.classList.remove('dark')
}

/**
 * Application entrypoint
 */

domReady(async () => {
  
  document.addEventListener('alpine:init', () => {
    Alpine.store('utils', {
      setCookie: (name, value, days) => window.setCookie(name, value, days),
      getCookie: (name, value, days) => window.getCookie(name, value, days),
    }),
    Alpine.store('quality', {
      qualitySwitch: window.getCookie('qualitySwitch') || 'webp-low'
    })
  })

  
  Alpine.start()

  let containers = document.querySelectorAll('.scroll-container')

  if (containers.length > 0) {
    containers.forEach(function (nav) {
      let idx
      let acceleration = 1
      let scrollSpeed = 1.2
      let maxScrollSpeed = 8
      let accelerationFactor = 0.6 // Adjust this value to control the acceleration sensitivity
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

      nav.addEventListener('mousemove', (event) => {
        resetScroll()
        let rect = nav.getBoundingClientRect()
        mousePos = {
          x: event.clientX - rect.left,
          y: event.clientY - rect.top,
        }

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
          // No need to update scroll position here
        }
      }
    })
  }
})

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error)
