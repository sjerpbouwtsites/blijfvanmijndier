const filter = require("./map/filter");
const models = require("./map/models");
const leafletShell = require("./map/leaflet-shell");
const buttonRenders = require("./map/buttons");
console.clear();
console.log(models);

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
    document.getElementById("map-own-dialog").classList.contains("map__dialog--open") &&
      document.getElementById("map-own-dialog").classList.remove("map__dialog--open");
    const vetId = event.target.getAttribute("data-id");
    const vet = models.Vet.find(vetId);
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

function init() {
  globalLeafletMap = leafletShell.createMap();
  addInteractive();
  getLocations().then((models) => {
    [...models.guests, ...models.vets, ...models.shelters, ...models.owners].map(function (model) {
      try {
        return leafletShell.locationMapper(model, globalLeafletMap);
      } catch (error) {
        console.error(model);
        console.error(error);
        throw new Error(`Fout in de location mapper met gelogde model`);
      }
    });
    populateAnimalList(models.animals);
    leafletShell.postLeafletWork();
  });
}

init();
