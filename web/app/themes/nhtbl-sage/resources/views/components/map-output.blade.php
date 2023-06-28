<div class="relative w-full max-w-screen @if ($size == 'large') h-[80vh] @else h-96 @endif">
  <div id="{{ $uniqueMapId }}" class="w-full @if ($size == 'large') h-[80vh] @else h-96 @endif">
  </div>
  @if (in_array($slug, ['resonance', 'all', 'triennale', 'oeuvres-publics'], true))
    <div @pin.window="placedata = $event.detail.features[0].properties; console.log(placedata)" x-data="{ placedata: null }"
      class="flex flex-col gap-3 text-black absolute -right-128 max-w-full lg:w-128 z-20 bg-white p-4 transition-all top-0 bottom-0 h-full max-h-full overflow-y-scroll"
      id="feature-info" @click.away="document.getElementById('feature-info').classList.remove('right-0')">
      <div class="flex items-center justify-between">
        <h3 x-html="placedata.title" x-data="console.log(placedata.image)"></h3>
        <button type="button" class="z-50 cursor-pointer" @click="document.getElementById('feature-info').classList.remove('right-0')">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <a :href="placedata.permalink"
        class="text-center inline p-4 border border-black font-ui uppercase tracking-wider hover:bg-black hover:bg-opacity-10">Evènements
        et plus d'informations</a>
      <figure>
        <picture class="block object-cover" x-data="{
            qualitySwitch: $store.utils.getCookie('qualitySwitch') || 'webp-low',
            basePath: '/app/uploads' + placedata.image.subdir + '/,
            imageSources: {
                'webp': placedata.image.other_formats.large.webp,
                'webp-low': placedata.image.other_formats.large.webp - low,
                'webp-bw': placedata.image.other_formats.large.webp - bw
            }
        }">
          <source x-data="console.log(basePath)" :srcset="basePath + qualitySwitch" media="(min-width: 0px)" alt=" ">
          <img :width="placedata.image.width" :height="placedata.image.height" class="w-full h-auto"
            :src="placedata.image.src[0]" :alt="placedata.image.alt" />
        </picture>
      </figure>
      <div x-html="placedata.acces" class="pb-3 font-serif text-base"></div>


    </div>
  @endif
</div>


<script>
  const initializeMap = () => {

    mapboxgl.accessToken = '{{ $mapboxApiToken }}';

    const map = new mapboxgl.Map({
      container: '{{ $uniqueMapId }}',
      style: 'mapbox://styles/erikhartin/cli27x8hu02he01pnhn0v9171',
      center: [2.3755593500912586, 51.04759496120762], // Set the initial map center
      zoom: 10, // Set the initial zoom level
      language: 'fr' // Set the language to French
    });

    const dispatchDetails = (slug) => {
      const event = new CustomEvent('pin', {
        detail: slug,
        bubbles: true
      });
      window.dispatchEvent(event);
    }


    map.on('load', function() {
      // Fetch the markers data from the API route
      fetch('/wp-json/triennale/v1/lieux/{{ $slug }}')
        .then(response => response.json())
        .then(data => {
          // Create separate layers for each category
          const categories = {};

          // Iterate through the features and group them by category
          data.features.forEach(marker => {
            const categorySlug = marker.properties.category.slug;

            if (!categories[categorySlug]) {
              categories[categorySlug] = {
                type: 'FeatureCollection',
                features: []
              };
            }

            categories[categorySlug].features.push(marker);
          });

          // Add each category layer to the map
          for (const categorySlug in categories) {
            const categoryData = categories[categorySlug];

            map.addSource(categorySlug, {
              type: 'geojson',
              data: categoryData
            });

            // Load the custom icon image
            map.loadImage(`@asset('./')../map-pins/pin-${categorySlug}.png`, function(error, image) {
              if (error) throw error;

              // Add the loaded icon image to the map
              console.log('image:')
              console.log(image)
              console.log('image name:')
              console.log(categorySlug + '-icon-image')
              map.addImage(categorySlug + '-icon-image', image);
            });

            map.addLayer({
              id: categorySlug + '-icon',
              type: 'symbol',
              source: categorySlug,
              layout: {
                'icon-image': categorySlug + '-icon-image', // Use a unique identifier for the icon image
                'icon-size': 0.5 // Adjust the icon size as needed
              }
            });


            // Apply pointer cursor on hover
            map.on('mouseenter', categorySlug + '-icon', function() {
              map.getCanvas().style.cursor = 'pointer';
            });

            // Revert cursor to default on mouseleave
            map.on('mouseleave', categorySlug + '-icon', function() {
              map.getCanvas().style.cursor = '';
            });
          }

          // Fit the map to display all the markers
          @if (in_array($slug, ['resonance', 'all', 'triennale', 'oeuvres-publics'], true))
            var bounds = new mapboxgl.LngLatBounds();
            data.features.forEach(marker => {
              bounds.extend(marker.geometry.coordinates);
            });
            map.fitBounds(bounds, {
              padding: 50
            });
          @else
            map.flyTo({
              center: data.features[0].geometry.coordinates,
              essential: true, // this animation is considered essential with respect to prefers-reduced-motion
              duration: 1000,
              zoom: 12.5,
            });
          @endif


          // Custom function to determine circle color based on category
          function getCircleColor(categorySlug) {
            switch (categorySlug) {
              case 'triennale':
                return '#ff0000'; // Red color for triennale category
              case 'resonance':
                return '#00ff00'; // Green color for resonance category
              case 'oeuvre-public':
                return '#0000ff'; // Blue color for oeuvre-public category
              default:
                return '#000000'; // Black color for other categories
            }
          }

          @if (in_array($slug, ['resonance', 'all', 'triennale', 'oeuvres-publics'], true))

            // Add click event listener to the layers
            Object.keys(categories).forEach(categorySlug => {
              map.on('click', categorySlug + '-icon', function(e) {
                e.preventDefault();

                const clickedFeature = e.features[0];

                fetch(`/wp-json/triennale/v1/lieux/${clickedFeature.properties.slug}`)
                  .then(response => response.json())
                  .then(data => {
                    // Display the returned data in the green div
                    dispatchDetails(data)
                    console.log(clickedFeature)
                    document.getElementById('feature-info').classList.add('right-0')
                    map.flyTo({
                      center: clickedFeature.geometry.coordinates,
                      essential: true, // this animation is considered essential with respect to prefers-reduced-motion
                      duration: 1000,
                      zoom: 12.5,
                    });
                  })
                  .catch(error => {
                    console.error('Error:', error);
                  });
              });

              map.on('click', function(e) {
                if (e.defaultPrevented === false) {
                  var bounds = new mapboxgl.LngLatBounds();
                  data.features.forEach(marker => {
                    bounds.extend(marker.geometry.coordinates);
                  });
                  map.fitBounds(bounds, {
                    padding: 50
                  });
                }
              });
            });
          @endif
        })
        .catch(error => {
          console.error('Error:', error);
        });
    });
  }

  typeof wp !== 'undefined' ? wp.domReady(initializeMap) : window.addEventListener('DOMContentLoaded', initializeMap);
</script>
