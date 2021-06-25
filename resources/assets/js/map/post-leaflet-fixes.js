const markerSrcConfig = {
  guest: {
    "animals-on-site": "guest/marker-groen.png",
    "no-animals": "guest/marker-groenblauw.png",
  },
  owner: {
    "animals-on-site": "owner/marker-diepblauw-fel.png",
    "no-animals": "owner/marker-diepblauw.png",
  },
  pension: {
    "animals-on-site": "pension/marker-magenta-fel.png",
    "no-animals": "pension/marker-magenta.png",
  },
  shelter: {
    "animals-on-site": "shelter/marker-lichtblauw-fel.png",
    "no-animals": "shelter/marker-lichtblauw.png",
  },
  vet: {
    "animals-on-site": "vet/marker-rood.png",
    "no-animals": "vet/marker-rood.png",
  },
  location: {
    "animals-on-site": "location/marker-geel-fel.png",
    "no-animals": "location/marker-geel.png",
  },  
};

/**
 * using the alt attribute is nice but a bit shit too.
 * move all to data-attributes after initialization.
 *
 * also have to link the shadow markers to the markers
 *
 *
 */
function postLeafletWork(locatedEntities) {

  

  const markerImages = Array.from(
    document.querySelectorAll(".leaflet-marker-pane img")
  );
  const shadowImages = Array.from(
    document.querySelectorAll(".leaflet-shadow-pane img")
  );

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


    const markerTempType = markerAltData
      .find((altPiece) => {
        return altPiece.includes("is-");
      })
      .replace("is-", "");
    marker.setAttribute("id", `marker-${markerTempType}-${markerId}`);
    marker.setAttribute(
      "data-shadow-id",
      `shadow-${markerTempType}-${markerId}`
    );
    shadowMarker.setAttribute("id", `shadow-${markerTempType}-${markerId}`);
    shadowMarker.setAttribute(
      "data-marker-id",
      `marker-${markerTempType}-${markerId}`
    );
 
    //    marker.setAttribute("alt", markerAltData.join(" "));
    
    // CUT INLINE STYLES TO STYLESHEET
    // const markerInlineStyle = marker.getAttribute("style");
    // const shadowInlineStyle = shadowMarker.getAttribute("style");
    // stylesheetHTMLArray.push(`
    //   #marker-${markerTempType}-${markerId} {
      //     ${markerInlineStyle}
    //   }
    //   #shadow-${markerTempType}-${markerId} {
      //     ${shadowInlineStyle}
      //   }      
      // `);
      // marker.removeAttribute("style");
      // shadowMarker.removeAttribute("style");
      
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

    // markers not on the map are in the north sea
    const onTheMap = markerAltData.find((altPiece) => {
      return altPiece.includes("on-the-map");
    });
    if (!onTheMap) {
      marker.classList.add('blurred');
      marker.setAttribute("data-on-the-map", 'in the north sea');
    } else {
      marker.setAttribute("data-on-the-map", 'damn right');
    }  
    // then hide the shadow markers
    document.getElementById(marker.getAttribute('data-shadow-id')).classList.add('blurred')

    // MOVE TYPE ALT ENTRY TO DATA-TYPE
    const type = markerAltData.find((altPiece) => {
      return altPiece.includes("is-");
    });
    marker.setAttribute("data-type", type.replace("is-", ""));

    //    marker.setAttribute("alt", marker.alt.replace(type, ""));
    
    // MOVE ANIMAL QUANTITY TO DATA ATTR
    const animalAmountData = markerAltData.filter((altPiece) => {
      return [
        "has-animals",
        "multiple-animals",
        "animals-on-site",
        "no-animals",
      ].includes(altPiece);
    });
    if (animalAmountData) {
      animalAmountData.forEach((animalAD) => {
        marker.setAttribute(`data-${animalAD}`, "true");
      });
    }
    
    // DESTROY THE ALT ATTRIBUTE
    marker.removeAttribute("alt");
    
    // FIX THE SRC ATTRIBUTE
    
    let typeSrcConfig = markerSrcConfig[markerTempType];
    let markerSrc = animalAmountData.includes("no-animals")
      ? typeSrcConfig["no-animals"]
      : typeSrcConfig["animals-on-site"];

      marker.src = `/img/markers/${markerSrc}`;
    shadowMarker.src = "/img/marker-shadow.png";
  });

  // PRINT STYLES TO HEAD
  const styleEl = document.createElement("style");
  styleEl.id = "handmade-marker-styles";
  styleEl.innerHTML = stylesheetHTMLArray.join("");
  document.head.appendChild(styleEl);

  writeTitleToMarkers(locatedEntities)
  focusOpMarkerIndienNodig();
}
/**
 * vanuit backend kan via GETs gefocust worden op een marker.
 *
 * @returns
 */
function focusOpMarkerIndienNodig(){
  if (!location.search) return;
  const searchRes = location.search.substring(1, location.search.length).split('&')
  if (!searchRes.includes('focus=true')) return;
  const searchObj = {};
  searchRes.forEach(searchR =>{
      const s = searchR.split('=');
      searchObj[s[0]] = s[1];
  });

const markerId = `marker-${searchObj['focus-type']}-id-${searchObj['focus-id']}`;
  const marker = document.getElementById(markerId)
  if (!marker) {
    throw new Error(`tracht te focussen op niet bestaande marker ${markerId}`)
  }
  marker.click();
}

/**
 * loops over markers via models and sets appropriate title.
 *
 */
function writeTitleToMarkers(locatedEntities){
  locatedEntities.forEach((entity, index) => {
    entity.marker.setAttribute('title', `${entity.type} ${entity.contact.name}`)
    
  })
}


module.exports = postLeafletWork;
