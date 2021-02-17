const { LocatedEntity } = require("./models");
const popups = require("./popups");
const { marker } = require("./svgs");

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
        res: "marker-vet",
      },
      {
        key: "type",
        check: (str) => {
          return str === "shelter";
        },
        res: "marker-shelter",
      },
      {
        key: "type",
        check: (str) => {
          return str === "guest";
        },
        res: "marker-guest",
      },
      {
        key: "type",
        check: (str) => {
          return str === "owner";
        },
        res: "marker-owner",
      },
      {
        key: "type",
        check: (str) => {
          return str === "pension";
        },
        res: "marker-pension",
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
      {
        key: "animalsOnSite",
        check: (animalsOnSite) => {
          return animalsOnSite.length === 0;
        },
        res: "no-animals",
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

  const marker = L.marker(
    [locatedEntity.location.lattitude, locatedEntity.location.longitude],
    options
  ).addTo(globalLeafletMap);

  marker.bindPopup(popups.markerHTML.create(locatedEntity), {
    maxWidth: "auto",
  });
}

function checkAndFixMarkersToClose(locatedEntities) {
  const markers = locatedEntities.map((locatedEntity) => {
    return locatedEntity.marker;
  });

  // reset de style van de markers.
  markers.forEach((marker) => {
    //marker.removeAttribute('style')
  });

  // creeer markers met x en y, afkomstig uit leaflet, map en sort per X.
  const markersPerXPositie = markers
    .map((marker) => {
      const x = marker._leaflet_pos.x;
      const y = marker._leaflet_pos.y;
      return {
        x,
        y,
        // gebied van 20px hoog en 20px breed rondom marker.
        eigenGebied: {
          xMin: x - 5,
          xMax: x + 5,
          yMin: y - 5,
          yMax: x + 5,
        },
        // markers gaan geroteerd worden om elkaar niet in de weg te zitten.
        rotatie: 0,
        // de eerste marker vanaf links regelt voor alle markers die in zijn gebied zijn dat ze geroteerd worden; vervolgens hoeven die markers zelf er niets meer mee.
        bijgewerktDoor: null,
        // markers in eigen gebied
        markersInGebied: [],
        marker: marker,
      };
    })
    .sort((marker1Data, marker2Data) => {
      if (marker1Data.x > marker2Data.x) return 1;
      if (marker1Data.x < marker2Data.x) return -1;
      return 0;
    });
  // nu per marker, zoek nabijgelegen markers.
  markersPerXPositie.forEach((markerData) => {
    markerData.markersInGebied = markersPerXPositie.filter((markerRefData) => {
      // skip zelf.
      if (markerRefData.marker.id === markerData.marker.id) return false;

      return (
        markerRefData.x > markerData.eigenGebied.xMin &&
        markerRefData.x < markerData.eigenGebied.xMax &&
        markerRefData.y > markerData.eigenGebied.yMin &&
        markerRefData.y < markerData.eigenGebied.yMax
      );
    });
  });

  // te roteren markers
  const teRoterenMarkers = markersPerXPositie.filter((markerData) => {
    return markerData.markersInGebied.length > 0;
  });

  if (!teRoterenMarkers.length) return;

  // nu te dichtbijzijnde markers rotatie berekenen.

  // als er veel markers zijn, > 4. waaier links laten beginenn op helft.

  teRoterenMarkers.forEach((markerData) => {
    let rotatieVerhoging = markerData.markersInGebied.length < 4 ? 1 : Math.floor(markerData.markersInGebied.length / -2) + 1;
    markerData.markersInGebied.forEach((markerInGebied, index) => {
      // als deze marker reeds is bijgewerkt, skip.
      if (markerData.bijgewerktDoor !== null) return;

      markerInGebied.rotatie = (markerData.rotatie + rotatieVerhoging) % 24;
      markerInGebied.bijgewerktDoor = markerData;
      rotatieVerhoging++;
    });
  });

  teRoterenMarkers.forEach((roteerMarkerData) => {
    const rotatieDeg = roteerMarkerData.rotatie * 15;

    try {
      const bestaandeStijl = getComputedStyle(roteerMarkerData.marker);

      const transformMatch = bestaandeStijl.transform
        .replace("matrix(", "")
        .replace(")", "")
        .split(",")
        .slice(4, 6)
        .map((n) => Number(n.trim()));

      if (!transformMatch || !transformMatch.length) {
        console.log("geen transform in ", bestaandeStijl.transform);
        throw new Error("mislukte transform match");
      }

      const [transformX, transformY] = transformMatch;

      const transformString = `transform: translate3d(${transformX}px, ${transformY}px, 0px) rotate(${rotatieDeg}deg); transform-origin: bottom center`;

      roteerMarkerData.marker.setAttribute("style", transformString);
    } catch (error) {
      console.error(error);
    }
  });
}

module.exports = { maakAlt, locationMapper, checkAndFixMarkersToClose };
