/**
 * turns-string-like this to turnsStringLike
 *
 * @param {*} string
 * @returns camelCase.
 */
function toCamelCase(string) {
  return string
    .split("-")
    .map((word, index) => {
      return index > 0 ? word[0].toUpperCase() + word.substring(1, word.length) : word;
    })
    .join("");
}

function getMarkerByIdAndType(id, type) {
  const marker = document.getElementById(`marker-${type}-id-${id}`);
  if (!marker) {
    throw new Error(`Marker for id ${id}, id attr val marker-${type}-id-${id}, not found`);
  }
  return marker;
}

/**
 * maps over selectors and adds modifier to end and returns join.
 * @param {Array<string>|string} selectors
 * @param {string} modifier
 * @returns {string} Bemmed CSS selectors
 */
function BEMMapper(selectors, modifier) {
  const workSelectors = Array.isArray(selectors) ? selectors : selectors.split(" ");
  return workSelectors.map((selector) => `${selector} ${selector}--${modifier}`).join(" ");
}

module.exports = {
  BEMMapper,
  toCamelCase,
  getMarkerByIdAndType,
};
