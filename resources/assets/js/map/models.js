//const rawData = require("./data");
const models = {};

/**
 * async func zodat debug data als laatste geconsoled wordt
 *
 */
function debugDataAlsLaatste(data, logSet) {
  return new Promise((succes, fail) => {
    try {
      setTimeout(() => {
        Object.entries(data).forEach(([setName, dataSet]) => {
          if (!logSet.includes(setName) && logSet !== setName) return;
          const print = [dataSet[0], dataSet[1], dataSet[2]];
          console.table(print);
        });
      }, 50);
      succes();
    } catch (e) {
      fail(e);
    }
  });
}

function createModels() {
  const addresses = baseData.addresses;

  models.animals = baseData.animals.map((baseAnimal) => {
    return new Animal(baseAnimal);
  });

  models.guests = baseData.guests.map((baseGuest) => {
    return new Guest(baseGuest, addresses);
  });
  models.shelters = baseData.shelters.map((baseshelter) => {
    return new Shelter(baseshelter, addresses);
  });
  models.vets = baseData.vets.map((baseVet) => {
    return new Vet(baseVet, addresses);
  });
  models.owners = baseData.owners.map((baseOwner) => {
    return new Owner(baseOwner, addresses);
  });

  debugDataAlsLaatste(models, ["animals"]);
}

/**
 * Shared methods of LocatedEntity and Animal.
 *
 * @class MayaModel
 */
class MayaModel {
  constructor(type) {
    if (typeof type !== "string") {
      throw new Error("Wrong 'type' of the type param.");
    }
    if (!type) {
      throw new Error("please specifify a type to the MayaModel super func.");
    }
    this._type = type;
  }
  get pluralType() {
    return this.type + "s";
  }

  get type() {
    return this._type;
  }

  /**
   * Creates a route to a maya-single page.
   *
   * @param {*} instanceId
   * @param {*} edit bool. Get the view or the edit route.
   * @returns route to Maya
   * @memberof LocatedEntity
   */
  mayaRoute(edit = false) {
    if (typeof edit !== "boolean") {
      throw new Error("wrong type of edit param ");
    }

    const editPostfix = edit ? `/edit` : "";
    return `${location.host}/${this.pluralType}/${this.id}${editPostfix}`;
  }
}

/**
 * Abstract Class. Only use as base Class.
 */
class LocatedEntity extends MayaModel {
  constructor(type, config, addresses) {
    super(type);
    if (!config.hasOwnProperty("name")) {
      throw new Error(`name forgotten`);
    }
    if (!config.hasOwnProperty("id")) {
      throw new Error(`id forgotten`);
    }
    if (!config.hasOwnProperty("address_id")) {
      throw new Error(`${config.name} heeft geen address_id`);
    }
    this.id = config.id;
    this.name = config.name;
    this.animals = [];
    try {
      this.location = addresses.find((address) => address.uuid === config.address_id); // potential memory leak, messes with garbage collection?
    } catch (error) {
      throw new Error(`${config.name} location niet gevonden in _locations. ${error.message}`);
    }
    if (config.text) {
      this.text = config.text;
    }
  }

  static find() {
    throw new Error("using find method of LocatedEntity, please implement in child");
  }
  get hasAnimals() {
    return this.animals.length > 0;
  }
  is(type) {
    return this.type === type;
  }
  animalOnSite() {
    return [];
  }
}

class Guest extends LocatedEntity {
  constructor(config, locations) {
    super("guest", config, locations);
  }
  get animals() {
    return models.animals.filter((animal) => {
      return animal.locationType === "guest" && animal.locationId === this.id;
    });
  }
  get animalsOnSite() {
    return this.animals;
  }
}

class Shelter extends LocatedEntity {
  constructor(config, locations) {
    super("shelter", config, locations);
  }
  get animals() {
    return models.animals.filter((animal) => {
      return animal.locationType === "shelter" && animal.locationId === this.id;
    });
  }
  get animalsOnSite() {
    return this.animals;
  }
}

class Vet extends LocatedEntity {
  constructor(config, locations) {
    super("vet", config, locations);
  }
  static find(vetId) {
    const possibleFound = models.vets.find((vet) => vetId === vet.id);
    if (!possibleFound) {
      throw new Error(`no Vet found with id ${vetId}`);
    }
    return possibleFound;
  }
  get animals() {
    return models.animals.filter((animal) => {
      return animal.vetId === this.id;
    });
  }
  get animalsOnSite() {
    return []; // animals never registered as with vet
  }
}

class Owner extends LocatedEntity {
  constructor(config, locations) {
    super("owner", config, locations);
  }
  get animals() {
    return models.animals.filter((animal) => {
      return animal.ownerId === this.id;
    });
  }
  get animalsOnSite() {
    return this.animals.filter((ownedAnimals) => {
      return ownedAnimals.staysAt.id === this.id;
    });
  }
}

class Animal extends MayaModel {
  constructor(config) {
    super("animal");
    for (let a in config) {
      this[a] = config[a];
    }
  }

  /**
    vetId: "v-1",
    ownerId: "e-1",
    locationType: "guest",
 */
  static find(animalId) {
    const possibleFound = models.animals.find((animal) => animalId === animal.id);
    if (!possibleFound) {
      throw new Error(`no animal found with id ${animalId}`);
    }
    return possibleFound;
  }
  get vet() {
    if (this.vet_id === null) return null;
    return models.vets.find((vet) => this.vet_id === vet.id);
  }
  get owner() {
    if (this.owner_id === null) return null;
    return models.owners.find((owner) => this.owner_id === owner.id);
  }
  get staysAt() {
    console.warn("argh!");
    throw new Error("ee");
    // if (this.locationType === "guest") {
    //   return models.guests.find((guest) => guest.id === this.locationId);
    // }
    // if (this.locationType === "shelter") {
    //   return models.shelters.find((shelter) => shelter.id === this.locationId);
    // }
  }
  get location() {
    return this.staysAt ? this.staysAt.location : null;
  }
}

createModels();

module.exports = {
  ...models,
  MayaModel,
  LocatedEntity,
  Animal,
  Vet,
  Guest,
  Shelter,
  Owner,
};
