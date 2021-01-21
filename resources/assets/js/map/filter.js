/**
 * Fills filter config more succincty
 * @returns {object} name: string, rule: string
 */
function filterConfigItem(name, rule) {
  if (!name || !rule) return null;
  return {
    name,
    rule,
  };
}

/**
 * name: printed to filter in plain text
 * rule: printed in input as name attr, corresponds to CSS / alt-attr for markers.
 * left, middle, right: corresponding / combining filters. So left[0], middle[0], combine.
 */
const filterConfig = {
  left: [
    filterConfigItem("dierenarts", "is-vet"),
    filterConfigItem("gastgezin", "is-guest"),
    filterConfigItem("pension", "is-shelter"),
    filterConfigItem("eigenaar", "is-owner"),
  ],
  middle: [
    filterConfigItem(null, null),
    filterConfigItem("Vangt dier op", "guest:has-animal"),
    filterConfigItem("Vangt dier op", "shelter:has-animal"),
    filterConfigItem("Dier aanwezig", "owner:has-animal"),
  ],
  right: [
    filterConfigItem(null, null),
    filterConfigItem("Meerdere dieren opgevangen", "guest:multiple-animals"),
    filterConfigItem("Meerdere dieren opgevangen", "shelter:multiple-animals"),
    filterConfigItem("Meerdere dieren aanwezig", "owner:multiple-animals"),
  ],
};
/**
 * reduces filter config to label & input HTML for form
 */
function populateFilterHTML() {
  const printTarget = document.getElementById("map-filters");

  // sorteer de filterconfig naar rijen toe.
  const fc = filterConfig;
  const filterConfigRijen = [];
  const [ll, ml, rl] = [fc.left.length, fc.middle.length, fc.right.length];
  const maxRijIndex = Math.min(ll, ml, rl);
  for (let rij = 0; rij < maxRijIndex; rij++) {
    filterConfigRijen[rij] = [];
    if (fc.left[rij]) {
      filterConfigRijen[rij].push(fc.left[rij]);
    }
    if (!fc.middle[rij]) continue;
    if (fc.middle[rij]) {
      filterConfigRijen[rij].push(fc.middle[rij]);
    }
    if (fc.right[rij]) {
      filterConfigRijen[rij].push(fc.right[rij]);
    }
  }
  printTarget.innerHTML = filterConfigRijen
    .map((rij) => {
      return `<div class='map__filter-row'>
      ${rij
        .map((kolom, columnIndex) => {
          if (!kolom) {
            // want leeg dus null
            return null;
          }
          return `
          <label 
            class='map__filter-column map__filter-column--${columnIndex}' 
            for='filter-checkbox-${kolom.rule}'>
            <span class='map__filter-column-title'>${kolom.name}</span>
            <input 
              class='map__filter-input' 
              id='filter-checkbox-${kolom.rule}' 
              type='checkbox' 
              checked='checked' 
              name='${kolom.rule}'
            >
          </label>`;
        })
        .join("")}
      </div>`;
    })
    .join("");
}

/**
 * when filter form change,
 * create object with rule-bool key-val
 * create and/or get own style el
 * translate rule-bool obj to CSS sheet & print
 */
function filterClickHandler() {
  const filterForm = document.getElementById("map-filters");
  filterForm.addEventListener("change", function (event) {
    if (!event.target || !event.target.form) {
      return;
    }
    const formData = {};
    Array.from(event.target.form).forEach((formInput) => {
      formData[formInput.name] = formInput.checked;
    });

    // sorteer de formData op vet, guest, shelter, owner.
    // absolute: false here no show.
    // animal on site: condititional on absolute.
    // multiple animals: condititional on has-animals.
    const sortedFormData = {
      vet: {},
      guest: {},
      shelter: {},
      owner: {},
    };
    for (let key in formData) {
      const splitted = key.split(":");
      if (splitted && splitted.length > 1) {
        // is niet absoluut, bv guest:has-animals
        const [naam, rule] = splitted;
        if (!sortedFormData.hasOwnProperty(naam)) {
          throw new Error(
            `mismatch in regels in filter configuratie en waar ze naartoe geschreven worden. Probleem: ${naam} ${rule}`
          );
        }
        sortedFormData[naam][rule] = formData[key];
      } else {
        // the absolute rule. In alt & filter config as 'is-vet' etc.
        const rKey = key.replace("is-", "");
        if (!sortedFormData.hasOwnProperty(rKey)) {
          throw new Error(
            `mismatch in regels in filter configuratie en waar ze naartoe geschreven worden. Probleem: ${rKey}`
          );
        }
        sortedFormData[rKey]["allowed"] = formData[key];
      }
    }

    const markerImgEls = Array.from(document.querySelectorAll(".leaflet-marker-pane img"));
    const shadowImgEls = Array.from(document.querySelectorAll(".leaflet-shadow-pane img"));

    markerImgEls.forEach((marker, markerIndex) => {
      const markerType = marker.getAttribute("data-type");
      const relevantRules = sortedFormData[markerType];
      const shadowMarker = shadowImgEls[markerIndex];

      // is it absolutely allowed to show this marker?
      let gonnaBlur = null;
      if (!relevantRules.allowed) {
        gonnaBlur = true;
      } else {
        gonnaBlur = false; // start with success
        const requirements = {};
        if (relevantRules["has-animal"]) {
          requirements["has-animal"] = relevantRules["has-animal"];
        }
        if (relevantRules["has-animal"] && relevantRules["multiple-animals"]) {
          requirements["multiple-animals"] = relevantRules["multiple-animals"];
        }

        for (let condition in requirements) {
          if (!marker.getAttribute(`data-${condition}`)) {
            gonnaBlur = false;
            break;
          }
        }
      }
      blurMarker(marker, gonnaBlur);
      blurMarker(shadowMarker, gonnaBlur);
    });
  });
}

/**
 * helper function to switch blurred state on markers.
 *
 * @param {HTMLImageElement} marker
 * @param {boolean} blur
 */
function blurMarker(marker, blurMarker) {
  if (typeof blurMarker !== "boolean") {
    throw new Error("blur state unclear in blurmarker");
  }
  if (!marker instanceof HTMLImageElement) {
    throw new Error(`marker is not an image element but rather ${marker}`);
  }
  const isBlurred = marker.classList.contains("blurred");
  if (blurMarker && !isBlurred) {
    marker.classList.add("blurred");
  } else if (!blurMarker && isBlurred) {
    marker.classList.remove("blurred");
  }

  return marker;
}

module.exports = {
  populateFilterHTML,
  filterClickHandler,
};

//   const styleElement = getFilterStyleElement();

//   const formCSSRules = Object.entries(formData)
//     .map(([rule, checked]) => {
//       return `[alt~="${rule}"] {
//           opacity: ${checked ? `1` : `0.2`}
//         }`;
//     })
//     .join("");
//   styleElement.innerHTML = formCSSRules;
// });
