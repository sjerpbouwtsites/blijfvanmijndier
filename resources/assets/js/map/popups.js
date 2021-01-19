const Animal = require("./models").Animal;
const Vet = require("./models").Vet;
const toCamelCase = require("./util").toCamelCase;
const getMarkerByIdAndType = require("./util").getMarkerByIdAndType;

// #region Button renders
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
function animalBtn(animalData) {
  return buttonBase(animalData, ["animal"], "open-animal-dialog");
}

/**
 * returns button linked to owner marker
 * @param {model} ownerData
 */
function ownerBtn(ownerData) {
  return buttonBase(ownerData, ["owner", "goto-marker"], "goto-marker");
}

/**
 * return button linked to animal's location button
 * @param {model} staysAtData
 */
function staysAtBtn(staysAtData) {
  return buttonBase(staysAtData, ["stays-at", "goto-marker"], "goto-marker");
}

/**
 * returns button linked to vet marker
 * @param {model} vetData
 */
function vetBtn(vetData) {
  return buttonBase(vetData, ["vet"], "open-vet-dialog");
}
//#endregion Button renders

// #region buttonHandler
/**
 * all buttons have or should have a data-action attr.
 * this links it to the callBacks.
 *
 */
const buttonHandlers = {
  init() {
    const knownActions = ["open-animal-dialog", "open-vet-dialog", "goto-marker"];
    document.body.addEventListener("click", (event) => {
      if (!event.target.hasAttribute("data-action")) {
        return;
      }
      const action = event.target.getAttribute("data-action");
      if (!knownActions.includes(action)) {
        alert(`unknown action: ${action}`);
        return;
      }

      const camelcasedAction = toCamelCase(action);
      this.callbacks[camelcasedAction](event);
    });
  },
  callbacks: {
    openAnimalDialog(event) {
      closeLeaflet();
      setOwnDialogState(true);
      const animalId = event.target.getAttribute("data-id");
      const animal = Animal.find(animalId);
      populateDialogWithAnimal(animal);
    },
    openVetDialog(event) {
      setOwnDialogState(false);
      const vetId = event.target.getAttribute("data-id");
      const vet = Vet.find(vetId);
      const marker = getMarkerByIdAndType(vet.id, "vet");
      marker && marker.click();
    },
    gotoMarker(event) {
      setOwnDialogState(false);
      const buttonId = event.target.getAttribute("data-id");
      const buttonType = event.target.getAttribute("data-type");
      const marker = getMarkerByIdAndType(buttonId, buttonType);
      marker && marker.click();
    },
  },
};
// #endregion

/**
 * Generic row with a left and right column to be used in either leaflet popup or dialog
 *
 * @param {*} left
 * @param {*} right
 * @returns
 */
function popupDataRow(left, right) {
  if (!right) return ``;
  return `<li class='bvmd-popup__data-row'>
    <span class='bvmd-popup__column bvmd-popup__column--left'>${left}</span> 
    <span class='bvmd-popup__column bvmd-popup__column--right'>${right}</span>  
  </li>`;
}

function popupDataList(rowList = [], title = "", modifier = "") {
  if (rowList.length < 1) return "";
  const titleHtml = !title ? `` : `<h3 class='bvmd-popup__list-title'>${title}</h3>`;
  return `
  <div class='bvmd-popup__list-wrapper'>
    ${titleHtml}
    <ul class='bvmd-popup__list bvmd-popup__list--${modifier}'>
      ${rowList.join("")}
    </ul>
  </div>
  `;
}

function popupFooter(entity, rijen = []) {
  return `<footer class="bvmd-popup__footer">
  ${popupDataList(
    [
      popupDataRow("Zien", `<a class="bvmd-popup__voet-link" target="_blank" href="${entity.mayaRoute()}">🔍</a>`),
      popupDataRow(
        "Bewerken",
        `<a class="bvmd-popup__voet-link" target="_blank" href="${entity.mayaRoute(true)}">✍</a>`
      ),
    ],
    "Maya",
    "maya-links"
  )}
  ${popupDataList(rijen, "Relaties", "relaties")}

</footer> `;
}

function popupHeader(title, subtitle = null) {
  // calculate minimum length with charcount of subtitle & title
  // allow for space for close button
  // subtitle is .66em
  const subtitleLength = subtitle ? subtitle.length : 0;
  const maxChars = Math.max(title.length, subtitleLength * 0.66);

  const minWidthCh = `min-width: calc(${maxChars}ch + 1em);`;
  const subtitleHTML = !subtitle ? `` : `<small class='bvmd-popup__header-subtitle'>${subtitle}</small>`;

  return `<header 
    class='bvmd-popup__header'
    style='${minWidthCh}'
  >
  <h3 class='bvmd-popup__header-title'>
    <span class='bvmd-popup__header-title-inner'>${title}</span>
    ${subtitleHTML}
  </h3>
</header>`;
}

function populateDialogWithAnimal(animal) {
  document.getElementById("dialog-print-target").innerHTML = `
    <div class='bvmd-popup'>
      ${popupHeader(animal.name, `${animal.breed} ${animal.animal_type.toLowerCase()}`)}
      <div class='bvmd-popup__brood'>
      ${popupDataList(
        [
          popupDataRow("Chip nr", animal.chip_nr),
          popupDataRow("Geboren", new Date(animal.birth_date).toLocaleDateString()),
          popupDataRow("Geregistreerd", new Date(animal.reg_data).toLocaleDateString()),
          popupDataRow("Geslacht", animal.gender),
          popupDataRow("Max uren alleen", animal.max_hours_alone),
          popupDataRow("Misbruik", animal.abuseConsolidatedText),
          popupDataRow("Paspoort", animal.passport),
        ],
        "Gegevens",
        "animal-info"
      )}

      </div>
      ${popupFooter(animal, [
        popupDataRow("Verblijft", staysAtBtn(animal.staysAt)),
        popupDataRow("Eigenaar", ownerBtn(animal.owner)),
      ])}
    </div>
`;
}

