const utils = require("./util");
const showHideNodes = utils.showHideNodes;
const modelsModule = require("./models.js");
const MayaModel = modelsModule.MayaModel;
const Animal = modelsModule.Animal;
const Vet = modelsModule.Vet;
const Guest = modelsModule.Guest;
const Shelter = modelsModule.Shelter;
const Owner = modelsModule.Owner;

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
  constructor() {
    // singleton enforecen.
    if (fakeStaticBecauseCodeBaseToOld._self) return fakeStaticBecauseCodeBaseToOld._self;

    this.configurations = [];

    this.setRow1();
    this.setRow2();
    fakeStaticBecauseCodeBaseToOld._self = this;
  }

  setRow1() {
    this.configurations.push(
      new FilterObject({
        entityFilter: this,
        name: `is-guest`,
        label: "Gastgezin",
        entities: Guest.all,
        row: 1,
        requiresName: "animals-on-site",
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
        requiresName: "animals-on-site",
      }),
      new FilterObject({
        entityFilter: this,
        name: `is-shelter`,
        label: "Pension",
        entities: Shelter.all,
        row: 1,
        requiresName: "animals-on-site",
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
          radioConfig("onbelangrijk", "skip", () => {
            return true;
          }),
          radioConfig("0", "nul", (entity) => {
            return entity.animalsOnSite.length === 0;
          }),
          radioConfig("1", "een", (entity) => {
            return entity.animalsOnSite.length === 1;
          }),
          radioConfig("Meerdere", "multiple", (entity) => {
            return entity.animalsOnSite.length > 1;
          }),
        ],
        entities: [Guest.all, Owner.all, Shelter.all].flat(),
        row: 2,
        requiresName: ["is-shelter", "is-guest", "is-pension"],
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
      const type = filterConfig.type;

      if (type === "checkbox") {
        // either all markers go or not.
        showHideNodes(filterConfig.markers, event.target.checked);
        return;
      }
      if (type === "radio") {
        // value is name
        const value = event.target.form[event.target.name].value;
        const evaluatedMarkers = filterConfig.evaluateRadio(value);
        showHideNodes(evaluatedMarkers.success, true);
        showHideNodes(evaluatedMarkers.failure, false);
        return;
      }
      throw new Error("unknown type");
    });
    filterForm().addEventListener("reset", (event) => {
      Array.from(filterForm().querySelectorAll(".map__filter-input")).forEach((input) => {
        const e = new Event("change");
        input.dispatchEvent(e);
        console.log(e);
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

/**
 *
 * @param {FilterConfig} filterConfig
 * @returns {string} HTML of a wrapper radio input.
 */
function wrappedRadioInput(filterConfig) {
  const labelsAndInputs = filterConfig.radioData.reduce((prev, radioDatum) => {
    return `${prev}
    <label 
      class="map__filter-label map__filter-label--radio map__filter-label--${filterConfig.name}" 
      for="${filterConfig.id}-${radioDatum.label}">
      <span class='map__filter-title'>${radioDatum.label}</span>
      <input 
        class="map__filter-input map__filter-input--${filterConfig.name}"
        id="${filterConfig.id}-${radioDatum.label}"
        name='${filterConfig.name}' 
        type='${filterConfig.type}'
        value='${radioDatum.value}'
      >
    </label>`;
  }, "");
  return `
    <span class='map__filter-radio-outer'>
      <span class='map__filter-title map__filter-title--high'>${filterConfig.label}
      </span>
      ${labelsAndInputs}
    </span>`;
}

function activateResetButton() {
  document.getElementById("filter-form-reset").addEventListener("click", () => {
    filterForm().reset();
  });
}

/**
 * reduces filter config to label & input HTML for form
 * @param {EntityFilter} entityFilter
 */
function populateFilterHTML(entityFilter) {
  const inputRow1 = `<div class='map__filter-row'>
        ${entityFilter.getRow(1).reduce((prev, filterConfig) => {
          return prev + wrappedInput(filterConfig);
        }, "")}
      </div>`;
  const inputRow2 = `<div class='map__filter-row'>
  ${entityFilter.getRow(2).reduce((prev, filterConfig) => {
    return prev + wrappedRadioInput(filterConfig);
  }, "")}
</div>`;

  document.getElementById("body-filter").innerHTML = `<form action='#' method='GET' id='map-filters'>
    ${inputRow1}
    ${inputRow2}
    <input type='reset' id='filter-form-reset' class='map-aside__input--reset' value='leeg'></form>`;
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
  activateResetButton();
}

module.exports = {
  init,
};
