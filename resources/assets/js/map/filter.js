const utils = require("./util");
const showHideNodes = utils.showHideNodes;
const svgs = require("./svgs");
const modelsModule = require("./models.js");
const MayaModel = modelsModule.MayaModel;
const Animal = modelsModule.Animal;
const Vet = modelsModule.Vet;
const Guest = modelsModule.Guest;
const Shelter = modelsModule.Shelter;
const Owner = modelsModule.Owner;
const Location = modelsModule.Location;

/**
 * document.getElementById('map-filter')
 * @throws when map filter is unknown.
 * @returns {HTMLFormElement} the map filter form.
 */
function filterForm() {
  const f = document.getElementById("map-filters");
  if (!f) throw new Error("map filter unfound");
  return f;
}

function radioConfig(label, value, evalFunc) {
  return {
    label,
    value,
    evalFunc,
  };
}

const evaluator = {
  data: {
    dropouts: [],
    failure: [],
    success: [],
  },
  run(changedFilterConfig, event) {
    this.checkAllFilters(changedFilterConfig, event);
    this.addShadows();
    this.animate();
    this.cleanupAfterFilters();
  },
  checkAllFilters(changedFilterConfig, event) {

    const filterForm = event.target.form;
    changedFilterConfig.entityFilter.configurations.forEach((filterConfig) => {
      const type = filterConfig.type;
      let evaluatedMarkers;

      if (type === "checkbox") {
        // either all markers go or not.
        evaluatedMarkers = filterConfig.evaluate(filterConfig.isChecked);
      }
      if (type === "radio") {
        // value is name
        const value = filterForm[filterConfig.name].value;
        evaluatedMarkers = filterConfig.evaluateRadio(value);
      }
      if (type === "select") {
        const selectedOptions = Array.from(filterForm[filterConfig.name].selectedOptions).map((option) => option.value);
        evaluatedMarkers = filterConfig.evaluateSelect(selectedOptions, filterConfig.name);
      }

      this.data.dropouts = this.data.dropouts.concat(evaluatedMarkers.failure);
    });

    this.data.failure = Array.from(new Set(this.data.dropouts));
    const failureIds = this.data.failure.map((failureMarker) => failureMarker.id);

    this.data.success = [...Guest.all, ...Vet.all, ...Location.all, Shelter.all, ...Owner.all]
      .map((locatedEntity) => {
        return locatedEntity.marker;
      })
      .filter((marker) => {
        return marker && !failureIds.includes(marker.id);
      });

    console.log(this.data.success.length, this.data.failure.length);
  },
  cleanupAfterFilters() {
    this.data.dropouts = [];
    this.data.success = [];
    this.data.failure = [];
  },

  /**
   * adds shadowmarkers to array of markers in order to
   * also animate that.
   *
   */
  addShadows() {
    const failureShadows = this.data.failure.map((marker) => {
      return document.getElementById(marker.id.replace("marker", "shadow"));
    });
    const successShadows = this.data.success.map((marker) => {
      return document.getElementById(marker.id.replace("marker", "shadow"));
    });
    this.data.failure = this.data.failure.concat(failureShadows);
    this.data.success = this.data.success.concat(successShadows);
  },
  animate() {
    showHideNodes(this.data.failure, false);
    showHideNodes(this.data.success, true);
  },
};

