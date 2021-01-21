const BEMMapper = require("./util").BEMMapper;
const modelsModule = require("./models.js");
const MayaModel = modelsModule.MayaModel;
const Animal = modelsModule.Animal;
const Vet = modelsModule.Vet;
const Guest = modelsModule.Guest;
const Shelter = modelsModule.Shelter;
const Owner = modelsModule.Owner;

/**
 *
 * @property {string} name used internally
 * @property {string} label used to client
 * @property {string} type inputtype
 * @property {integer} row number
 * @property {Array<HTMLElement} array of markers
 * @property {*} requiresName setting true causes other also true
 * @property {*} excludesName setting false causes other also false
 * @class FilterObject
 */
class FilterObject {
  /**
   * Rules for the filter config
   *
   * in config!

   * @returns configObj
   */
  constructor(config) {
    this.type = "checkbox";
    for (let k in config) {
      this[k] = config[k];
    }
    return this;
  }

  get id() {
    return `filter-input-${this.name}`;
  }

  get inputOrInputs() {
    let r;
    if (this.type === "checkbox") {
      r = document.getElementById(this.id);
    } else {
      r = [document.getElementById("map-filters")[this.name]];
    }
    if (!r) {
      console.error("geen ", filterObject.id);
    }
    return r;
  }
  get markers() {
    return this.entities.map((model) => model.marker);
  }

  isChecked() {
    if (this.type !== "checkbox") {
      throw new Error("fdfd");
    }
    return document.getElementById(this.id).value === "on";
  }
  /**
   * returns bool in the end!
   *
   * @param {*} radioEvaluationKey
   * @memberof FilterObject
   */
  evaluate(radioEvaluationKey) {
    const r = {
      success: [],
      failure: [],
    };

    const radioFunc = this.radioEvaluations[radioEvaluationKey];
    this.entities.forEach((entity) => {
      if (radioFunc(entity)) {
        r.success.push(entity.marker);
      } else {
        r.failure.push(entity.marker);
      }
      console.error("HIER BEN JE");
    });
    return r;
  }
}

class EntityFilter {
  constructor() {
    this.data = [];
    this.markers = MayaModel.allMayaModels.map((model) => model.marker);
    this.setRow1();
    this.setRow2();
  }

  static showHideMarkers(markers, showMarkers = true) {
    markers.map((marker) => {
      const isBlurred = marker.classList.contains("blurred");
      if (showMarkers && isBlurred) {
        marker.classList.remove("blurred");
      } else if (!showMarkers && !isBlurred) {
        marker.classList.add("blurred");
      }

      return marker;
    });
  }

  setRow1() {
    this.data.push(
      new FilterObject({
        entityFilter: this,
        name: `is-guest`,
        label: "Gastgezin",
        entities: Guest.all,
        row: 1,
        requiresName: "number-animals",
      }),
      new FilterObject({
        entityFilter: this,
        name: `is-vet`,
        label: "Dierenartsen",
        entities: Vet.all,
        row: 1,
      }),
      new FilterObject({
        entityFilter: this,
        name: `is-owner`,
        label: "Eigenaar",
        entities: Owner.all,
        row: 1,
        requiresName: "number-animals",
      }),
      new FilterObject({
        entityFilter: this,
        name: `is-shelter`,
        label: "Pension",
        entities: Shelter.all,
        row: 1,
        requiresName: "number-animals",
      })
    );
  }
  setRow2() {
    this.data.push(
      new FilterObject({
        entityFilter: this,
        name: `animals-on-site`,
        type: `radio`,
        label: "Aantal opgevangen",
        radioLabels: ["onbelangrijk", "0", "1", "Meerdere"],
        radioValues: ["skip", "nul", "een", "meerdere"],
        radioEvaluations: {
          skip: (entity) => {
            return true;
          },
          nul: (entity) => {
            return entity.animalsOnSite.length === 0;
          },
          een: (entity) => {
            return entity.animalsOnSite.length === 1;
          },
          meerdere: (entity) => {
            return entity.animalsOnSite.length > 2;
          },
        },
        entities: [Guest.all, Owner.all, Shelter.all].flat(),
        row: 2,
        requiresName: ["is-shelter", "is-guest", "is-pension"],
      })
    );
  }
  getByName(name) {
    const re = this.data.find((filterConfig) => filterConfig.name === name);
    return re;
  }
  getById(id) {
    return this.data.find((filterConfig) => filterConfig.id === id);
  }
  getRow(row) {
    return this.data.filter((configObj) => configObj.row === row);
  }

