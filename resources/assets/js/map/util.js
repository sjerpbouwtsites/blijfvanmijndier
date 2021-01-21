const { closeAllDialogsPopupsIframesEscape } = require("./popups");

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

/**
 * Loops over a collection of elements and adds / removes the styling class
 *
 * @param {Array<HTMLElement>|<HTMLElement} nodes arrayed or single element.
 * @param {boolean} [showNodes=true] the boolean.
 * @param {string} [hideClass='blurred'] optional hideClass to be written.
 */
function showHideNodes(nodes, showNodes = true, hideClass = "blurred") {
  try {
    if (typeof nodes.length !== "undefined" && nodes.length === 0) {
      return "";
    }
    const workNodes = typeof nodes.length !== "undefined" && typeof nodes.map !== "undefined" ? nodes : [nodes];
    workNodes.map((node) => {
      const isHidden = node.classList.contains(hideClass);
      if (showNodes && isHidden) {
        node.classList.remove(hideClass);
      } else if (!showNodes && !isHidden) {
        node.classList.add(hideClass);
      }
      return node;
    });
  } catch (err) {
    console.error(`show hide nodes error with nodes: ${typeof nodes} nodes.length`);
    console.log(nodes);
    throw new Error(err);
  }
}

module.exports = {
  BEMMapper,
  toCamelCase,
  getMarkerByIdAndType,
  showHideNodes,
};
