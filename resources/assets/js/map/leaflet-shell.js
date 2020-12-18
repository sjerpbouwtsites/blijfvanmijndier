const buttonRenders = require("./buttons");
const { MayaModel } = require("./models");

/**
 * Leaflet prints images for the shadows in a different div
 * then the actual markers. In order to also style the shadows
 * through the alt-as-an-array concept we apply the same alt attribute
 * to the shadow image.
 * Must strip off id attr or conflict will arise
 */
function linkShadowsToMarkers() {
  return new Promise((resolve, reject) => {
    try {
      const markerImages = Array.from(document.querySelectorAll(".leaflet-marker-pane img"));
      const shadowImages = Array.from(document.querySelectorAll(".leaflet-shadow-pane img"));
      // images and shadows correspond by their index.
      markerImages.forEach((marker, markerIndex) => {
        const allowedAltValues = marker.alt
          .split(" ")
          .filter((altValue) => {
            return altValue.substring(0, 3) !== "id-";
          })
          .join(" "); // prevent conflict strip off id
        shadowImages[markerIndex].setAttribute("alt", allowedAltValues);
      });
    } catch (error) {
      reject(error);
    }
    resolve();
  });
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

  const marker = L.marker([locatedEntity.location.lat, locatedEntity.location.lon], options).addTo(globalLeafletMap);

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
        <span class='bvmd-popup__voet-link-wrap'>
          Naar Maya: 
          <a class='bvmd-popup__voet-link' href='${locatedEntity.mayaRoute()}'>🔍</a>
          <a class='bvmd-popup__voet-link' href='${locatedEntity.mayaRoute(true)}'>✍</a>
        </span>
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

module.exports = { linkShadowsToMarkers, maakAlt, createMap, locationMapper };
