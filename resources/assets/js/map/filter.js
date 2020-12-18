/**
 * name: printed to filter in plain text
 * rule: printed in input as name attr, corresponds to CSS / alt-attr for markers.
 */
const filterConfig = [
  {
    name: "dierenartsen",
    rule: "is-vet",
  },
  {
    name: "gastgezinnen",
    rule: "is-guest",
  },
  {
    name: "pensionnen",
    rule: "is-shelter",
  },
  {
    name: "eigenaren",
    rule: "is-owner",
  },
];

/**
 * reduces filter config to label & input HTML for form
 */
function populateFilterHTML() {
  const printTarget = document.getElementById("map-filters");

  printTarget.innerHTML = filterConfig
    .map((configItem) => {
      return `<label class='map__filter-row' for='filter-checkbox-${configItem.rule}'>${configItem.name}
      <input class='map__filter-input' id='filter-checkbox-${configItem.rule}' type='checkbox' checked='checked' name='${configItem.rule}'>
    </label>
    `;
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

    const styleElement = getFilterStyleElement();

    const formCSSRules = Object.entries(formData)
      .map(([rule, checked]) => {
        return `[alt~="${rule}"] {
            opacity: ${checked ? `1` : `0.2`}
          }`;
      })
      .join("");
    styleElement.innerHTML = formCSSRules;
  });
}

/**
 * create and/or get filter-style style sheet
 */
function getFilterStyleElement() {
  let styleElement = document.querySelector("#filter-style");
  if (!styleElement) {
    styleElement = document.createElement("style");
    styleElement.id = "filter-style";
    document.head.appendChild(styleElement);
  }
  return styleElement;
}

module.exports = {
  populateFilterHTML,
  filterClickHandler,
};
