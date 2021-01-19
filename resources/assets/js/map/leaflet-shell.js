const buttonRenders = require("./buttons");
const { MayaModel } = require("./models");

/**
 * using the alt attribute is nice but a bit shit too.
 * move all to data-attributes after initialization.
 *
 * also have to link the shadow markers to the markers
 *
 *
 */
function postLeafletWork() {
  const markerImages = Array.from(document.querySelectorAll(".leaflet-marker-pane img"));
  const shadowImages = Array.from(document.querySelectorAll(".leaflet-shadow-pane img"));

  stylesheetHTMLArray = [];

  markerImages.forEach((marker, markerIndex) => {
    const shadowMarker = shadowImages[markerIndex]; // exact same index
    const markerAltData = marker.alt.split(" ");

    // MARKER ALT TO ID
    const markerId = markerAltData.find((altPiece) => {
      return altPiece.includes("id-");
    });
    if (!markerId) {
      throw new Error(`marker Id unknown ${marker} postLeafletWork func`);
    }
    //  markerAltData.splice(markerAltData.indexOf(markerId), 1); // just delete alt in the end.

    const markerTempType = markerAltData
      .find((altPiece) => {
        return altPiece.includes("is-");
      })
      .replace("is-", "");
    marker.setAttribute("id", `marker-${markerTempType}-${markerId}`);
    marker.setAttribute("data-shadow-id", `shadow-${markerTempType}-${markerId}`);
    shadowMarker.setAttribute("id", `shadow-${markerTempType}-${markerId}`);
    shadowMarker.setAttribute("data-marker-id", `marker-${markerTempType}-${markerId}`);
    //    marker.setAttribute("alt", markerAltData.join(" "));

    // CUT INLINE STYLES TO STYLESHEET
    const markerInlineStyle = marker.getAttribute("style");
    const shadowInlineStyle = shadowMarker.getAttribute("style");
    stylesheetHTMLArray.push(`
      #marker-${markerTempType}-${markerId} {
        ${markerInlineStyle}
      }
      #shadow-${markerTempType}-${markerId} {
        ${shadowInlineStyle}
      }      
    `);
    marker.removeAttribute("style");
    shadowMarker.removeAttribute("style");

    // RENAME BULKY CLASSNAMES
    if (marker.classList.contains("leaflet-marker-icon")) {
      marker.classList.remove("leaflet-marker-icon");
      marker.classList.add("lmi");
    }
    if (marker.classList.contains("leaflet-zoom-animated")) {
      marker.classList.remove("leaflet-zoom-animated");
      marker.classList.add("lza");
    }
    if (marker.classList.contains("leaflet-interactive")) {
      marker.classList.remove("leaflet-interactive");
      marker.classList.add("lei");
    }

    // MOVE ALT BASED STYLES TO CLASSES
    const markerColor = markerAltData.find((altPiece) => {
      return altPiece.includes("color-");
    });
    if (markerColor) {
      marker.classList.add(markerColor);
    }

    // MOVE TYPE ALT ENTRY TO DATA-TYPE
    const type = markerAltData.find((altPiece) => {
      return altPiece.includes("is-");
    });
    marker.setAttribute("data-type", type.replace("is-", ""));
    //    marker.setAttribute("alt", marker.alt.replace(type, ""));

    // MOVE ANIMAL QUANTITY TO DATA ATTR
    const animalAmountData = markerAltData.filter((altPiece) => {
      return ["has-animals", "multiple-animals", "animals-on-site"].includes(altPiece);
    });
    if (animalAmountData) {
      animalAmountData.forEach((animalAD) => {
        marker.setAttribute(`data-${animalAD}`, "true");
      });
    }

    // DESTROY THE ALT ATTRIBUTE
    marker.removeAttribute("alt");

    // FIX THE SRC ATTRIBUTE
    marker.src = "/img/marker.png";
    shadowMarker.src = "/img/marker-shadow.png";
  });

  // PRINT STYLES TO HEAD
  const styleEl = document.createElement("style");
  styleEl.id = "handmade-marker-styles";
  styleEl.innerHTML = stylesheetHTMLArray.join("");
  document.head.appendChild(styleEl);
}

