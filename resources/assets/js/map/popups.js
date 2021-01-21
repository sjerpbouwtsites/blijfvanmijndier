const Animal = require("./models").Animal;
const Vet = require("./models").Vet;
const utils = require("./util");
const toCamelCase = utils.toCamelCase;
const texts = require("./texts");
const getMarkerByIdAndType = utils.getMarkerByIdAndType;

const svgs = require("./svgs");

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
      ${svgs.marker("rgb(81, 81, 211)")}
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

/**
 * returns button opening dialog for text
 * @param {string} slug corresponding to keys in texts.js
 */
function textBtn(slug, modifier = "") {
  return `<a 
    class="map__link-style-button map__link-style-button--text-btn ${modifier}" 
    data-action='open-explanation' data-text-id='${slug.toLowerCase()}' href="#">${svgs.info("#5151d3")}</a>`;
}

/**
 * creates maya btn, opens window with maya page.
 *
 * @param {*} entity
 * @param {boolean} [edit=false] open show / edit maya page
 * @param {string} [modifier=""] added as BEM modifiers
 * @returns string mayaBtn
 */
function mayaBtn(entity, edit = false, modifier = "") {
  const m = !!modifier ? `map__link-style-button--${modifier}` : "";
  const t = !!edit ? `Bewerk` : "Bekijk";
  const r = entity.mayaRoute(edit);
  return `<a 
    class="map__link-style-button map__link-style-button--maya-btn ${m} " 
    data-action='open-maya-page' href="${r}">${t} ${svgs.open("rgb(81, 81, 211)")}</a>`;
}

//#endregion Button renders

// #region buttonHandler
/**
 * all buttons have or should have a data-action attr.
 * this links it to the callBacks.
 *
 */
const buttonHandlers = {
  /**
   * adds the data-action event listener
   */
  init() {
    document.addEventListener("click", (event) => {
      const t = event.target;
      console.log(t);
      const actionBtn = utils.findInParents(
        t,
        (el) => {
          return el.hasAttribute("data-action");
        },
        5
      );
      if (!actionBtn) {
        return;
      }
      event.preventDefault();
      console.log(actionBtn);
      this.callbacks[toCamelCase(actionBtn.getAttribute("data-action"))](actionBtn);
    });
  },

  callbacks: {
    openAnimalDialog(actionBtn) {
      closeLeaflet();
      const animalId = actionBtn.getAttribute("data-id");
      const animal = Animal.find(animalId);
      populateDialogWithAnimal(animal);
      setOwnDialogState(true);
    },
    openVetDialog(actionBtn) {
      setOwnDialogState(false);
      const vetId = actionBtn.getAttribute("data-id");
      const vet = Vet.find(vetId);
      const marker = getMarkerByIdAndType(vet.id, "vet");
      marker && marker.click();
    },
    gotoMarker(actionBtn) {
      setOwnDialogState(false);
      const buttonId = actionBtn.getAttribute("data-id");
      const buttonType = actionBtn.getAttribute("data-type");
      const marker = getMarkerByIdAndType(buttonId, buttonType);
      marker && marker.click();
    },
    openExplanation(actionBtn) {
      closeLeaflet();
      const textId = actionBtn.getAttribute("data-text-id");
      console.log(textId);
      populateDialogWithText(textId);
      setOwnDialogState(true);
    },
    /**
     * prints an iframe to maya on the page
     *
     * @param {*} actionBtn
     */
    openMayaPage(actionBtn) {
      closeLeaflet();
      setOwnDialogState(false);
      const singularId = actionBtn.href.replace(/\W/g, "");
      const wrapperDiv = document.createElement("div");
      wrapperDiv.id = singularId;
      wrapperDiv.classList.add("map__iframe-wrapper");
      const closeBtn = document.createElement("button");
      closeBtn.className = "map__iframe-close";
      closeBtn.innerHTML = svgs.arrowBack("#fff");
      closeBtn.id = `${singularId}__close`;
      wrapperDiv.innerHTML = `<iframe scrolling="auto" src="${actionBtn.href}"></iframe>`;
      const docBody = document.getElementsByTagName("body")[0];
      wrapperDiv.appendChild(closeBtn);
      docBody.appendChild(wrapperDiv);
      document.getElementById(`${singularId}__close`).addEventListener("click", removeIframeWrapper);
    },
  },
};
// #endregion

/**
 * removes the maya iframe it
 */
