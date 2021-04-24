const models = require("./map/models");
const leafletShell = require("./map/leaflet-shell");
const sidebar = require("./map/sidebar");
const postLeafletFixes = require("./map/post-leaflet-fixes");
const popups = require("./map/popups");

function addInteractive() {
  //debug.overwriteEventListener();
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
      dataModel.meta = baseData.meta;
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
  const globalInfobtn = popups.textBtn('algemeen', 'absoluut-op-kaart');
  const emptyDiv = document.createElement('div');
  emptyDiv.innerHTML = globalInfobtn;  
  document.getElementById('leaflet-map').appendChild(emptyDiv)

  getMapAPIData().then((dataModels) => {

    const meta = dataModels.meta;
    return new Promise((resolve, reject) => {
      _globalModels = dataModels;

      const locatedEntities = []
        .concat(dataModels.guests)
        .concat(dataModels.vets)
        .concat(dataModels.shelters) 
        .concat(dataModels.owners)
        .concat(dataModels.locations);
      
      locatedEntities.forEach(function (model) {
        try {
          return leafletShell.locationMapper(model, globalLeafletMap);
        } catch (error) {
          console.error(model);
          console.error(error);
          reject(`Fout in de location mapper met gelogde model`);
        }
      });
  
      addInteractive();
      postLeafletFixes();
      sidebar.init(meta);
      resolve({dataModels, locatedEntities})
    }).then(({dataModels, locatedEntities}) =>{
      //leafletShell.setLeafletEventListeners(globalLeafletMap, dataModels);

        // invalidate map to paint it correctly.
        globalLeafletMap.invalidateSize();
        console.log('map invalidated')

        setTimeout(()=>{
          leafletShell.checkAndFixMarkersToClose(locatedEntities);
          globalLeafletMap.on('zoomend', ()=>{
            leafletShell.checkAndFixMarkersToClose(locatedEntities);
          })
        }, 50)
      
    }).catch(err => {
      console.error('error aan einde initMap', err);
    })


  });
}

initMap();