/**
 * One filterObject per filter 'rule' / input
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

  get isChecked() {
    return document.getElementById(this.id).checked === true;
  }

  /**
   * retrieve current value of corresponding input in DOM.
   *
   * @readonly
   * @memberof FilterObject
   * @return {bool|string} the current checked bool or radio string.
   */
  get inputCurrentValue() {
    if (this.type === "checkbox") {
      return document.getElementById(this.id) && document.getElementById(this.id).checked;
    } else if (this.type === "radio") {
      // create radioButtonList and get the value from there
      // note: radios give their name as the value.
      filterForm()[this.name].value;
    } else {
      throw new Error("unknow type ", this.type);
    }
  }

  /**
   * Get markers of entities related to this input.
   *
   * @readonly
   * @returns {Array<Marker>} array of markers
   */
  get markers() {
    return this.entities.map((model) => model.marker);
  }

  /**
   * dispatches to corresponding evaluators
   *
   * @param {bool|string} evaluateWith result of the inbox' value... either bool or string atm.
   * @returns {object} width success.array of markers & failure.array of markers.
   */
  evaluate(evaluateWith) {
    return this.type === "checkbox" ? this.evaluateCheckbox(evaluateWith) : this.evaluateRadio(evaluateWith);
  }

  disables() {
    return null;
  }

  enforces() {
    return null;
  }

  /**
   * @param {bool} evaluateWith checkbox res
   * @returns {object} width success.array of markers & failure.array of markers.
   */
  evaluateCheckbox(evaluateWith) {
    // checkbox are really straightforward.
    if (evaluateWith === true) {
      return {
        success: this.markers,
        failure: [],
      };
    }
    return {
      success: [],
      failure: this.markers,
    };
  }

  /**
   * @param {array} evaluateWith list of chosen options
   * @param {string} metaName name of meta key
   * @returns {object} width success.array of markers & failure.array of markers.
   */
  evaluateSelect(evaluateWith, metaName) {
    let success = [];
    let failure = [];
    this.entities.forEach((entity, index) => {
      const foundWithMeta = entity.meta[metaName].filter((entityMetaValue) => {
        return evaluateWith.includes(entityMetaValue);
      });
      if (evaluateWith.includes("NONE") || !evaluateWith.length) {
        foundWithMeta.push('not filtering')
      }

      if (foundWithMeta.length > 0) {
        success.push(entity.marker);
      } else {
        failure.push(entity.marker);
      }
    });

    return {
      success,
      failure,
    };
  }

  /**
   * helper of evaluateRadio.
   *
   * @param {string} radioNameValue
   * @return {Function} radioEvalFunc
   * @memberof FilterObject
   */
  getRadioEvalFunc(radioNameValue) {
    const radioFound = this.radioData.find((radioDatum) => {
      return radioDatum.value === radioNameValue;
    });
    if (!radioFound) {
      throw new Error("no radio config with value set as ", radioNameValue);
    }
    const radioEvalFunc = radioFound.evalFunc;
    if (!radioEvalFunc || typeof radioEvalFunc !== "function") {
      utils.throwError(` no radioFunc for ${radioNameValue}`);
    }
    return radioEvalFunc;
  }

  /**
   * tests all entities associated with this config to the set radioEvaluations.
   *
   * @param {string} radioEvaluationKey the value of the name of the radio in the end chosen.
   * @returns {object} width success.array of markers & failure.array of markers.
   */
  evaluateRadio(radioNameValue) {
    this.type !== "radio" && utils.throwError("wrong eval func");
    const r = {
      success: [],
      failure: [],
    };
    // get evalfunc from radioData.
    const radioEvalFunc = this.getRadioEvalFunc(radioNameValue);

    // test every single entity and push marker.
    this.entities.forEach((entity) => {
      if (radioEvalFunc(entity)) {
        r.success.push(entity.marker);
      } else {
        r.failure.push(entity.marker);
      }
    });

    return r;
  }
}

let fakeStaticBecauseCodeBaseToOld = {};
/**
 * Singleton, holder of configurations, orchestrates HTML, allows for retrieving configurations by id, name,
 *
 * @property {Array<FilterObject>} configurations
 */
class EntityFilter {
  constructor(meta) {
    // singleton enforecen.
    if (fakeStaticBecauseCodeBaseToOld._self) return fakeStaticBecauseCodeBaseToOld._self;

    this.configurations = [];

    this.setRow1();
    this.setRow2();
    this.setRow3(meta);
    this.setRow4(meta);
    this.setRow5(meta);
    fakeStaticBecauseCodeBaseToOld._self = this;
  }

  /**
   * insert row number get title
   *
   * @param {*} row
   * @returns
   * @memberof EntityFilter
   */
  getRowTitles(row) {
    return ["Types"]["Opgevangen dieren"];
  }

