

/**
 * intialises map on roelofarendsveen and returns leaflet map instance.
 */
function createMap() {
  const goudaMapConfig = {
    lat: 52.2,
    lon: 4.6,
    zoom: 9,
  };

  // initialize the map on the "map" div with a given center and zoom
  var leafletMap = L.map("leaflet-map", {
    center: [goudaMapConfig.lat, goudaMapConfig.lon],
    zoom: goudaMapConfig.zoom,
  });

  L.tileLayer(
    "https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoic2plcnAtdmFuLXdvdWRlbiIsImEiOiJjajh5NmExaTAxa29iMzJwbDV0eXF4eXh4In0.HVBgF1SbusJzMwmjHcHS2w",
    {
      attribution:
        '<span id="map-info"></span> <strong>Door Sjerp van Wouden </strong>Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 18,
      id: "mapbox/streets-v11",
      tileSize: 512,
      zoomOffset: -1,
      accessToken: "your.mapbox.access.token",
    }
  ).addTo(leafletMap);
  return leafletMap;
}

function addInteractive(){
  dataActionEventHandler();
  closeDialogClickHandler();
  populateFilterHTML();
  filterClickHandler();
}

const filterConfig = [
  {
    name: 'dierenartsen',
    rule: 'is-vet'
  },
  {
    name: 'gastgezinnen',
    rule: 'is-guest'
  },
  {
    name: 'pensionnen',
    rule: 'is-pension',
  },
  {
    name: 'eigenaren',
    rule: 'is-owner'
  },
  {
    name: 'dier aanwezig',
    rule: 'has-animals'
  },
  {
    name: 'meerdere dieren aanwezig',
    rule: 'multiple-animals'
  }
];

function populateFilterHTML(){
  const printTarget = document.getElementById('map-filters');

  printTarget.innerHTML = filterConfig.map(configItem =>{
    return `<label class='map__filter-row' for='filter-checkbox-${configItem.rule}'>${configItem.name}
      <input class='map__filter-input' id='filter-checkbox-${configItem.rule}' type='checkbox' checked='checked' name='${configItem.rule}'>
    </label>
    `;
  }).join('');
}

function filterClickHandler(){
  const filterForm = document.getElementById('map-filters');
  filterForm.addEventListener('change', function(event){
    console.log(event);
  });
}

function populateAnimalList(animals) {
  const printTarget = document.getElementById('animal-list');
  const animalListHTML = animals
  .sort(function(a, b){
    if(a.title < b.title) { return -1; }
    if(a.title > b.title) { return 1; }
    return 0;
})
  .map(animal=>{
    return `<li class='map__list-item'>
      ${animalButtonHTML(animal)} van
      ${ownerButtonHTML(animal.owner)}
       verblijft te
      ${staysAtButtonHTML(animal.staysAt)}
    </li>`;
  }).join(``);
  printTarget.innerHTML = animalListHTML;
}

