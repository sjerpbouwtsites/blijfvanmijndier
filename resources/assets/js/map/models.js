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
    return new Guest(baseGuest, addresses, baseData.meta);
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
  models.locations = baseData.locations.map((baseLocation) => {
    return new Location(baseLocation, addresses);
  });  

  console.log(models)

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
   * returns all models
   *
   * @readonly
   * @static
   * @memberof MayaModel
   */
  static get allMayaModels() {
    return Object.values(models);
  }

  get marker() {
    const r = document.getElementById(`marker-${this.type}-id-${this.id}`);
    if (!r)
      throw new Error(
        `geen marker gevonden met type ${this.type} en id ${this.id}`
      );
    return r;
  }

  get shown(){
    return !this.marker.classList.contains('blurred');
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

    const addressKeys = [
      "phone_number",
      "prefix",
      "surname",
      "email_address",
      "name",
      "website",
      "contact_person",
    ];

    this.meta = {
      own_animals: [] // TODO dit is omdat de filters niet onderscheid maken
    }

    this.name = config.name;
    this.contact = {};

    this.processConfig(addressKeys, config)

    this.located = this.tryToFindLocation(addresses, config); 

    if (config.text) {
      this.text = config.text;
    }
  } // end constructor

  processConfig(addressKeys, config){
    Object.keys(config).forEach((key) => {
      if (key === "address_id") return;
      if (addressKeys.includes(key)) {
        this.contact[key] = config[key];
        return;
      }
      this[key] = config[key];
    });

  }
  /**
   * Goes through the addresses (from the db->addresses)  and tries to find the location.
   * If the address is assigned the label 'faulty_address' a north sea alternative is used and the 
   * return value sets the located prop in the  constructor.
   * @param {*} addresses 
   * @param {*} config 
   * @return bool of gelukt is
   */
  tryToFindLocation(addresses, config){
    try {
      const l = addresses.find((address) => address.uuid === config.address_id); // potential memory leak, messes with garbage collection?
      delete l.manual_geolocation;
      delete l.updated_at;
      this.location = l;
    } catch (error) {
      throw new Error(
        `${config.name} location niet gevonden in _locations. ${error.message}`
      );
    }   
    // hup to the sea you rascal mwou hahaha
    if (this.location.faulty_address === 1) {
      this.location.city = "";
      this.location.house_number = "";
      this.location.postal_code = "";
      this.location.street = "";
      this.location.lattitude = "53.033";
      this.location.longitude = "3.888";
      return false;
    } 
    return true;
  }

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
    throw new Error(
      "using find method of LocatedEntity, please implement in child"
    );
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
  constructor(config, locations, meta) {
    super("guest", config, locations);

    const own_animals_absent_total_list = Object.values(meta.own_animals_absent);
    this.meta = {
      animal_preference: config.animal_preference.map(a => a.toLowerCase().replace(/\s/,'-')),
      behaviour: config.behaviour.map(a => a.toLowerCase().replace(/\s/,'-')),
      residence: config.residence.map(a => a.toLowerCase().replace(/\s/,'-')),
      own_animals: Array.isArray(config.own_animals) ? config.own_animals : [],
      own_animals_absent: own_animals_absent_total_list.filter(mogelijkAfwezigDier =>{
        return !config.own_animals.includes(mogelijkAfwezigDier)
      })

    }
    this.verwijderOudeMeta();
  }
  verwijderOudeMeta(){
    delete this.animal_preference;
    delete this.behaviour;
    delete this.residence;
    delete this.own_animals;
    delete this.own_animals_absent;
  }

  heeftEigenDier(dier) {
    if (!dier) {throw new Error('params gemist Guest.heeftEigenDier')}
    return this.meta.includes(dier);
  }

  get animalsOnSite() {
    return this.animals;
  }

  static get all() {
    return models.guests;
  }
}

class Shelter extends LocatedEntity {
  constructor(config, locations) {
    super("shelter", config, locations);
  }

  get animalsOnSite() {
    return this.animals;
  }
  static get all() {
    return models.shelters;
  }
}

class Location extends LocatedEntity {
  constructor(config, locations) {
    super("location", config, locations);
  }

  get animalsOnSite() {
    return this.animals;
  }
  static get all() {
    return models.locations;
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
  static get all() {
    return models.vets;
  }
}

class Owner extends LocatedEntity {
  constructor(config, locations) {
    super("owner", config, locations);
  }

  get animalsOnSite() {
    return this.animals.filter((ownedAnimals) => {
      return ownedAnimals && ownedAnimals.staysAt && ownedAnimals.staysAt.location.uuid === this.location.uuid;
    });
  }
  static get all() {
    return models.owners;
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
  static get all() {
    return models.animals;
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
  Location
};