  setRow1() {
    this.configurations.push(
      new FilterObject({
        entityFilter: this,
        name: `is-guest`,
        label: "Gast gezin",
        entities: Guest.all,
        row: 1,
        enforces() {
          if (!this.isChecked) {
            return {
              "filter-input-animal_preference": "NONE",
              "filter-input-behaviour": "NONE",
              "filter-input-residence": "NONE",
            };
          }
          return null;
        },
      }),
      new FilterObject({
        entityFilter: this,
        name: `is-vet`,
        label: "Dieren artsen",
        entities: Vet.all,
        row: 1,
        enforces() {
          if (this.isChecked) {
            return {
              "filter-input-animals-on-site-negeer": true,
            };
          }
          return null;
        },
      }),
      new FilterObject({
        entityFilter: this,
        name: `is-owner`,
        label: "Eigen aar",
        entities: Owner.all,
        row: 1,
      }),
      new FilterObject({
        entityFilter: this,
        name: `is-shelter`,
        label: "Pen sion",
        entities: Shelter.all,
        row: 1,
      }),
      new FilterObject({
        entityFilter: this,
        name: `is-location`,
        label: "Op vang",
        entities: Location.all,
        row: 1,
        enforces() {
          if (this.isChecked) {
            return {
              "filter-input-animals-on-site-negeer": true,
            };
          }
          return null;
        },
      })
    );
  }
  setRow2() {
    this.configurations.push(
      new FilterObject({
        entityFilter: this,
        name: `animals-on-site`,
        type: `radio`,
        label: "Aantal opgevangen",
        radioData: [
          radioConfig("negeer", "skip", () => {
            return true;
          }),
          radioConfig("nul", "nul", (entity) => {
            const entityCheckboxInput = this.getByName(`is-${entity.type}`);
            return entityCheckboxInput.isChecked && entity.animalsOnSite.length === 0;
          }),
          radioConfig("&eacute;&eacute;n", "een", (entity) => {
            const entityCheckboxInput = this.getByName(`is-${entity.type}`);
            return entityCheckboxInput.isChecked && entity.animalsOnSite.length === 1;
          }),
          radioConfig("meer", "multiple", (entity) => {
            const entityCheckboxInput = this.getByName(`is-${entity.type}`);
            return entityCheckboxInput.isChecked && entity.animalsOnSite.length > 1;
          }),
        ],
        entities: [Guest.all, Owner.all, Shelter.all].flat(),
        row: 2,
        disables() {
          if (document.querySelector('input[name="animals-on-site"]:checked').value !== "skip") {
            return ["is-vet", "is-location"];
          } else {
            return [];
          }
        },
      })
    );
  }
  setRow3(meta) {
    this.configurations.push(
      new FilterObject({
        entityFilter: this,
        name: `animal_preference`,
        label: "Dier voorkeur",
        entities: Guest.all,
        type: "select",
        row: 3,
        selectData: meta.animal_preference.map((animalPreference) => {
          return [animalPreference, animalPreference.toLowerCase().replace(/\s/g, "-")];
        }),
        enforces() {
          return {
            "filter-input-is-guest": true,
          };
        },
      })
    );
  }
  setRow4(meta) {
    this.configurations.push(
      new FilterObject({
        entityFilter: this,
        name: `behaviour`,
        label: "Gedrag",
        type: "select",
        entities: Guest.all,
        row: 4,
        selectData: meta.behaviour.map((behaviour) => {
          return [behaviour, behaviour.toLowerCase().replace(/\s/g, "-")];
        }),
      })
    );
  }
  setRow5(meta) {
    this.configurations.push(
      new FilterObject({
        entityFilter: this,
        name: `residence`,
        label: "Woonstijl",
        type: "select",
        entities: Guest.all,
        row: 5,
        selectData: meta.residence.map((residential) => {
          return [residential, residential.toLowerCase().replace(/\s/g, "-")];
        }),
      })
    );
  }
  /**
   * @param {string} name
   * @returns {FilterConfig} filterConfig from this.configurations.
   */
  getByName(name) {
    const re = this.configurations.find((filterConfig) => filterConfig.name === name);
    return re;
  }
  /**
   * @param {string} id
   * @returns {FilterConfig} filterConfig from this.configurations.
   */
  getById(id) {
    return this.configurations.find((filterConfig) => filterConfig.id === id);
  }
  /**
   * retrieves array of filterConfigs.
   * @param {string} row
   * @returns {Array<FilterConfig>} filterConfig from this.configurations.
   */
  getRow(row) {
    return this.configurations.filter((configObj) => configObj.row === row);
  }

  setEventHandlers() {
    filterForm().addEventListener("change", (event) => {
      // FilterConfig from this.configurations.
      const filterConfig = this.getByName(event.target.name);

      filterConfig.disables() &&
        filterConfig.disables().forEach((disabledOption) => {
          const thisDisabledInput = document.querySelector(`[name="${disabledOption}"]`);
          if (thisDisabledInput.checked) {
            thisDisabledInput.checked = false;
            const changeEvent = new Event("change");
            thisDisabledInput.dispatchEvent(changeEvent);
          }
        });

      filterConfig.enforces() &&
        Object.entries(filterConfig.enforces()).forEach(([enforcedOptionId, enforcedOptionValue]) => {
          const thisEnforcedInput = document.getElementById(enforcedOptionId);
          console.log(thisEnforcedInput);
          if (thisEnforcedInput.tagName === 'SELECT') {
            thisEnforcedInput.value = enforcedOptionValue;
          } else {
            if (thisEnforcedInput.checked !== enforcedOptionValue) {
              thisEnforcedInput.checked = enforcedOptionValue;
              const changeEvent = new Event("change");
              thisEnforcedInput.dispatchEvent(changeEvent);
            }
          }
        });
      evaluator.run(filterConfig, event);
    });
    filterForm().addEventListener("reset", (event) => {
      Array.from(filterForm().querySelectorAll(".map__filter-input")).forEach((input) => {
        const e = new Event("change");
        input.dispatchEvent(e);
      });
    });
  }
}

/**
 * @param {FilterConfig} filterConfig
 * @returns {string} HTML of wrapped input
 */
