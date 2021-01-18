/**
 * returns button linked to animal view.
 * @param {model} animal
 */
function animal(animal) {
  return `<button 
    data-action='open-animal-dialog' 
    data-id='${animal.id}'
    class='map__link-style-button map__link-style-button--animal'>
    ${animal.name}
    </button>`;
}

/**
 * returns button linked to owner marker
 * @param {model} owner
 */
function owner(owner) {
  return `<button 
    data-action='goto-marker' 
    data-id='${owner.id}'
    class='map__link-style-button map__link-style-button--goto-marker map__link-style-button--owner'>
    ${owner.name}
    </button>`;
}

/**
 * return button linked to animal's location button
 * @param {model} staysAt
 */
function staysAt(staysAt) {
  return `<button 
    data-action='goto-marker' 
    data-id='${staysAt.id}'
    class='map__link-style-button map__link-style-button--goto-marker map__link-style-button--stays-at'>
    ${staysAt.name}
    </button>`;
}

/**
 * returns button linked to vet marker
 * @param {model} vet
 */
function vet(vet) {
  return `<button 
      data-action='open-vet-dialog' 
      data-id='${vet.id}'
      class='map__link-style-button map__link-style-button--vet'>
      ${vet.name}
      </button>`;
}

module.exports = {
  animal,
  owner,
  staysAt,
  vet,
};
