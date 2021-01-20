const models = {};

/**
 * creates (further) hydrated and functionalized Animal, Guest, Shelter, Vet and Owner instances.
 *
 * @param {*} baseData API results
 * @returns models
 */
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
    return `${location.protocol}//${location.host}/${this.pluralType}/${this.id}${editPostfix}`;
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
  } // end constructor

  get fullName() {
    const n = !!this.contact.name ? this.contact.name : "",
      p = !!this.contact.prefix ? this.contact.prefix : "",
      s = !!this.contact.surname ? this.contact.surname : "";
    return `${n} ${p} ${s}`.trim();
  }

  /**
   * search all animals by this.id matching animal foreign key like owner_id
   *
   * @readonly
   * @memberof LocatedEntity
   * @returns Array<Animal>
   */
  get animals() {
    const foreignKey = `${this.type}_id`;
    const foundAnimals = models.animals.filter((animal) => {
      return animal[foreignKey] === this.id;
    });
    return foundAnimals || [];
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

  get animalsOnSite() {
    return this.animals;
  }
}

class Shelter extends LocatedEntity {
  constructor(config, locations) {
    super("shelter", config, locations);
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

  get animalsOnSite() {
    return []; // animals never registered as with vet
  }
}

class Owner extends LocatedEntity {
  constructor(config, locations) {
    super("owner", config, locations);
  }

  get animalsOnSite() {
    return this.animals.filter((ownedAnimals) => {
      return ownedAnimals.staysAt.location.uuid === this.location.uuid;
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

  static find(animalId) {
    const animalIdInt = Number(animalId);
    const possibleFound = models.animals.find((animal) => {
      return animalIdInt === animal.id;
    });
    if (!possibleFound) {
      throw new Error(`no animal found with id ${animalIdInt}`);
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
  get animals() {
    throw new Error("accessing animals... of animals");
    return;
  }

  get abuseConsolidatedText() {
    return this.abused && this.witnessed_abuse
      ? `Gezien en meegemaakt`
      : this.abused
      ? `Meegemaakt`
      : this.witnessed_abuse
      ? `Gezien`
      : `Geen`;
  }
  /**
   * helper for staysAt
   * returns
   *
   * @memberof Animal
   */
  get staysAtMeta() {
    if (this.guest_id !== null) {
      return {
        searchModel: "guests",
        foreignId: this.guest_id,
      };
    }
    if (this.shelter_id !== null) {
      return {
        searchModel: "shelters",
        foreignId: this.shelter_id,
      };
    }
    return {
      searchModel: "owners",
      foreignId: this.owner_id,
    };
  }

  /**
   * if guest id exists, find in guests for match; same for shelter.
   * else, assume still at owner.
   *
   * @readonly
   * @memberof Animal
   */
  get staysAt() {
    const meta = this.staysAtMeta;
    const staysAtLoc = models[meta.searchModel].find((model) => {
      return model.id === meta.foreignId;
    });
    if (!staysAtLoc) {
      return null;
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