function wrappedInput(filterConfig) {
  return `
  <label 
    class='map__filter-label map__filter-label--${filterConfig.type} map__filter-label--${filterConfig.name}' 
    for='${filterConfig.id}'>
    <input 
    class='hidden' 
    id='${filterConfig.id}' 
    name='${filterConfig.name}' 
    type='${filterConfig.type}'
    checked 
    >
    <span class='map__filter-fake-box map__filter-fake-box--${filterConfig.type}'>
      ${svgs.checked("#ededfa")}
      ${svgs.removed("#ededfa")}    
    </span>
    <span class='map__filter-title'>${filterConfig.label}</span>
  </label>`;
}

/**
 *
 * @param {FilterConfig} filterConfig
 * @returns {string} HTML of a wrapper radio input.
 */
function wrappedRadioInput(filterConfig) {
  const labelsAndInputs = filterConfig.radioData.reduce((prev, radioDatum, index) => {
    return `${prev}
    <label 
      class="map__filter-label map__filter-label--radio map__filter-label--${filterConfig.name}" 
      for="${filterConfig.id}-${radioDatum.label}">
      <input 
        class='hidden'
        id="${filterConfig.id}-${radioDatum.label}"
        name='${filterConfig.name}' 
        type='${filterConfig.type}'
        value='${radioDatum.value}'
        ${index === 0 ? `checked='checked'` : ""}
      >
      <span class='map__filter-fake-box map__filter-fake-box--${filterConfig.type}'>
        ${svgs.checked("#5151d3")}
        ${svgs.removed("#5151d3")}
      </span>
      <span class='map__filter-title'>${radioDatum.label}</span>
    </label>  
    `;
  }, "");

  // paste in outer.
  return `
    <span class='map__filter-radio-outer'>
      ${labelsAndInputs}
    </span>`;
}

/**
 *
 * @param {FilterConfig} filterConfig
 * @returns {string} HTML of a wrapper radio input.
 */
function wrappedSelectInput(filterConfig) {
  return `
    <span class='map__filter-select-outer'>
    
      <select name='${filterConfig.name}' class="map__filter-label map__filter-label--select map__filter-label--${
    filterConfig.name
  }"  id='${filterConfig.id}' multiple>
        <option value='NONE' name='${filterConfig.id}'>Geen keuze</option>
        ${filterConfig.selectData
          .map(([optionName, optionValue]) => {
            return `<option name='${filterConfig.id}' value='${optionValue}'>${optionName}</option>`;
          })
          .join("")}
      </select>

    
    </span>`;
}

/**
 * THIS HAS NOT BEEN BUILD YET
 * @TODO
 *
 */
function activateResetButton() {
  // document.getElementById("filter-form-reset").addEventListener("click", () => {
  //   filterForm().reset();
  // });
}

/**
 * reduces filter config to label & input HTML for form
 * @param {EntityFilter} entityFilter
 */
function populateFilterHTML(entityFilter) {
  const inputRow1 = `
  <div class='map__filter-row-outer'>
    <span class='map__filter-row-title'>Typen</span>
    <div class='map__filter-row'>
      ${entityFilter.getRow(1).reduce((prev, filterConfig) => {
        return prev + wrappedInput(filterConfig);
      }, "")}
    </div>
  </div>`;
  const inputRow2 = `
  <div class='map__filter-row-outer'>
  <span class='map__filter-row-title'>Aantal opgevangen</span>
    <div class='map__filter-row'>
      ${entityFilter.getRow(2).reduce((prev, filterConfig) => {
        return prev + wrappedRadioInput(filterConfig);
      }, "")}
    </div>
  </div>`;

  const selectNamen = [null, null, null, "Dier voorkeur", "Gedrag", "Woonstijl"];
  const inputRow345 = [3, 4, 5]
    .map((rowNumber) => {
      return `
    <div class='map__filter-row-outer'>
    <span class='map__filter-row-title'>${selectNamen[rowNumber]} </span>
      <div class='map__filter-row'>
        ${entityFilter.getRow(rowNumber).reduce((prev, filterConfig) => {
          return prev + wrappedSelectInput(filterConfig);
        }, "")}
      </div>
    </div>`;
    })
    .join("");

  document.getElementById("body-filter").innerHTML = `<form action='#' method='GET' id='map-filters'>
    ${inputRow1}
    ${inputRow2}
    ${inputRow345}
    `;
  // skipping reset button for now.
  // <input type='reset' id='filter-form-reset' class='map-aside__input--reset' value='leeg'></form>`
}

/**
 *
 * @param {EntityFilter} entityFilter
 */
function setFilterEventHandlers(entityFilter) {
  entityFilter.setEventHandlers();
}

function init(meta) {
  const entityFilter = new EntityFilter(meta);
  populateFilterHTML(entityFilter);
  setFilterEventHandlers(entityFilter);
  activateResetButton();
}

module.exports = {
  init,
};
