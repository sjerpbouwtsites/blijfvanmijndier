const rawData = require("./data");

function createDummyData() {
  dummyData.animals = rawData.animals.map((baseAnimal) => {
    return new Animal(baseAnimal);
  });
  dummyData.guests = rawData.guests.map((baseGuest) => {
    return new Guest(baseGuest, rawData.locations);
  });
  dummyData.pensions = rawData.pensions.map((basePension) => {
    return new Pension(basePension, rawData.locations);
  });
  dummyData.vets = rawData.vets.map((baseVet) => {
    return new Vet(baseVet, rawData.locations);
  });
  dummyData.owners = rawData.owner.map((baseOwner) => {
    return new Owner(baseOwner, rawData.locations);
  });
  return dummyData;
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
    return dummyData.animals.filter((animal) => {
      return animal.locationType === "guest" && animal.locationId === this.id;
    });
  }
  get animalsOnSite() {
    return this.animals;
  }
}

class Pension extends LocatedEntity {
  constructor(config, locations) {
    super(config, locations);
    this.type = "pension";
  }
  get animals() {
    return dummyData.animals.filter((animal) => {
      return animal.locationType === "pension" && animal.locationId === this.id;
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
    return dummyData.vets.find((vet) => vetId === vet.id);
  }
  get animals() {
    return dummyData.animals.filter((animal) => {
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
    return dummyData.animals.filter((animal) => {
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
    return dummyData.animals.find((animal) => animalId === animal.id);
  }
  get vet() {
    return dummyData.vets.find((vet) => this.vetId === vet.id);
  }
  get owner() {
    return dummyData.owners.find((owner) => this.ownerId === owner.id);
  }
  get staysAt() {
    if (this.locationType === "guest") {
      return dummyData.guests.find((guest) => guest.id === this.locationId);
    }
    if (this.locationType === "pension") {
      return dummyData.pensions.find((pension) => pension.id === this.locationId);
    }
  }
  get location() {
    return this.staysAt ? this.staysAt.location : null;
  }
}

const dummyData = {
  animals: [],
  guests: [],
  pensions: [],
  vets: [],
  owners: [],
};

module.exports = dummyData;
