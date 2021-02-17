/**
 * using the alt attribute is nice but a bit shit too.
 * move all to data-attributes after initialization.
 *
 * also have to link the shadow markers to the markers
 *
 *
 */
function postLeafletWork() {
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
    //  markerAltData.splice(markerAltData.indexOf(markerId), 1); // just delete alt in the end.

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
      return ["has-animals", "multiple-animals", "animals-on-site"].includes(
        altPiece
      );
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

module.exports = postLeafletWork;
