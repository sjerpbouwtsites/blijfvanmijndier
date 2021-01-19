const Animal = require("./models").Animal;
// const Guest = require("./models").Guest;
const Vet = require("./models").Vet;
// const Shelter = require("./models").Shelter;
// const Owner = require("./models").Owner;

/**
 * 'abstract' buttonHTML func.
 *
 * @param {*} buttonData animal, vet, etc instance.
 * @param array modifiers list of CSS BEM modifiers
 * @param {*} action function to be called
 * @returns
 */
function buttonBase(buttonData, modifiers, action) {
  if (buttonData === null) return ``;

  const modifierHTML = modifiers
    .map((mod) => {
      return `map__link-style-button--${mod}`;
    })
    .join("");

  return `<button 
    data-action='${action}' 
    data-id='${buttonData.id}'
    data-type='${buttonData._type}'
    class='
      map__link-style-button 
      ${modifierHTML}
      '>
    ${buttonData.name}
    </button>`;
}

/**
 * returns button linked to animal view.
 * @param {model} animal
 */
function animal(animalData) {
  return buttonBase(animalData, ["animal"], "open-animal-dialog");
}

/**
 * returns button linked to owner marker
 * @param {model} ownerData
 */
function owner(ownerData) {
  return buttonBase(ownerData, ["owner", "goto-marker"], "goto-marker");
}

/**
 * return button linked to animal's location button
 * @param {model} staysAtData
 */
function staysAt(staysAtData) {
  return buttonBase(staysAtData, ["stays-at", "goto-marker"], "goto-marker");
}

/**
 * returns button linked to vet marker
 * @param {model} vetData
 */
function vet(vetData) {
  return buttonBase(vetData, ["vet"], "open-vet-dialog");
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

function closeLeafletPopupWhenOpen() {
  const mightBeAnchorElement = document.querySelector(".leaflet-popup-close-button");
  if (mightBeAnchorElement) mightBeAnchorElement.click();
}

const dataActionCallbacks = {
  openAnimalDialog(event) {
    closeLeafletPopupWhenOpen();
    const animalId = event.target.getAttribute("data-id");
    const animal = Animal.find(animalId);
    document.getElementById("map-own-dialog").classList.add("map__dialog--open");
    document.getElementById("dialog-print-target").innerHTML = `
      <h3 class='map__dialog-title'>${animal.name} - <small>${animal.breed} ${animal.animal_type}</small></h3>
      <ul class='bvmd-popup__adres-lijst bvmd-popup__adres-lijst--animal-info'>
        <li class='bvmd-popup__adres-stuk'>
          <span class='bvmd-pop__kolom bvmd-pop__kolom--title'>Geslacht: </span> 
          <span class='bvmd-pop__kolom'>${animal.gender}</span> 
        </li>      
        <li class='bvmd-popup__adres-stuk'>
          <span class='bvmd-pop__kolom bvmd-pop__kolom--title'>Geregistreerd: </span> 
          <span class='bvmd-pop__kolom'>${new Date(animal.reg_data).toLocaleDateString()}</span> 
        </li>              
        <li class='bvmd-popup__adres-stuk'>
          <span class='bvmd-pop__kolom bvmd-pop__kolom--title'>Geboren: </span> 
          <span class='bvmd-pop__kolom'>${new Date(animal.birth_date).toLocaleDateString()}</span> 
        </li>                      
        ${
          animal.chip_nr
            ? `<li class='bvmd-popup__adres-stuk'>
              <span class='bvmd-pop__kolom bvmd-pop__kolom--title'>Chip nr: </span> 
              <span class='bvmd-pop__kolom'>${animal.chip_nr}</span> 
              </li>`
            : `
        `
        }
        ${
          animal.passport
            ? `<li class='bvmd-popup__adres-stuk'>
              <span class='bvmd-pop__kolom bvmd-pop__kolom--title'>Passpoort: </span> 
              <span class='bvmd-pop__kolom'>${animal.passport}</span> 
              </li>`
            : `
        `
        }        
        ${
          animal.passport
            ? `<li class='bvmd-popup__adres-stuk'>
              <span class='bvmd-pop__kolom bvmd-pop__kolom--title'>Max uren alleen: </span> 
              <span class='bvmd-pop__kolom'>${animal.max_hours_alone}</span> 
              </li>`
            : `
        `
        }           
        ${
          animal.passport
            ? `<li class='bvmd-popup__adres-stuk'>
              <span class='bvmd-pop__kolom bvmd-pop__kolom--title'>Misbruik:</span> 
              <span class='bvmd-pop__kolom'>${
                animal.abused && animal.witnessed_abuse
                  ? `Gezien en meegemaakt`
                  : animal.abused
                  ? `Meegemaakt`
                  : animal.witnessed_abuse
                  ? `Gezien`
                  : `Geen`
              }</span> 
              </li>`
            : `
        `
        }           
      <ul>

      <div class='map__dialog-button-group'>
        ${animal.vet ? `<div class='map_dialog-button-row'>Arts: ${vet(animal.vet)}</div>` : ""}
        ${animal.staysAt ? `<div class='map_dialog-button-row'>Verblijft te: ${staysAt(animal.staysAt)}</div>` : ""}
      </div>

      <footer class="bvmd-popup__voet">
      <span class="bvmd-popup__voet-link-wrap">
        Naar Maya: 
        <a class="bvmd-popup__voet-link" target="_blank" href="${animal.mayaRoute()}">🔍</a>
        <a class="bvmd-popup__voet-link" target="_blank" href="${animal.mayaRoute(true)}">✍</a>
      </span>
    </footer>

    `;
  },
  openVetDialog(event) {
    closeOwnDialog();
    const vetId = event.target.getAttribute("data-id");
    const vet = Vet.find(vetId);
    const marker = getMarkerByIdAndType(vet.id, "vet");
    marker && marker.click();
  },
  gotoMarker(event) {
    closeOwnDialog();
    const buttonId = event.target.getAttribute("data-id");
    const buttonType = event.target.getAttribute("data-type");
    const marker = getMarkerByIdAndType(buttonId, buttonType);
    marker && marker.click();
  },
};

function getMarkerByIdAndType(id, type) {
  const marker = document.getElementById(`marker-${type}-id-${id}`);
  if (!marker) {
    throw new Error(`Marker for id ${id}, id attr val marker-${type}-id-${id}, not found`);
  }
  return marker;
}
/**
 * own dialog as in not the one made by leaflet. Used by Animal.
 */
function closeOwnDialog() {
  document.getElementById("map-own-dialog").classList.contains("map__dialog--open") &&
    document.getElementById("map-own-dialog").classList.remove("map__dialog--open");
}
module.exports = {
  closeOwnDialog,
  animal,
  owner,
  staysAt,
  vet,
  dataActionEventHandler,
};
