/**
 * 'abstract' buttonHTML func.
 *
 * @param {*} buttonData animal, vet, etc instance.
 * @param array modifiers list of CSS BEM modifiers
 * @param {*} action function to be called
 * @returns
 */
function buttonBase(buttonData, modifiers, action) {
  if (buttonData === null) return ``;

  const modifierHTML = modifiers
    .map((mod) => {
      return `map__link-style-button--${mod}`;
    })
    .join("");

  return `<button 
    data-action='${action}' 
    data-id='${buttonData.id}'
    class='
      map__link-style-button 
      ${modifierHTML}
      '>
    ${buttonData.name}
    </button>`;
}

/**
 * returns button linked to animal view.
 * @param {model} animal
 */
function animal(animalData) {
  return buttonBase(animalData, ["animal"], "open-animal-dialog");
}

/**
 * returns button linked to owner marker
 * @param {model} ownerData
 */
function owner(ownerData) {
  return buttonBase(ownerData, ["owner", "goto-marker"], "goto-marker");
}

/**
 * return button linked to animal's location button
 * @param {model} staysAtData
 */
function staysAt(staysAtData) {
  return buttonBase(staysAtData, ["stays-at", "goto-marker"], "goto-marker");
}

/**
 * returns button linked to vet marker
 * @param {model} vetData
 */
function vet(vetData) {
  return buttonBase(vetData, ["vet"], "open-vet-dialog");
}

module.exports = {
  animal,
  owner,
  staysAt,
  vet,
};
