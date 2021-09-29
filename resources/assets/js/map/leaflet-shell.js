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
        key: "located",
        check: (located) => {
          return located;
        },
        res: "on-the-map",
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
      .join(" ") + ` is-${locatedEntity.type} id-${locatedEntity.id} name-${locatedEntity.name}`
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
    console.error('fout in maken alts met:');
    console.log(locatedEntity)
    throw error;
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
  const markers = locatedEntities.filter((locatedEntity)=>{
    return locatedEntity.shown;
  }).map((locatedEntity) => {
    return locatedEntity.marker;
  });


  // creeer markers met x en y, afkomstig uit leaflet, map en sort per X.
  const markersPerXPositie = markers
    .map((marker) => {
      const rect = marker.getBoundingClientRect();
      const eigenGebied = {
        left: rect.left,
        right: rect.right,
        top: rect.top + 20, // boven mogen ze overlappen
        bottom: rect.bottom,
      };
      return {
        eigenGebied,
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
      if (marker1Data.eigenGebied.left > marker2Data.eigenGebied.left) return 1;
      if (marker1Data.eigenGebied.left < marker2Data.eigenGebied.left)
        return -1;
      return 0;
    });
  // nu per marker, zoek nabijgelegen markers.
  markersPerXPositie.forEach((markerData) => {
    markerData.markersInGebied = markersPerXPositie.filter((markerRefData) => {
      // skip zelf.
      if (markerRefData.marker.id === markerData.marker.id) return false;

      const gevonden =
        !(
          markerData.eigenGebied.right < markerRefData.eigenGebied.left ||
          markerData.eigenGebied.left > markerRefData.eigenGebied.right ||
          markerData.eigenGebied.bottom < markerRefData.eigenGebied.top ||
          markerData.eigenGebied.top > markerRefData.eigenGebied.bottom
        ) ||
        (markerData.eigenGebied.left === markerRefData.eigenGebied.left &&
          markerData.eigenGebied.right === markerRefData.eigenGebied.right &&
          markerData.eigenGebied.top === markerRefData.eigenGebied.top &&
          markerData.eigenGebied.bottom === markerRefData.eigenGebied.bottom);

      return gevonden;
    });
  });

  // te roteren markers
  const teRoterenMarkers = markersPerXPositie.filter((markerData) => {
    return markerData.markersInGebied.length > 0;
  });

  if (!teRoterenMarkers.length) {
    console.log("niets te roteren");
    return;
  }

  // nu te dichtbijzijnde markers rotatie berekenen.

  // als er veel markers zijn, > 4. waaier links laten beginenn op helft.

  teRoterenMarkers.forEach((markerData) => {
    let rotatieVerhoging =
      markerData.markersInGebied.length < 4
        ? 1
        : Math.floor(markerData.markersInGebied.length / -2) + 1;
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

      const stijlAttrWaarde = roteerMarkerData.marker.getAttribute('style') ||'';

      const bestaandeStijlArray = stijlAttrWaarde.split(';').filter(a => a).map(CSSRegel => {
        if (!CSSRegel.includes('transform')) return CSSRegel;
        if (!CSSRegel.includes('rotate')) return `${CSSRegel} rotate(${rotatieDeg}deg)`;
        return CSSRegel.replace(/rotate\(\d+deg\)/, `rotate(${rotatieDeg}deg)`)
      });
      const nieuweStijl = bestaandeStijlArray.join(';')
      roteerMarkerData.marker.setAttribute('style', nieuweStijl);

    } catch (error) {
      console.error(error);
    }
  });
}

function setLeafletEventListeners(leafletMap, locatedEntities) {
  
// Select the node that will be observed for mutations
const targetNode = document.querySelector('.leaflet-marker-pane .lmi');

// Options for the observer (which mutations to observe)
const config = { attributes: true };

let laatsteTransformWaardeMarker1 = null;

function callback(mutationList, observer) {
  mutationList.forEach( (mutation) => {
    switch(mutation.type) {
      case 'attributes':
        if (mutation.attributeName === 'style') {
          const transformMomenteelMatch = mutation.target.getAttribute('style').split(';').filter(CSSRegel => {
            return CSSRegel.includes('transform:')
          })

          if (!transformMomenteelMatch || !transformMomenteelMatch.length) return;

          if (transformMomenteelMatch[0] !== laatsteTransformWaardeMarker1) {
            laatsteTransformWaardeMarker1 = transformMomenteelMatch[0];
            
            setTimeout(()=>{
              runMarkerRotateFixes(locatedEntities)
            }, 1000)
          } else {
            console.log('bleef zelfde qwaarde')
          }


        }
        break;
    }
  });
}


// Create an observer instance linked to the callback function
const observer = new MutationObserver(callback);

// Start observing the target node for configured mutations
observer.observe(targetNode, config);
// Later, you can stop observing
//observer.disconnect();
}


module.exports = {
  maakAlt,
  locationMapper,
  checkAndFixMarkersToClose,
  setLeafletEventListeners,
};
