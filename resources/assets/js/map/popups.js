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

  const CssClasses = ["", "does-action"].concat(modifiers).map((modifierBase) => {
    return {
      wrapper: modifierBase ? `map__button-wrapper--${modifierBase}` : `map__button-wrapper`,
      button: modifierBase ? `map__link-style-button--${modifierBase}` : `map__link-style-button`,
    };
  });

  const wrapperCss = CssClasses.map((selectorSet) => selectorSet.wrapper).join("  ");
  const buttonCss = CssClasses.map((selectorSet) => selectorSet.button).join("  ");

  return `
    <span class='${wrapperCss}'>
      <a 
      data-action='${action}' 
      data-id='${buttonData.id}'
      data-type='${buttonData._type}'
      class='${buttonCss}'>
      ${buttonData.name}
      </a>
    </span>`;
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

// #region popup HTML renderfuncs

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
      popupDataRow(
        `Bekijk ${entity.type} ${entity.fullName}`,
        `<a class="map__link-style-button map__link-style-button--real-anchor bvmd-popup__voet-link" target="_blank" href="${entity.mayaRoute()}">Open leesscherm</a>`
      ),
      popupDataRow(
        `Bewerk ${entity.type} ${entity.fullName}`,
        `<a class="map__link-style-button map__link-style-button--real-anchor bvmd-popup__voet-link" target="_blank" href="${entity.mayaRoute(
          true
        )}">Open schrijfscherm</a>`
      ),
    ].concat(
      entity.animals.map((animal) => {
        // links for all animals to maya.
        return popupDataRow(
          `${animal.breed} ${animal.name}`,
          `<a class="map__link-style-button map__link-style-button--real-anchor bvmd-popup__voet-link" target="_blank" href="${animal.mayaRoute()}">Bekijk dier</a> | <a class="map__link-style-button map__link-style-button--real-anchor bvmd-popup__voet-link" target="_blank" href="${animal.mayaRoute(
            true
          )}">Bewerk dier</a>`
        );
      })
    ),
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

// #endregion popup HTML render funcs

// #region markerHTML
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
  addressHelper2(key, value) {
    if (value.length < 18) return value;
    if (key === "phone_number") return value;

    if (key === "email_address") {
      // cut off to lang addresses. like ik@[...]
      const firstPart = value.substring(0, value.indexOf("@") + 4);
      const dotSplit = value.split(".");
      const lastPart = dotSplit[dotSplit.length - 1];
      const dotsToFill = 18 - firstPart.length - lastPart.length;
      return `${firstPart.padEnd(dotsToFill, ".")}${lastPart}`;
    } else {
      // remove http, www.
      const noHttp = value.includes("//") ? value.split("//")[1] : value;
      const noWww = value.includes("www.") ? value.split("www.")[1] : value;
      if (noWww.length < 18) {
        return noWww;
      } else {
        return `${`${noWww[0]}${noWww[1]}${noWww[2]}${noWww[3]}${noWww[5]}`.padEnd(8, ".")}${noWww[noWww.length - 1]}${
          noWww[noWww.length - 2]
        }${noWww[noWww.length - 3]}${noWww[noWww.length - 4]}${noWww[noWww.length - 5]}`;
      }
    }
  },
  addressHelper1(key, value) {
    const printValue = this.addressHelper2(key, value);

    if (key === "phone_number") {
      return `<a 
        class='map__link-style-button map__link-style-button--real-anchor map__link-style-button--tel' href='call:${value}'>${value}</a>`;
    }
    if (key === "email_address") {
      return `<a class='map__link-style-button map__link-style-button--real-anchor map__link-style-button--mail' href='mailto:${value}'>${printValue}</a>`;
    }
    if (key === "website") {
      return `<a class='map__link-style-button map__link-style-button--real-anchor' target='_blank' href='${value}'>${printValue}</a>`;
    }
    return value;
  },
  address(locatedEntity) {
    const l = locatedEntity.location;
    const c = locatedEntity.contact;

    return (
      popupDataList(
        [
          popupDataRow("straat", `${l.street} ${l.house_number}`),
          popupDataRow("stad&postcode", `${l.city} ${l.postal_code}`),
        ],
        "Adres",
        "address"
      ) +
      popupDataList(
        Object.entries({
          phone_number: "telefoonnummer",
          email_address: "e-mailadres",
          website: "website",
          contact_person: "contactpersoon",
        }).map(([contactKey, contactNl]) => {
          // contactKey bv phone_number, contactNl de NL string
          if (!c[contactKey]) return ``;
          return popupDataRow(contactNl, this.addressHelper1(contactKey, c[contactKey]));
        }),
        "Contact",
        "contact"
      )
    );
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

// #endregion markerHTML

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
