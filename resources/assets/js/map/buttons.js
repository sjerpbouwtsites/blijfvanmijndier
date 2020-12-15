function animalButtonHTML(animal) {
  return `<button 
    data-action='open-animal-dialog' 
    data-id='${animal.id}'
    class='map__link-style-button map__link-style-button--animal'>
    ${animal.title}
    </button>`;
}

function ownerButtonHTML(owner) {
  return `<button 
    data-action='goto-marker' 
    data-id='${owner.id}'
    class='map__link-style-button map__link-style-button--goto-marker map__link-style-button--owner'>
    ${owner.title}
    </button>`;
}

function staysAtButtonHTML(staysAt) {
  return `<button 
    data-action='goto-marker' 
    data-id='${staysAt.id}'
    class='map__link-style-button map__link-style-button--goto-marker map__link-style-button--stays-at'>
    ${staysAt.title}
    </button>`;
}

function vetButtonHTML(vet) {
  return `<button 
      data-action='open-vet-dialog' 
      data-id='${vet.id}'
      class='map__link-style-button map__link-style-button--vet'>
      ${vet.title}
      </button>`;
}

module.exports = {
  animalButtonHTML,
  ownerButtonHTML,
  staysAtButtonHTML,
  vetButtonHTML,
};
