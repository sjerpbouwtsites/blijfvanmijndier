const popups = require("./popups");

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

  marker.bindPopup(popups.markerHTML.create(locatedEntity));
}

module.exports = { maakAlt, locationMapper };
