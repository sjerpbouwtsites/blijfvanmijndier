const filter = require("./map/filter");
const models = require("./map/models");

/**
 * intialises map on roelofarendsveen and returns leaflet map instance.
 */
function createMap() {
  const goudaMapConfig = {
    lat: 52.2,
    lon: 4.6,
    zoom: 9,
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

function addInteractive() {
  dataActionEventHandler();
  closeDialogClickHandler();
  filter.populateFilterHTML();
  filter.filterClickHandler();
}

function populateAnimalList(animals) {
  const printTarget = document.getElementById("animal-list");
  const animalListHTML = animals
    .sort(function (a, b) {
      if (a.title < b.title) {
        return -1;
      }
      if (a.title > b.title) {
        return 1;
      }
      return 0;
    })
    .map((animal) => {
      return `<li class='map__list-item'>
      ${animalButtonHTML(animal)} van
      ${ownerButtonHTML(animal.owner)}
       verblijft te
      ${staysAtButtonHTML(animal.staysAt)}
    </li>`;
    })
    .join(``);
  printTarget.innerHTML = animalListHTML;
}

function animalButtonHTML(animal) {
  return `<button 
    data-action='open-animal-dialog' 
    data-id='${animal.id}'
    class='map__link-style-button map__link-style-button--animal'>
    ${animal.title}
    </button>`;
}

function ownerButtonHTML(owner) {
  return `<button 
    data-action='goto-marker' 
    data-id='${owner.id}'
    class='map__link-style-button map__link-style-button--goto-marker map__link-style-button--owner'>
    ${owner.title}
    </button>`;
}

function staysAtButtonHTML(staysAt) {
  return `<button 
    data-action='goto-marker' 
    data-id='${staysAt.id}'
    class='map__link-style-button map__link-style-button--goto-marker map__link-style-button--stays-at'>
    ${staysAt.title}
    </button>`;
}

function vetButtonHTML(vet) {
  return `<button 
      data-action='open-vet-dialog' 
      data-id='${vet.id}'
      class='map__link-style-button map__link-style-button--vet'>
      ${vet.title}
      </button>`;
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

dataActionCallbacks = {
  openAnimalDialog(event) {
    closeLeafletPopupWhenOpen();
    const animalId = event.target.getAttribute("data-id");
    const animal = Animal.find(animalId);
    document.getElementById("map-own-dialog").classList.add("map__dialog--open");
    document.getElementById("dialog-print-target").innerHTML = `
      <h3 class='map__dialog-title'>${animal.title}</h3>
      <p class='map__dialog-text'>${animal.text}</p>
      <div class='map__dialog-button-group'>
        ${animal.vet ? `<div class='map_dialog-button-row'>Arts: ${vetButtonHTML(animal.vet)}</div>` : ""}
        ${
          animal.staysAt
            ? `<div class='map_dialog-button-row'>Verblijft te: ${staysAtButtonHTML(animal.staysAt)}</div>`
            : ""
        }
      </div>
    `;
  },
  openVetDialog(event) {
    document.getElementById("map-own-dialog").classList.contains("map__dialog--open") &&
      document.getElementById("map-own-dialog").classList.remove("map__dialog--open");
    const vetId = event.target.getAttribute("data-id");
    const vet = Vet.find(vetId);
    document.querySelector(`[alt~='id-${vet.id}']`).click();
  },
  gotoMarker(event) {
    document.getElementById("map-own-dialog").classList.contains("map__dialog--open") &&
      document.getElementById("map-own-dialog").classList.remove("map__dialog--open");
    const targetMarker = event.target.getAttribute("data-id");
    document.querySelector(`[alt~='id-${targetMarker}']`).click();
  },
};

function closeDialogClickHandler() {
  document.getElementById("map-dialog-close").addEventListener("click", function () {
    document.getElementById("map-own-dialog") &&
      document.getElementById("map-own-dialog").classList.remove("map__dialog--open");
  });
}

function closeLeafletPopupWhenOpen() {
  const mightBeAnchorElement = document.querySelector(".leaflet-popup-close-button");
  if (mightBeAnchorElement) mightBeAnchorElement.click();
}

/**
 * dummy function to get locations
 * @returns Promise always succes with dummydata
 */
function getLocations() {
  return new Promise((locationSucces, locationFailure) => {
    setTimeout(() => {
      return locationSucces(models);
    }, 250);
  });
}

/**
 * create alt attribute which is a general styling & identifying attribute in this app for markers.
 * loops over list of conditions
 * @param {*} locatedEntity
 */
function maakAlt(locatedEntity) {
  return (
    [
      {
        key: "type",
        check: (str) => {
          return str === "vet";
        },
        res: "color-red",
      },
      {
        key: "type",
        check: (str) => {
          return str === "pension";
        },
        res: "color-purple",
      },
      {
        key: "type",
        check: (str) => {
          return str === "guest";
        },
        res: "color-green",
      },
      {
        key: "type",
        check: (str) => {
          return str === "owner";
        },
        res: "color-blue",
      },
      {
        key: "animals",
        check: (animals) => {
          return animals.length > 0;
        },
        res: "has-animals",
      },
      {
        key: "animals",
        check: (animals) => {
          return animals.length > 1;
        },
        res: "multiple-animals",
      },
      {
        key: "animalsOnSite",
        check: (animalsOnSite) => {
          return animalsOnSite.length > 1;
        },
        res: "animals-on-site",
      },
    ]
      .map((condition) => {
        const locationVal = locatedEntity[condition.key];
        return condition.check(locationVal) ? condition.res : "";
      })
      .filter((a) => a)
      .join(" ") + ` is-${locatedEntity.type} id-${locatedEntity.id}`
  );
}

/**
 * callback for locations.map
 * refers maakAlt
 * creates marker and binds to global mapInstance
 * bindspopup.
 * @param {*} locatedEntity
 */
function locationMapper(locatedEntity) {
  const options = {
    alt: maakAlt(locatedEntity),
  };

  const marker = L.marker([locatedEntity.location.lat, locatedEntity.location.lon], options).addTo(leafletMap);

  marker.bindPopup(
    `<div class='bvmd-popup'>
      ${markerHTML.header(locatedEntity)}
      <div class='bvmd-popup__brood'>
        <p class='bvmd-popup__tekst'>
          ${locatedEntity.text}
        </p>
        ${markerHTML.address(locatedEntity)}
        ${markerHTML.animalList(locatedEntity)}
      </div>

      <footer class='bvmd-popup__voet'>
      </footer>

    </div>`
  );
}

const markerHTML = {
  header(locatedEntity) {
    return `<header class='bvmd-popup__header'>
    <h3 class='bvmd-popup__header-links'>
      ${locatedEntity.title}
    </h3>
  </header>
  `;
  },
  address(locatedEntity) {
    return `<div class='bvmd-popup__adres-outer'>
    <h4 class='bvmd-popup__inner-title'>Adres</h4>
    <address class='bvmd-popup__adres'>
      <ul class='bvmd-popup__adres-lijst'>
        <li class='bvmd-popup__adres-stuk'>${locatedEntity.location.straat} ${locatedEntity.location.huisnummer}</li>
        <li class='bvmd-popup__adres-stuk'>${locatedEntity.location.postcode} ${locatedEntity.location.plaatsnaam}</li>
      </ul>
    </address>
  </div>`;
  },
  animalList(locatedEntity) {
    return `${
      locatedEntity.hasAnimals
        ? `
    <div class='bvmd-popup__animal-list-outer'>
      <h4 class='bvmd-popup__inner-title'>Dieren</h4>
      <ol class='bvmd-popup__animal-list'>
        ${locatedEntity.animals
          .map((animal) => {
            return locatedEntity.is("owner") ? this.animalListItemOwner(animal) : this.animalListItemSafeHouse(animal);
          })
          .join(``)}
      </ol>
    </div>
    `
        : ""
    } `;
  },
  animalListItemOwner(animal) {
    return `
    <li class='bvmd-popup__animal-list-item'>
      ${animalButtonHTML(animal)} verblijft bij
      ${staysAtButtonHTML(animal.staysAt)}
    </li>
    `;
  },
  animalListItemSafeHouse(animal) {
    return `
    <li class='bvmd-popup__animal-list-item'>
      ${animalButtonHTML(animal)} van 
      ${ownerButtonHTML(animal.owner)}
    </li>
    `;
  },
};

let leafletMap;

/**
 * Leaflet prints images for the shadows in a different div
 * then the actual markers. In order to also style the shadows
 * through the alt-as-an-array concept we apply the same alt attribute
 * to the shadow image.
 */
function linkShadowsToMarkers() {
  return new Promise((resolve, reject) => {
    try {
      const markerImages = Array.from(document.querySelectorAll(".leaflet-marker-pane img"));
      const shadowImages = Array.from(document.querySelectorAll(".leaflet-shadow-pane img"));
      // images and shadows correspond by their index.
      markerImages.forEach((marker, markerIndex) => {
        shadowImages[markerIndex].setAttribute("alt", marker.alt);
      });
    } catch (error) {
      reject(error);
    }
    resolve();
  });
}

function init() {
  leafletMap = createMap();
  addInteractive();
  getLocations().then((models) => {
    [...models.guests, ...models.vets, ...models.pensions, ...models.owners].map(locationMapper);
    populateAnimalList(models.animals);
    linkShadowsToMarkers();
  });
}

init();
