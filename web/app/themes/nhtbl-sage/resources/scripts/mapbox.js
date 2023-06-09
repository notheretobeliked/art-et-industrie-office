const initializeMap = (token, container) => {
  mapboxgl.accessToken = token;

  const map = new mapboxgl.Map({
    container: container,
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [2.3755593500912586, 51.04759496120762], // Set the initial map center
    zoom: 10, // Set the initial zoom level
    language: 'fr' // Set the language to French

  });


  map.on('load', function() {
    // Fetch the markers data from the API route
    fetch('/wp-json/triennale/v1/lieux/all')
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

          map.addLayer({
            id: categorySlug,
            type: 'circle',
            source: categorySlug,
            paint: {
              'circle-color': getCircleColor(
                categorySlug), // Custom function to determine circle color based on category
              'circle-radius': 6,
              'circle-stroke-width': 2,
              'circle-stroke-color': '#ffffff'
            }
          });
          // Apply pointer cursor on hover
          map.on('mouseenter', categorySlug, function() {
            map.getCanvas().style.cursor = 'pointer';
          });

          // Revert cursor to default on mouseleave
          map.on('mouseleave', categorySlug, function() {
            map.getCanvas().style.cursor = '';
          });
        }

        // Fit the map to display all the markers
        var bounds = new mapboxgl.LngLatBounds();
        data.features.forEach(marker => {
          bounds.extend(marker.geometry.coordinates);
        });
        map.fitBounds(bounds, {
          padding: 50
        });

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

        // Add click event listener to the layers
        Object.keys(categories).forEach(categorySlug => {
          map.on('click', categorySlug, function(e) {
            
            const clickedFeature = e.features[0];

            // Create HTML content for the feature information
            const title = document.createElement('h3');
            title.innerHTML = clickedFeature.properties.title;

            const description = document.createElement('p');
            description.innerHTML = clickedFeature.properties.description;

            // Clear existing content in the #feature-info div
            const featureInfoDiv = container.querySelector('#feature-info');
            featureInfoDiv.classList.add('right-0');
            featureInfoDiv.innerHTML = '';

            // Append the title and description to the #feature-info div
            featureInfoDiv.appendChild(title);
            featureInfoDiv.appendChild(description);
          });
        });
      })
      .catch(error => {
        console.error('Error:', error);
      });
  });
}

