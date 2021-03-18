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
      return (
        document.getElementById(this.id) &&
        document.getElementById(this.id).checked
      );
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
    return this.type === "checkbox"
      ? this.evaluateCheckbox(evaluateWith)
      : this.evaluateRadio(evaluateWith);
  }

  disables(){
    return null
  }

  enforces(){
    return null
  }

  /**
   * @param {bool} evaluateWith checkbox res
   * @returns {object} width success.array of markers & failure.array of markers.
   */
  evaluateCheckbox(evaluateWith) {
    // checkbox are really straightforward.
    if (evaluateWith === true) {
      return {
        success: this.addShadows(this.markers),
        failure: [],
      };
    }
    return {
      success: [],
      failure: this.addShadows(this.markers),
    };
  }

  /**
   * adds shadowmarkers to array of markers in order to 
   * also animate that.
   *
   * @param {*} markerList
   * @memberof FilterObject
   */
  addShadows(markerList){
    const shadows = markerList.map(marker => {
      return document.getElementById(marker.id.replace('marker','shadow'))
    })
    return markerList.concat(shadows)
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
    r.success = this.addShadows(r.success)
    r.failure = this.addShadows(r.failure)
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
    if (fakeStaticBecauseCodeBaseToOld._self)
      return fakeStaticBecauseCodeBaseToOld._self;

    this.configurations = [];

    this.setRow1();
    this.setRow2();
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
        requiresName: ["animals-on-site"],
      }),
      new FilterObject({
        entityFilter: this,
        name: `is-vet`,
        label: "Dieren artsen",
        entities: Vet.all,
        row: 1,
        enforces(){
          return {
            'filter-input-animals-on-site-negeer': true
          }
        }
      }),
      new FilterObject({
        entityFilter: this,
        name: `is-owner`,
        label: "Eigen aar",
        entities: Owner.all,
        row: 1,
        requiresName: ["animals-on-site"],
      }),
      new FilterObject({
        entityFilter: this,
        name: `is-shelter`,
        label: "Pen sion",
        entities: Shelter.all,
        row: 1,
        requiresName: ["animals-on-site"],
      }),
      new FilterObject({
        entityFilter: this,
        name: `is-location`,
        label: "Op vang",
        entities: Location.all,
        row: 1,
        requiresName: ["animals-on-site"],
        enforces(){
          return {
            'filter-input-animals-on-site-negeer': true
          }
        }        
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
            return entity.animalsOnSite.length === 0;
          }),
          radioConfig("&eacute;&eacute;n", "een", (entity) => {
            return entity.animalsOnSite.length === 1;
          }),
          radioConfig("meer", "multiple", (entity) => {
            return entity.animalsOnSite.length > 1;
          }),
        ],
        entities: [Guest.all, Owner.all, Shelter.all].flat(),
        row: 2,
        disables(){
          if (document.querySelector('input[name="animals-on-site"]:checked').value !== 'skip') {
            return["is-vet", "is-location"];
          } else {
            return []
          }
        },
      })
    );
  }
  /**
   * @param {string} name
   * @returns {FilterConfig} filterConfig from this.configurations.
   */
  getByName(name) {
    const re = this.configurations.find(
      (filterConfig) => filterConfig.name === name
    );
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

  static runFilter(filterConfig, event){
    const type = filterConfig.type;

    if (type === "checkbox") {
      // either all markers go or not.
      console.log(' set to ', event.target.checked)
      const evaluatedMarkers = filterConfig.evaluate(event.target.checked);
      showHideNodes(evaluatedMarkers.success, true);
      showHideNodes(evaluatedMarkers.failure, false);
      //  // TODO UGLY FIX. GET SHADOWS FROM MARKERS.
      //  showHideNodes(filterConfig.markers.map(marker => {
      //    return document.getElementById(marker.id.replace('marker', 'shadow'))
      //  }), event.target.checked)

      return;
    }
    if (type === "radio") {
      // value is name
      
      const value = event.target.form[event.target.name].value;
      const evaluatedMarkers = filterConfig.evaluateRadio(value);
      showHideNodes(evaluatedMarkers.success, true);
      showHideNodes(evaluatedMarkers.failure, false);
       // TODO UGLY FIX. GET SHADOWS FROM MARKERS.
       showHideNodes(evaluatedMarkers.success.map(marker => {
        return document.getElementById(marker.id.replace('marker', 'shadow'))
      }), true)
       // TODO UGLY FIX. GET SHADOWS FROM MARKERS.
       showHideNodes(evaluatedMarkers.failure.map(marker => {
        return document.getElementById(marker.id.replace('marker', 'shadow'))
      }), false)
      return;
    }
    throw new Error("unknown type");    
  }

  setEventHandlers() {
    filterForm().addEventListener("change", (event) => {
      // FilterConfig from this.configurations.
      const filterConfig = this.getByName(event.target.name);
      EntityFilter.runFilter(filterConfig, event);

      // TODO UGLY
      if (filterConfig.disables()) {
        
        setTimeout(()=>{
          filterConfig.disables().forEach(disabledOption => {
            const thisDisabledInput = document.querySelector(`[name="${disabledOption}"]`);
            thisDisabledInput.checked = false;
            const changeEvent = new Event('change');
            thisDisabledInput.dispatchEvent(changeEvent);
            EntityFilter.runFilter(this.getByName(disabledOption), changeEvent, false);
          })
        }, 150);
        
      } 

      // TODO UGLY
      if (filterConfig.enforces()) {
        
        setTimeout(()=>{
          Object.entries(filterConfig.enforces()).forEach(([enforcedOptionId, enforcedOptionValue]) => {
            const thisEnforcedInput = document.getElementById(enforcedOptionId);
            thisEnforcedInput.checked = enforcedOptionValue;
            const changeEvent = new Event('change');
            thisEnforcedInput.dispatchEvent(changeEvent);
            EntityFilter.runFilter(this.getByName(thisEnforcedInput.name), changeEvent, false);
          })
        }, 300)
        
      }   

    });
    filterForm().addEventListener("reset", (event) => {
      Array.from(filterForm().querySelectorAll(".map__filter-input")).forEach(
        (input) => {
          const e = new Event("change");
          input.dispatchEvent(e);
          console.log(e);
        }
      );
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
    class='map__filter-label map__filter-label--${
      filterConfig.type
    } map__filter-label--${filterConfig.name}' 
    for='${filterConfig.id}'>
    <input 
    class='hidden' 
    id='${filterConfig.id}' 
    name='${filterConfig.name}' 
    type='${filterConfig.type}'
    checked 
    >
    <span class='map__filter-fake-box map__filter-fake-box--${
      filterConfig.type
    }'>
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
  const labelsAndInputs = filterConfig.radioData.reduce(
    (prev, radioDatum, index) => {
      return `${prev}
    <label 
      class="map__filter-label map__filter-label--radio map__filter-label--${
        filterConfig.name
      }" 
      for="${filterConfig.id}-${radioDatum.label}">
      <input 
        class='hidden'
        id="${filterConfig.id}-${radioDatum.label}"
        name='${filterConfig.name}' 
        type='${filterConfig.type}'
        value='${radioDatum.value}'
        ${index === 0 ? `checked='checked'` : ""}
      >
      <span class='map__filter-fake-box map__filter-fake-box--${
        filterConfig.type
      }'>
        ${svgs.checked("#5151d3")}
        ${svgs.removed("#5151d3")}
      </span>
      <span class='map__filter-title'>${radioDatum.label}</span>
    </label>  
    `;
    },
    ""
  );

  // paste in outer.
  return `
    <span class='map__filter-radio-outer'>
      ${labelsAndInputs}
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

  document.getElementById(
    "body-filter"
  ).innerHTML = `<form action='#' method='GET' id='map-filters'>
    ${inputRow1}
    ${inputRow2}
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

function init() {
  const entityFilter = new EntityFilter();
  populateFilterHTML(entityFilter);
  setFilterEventHandlers(entityFilter);
  activateResetButton();
}

module.exports = {
  init,
};