function removeIframeWrapper() {
  const wrapperEl = document.querySelector(".map__iframe-wrapper");
  if (wrapperEl) wrapperEl.parentNode.removeChild(wrapperEl);
}

// #region popup HTML renderfuncs

/**
 * Generic row with a left and right column to be used in either leaflet popup or dialog
 *
 * @param {*} left
 * @param {*} right
 * @returns {string} HTML
 */
function popupDataRow(left, right) {
  if (!right) return ``;
  return `<li class='bvmd-popup__data-row'>
    <span class='bvmd-popup__column bvmd-popup__column--left'>${left}</span> 
    <span class='bvmd-popup__column bvmd-popup__column--right'>${right}</span>  
  </li>`;
}
/**
 * takes popupDataRows and returns in ul wrapper in div, possibly with title and modifier
 *
 * @param {*} [rowList=[]] popupDataRow() output
 * @param {string} [title=""]
 * @param {string} [modifier=""]
 * @returns {string} HTML
 */
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
/**
 * creates popup footer HTML with edit and view links; if not used on animal entity, add animal list.
 * @param {*} entity
 * @param {*} [rijen=[]]
 * @returns {string} HTML popup footer
 */
function popupFooter(entity, rijen = []) {
  const addToFooter =
    entity.type !== "animal"
      ? entity.animals.map((animal) => {
          // links for all animals to maya.
          return popupDataRow(
            `${animal.breed} ${animal.name}`,
            `${mayaBtn(animal, false, "in-popup-footer")} | ${mayaBtn(animal, true, "in-popup-footer")}`
          );
        })
      : "";

  return `<footer class="bvmd-popup__footer">
  ${popupDataList(
    [
      popupDataRow(`Bekijk ${entity.name}`, `${mayaBtn(entity, false)}`),
      popupDataRow(`Bewerk ${entity.name}`, `${mayaBtn(entity, true)}`),
    ].concat(addToFooter),
    "Maya",
    "maya-links"
  )}
  ${popupDataList(rijen, "Relaties", "relaties")}

</footer> `;
}
/**
 * creates popup header and sets overal popup width based on title width.
 *
 * @param {*} title
 * @param {*} [subtitle=null]
 * @returns {string} HTML
 */
function popupHeader(title, subtitle = null) {
  // calculate minimum length with charcount of subtitle & title
  // allow for space for close button
  // subtitle is .66em
  const subtitleLength = subtitle ? subtitle.length : 0;
  const maxChars = Math.max(title.length, subtitleLength * 0.66);

  const widthCh = `width: calc(${maxChars}ch + 1em);`;
  const maxWidthCh = `max-width: calc(${25}ch + 1em);`; // no too long titles.
  const subtitleHTML = !subtitle ? `` : `<small class='bvmd-popup__header-subtitle'>${subtitle}</small>`;

  return `<header 
    class='bvmd-popup__header'
    style='${widthCh}${maxWidthCh}'
  >
  <h3 class='bvmd-popup__header-title'>
    <span class='bvmd-popup__header-title-inner'>${title}</span>
    ${subtitleHTML}
  </h3>
</header>`;
}

/**
 * creates and prints animal dialog HTML
 * @param {Animal} animal
 */
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

function populateDialogWithText(textId) {
  const textCollection = texts[textId];
  console.log(textCollection);
  document.getElementById("dialog-print-target").innerHTML = `<div class='bvmd-popup'>
    ${popupHeader(textCollection.title, textCollection.subtitle)}
    <div class='bvmd-popup__brood'>
      ${textCollection.body}
    </div>
  </div>`;
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
      "Dieren & verblijf",
      "animals"
    );
  },
  animalListItemOwner(animal) {
    return popupDataRow(`${animalBtn(animal)}`, staysAtBtn(animal.staysAt));
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

function closeAllDialogsPopupsIframesEscape() {
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      removeIframeWrapper();
      closeLeaflet();
      setOwnDialogState(false);
    }
  });
}

//#endregion open closing popups and dialogs

module.exports = {
  markerHTML,
  closeDialogClickHandler,
  closeAllDialogsPopupsIframesEscape,
  closeLeaflet,
  populateDialogWithAnimal,
  popupDataRow,
  popupFooter,
  animalBtn,
  ownerBtn,
  textBtn,
  staysAtBtn,
  vetBtn,
  buttonHandlers,
};
