const showHideNodes = require("./util").showHideNodes;

module.exports = {
  data: {
    locatedEntities: [],
    dropouts: [],
    failure: [],
    success: [],
  },
  init(locatedEntities){
    this.data.locatedEntities = locatedEntities;
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

    this.data.success = [this.locatedEntities]
      .map((locatedEntity) => {
        return locatedEntity.marker;
      })
      .filter((marker) => {
        return marker && !failureIds.includes(marker.id);
      });

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