function animalButtonHTML(animal){
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

function staysAtButtonHTML(staysAt){
  return `<button 
    data-action='goto-marker' 
    data-id='${staysAt.id}'
    class='map__link-style-button map__link-style-button--goto-marker map__link-style-button--stays-at'>
    ${staysAt.title}
    </button>`;  
}

function vetButtonHTML(vet){
    return `<button 
      data-action='open-vet-dialog' 
      data-id='${vet.id}'
      class='map__link-style-button map__link-style-button--vet'>
      ${vet.title}
      </button>`;  
}

function dataActionEventHandler(){

  const knownActions = ['open-animal-dialog', 'open-vet-dialog', 'goto-marker'];
  document.body.addEventListener('click', function(event){
    if (!event.target.hasAttribute('data-action')) {
      return;
    }
    const action = event.target.getAttribute('data-action');
    if (!knownActions.includes(action)) {
      alert(`unknown action: ${action}`);
      return;
    }

    const camelcasedAction = action
      .split('-')
      .map((word, index) =>  {
        return index > 0 
          ? word[0].toUpperCase() + word.substring(1, word.length)
          : word
      })
      .join('');
    dataActionCallbacks[camelcasedAction](event);    
  });  
}

dataActionCallbacks = {
  openAnimalDialog(event){
    closeLeafletPopupWhenOpen();
    const animalId = event.target.getAttribute('data-id');
    const animal = Animal.find(animalId);
    document.getElementById('map-own-dialog')
    .classList.add('map__dialog--open');
    document.getElementById('dialog-print-target').innerHTML = `
      <h3 class='map__dialog-title'>${animal.title}</h3>
      <p class='map__dialog-text'>${animal.text}</p>
      <div class='map__dialog-button-group'>
        ${(animal.vet 
          ? `<div class='map_dialog-button-row'>Arts: ${vetButtonHTML(animal.vet)}</div>`
          : '')
        }
        ${(animal.staysAt 
          ? `<div class='map_dialog-button-row'>Verblijft te: ${staysAtButtonHTML(animal.staysAt)}</div>` 
          : '')
        }
      </div>
    `;
  },
  openVetDialog(event){
    document.getElementById('map-own-dialog').classList.contains('map__dialog--open') && document.getElementById('map-own-dialog').classList.remove('map__dialog--open');    
    const vetId = event.target.getAttribute('data-id');
    const vet = Vet.find(vetId);
    document.querySelector(`[alt~='id-${vet.id}']`).click();    
  },  
  gotoMarker(event){
    document.getElementById('map-own-dialog').classList.contains('map__dialog--open') && document.getElementById('map-own-dialog').classList.remove('map__dialog--open');
    const targetMarker = event.target.getAttribute('data-id');
    document.querySelector(`[alt~='id-${targetMarker}']`).click();
  }
}


function closeDialogClickHandler(){
  document.getElementById('map-dialog-close')
  .addEventListener('click', function(){
    document.getElementById('map-own-dialog') &&
    document.getElementById('map-own-dialog').classList.remove('map__dialog--open');
  });
}

function closeLeafletPopupWhenOpen(){
  const mightBeAnchorElement = document.querySelector('.leaflet-popup-close-button');
  if (mightBeAnchorElement) mightBeAnchorElement.click();
}

/**
 * dummy function to get locations
 * @returns Promise always succes with dummydata
 */
function getLocations() {
  return new Promise((locationSucces, locationFailure) => {
    setTimeout(() => {
      return locationSucces(createDummyData());
    }, 250);
  });
}

/**
 * create alt attribute which is a general styling & identifying attribute in this app for markers.
 * loops over list of conditions
 * @param {*} locatedEntity
 */
function maakAlt(locatedEntity) {
  return [

 {
      key: "type",
      check: (str) => {
        return str === "vet";
      },
      res: "color-red",
    },
    {
      key: "type",
      check: (str) => {
        return str === "pension";
      },
      res: "color-purple",
    },
    {
      key: "type",
      check: (str) => {
        return str === "guest";
      },
      res: "color-green",
    },
    {
      key: "type",
      check: str => {
        return str === "owner";
      },
      res: "color-blue",
    },
    {
      key: "animals",
      check: animals => {
        return animals.length > 0;
      },
      res: "has-animals"
    },
    {
      key: "animals",
      check: animals => {
        return animals.length > 1;
      },
      res: "multiple-animals"
    }
  ]
    .map((condition) => {
      const locationVal = locatedEntity[condition.key];
      return condition.check(locationVal) ? condition.res : "";
    })
    .filter(a=>a)
    .join(" ") + ` is-${locatedEntity.type} id-${locatedEntity.id}`;
}

/**
 * callback for locations.map
 * refers maakAlt
 * creates marker and binds to global mapInstance
 * bindspopup.
 * @param {*} locatedEntity
 */
function locationMapper(locatedEntity) {

  const options = {
    alt: maakAlt(locatedEntity),
  };
  
  const marker = L.marker([locatedEntity.location.lat, locatedEntity.location.lon], options).addTo(leafletMap);

  marker.bindPopup(
    `<div class='bvmd-popup'>
      ${markerHTML.header(locatedEntity)}
      <div class='bvmd-popup__brood'>
        <p class='bvmd-popup__tekst'>
          ${locatedEntity.text}
        </p>
        ${markerHTML.address(locatedEntity)}
        ${markerHTML.animalList(locatedEntity)}
      </div>

      <footer class='bvmd-popup__voet'>
      </footer>

    </div>`
  );
}

const markerHTML = {
  header(locatedEntity) {
    return `<header class='bvmd-popup__header'>
    <h3 class='bvmd-popup__header-links'>
      ${locatedEntity.title}
    </h3>
  </header>
  `
  },
  address(locatedEntity) {
    return `<div class='bvmd-popup__adres-outer'>
    <h4 class='bvmd-popup__inner-title'>Adres</h4>
    <address class='bvmd-popup__adres'>
      <ul class='bvmd-popup__adres-lijst'>
        <li class='bvmd-popup__adres-stuk'>${locatedEntity.location.straat} ${locatedEntity.location.huisnummer}</li>
        <li class='bvmd-popup__adres-stuk'>${locatedEntity.location.postcode} ${locatedEntity.location.plaatsnaam}</li>
      </ul>
    </address>
  </div>`;
  },
  animalList(locatedEntity){
    return  `${locatedEntity.hasAnimals ? `
    <div class='bvmd-popup__animal-list-outer'>
      <h4 class='bvmd-popup__inner-title'>Dieren</h4>
      <ol class='bvmd-popup__animal-list'>
        ${locatedEntity.animals.map(animal => {
          return locatedEntity.is('owner')
            ? this.animalListItemOwner(animal)
            : this.animalListItemSafeHouse(animal);
        }
        ).join(``)
      }
      </ol>
    </div>
    `: ''} ` ;
    },
  animalListItemOwner(animal) {
    return `
    <li class='bvmd-popup__animal-list-item'>
      ${animalButtonHTML(animal)} verblijft bij
      ${staysAtButtonHTML(animal.staysAt)}
    </li>
    `
  },
  animalListItemSafeHouse(animal) {
    return `
    <li class='bvmd-popup__animal-list-item'>
      ${animalButtonHTML(animal)} van 
      ${ownerButtonHTML(animal.owner)}
    </li>
    `
  }
}


function createDummyData(){
  dummyData.animals= _animals.map(baseAnimal => new Animal(baseAnimal));
  dummyData.guests= _guests.map(baseGuest => new Guest(baseGuest));
  dummyData.pensions= _pensions.map(basePension => new Pension(basePension));
  dummyData.vets= _vets.map(baseVet => new Vet(baseVet));
  dummyData.owners= _owner.map(baseOwner => new Owner(baseOwner));
  return dummyData;

}

class LocatedEntity {
  constructor(config){
    if (!config.hasOwnProperty('title')) {
      throw new Error(`title forgotten`);
    }
    if (!config.hasOwnProperty('id')) {
      throw new Error(`id forgotten`);
    }
    if (!config.hasOwnProperty('location')) {
      throw new Error(`${config.title} heeft geen location`);
    }
    this.id = config.id;
    this.title = config.title;
    this.animals = [];
    try {
      this.location = _locations.find(loc => loc.id === config.location);
    } catch (error) {
      throw new Error(`${config.title} location niet gevonden in _locations. ${error.message}`)
    }
    if(config.text) {
      this.text = config.text;
    }
  }

  get hasAnimals(){
    return this.animals.length > 0;
  }
  is(type){
    return this.type === type;
  }
  animalOnSite(){
    return [];
  }
}

class Guest extends LocatedEntity {
  constructor(config) {
    super(config);
    this.type = "guest";
  }
  get animals(){
    return dummyData.animals.filter(animal => {
      return animal.locationType === 'guest' && animal.locationId === this.id
    });
  }
  get animalsOnSite() {
    return this.animals;
  }
}

class Pension extends LocatedEntity {
  constructor(config) {
    super(config);
    this.type = "pension";
  }
  get animals(){
    return dummyData.animals.filter(animal => {
      return animal.locationType === "pension" && animal.locationId === this.id
    });
  }
  get animalsOnSite() {
    return this.animals;
  }
}

class Vet extends LocatedEntity {
  constructor(config) {
    super(config);
    this.type = "vet";
  }
  static find (vetId){
    return dummyData.vets.find(vet => vetId === vet.id);
  }
  get animals(){
    return dummyData.animals.filter(animal => {
      return animal.vetId === this.id
    });
  }
}

class Owner extends LocatedEntity {
  constructor(config) {
    super(config);
    this.type = "owner";
  }
  get animals(){
    return dummyData.animals.filter(animal => {
     return animal.ownerId === this.id
    });
  }
}

class Animal {
  constructor(config){
    this.type = 'animal';
    for (let a in config) {
      this[a] = config[a];
    }
  }
  static find (animalId){
    return dummyData.animals.find(animal => animalId === animal.id);
  }
  get vet(){
    return dummyData.vets.find(vet => this.vetId === vet.id)
  }
  get owner(){
    return dummyData.owners.find(owner => this.ownerId === owner.id);
  }
  get staysAt(){
    if (this.locationType === 'guest') {
      return dummyData.guests.find(guest => guest.id === this.locationId)
    }
    if (this.locationType === 'pension') {
      return dummyData.pensions.find(pension => pension.id === this.locationId)
    }
  }
  get location(){
    return this.staysAt ? this.staysAt.location : null
  }
}

const dummyData = {
  animals: [],
  guests: [],
  pensions: [],
  vets: [],
  owners: [],
};


class BsStraat {
  
  constructor() {
    this.counter = 0;
    this.straat = "straatnaam";
    this.huisnummer = 100;
    this.postcode= "1000BB";
    this.plaatsnaam= "Ons Dorp";  
  }
  make(){
    this.counter++;
    return {
      straat: this.straat,
      huisnummer: this.huisnummer + this.counter,
      postcode: this.postcode,
      plaatsnaam: this.plaatsnaam
    }
  }
};

const BsStraatInst = new BsStraat();

const _locations = [
  {
    lat: 52.090736,
    lon: 5.121420,
    id: 1,
    ...BsStraatInst.make(),
  },
  {
    lat: 53.201233,
    lon: 5.799913,
    ...BsStraatInst.make(),
    id: 2,
  },
  {
    lat: 53.219383,
    lon: 6.469502,
    ...BsStraatInst.make(),
    id: 3,
  },
  {
    lat: 52.792752,
    lon: 6.564228,
    ...BsStraatInst.make(),
    id: 4,
  },
  {
    lat: 52.3156,
    lon: 4.5876,
    ...BsStraatInst.make(),
    id: 5,
  },
  {
    lat: 51.985104,
    lon: 5.898730,
    ...BsStraatInst.make(),
    id: 6,
  },
  {
    lat: 51.690090,
    lon: 5.303690,
    ...BsStraatInst.make(),
    id: 7
  },
  {
    lat: 52.090936,
    lon: 5.191620,
    ...BsStraatInst.make(),
    id: 8,
  },
  {
    lat: 53.201433,
    lon: 5.792113,
    ...BsStraatInst.make(),
    id: 9,
  },
  {
    lat: 53.219183,
    lon: 6.566702,
    ...BsStraatInst.make(),
    id: 10,
  },
  {
    lat: 52.992952,
    lon: 6.564428,
    ...BsStraatInst.make(),
    id: 11,
  },
  {
    lat: 52.3136,
    lon: 4.6476,    
    ...BsStraatInst.make(),
    id: 12,
  },
  {
    lat: 51.995104,
    lon: 5.998730,
    ...BsStraatInst.make(),
    id: 13,
  },
  {
    lat: 51.720090,
    lon: 5.343690,
    ...BsStraatInst.make(),
    id: 14,
  },
  {
    lat: 52.118,
    lon: 4.987,
    ...BsStraatInst.make(),
    id: 15,
  },
  {
    lat: 52.387386,
    lon: 4.646219,
    ...BsStraatInst.make(),
    id: 16,
  },
  {
    lat: 52.641460,
    lon: 5.056810,
    ...BsStraatInst.make(),
    id: 17
  },
  {
    lat: 52.305691,
    lon: 4.862510,
    ...BsStraatInst.make(),
    id: 18,
  },
  {
    lat: 52.350784,
    lon: 5.264702,
    ...BsStraatInst.make(),
    id: 19,
  },
  {
    lat: 50.851368,
    lon: 5.690972,
    ...BsStraatInst.make(),
    id: 20
  },
  {
    lat: 50.951368,
    lon: 5.490972,
    ...BsStraatInst.make(),
    id: 21
  },
  {
    lat: 50.888172,
    lon: 5.979499,
    ...BsStraatInst.make(),
    id: 22
  },
  {
    lat: 50.908172,
    lon: 4.5979499,
    ...BsStraatInst.make(),
    id: 23
  },
  {
    lat: 52.221539,
    lon: 6.893662,
    ...BsStraatInst.make(),
    id: 24
  },
  {
    lat: 51.221539,
    lon: 6.953662,
    ...BsStraatInst.make(),
    id: 25
  },
  {
    lat: 53.053921,
    lon: 4.796050,
    ...BsStraatInst.make(),
    id: 26
  },
  {
    lat: 51.498795,
    lon: 3.610998,
    ...BsStraatInst.make(),
    id: 27
  },
  {
    lat: 51.398795,
    lon: 4.610998,
    ...BsStraatInst.make(),
    id: 28
  },
  {
    lat: 51.495795,
    lon: 3.610998,
    ...BsStraatInst.make(),
    id: 29
  },
  {
    lat: 51.398795,
    lon: 4.620998,
    ...BsStraatInst.make(),
    id: 30
  }
];

const _guests = [
  {
    title: "Familie de vries",
    text: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 1,
    id: 'g-1',
  },
  {
    title: "Familie de Sjaak",
    text: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 2,
    id: 'g-2'
  },
  {
    title: "Familie de Pineut",
    text: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 3,
    id: 'g-3'
  },
  {
    title: "Familie de Pisang",
    text: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 4,
    id: 'g-4'
  },
  {
    title: "Hendrik Anton Zeurstra",
    text: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 5,
    id: 'g-5',
  },  
  {
    title: "Tonnie B. Abbelveel",
    text: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 6,
    id: 'g-6'
  },  
];

const _vets = [  {
  title: "Doktor harry",
  text:
    "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    id: 'v-1',
    location: 7,
},
{
  title: "doktor Janssen",
  text:
    "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    id: 'v-2',    location: 8,
},
{
  title: "Doktor Snuffel",
  text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
  id: 'v-3',    location: 9,
},
{
  title: "Doktor Jaapstra",
  text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
  id: 'v-4',    location: 10,
},
{
  title: "Doktor Tuinstra",
  text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    location: 11,
  id: 'v-5'
},
{
  title: "Doktor Terpstra",
  text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    location: 12,
  id: 'v-6'
},
];

const _pensions = [{
  title: "Pension een",
  text:
    "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    location: 13,
    id: 'p-1',
},
{
  title: "Pension twee",
  text:
    "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    location: 18,
    id: 'p-2',
},
{
  title: "Pension 3",
  text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    location: 14,
  id: 'p-3',
},
{
  title: "Pension 4",
  text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
  id: 'p-4',    location: 15,
},
{
  title: "Pension 5",
  text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    location: 16,
  id: 'p-5'
},
{
  title: "Pension 6",
  text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    location: 17,
  id: 'p-6'
},];

const _owner = [
  {
    title: "Bertus Bever",
    text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    
    location: 19,
    id: 'e-1'
  },  
  {
    title: "Els Ezel",
    text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    
    location: 20,
    id: 'e-2'
  },  
  {
    title: "Tinus Turqoise",
    text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    
    location: 21,
    id: 'e-3'
  },  
  {
    title: "Machteld Mangaanknoop",
    text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    
    location: 22,
    id: 'e-4'
  },  
  {
    title: "Sebaldinus Sneekerburgertoren",
    text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    
    location: 23,
    id: 'e-5'
  },  
  {
    title: "Arie Achteropdefiets",
    text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    
    location: 24,
    id: 'e-6'
  },  
];

const _animals = [
  {
    title: "Kleine Wifwaf",
    text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    
    id: 'a-1',
    vetId: 'v-1',
    ownerId: 'e-1',
    locationType: 'guest',
    locationId: 'g-1'
  },
  {
    title: "Blub",
    text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    
    id: 'a-2',
    vetId: 'v-2',
    ownerId: 'e-2',
    locationType: 'pension',
    locationId: 'p-1'
  },
  {
    title: "Knabbel",
    text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    
    id: 'a-3',
    vetId: 'v-3',
    ownerId: 'e-3',
    locationType: 'guest',
    locationId: 'g-3'
  },
  {
    title: "Dikzak",
    text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    
    id: 'a-4',
    vetId: 'v-4',
    ownerId: 'e-4',
    locationType: 'pension',
    locationId: 'p-1'
  },
  {
    title: "Meur",
    text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    
    id: 'a-5',
    vetId: 'v-5',
    ownerId: 'e-5',
    locationType: 'guest',
    locationId: 'g-4'
  },
  {
    title: "Carola",
    text:      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",    
    id: 'a-6',
    vetId: 'v-6',
    ownerId: 'e-6',
    locationType: 'guest',
    locationId: 'g-5'
  },   
];


let leafletMap;

function init() {
  leafletMap = createMap();
  addInteractive();
  getLocations().then((dummyData) => {
    [...dummyData.guests, ...dummyData.vets, ...dummyData.pensions, ...dummyData.owners].map(locationMapper);
    populateAnimalList(dummyData.animals);
  });
}

init();
