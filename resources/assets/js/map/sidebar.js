const popups = require("./popups");
var BEMMapper = require("./util").BEMMapper;
const filterInit = require("./filter").init;
const Animal = require("./models").Animal;

/**
 * aside section render func.
 *
 * @param {string} title
 * @param {string} contentsHTML
 * @returns {string}
 */
function createMapSection(title, contents) {
  const sluggedTitle = title.replace(/[/W\s]/g, "-").toLowerCase();
  return (section = `
    <section id='section-${sluggedTitle}' class='map-aside__section'>
      <header class='map-aside__header'>
        <h2 class='${BEMMapper("map-aside__heading", "2")}'>
          ${title} ${popups.textBtn(title)}
        </h2>
      </header>        
      <div id='body-${sluggedTitle}' class='map-aside__body'>${contents}</div>
    </section>`);
}

function prepareAsideHTML() {
  const aside = document.getElementById("map-aside");
  const section1 = createMapSection("Dieren", "");
  const section2 = createMapSection("Filter", "");
  aside.innerHTML = `
    ${section1}
    <div class="divider div-transparent"></div>
    ${section2}
  `;
}

function init(meta) {
  prepareAsideHTML();
  const animals = Animal.all;
  populateAnimalList(animals);
  addAnimalListEventHandlers(animals);
  filterInit(meta);
}

/**
 * Creates list of animals for sidebar. List has buttons to owner & location.
 *
 * @param {Animal} animals
 */
function populateAnimalList(animals) {
  const animalListWrapper = document.getElementById("body-dieren");
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
      return `<li class='map-aside__list-item animal-list__item'>
      ${popups.animalBtn(animal)} 
      ${o ? `${popups.ownerBtn(o)}` : ``}
      ${sa ? `${popups.staysAtBtn(sa)}` : ``}
    </li>`;
    })
    .join(``);

  animalListWrapper.innerHTML = `
    <input class='map-aside__input map-aside__input--text-field' type='text' tabindex='1' id='animal-list-search' placeholder='Zoek...'>    
    <ul class='map-aside__list animal-list'>
      ${animalListHTML}
    </ul>
  `;
}

function addAnimalListEventHandlers(animals) {
  let waitingForEndOfTyping;
  document
    .getElementById("animal-list-search")
    .addEventListener("keyup", (e) => {
      clearTimeout(waitingForEndOfTyping);
      waitingForEndOfTyping = setTimeout(() => {
        searchAnimalList(e, animals);
      }, 250);
    });
  document.getElementById("animal-list-search").focus();
}

function undoSearchAnimal() {
  document.getElementById("animal-list-search").value = "";
  document.getElementById("animal-list-search").blur();
  Array.from(
    document.querySelectorAll(".animal-list__item")
  ).forEach((animalListItem) => animalListItem.classList.remove("hidden"));
  Array.from(
    document.querySelectorAll(".animal-list .map__link-style-button")
  ).forEach((button) => {
    button.removeAttribute("tabindex");
  });
  document.getElementById("animal-list").scrollTop = 0;
}

function searchAnimalList(event, animals) {
  if (event.key === "Escape") {
    undoSearchAnimal();
    return;
  }
  const seekFor = event.target.value.toLowerCase();
  const passingAnimalIds = animals
    .filter((animal) => {
      const oName = animal.owner ? animal.owner.fullName : "";
      const sa = animal.staysAt;
      const lName = sa ? sa.fullName : "";
      const seekIn = `${animal.breed.toLowerCase()} ${animal.animal_type.toLowerCase()} ${animal.name.toLowerCase()} ${oName.toLowerCase()} ${lName.toLowerCase()}`;
      if (seekIn.includes(seekFor)) {
      }
      return seekIn.includes(seekFor);
    })
    .map((animal) => animal.id.toString());

  // now filter in the animal list.
  const animalItems = Array.from(
    document.querySelectorAll(".animal-list__item")
  );
  const passedAnimals = [];
  const droppedAnimals = [];
  animalItems.forEach((animalItem) => {
    const localAnimalId = animalItem
      .querySelector(".map__link-style-button--animal")
      .getAttribute("data-id");
    if (passingAnimalIds.includes(localAnimalId)) {
      passedAnimals.push(animalItem);
    } else {
      droppedAnimals.push(animalItem);
    }
  });

  passedAnimals.forEach((animalItem, animalItemIndex) => {
    animalItem.classList.contains("hidden") &&
      animalItem.classList.remove("hidden");
    animalItem
      .querySelectorAll(".map__link-style-button")
      .forEach((button, buttonIndex) => {
        const tabIndex = animalItemIndex * 3 + buttonIndex + 1;
        button.setAttribute("tabindex", tabIndex);
      });
  });
  droppedAnimals.forEach((animalItem) => {
    !animalItem.classList.contains("hidden") &&
      animalItem.classList.add("hidden");
    animalItem.querySelectorAll(".map__link-style-button").forEach((button) => {
      button.removeAttribute("tabindex");
    });
  });
}

module.exports = {
  init,
};
