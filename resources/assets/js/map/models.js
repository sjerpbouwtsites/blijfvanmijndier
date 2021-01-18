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
        const useSet = !!data ? data : models;

        Object.entries(useSet).forEach(([setName, dataSet]) => {
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

function create(baseData) {
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
  return models;
  //  debugDataAlsLaatste(models, ["animals"]);
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

    const addressKeys = ["phone_number", "prefix", "surname", "email_address", "name", "website", "contact_person"];

    this.name = config.name;
    this.contact = {};
    Object.keys(config).forEach((key) => {
      if (key === "address_id") return;
      if (addressKeys.includes(key)) {
        this.contact[key] = config[key];
        return;
      }
      this[key] = config[key];
    });

    this.animals = [];
    try {
      const l = addresses.find((address) => address.uuid === config.address_id); // potential memory leak, messes with garbage collection?
      delete l.manual_geolocation;
      delete l.updated_at;
      this.location = l;
    } catch (error) {
      throw new Error(`${config.name} location niet gevonden in _locations. ${error.message}`);
    }
    if (config.text) {
      this.text = config.text;
    }
  }

  get fullName() {
    const n = !!this.contact.name ? this.contact.name : "",
      p = !!this.contact.prefix ? this.contact.prefix : "",
      s = !!this.contact.surname ? this.contact.surname : "";
    return `${n} ${p} ${s}`.trim();
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

    // let log = ["name", "guest_id", "owner_id", "shelter_id", "placement_date"];
    // const logData = {};
    // log.forEach((logNaam) => {
    //   logData[logNaam] = config[logNaam];
    // });
    // console.dir(logData);
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

  /**
   * helper for staysAt
   * returns
   *
   * @memberof Animal
   */
  staysAtMeta() {
    if (this.guest_id !== null) {
      return ["guest", "guests", "guest_id", this.guest_id];
    }
    if (this.shelter_id !== null) {
      return ["shelter", "shelters", "shelter_id", this.shelter_id];
    }
    if (this.owner_id !== null) {
      return ["owner", "owners", "owner_id", this.owner_id];
    }
    console.error("guest id, shelter id and owner id not set.");
    console.error(this);
    return [null, null];
  }

  /**
   * if guest id exists, find in guests for match; same for shelter.
   * else, assume still at owner.
   *
   * @readonly
   * @memberof Animal
   */
  get staysAt() {
    const [modelSingular, modelPlural, modelIdKey, modelId] = this.staysAtMeta();
    if (modelSingular === null) return null;
    const modelsToSearch = models[modelPlural];
    const staysAtLoc = modelsToSearch.find((model) => {
      return model[modelIdKey] === this[modelId];
    });

    if (!staysAtLoc) {
      console.table(models[`${modelsToSearch}s`]);
      throw new Error(`unfound staysAt location! ${modelsToSearch} ${modelId}`);
    }
    return staysAtLoc;
  }
  get location() {
    return this.staysAt ? this.staysAt.location : null;
  }
}

module.exports = {
  create,
  MayaModel,
  LocatedEntity,
  Animal,
  Vet,
  Guest,
  Shelter,
  Owner,
};