/**
 * create alt attribute which is a general styling & identifying attribute in this app for markers.
 * the alt attribute of the markers is used as a data store of boolean-like / id properties.
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
          return str === "shelter";
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
function locationMapper(locatedEntity, globalLeafletMap) {
  let options;
  try {
    options = {
      alt: maakAlt(locatedEntity),
    };
  } catch (error) {
    console.error(locatedEntity);
    throw new Error(`fout in maakAlt, ${error.message}`);
  }

  const marker = L.marker([locatedEntity.location.lattitude, locatedEntity.location.longitude], options).addTo(
    globalLeafletMap
  );

  marker.bindPopup(
    `<div class='bvmd-popup'>
      ${markerHTML.header(locatedEntity)}
      <div class='bvmd-popup__brood'>
        ${markerHTML.address(locatedEntity)}
        ${markerHTML.body(locatedEntity)}
        ${markerHTML.animalList(locatedEntity)}
      </div>

      <footer class='bvmd-popup__voet'>
        <span class='bvmd-popup__voet-link-wrap'>
          Naar Maya: 
          <a class='bvmd-popup__voet-link' target='_blank' href='${locatedEntity.mayaRoute()}'>🔍</a>
          <a class='bvmd-popup__voet-link' target='_blank' href='${locatedEntity.mayaRoute(true)}'>✍</a>
        </span>
      </footer>

    </div>`
  );
}

const markerHTML = {
  header(locatedEntity) {
    return `<header class='bvmd-popup__header'>
    <h3 class='bvmd-popup__header-links'>
      ${locatedEntity.fullName}
    </h3>
  </header>
  `;
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
        return `<li class='bvmd-popup__adres-stuk'>
          <span class='bvmd-pop__kolom'>${contactNl}</span> 
          <span class='bvmd-pop__kolom'>${this.addressHelper1(contactKey, c[contactKey])}</span> 
        </li>`;
      })
      .join("");

    return `<div class='bvmd-popup__adres-outer'>
    <h4 class='bvmd-popup__inner-title'>Contact</h4>
    <address class='bvmd-popup__adres'>
      <ul class='bvmd-popup__adres-lijst bvmd-popup__adres-lijst--adres'>
        <li class='bvmd-popup__adres-stuk'>
          <span class='bvmd-pop__kolom'>${l.street}</span> 
          <span class='bvmd-pop__kolom'>${l.house_number}</span> 
        </li>
        <li class='bvmd-popup__adres-stuk'>
          <span class='bvmd-pop__kolom'>${l.postal_code}</span> 
          <span class='bvmd-pop__kolom'>${l.city}</span> 
        </li>
      </ul>
      <ul class='bvmd-popup__adres-lijst bvmd-popup__adres-lijst--contact'>
      ${secondAddressBlock}
    </ul>
    </address>
  </div>`;
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
  vetBody(locatedEntity) {
    return [
      {
        key: "remarks_contract",
        nl: "afspraken",
      },
      {
        key: "remarks_general",
        nl: "opmerkingen",
      },
    ]
      .map(({ key, nl }) => {
        if (!locatedEntity[key]) return "";
        return `
      <p class="bvmd-popup__tekst">
        <strong>${nl}</strong><br>
        ${locatedEntity[key]}
      </p>`;
      })
      .join("");
  },
  guestBody(locatedEntity) {
    return [
      {
        key: "max_hours_alone",
        nl: "max uren alleen",
      },
      {
        key: "text",
        nl: "opmerkingen",
      },
    ]
      .map(({ key, nl }) => {
        if (!locatedEntity[key]) return "";
        return `
      <p class="bvmd-popup__tekst">
        <strong>${nl}</strong><br>
        ${locatedEntity[key]}
      </p>`;
      })
      .join("");
  },
  animalList(locatedEntity) {
    if (!locatedEntity.hasAnimals) {
      return ``;
    }

    return `
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
    `;
  },
  animalListItemOwner(animal) {
    return `
    <li class='bvmd-popup__animal-list-item'>
      ${buttonRenders.animal(animal)} verblijft bij
      ${buttonRenders.staysAt(animal.staysAt)}
    </li>
    `;
  },
  animalListItemSafeHouse(animal) {
    return `
    <li class='bvmd-popup__animal-list-item'>
      ${buttonRenders.animal(animal)} van 
      ${buttonRenders.owner(animal.owner)}
    </li>
    `;
  },
};

module.exports = { postLeafletWork, maakAlt, locationMapper };