/**
 * leaflet's marker: guest, shelter, vet, owner data.
 */
const markerHTML = {
  create(locatedEntity) {
    return `
    <div class='bvmd-popup'>
      ${this.header(locatedEntity)}
      <div class='bvmd-popup__brood'>
        ${this.address(locatedEntity)}
        ${this.body(locatedEntity)}
        ${this.animalList(locatedEntity)}
      </div>
      ${popupFooter(locatedEntity)}
    </div>`;
  },
  header(locatedEntity) {
    return popupHeader(locatedEntity.fullName);
  },
  addressHelper1(key, value) {
    if (key === "phone_number") {
      return `<a href='call:${value}'>${value}</a>`;
    }
    if (key === "email_address") {
      return `<a href='mailto:${value}'>${value}</a>`;
    }
    if (key === "website") {
      return `<a target='_blank' href='${value}'>${value}</a>`;
    }
    return value;
  },
  address(locatedEntity) {
    const l = locatedEntity.location;
    const c = locatedEntity.contact;

    const cToNl = {
      phone_number: "telefoonnummer",
      email_address: "e-mailadres",
      website: "website",
      contact_person: "contactpersoon",
    };

    const secondAddressBlock = Object.entries(cToNl)
      .map(([contactKey, contactNl]) => {
        if (!c[contactKey]) return ``;
        return popupDataRow(contactNl, this.addressHelper1(contactKey, c[contactKey]));
      })
      .join("");

    return `
      <ul class='bvmd-popup__list bvmd-popup__list--address'>
        ${popupDataRow(l.street, l.house_number)}
        ${popupDataRow(l.postal_code, l.city)}
      </ul>
      <ul class='bvmd-popup__list bvmd-popup__list--contact'>
        ${secondAddressBlock}
      </ul>`;
  },
  body(locatedEntity) {
    const bodyFuncName = `${locatedEntity._type}Body`;
    return this[bodyFuncName](locatedEntity);
  },
  ownerBody(locatedEntity) {
    return ``;
  },
  shelterBody(locatedEntity) {
    return "";
  },
  get vetBodyData() {
    return [
      {
        key: "remarks_contract",
        nl: "afspraken",
      },
      {
        key: "remarks_general",
        nl: "opmerkingen",
      },
    ];
  },
  vetBody(locatedEntity) {
    return popupDataList(
      this.vetBodyData.map(({ key, nl }) => {
        if (!locatedEntity[key]) return "";
        return popupDataRow(nl, locatedEntity[key]);
      }),
      "Aantekingen",
      "vet-info"
    );
  },
  get guestBodyData() {
    return [
      {
        key: "max_hours_alone",
        nl: "max uren alleen",
      },
      {
        key: "text",
        nl: "opmerkingen",
      },
    ];
  },

  guestBody(locatedEntity) {
    return popupDataList(
      this.guestBodyData.map(({ key, nl }) => {
        if (!locatedEntity[key]) return "";
        return popupDataRow(nl, locatedEntity[key]);
      }),
      "Aantekingen",
      "guest-info"
    );
  },
  animalList(locatedEntity) {
    if (!locatedEntity.hasAnimals) {
      return ``;
    }
    return popupDataList(
      locatedEntity.animals.map((animal) => {
        return locatedEntity.is("owner") ? this.animalListItemOwner(animal) : this.animalListItemSafeHouse(animal);
      }),
      "Dieren",
      "animals"
    );
  },
  animalListItemOwner(animal) {
    return popupDataRow(`${animalBtn(animal)} verblijft`, staysAtBtn(animal.staysAt));
  },
  animalListItemSafeHouse(animal) {
    return popupDataRow(`${animalBtn(animal)} van`, ownerBtn(animal.owner));
  },
};

//#region dialogs / popups open close
/**
 * toggles the #map-own-dialog dialog.
 *
 * @param {boolean} [zetOpen=true]
 */
function setOwnDialogState(zetOpen = true) {
  const dialog = document.getElementById("map-own-dialog");
  const isOpen = dialog.classList.contains("map__dialog--open");
  if (zetOpen && !isOpen) {
    dialog.classList.add("map__dialog--open");
  } else if (!zetOpen && isOpen) {
    dialog.classList.remove("map__dialog--open");
  }
}

function closeDialogClickHandler() {
  document.getElementById("map-dialog-close").addEventListener("click", () => {
    setOwnDialogState(false);
  });
}

function closeLeaflet() {
  const mightBeAnchorElement = document.querySelector(".leaflet-popup-close-button");
  if (mightBeAnchorElement) mightBeAnchorElement.click();
}

//#endregion open closing popups and dialogs

module.exports = {
  markerHTML,
  closeDialogClickHandler,
  closeLeaflet,
  populateDialogWithAnimal,
  popupDataRow,
  popupFooter,
  animalBtn,
  ownerBtn,
  staysAtBtn,
  vetBtn,
  buttonHandlers,
};
