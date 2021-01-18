const filter = require("./map/filter");
const models = require("./map/models");
const leafletShell = require("./map/leaflet-shell");
const buttonRenders = require("./map/buttons");

function addInteractive() {
  dataActionEventHandler();
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
      return `<li class='map__list-item'>
      ${buttonRenders.animal(animal)} van
      ${buttonRenders.owner(animal.owner)}
       verblijft te
      ${buttonRenders.staysAt(animal.staysAt)}
    </li>`;
    })
    .join(``);
  printTarget.innerHTML = animalListHTML;
}

function dataActionEventHandler() {
  const knownActions = ["open-animal-dialog", "open-vet-dialog", "goto-marker"];
  document.body.addEventListener("click", function (event) {
    if (!event.target.hasAttribute("data-action")) {
      return;
    }
    const action = event.target.getAttribute("data-action");
    if (!knownActions.includes(action)) {
      alert(`unknown action: ${action}`);
      return;
    }

    const camelcasedAction = action
      .split("-")
      .map((word, index) => {
        return index > 0 ? word[0].toUpperCase() + word.substring(1, word.length) : word;
      })
      .join("");
    dataActionCallbacks[camelcasedAction](event);
  });
}

function getMarkerById(id) {
  const marker = document.getElementById(`marker-id-${id}`);
  if (!marker) {
    throw new Error(`Marker for id ${id}, id attr val marker-id-${id}, not found`);
    return false;
  }
  return marker;
}

dataActionCallbacks = {
  openAnimalDialog(event) {
    closeLeafletPopupWhenOpen();
    const animalId = event.target.getAttribute("data-id");
    const animal = models.Animal.find(animalId);
    document.getElementById("map-own-dialog").classList.add("map__dialog--open");
    document.getElementById("dialog-print-target").innerHTML = `
      <h3 class='map__dialog-title'>${animal.title}</h3>
      <p class='map__dialog-text'>${animal.text}</p>
      <div class='map__dialog-button-group'>
        ${animal.vet ? `<div class='map_dialog-button-row'>Arts: ${buttonRenders.vet(animal.vet)}</div>` : ""}
        ${
          animal.staysAt
            ? `<div class='map_dialog-button-row'>Verblijft te: ${buttonRenders.staysAt(animal.staysAt)}</div>`
            : ""
        }
      </div>
    `;
  },
  openVetDialog(event) {
    closeOwnDialog();
    const vetId = event.target.getAttribute("data-id");
    const vet = models.Vet.find(vetId);
    const marker = getMarkerById(vet.id);
    marker && marker.click();
  },
  gotoMarker(event) {
    closeOwnDialog();
    const targetMarker = event.target.getAttribute("data-id");
    console.log(targetMarker);
    const marker = getMarkerById(targetMarker);
    marker && marker.click();
  },
};
/**
 * own dialog as in not the one made by leaflet. Used by Animal.
 */
function closeOwnDialog() {
  document.getElementById("map-own-dialog").classList.contains("map__dialog--open") &&
    document.getElementById("map-own-dialog").classList.remove("map__dialog--open");
}

function closeDialogClickHandler() {
  document.getElementById("map-dialog-close").addEventListener("click", closeOwnDialog);
}

function closeLeafletPopupWhenOpen() {
  const mightBeAnchorElement = document.querySelector(".leaflet-popup-close-button");
  if (mightBeAnchorElement) mightBeAnchorElement.click();
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
  addInteractive();
  getMapAPIData().then((dataModels) => {
    [...dataModels.guests, ...dataModels.vets, ...dataModels.shelters, ...dataModels.owners].map(function (model) {
      try {
        return leafletShell.locationMapper(model, globalLeafletMap);
      } catch (error) {
        console.error(model);
        console.error(error);
        throw new Error(`Fout in de location mapper met gelogde model`);
      }
    });
    populateAnimalList(dataModels.animals);
    leafletShell.postLeafletWork();
    globalLeafletMap.setZoom(8);
    window.leesModels = dataModels;
  });
}

initMap();
