const popups = require("./popups");

/**
 * Creates list of animals for sidebar. List has buttons to owner & location.
 *
 * @param {*} animals
 */
function populateAnimalList(animals) {
  const animalListWrapper = document.getElementById("animal-list");
  const animalListHTML = animals
    .sort(function (a, b) {
      if (a.name < b.name) {
        return -1;
      }
      if (a.name > b.name) {
        return 1;
      }
      return 0;
    })
    .map((animal) => {
      const sa = animal.staysAt;
      const o = animal.owner;
      return `<li class='map__list-item'>
      ${popups.animalBtn(animal)} 
      ${o ? `van ${popups.ownerBtn(o)}` : ``}
      ${sa ? `verblijft te ${popups.staysAtBtn(sa)}` : ``}
    </li>`;
    })
    .join(``);
  animalListWrapper.innerHTML = animalListHTML;
}

module.exports = {
  populateAnimalList,
};
