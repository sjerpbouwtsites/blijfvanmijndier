const models = require("./map/models");
const leafletShell = require("./map/leaflet-shell");
const sidebar = require("./map/sidebar");
const postLeafletFixes = require("./map/post-leaflet-fixes");
const popups = require("./map/popups");

function addInteractive() {
  popups.buttonHandlers.init();
  popups.closeDialogClickHandler();
  popups.closeAllDialogsPopupsIframesEscape();
}

/**
 * intialises map on roelofarendsveen and returns leaflet map instance.
 */
function createMap() {
  const goudaMapConfig = {
    lat: 52.2,
    lon: 4.6,
    zoom: 7,
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
        '<span id="map-info"></span> <strong>Door Sjerp van Wouden </strong>Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
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
 * getMapAPIData
 * @returns Promise
 */
function getMapAPIData() {
  return fetch("/map/data")
    .then((unjson) => {
      return unjson.json();
    })
    .then((jsonBlob) => {
      const baseData = JSON.parse(jsonBlob);
      const dataModel = models.create(baseData);

      return dataModel;
    })
    .catch((err) => {
      alert("fout bij aanvragen map data");
      throw err;
    });
}

function initMap() {
  if (!location.href.includes("/map")) {
    console.log("no map here");
    return;
  }

  globalLeafletMap = createMap();
  getMapAPIData().then((dataModels) => {
    _globalModels = dataModels;
    const locatedEntities = []
      .concat(dataModels.guests)
      .concat(dataModels.vets)
      .concat(dataModels.shelters)
      .concat(dataModels.owners);
    locatedEntities.forEach(function (model) {
      try {
        return leafletShell.locationMapper(model, globalLeafletMap);
      } catch (error) {
        console.error(model);
        console.error(error);
        throw new Error(`Fout in de location mapper met gelogde model`);
      }
    });

    addInteractive();
    postLeafletFixes();
    setMarkerRotateFixesHandlers(locatedEntities, globalLeafletMap);

    sidebar.init();
  });
}

function setMarkerRotateFixesHandlers(locatedEntities, globalLeafletMap) {
  runMarkerRotateFixes(locatedEntities);

  [("moveend", "viewreset", "zoomend")].forEach((eventNaam) => {
    globalLeafletMap.on(eventNaam, function (e) {
      runMarkerRotateFixes(locatedEntities);
    });
  });
}

let laatsteMarkerRotateFix = null;
function runMarkerRotateFixes(locatedEntities, tijd = 500) {
  if (laatsteMarkerRotateFix) {
    setTimeout(()=>{
      runMarkerRotateFixes(locatedEntities, tijd)
    }, 1000)
  }

  laatsteMarkerRotateFix = setTimeout(() => {
    leafletShell.checkAndFixMarkersToClose(locatedEntities);
    clearTimeout(laatsteMarkerRotateFix);
  }, tijd);
}

initMap();