  setEventHandlers() {
    document.getElementById("map-filters").addEventListener("change", (event) => {
      const filterConfig = this.getByName(event.target.name);
      const type = filterConfig.type;
      if (type === "checkbox") {
        EntityFilter.showHideMarkers(filterConfig.markers, event.target.checked);
      } else {
        // value is name
        const value = event.target.form[event.target.name].value;
        const evaluatedMarkers = filterConfig.evaluate(value);
        EntityFilter.showHideMarkers(evaluatedMarkers.success, true);
        EntityFilter.showHideMarkers(evaluatedMarkers.failure, false);
      }
    });
  }

  static wrappedInput(filterConfig) {
    return `
    <label 
      class='map__filter-label map__filter-label--${filterConfig.type} map__filter-label--${filterConfig.name}' 
      for='${filterConfig.id}'>
      <span class='map__filter-title'>${filterConfig.label}</span>
      <input 
        class='map__filter-input map__filter-input--${filterConfig.name}' 
        id='${filterConfig.id}' 
        name='${filterConfig.name}' 
        type='${filterConfig.type}'
        checked 
      >
    </label>`;
  }

  static wrappedRadioInput(filterConfig) {
    let labelsAndInputs = "";
    for (let i = 0; i < filterConfig.radioValues.length; i++) {
      const value = filterConfig.radioValues[i];
      const label = filterConfig.radioLabels[i];
      labelsAndInputs += `
      <label 
        class="map__filter-label map__filter-label--radio map__filter-label--${filterConfig.name}" 
        for="${filterConfig.id}-${label}">
        <span class='map__filter-title'>${label}</span>
        <input 
          class="map__filter-input map__filter-input--${filterConfig.name}"
          id="${filterConfig.id}-${label}"
          name='${filterConfig.name}' 
          type='${filterConfig.type}'
          value='${value}'
        >
      </label>`;
    }

    return `
    <span class='map__filter-radio-outer'>
    <span class='map__filter-title map__filter-title--high'>${filterConfig.label}</span>
    ${labelsAndInputs}
    </span>`;
  }
}

/**
 * reduces filter config to label & input HTML for form
 * @param {EntityFilter} entityFilter
 */
function populateFilterHTML(entityFilter) {
  const inputRow1 = `<div class='map__filter-row'>
        ${entityFilter.getRow(1).reduce((prev, filterConfig) => {
          return prev + EntityFilter.wrappedInput(filterConfig);
        }, "")}
      </div>`;
  const inputRow2 = `<div class='map__filter-row'>
  ${entityFilter.getRow(2).reduce((prev, filterConfig) => {
    return prev + EntityFilter.wrappedRadioInput(filterConfig);
  }, "")}
</div>`;

  document.getElementById("body-filter").innerHTML = `<form action='#' method='GET' id='map-filters'>
    ${inputRow1}
    ${inputRow2}
    <input type='reset' class='map-aside__input--reset' value='leeg'></form>`;
}

/**
 *
 * @param {EntityFilter} entityFilter
 */
function setFilterEventHandlers(entityFilter) {
  entityFilter.setEventHandlers();
}

function init() {
  const entityFilter = new EntityFilter();
  populateFilterHTML(entityFilter);
  setFilterEventHandlers(entityFilter);
}

module.exports = {
  init,
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
