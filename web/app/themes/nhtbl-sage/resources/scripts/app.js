import domReady from '@roots/sage/client/dom-ready'
import Alpine from 'alpinejs'

window.Alpine = Alpine

/**
 * Application entrypoint
 */
domReady(async () => {
  Alpine.start()
  // ...
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);
