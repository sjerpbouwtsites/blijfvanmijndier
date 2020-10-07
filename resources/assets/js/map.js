/**
 * intialises map on roelofarendsveen and returns leaflet map instance.
 */
function createMap() {
  const goudaMapConfig = {
    lat: 52.2,
    lon: 4.6,
    zoom: 6,
  };

  // initialize the map on the "map" div with a given center and zoom
  var leafletMap = L.map("leaflet-map", {
    center: [goudaMapConfig.lat, goudaMapConfig.lon],
    zoom: goudaMapConfig.zoom,
  });

  L.tileLayer(
    "https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoic2plcnAtdmFuLXdvdWRlbiIsImEiOiJjajh5NmExaTAxa29iMzJwbDV0eXF4eXh4In0.HVBgF1SbusJzMwmjHcHS2w",
    {
      attribution:
        '<span id="map-info"></span> <strong>NOOOORD</strong>Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 18,
      id: "mapbox/streets-v11",
      tileSize: 512,
      zoomOffset: -1,
      accessToken: "your.mapbox.access.token",
    }
  ).addTo(leafletMap);
  return leafletMap;
}

/**
 * dummy function to get locations
 * @returns Promise always succes with dummydata
 */
function getLocations() {
  return new Promise((locationSucces, locationFailure) => {
    setTimeout(() => {
      return locationSucces(dummyData);
    }, 250);
  });
}

/**
 * create alt attribute which is a general styling & identifying attribute in this app for markers.
 * loops over list of conditions
 * @param {*} location
 */
function maakAlt(location) {
  return [
    {
      key: "type",
      check: (str) => {
        return str === "vet";
      },
      res: "color-red",
    },
  ]
    .map((condition) => {
      const locationVal = location[condition.key];

      return condition.check(locationVal) ? condition.res : "";
    })
    .join(" ");
}

/**
 * callback for locations.map
 * refers maakAlt
 * creates marker and binds to global mapInstance
 * bindspopup.
 * @param {*} location
 */
function locationMapper(location) {
  const options = {
    alt: maakAlt(location),
  };

  const marker = L.marker([location.lat, location.lon], options).addTo(leafletMap);

  marker.bindPopup(
    `<div class='leaflet-popup-publicatie'>
      <header class='leaflet-popup-header'>
        
        <span class='leaflet-popup-header-left'>
          ${location.title}
        </span>
        
        <div class='leaflet-popup-header-right'>
          
        </div>
          
      </header>

      <div class='leaflet-popup-text verborgen'>
        ${location.text}
      </div>

      </div class='leaflet-popup-publicatie'>`
  );
}

const dummyData = [
  {
    type: "vet",
    lat: 52.2,
    lon: 4.6,
    title: "harry",
    text: "harry text",
  },
  {
    type: "vet",
    lat: 52.25,
    lon: 4.65,
    title: "harry 2",
    text: "harry textdf dgfdg",
  },
  {
    type: "bakery",
    lat: 53.25,
    lon: 4.65,
    title: "bakery",
    text: "bread is good",
  },
];

let leafletMap;

function init() {
  leafletMap = createMap();
  getLocations().then((locations) => {
    locations.map(locationMapper);
  });
}

init();
