const rawData = require("./data");
const models = {};

function createModels() {
  models.animals = rawData.animals.map((baseAnimal) => {
    return new Animal(baseAnimal);
  });
  models.guests = rawData.guests.map((baseGuest) => {
    return new Guest(baseGuest, rawData.locations);
  });
  models.shelters = rawData.shelters.map((baseshelter) => {
    return new Shelter(baseshelter, rawData.locations);
  });
  models.vets = rawData.vets.map((baseVet) => {
    return new Vet(baseVet, rawData.locations);
  });
  models.owners = rawData.owner.map((baseOwner) => {
    return new Owner(baseOwner, rawData.locations);
  });
}

class LocatedEntity {
  constructor(config, locations) {
    if (!config.hasOwnProperty("title")) {
      throw new Error(`title forgotten`);
    }
    if (!config.hasOwnProperty("id")) {
      throw new Error(`id forgotten`);
    }
    if (!config.hasOwnProperty("location")) {
      throw new Error(`${config.title} heeft geen location`);
    }
    this.id = config.id;
    this.title = config.title;
    this.animals = [];
    try {
      this.location = locations.find((loc) => loc.id === config.location); // potential memory leak, messes with garbage collection?
    } catch (error) {
      throw new Error(`${config.title} location niet gevonden in _locations. ${error.message}`);
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
    super(config, locations);
    this.type = "guest";
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
    super(config, locations);
    this.type = "shelter";
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
    super(config, locations);
    this.type = "vet";
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
    super(config, locations);
    this.type = "owner";
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

class Animal {
  constructor(config) {
    this.type = "animal";
    for (let a in config) {
      this[a] = config[a];
    }
  }
  static find(animalId) {
    const possibleFound = models.animals.find((animal) => animalId === animal.id);
    if (!possibleFound) {
      throw new Error(`no animal found with id ${animalId}`);
    }
    return possibleFound;
  }
  get vet() {
    return models.vets.find((vet) => this.vetId === vet.id);
  }
  get owner() {
    return models.owners.find((owner) => this.ownerId === owner.id);
  }
  get staysAt() {
    if (this.locationType === "guest") {
      return models.guests.find((guest) => guest.id === this.locationId);
    }
    if (this.locationType === "shelter") {
      return models.shelters.find((shelter) => shelter.id === this.locationId);
    }
  }
  get location() {
    return this.staysAt ? this.staysAt.location : null;
  }
}

createModels();

module.exports = {
  ...models,
  LocatedEntity,
  Animal,
  Vet,
  Guest,
  Shelter,
  Owner,
};
