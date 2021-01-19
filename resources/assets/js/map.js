const filter = require("./map/filter");
const models = require("./map/models");
const leafletShell = require("./map/leaflet-shell");
const buttons = require("./map/buttons");

function addInteractive() {
  buttons.dataActionEventHandler();
  closeDialogClickHandler();
  filter.populateFilterHTML();
  filter.filterClickHandler();
}

function populateAnimalList(animals) {
  const printTarget = document.getElementById("animal-list");
  //console.log(animals);
  const animalListHTML = animals
    .sort(function (a, b) {
      if (a.name < b.name) {
        return -1;
      }
      if (a.name > b.name) {
        return 1;
      }
      return 0;
    })
    .map((animal) => {
      const sa = animal.staysAt;
      const o = animal.owner;
      return `<li class='map__list-item'>
      ${buttons.animal(animal)} 
      ${o ? `van ${buttons.owner(o)}` : ``}
      ${sa ? `verblijft te ${buttons.staysAt(sa)}` : ``}
    </li>`;
    })
    .join(``);
  printTarget.innerHTML = animalListHTML;
}

function closeDialogClickHandler() {
  document.getElementById("map-dialog-close").addEventListener("click", buttons.closeOwnDialog);
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
    [...dataModels.guests, ...dataModels.vets, ...dataModels.shelters, ...dataModels.owners].map(function (model) {
      try {
        return leafletShell.locationMapper(model, globalLeafletMap);
      } catch (error) {
        console.error(model);
        console.error(error);
        throw new Error(`Fout in de location mapper met gelogde model`);
      }
    });
    addInteractive();
    populateAnimalList(dataModels.animals);
    leafletShell.postLeafletWork();
    globalLeafletMap.setZoom(8);
  });
}

initMap();
