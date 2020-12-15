/**
 * @file non sense data
 */
class BsStraat {
  constructor() {
    this.counter = 0;
    this.straat = "straatnaam";
    this.huisnummer = 100;
    this.postcode = "1000BB";
    this.plaatsnaam = "Ons Dorp";
  }
  make() {
    this.counter++;
    return {
      straat: this.straat,
      huisnummer: this.huisnummer + this.counter,
      postcode: this.postcode,
      plaatsnaam: this.plaatsnaam,
    };
  }
}

const BsStraatInst = new BsStraat();

const locations = [
  {
    lat: 52.090736,
    lon: 5.12142,
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
    lon: 5.89873,
    ...BsStraatInst.make(),
    id: 6,
  },
  {
    lat: 51.69009,
    lon: 5.30369,
    ...BsStraatInst.make(),
    id: 7,
  },
  {
    lat: 52.090936,
    lon: 5.19162,
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
    lon: 5.99873,
    ...BsStraatInst.make(),
    id: 13,
  },
  {
    lat: 51.72009,
    lon: 5.34369,
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
    lat: 52.64146,
    lon: 5.05681,
    ...BsStraatInst.make(),
    id: 17,
  },
  {
    lat: 52.305691,
    lon: 4.86251,
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
    id: 20,
  },
  {
    lat: 50.951368,
    lon: 5.490972,
    ...BsStraatInst.make(),
    id: 21,
  },
  {
    lat: 50.888172,
    lon: 5.979499,
    ...BsStraatInst.make(),
    id: 22,
  },
  {
    lat: 50.908172,
    lon: 4.5979499,
    ...BsStraatInst.make(),
    id: 23,
  },
  {
    lat: 52.221539,
    lon: 6.893662,
    ...BsStraatInst.make(),
    id: 24,
  },
  {
    lat: 51.221539,
    lon: 6.953662,
    ...BsStraatInst.make(),
    id: 25,
  },
  {
    lat: 53.053921,
    lon: 4.79605,
    ...BsStraatInst.make(),
    id: 26,
  },
  {
    lat: 51.498795,
    lon: 3.610998,
    ...BsStraatInst.make(),
    id: 27,
  },
  {
    lat: 51.398795,
    lon: 4.610998,
    ...BsStraatInst.make(),
    id: 28,
  },
  {
    lat: 51.495795,
    lon: 3.610998,
    ...BsStraatInst.make(),
    id: 29,
  },
  {
    lat: 51.398795,
    lon: 4.620998,
    ...BsStraatInst.make(),
    id: 30,
  },
];

const guests = [
  {
    title: "Familie de vries",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 1,
    id: "g-1",
  },
  {
    title: "Familie de Sjaak",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 2,
    id: "g-2",
  },
  {
    title: "Familie de Pineut",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 3,
    id: "g-3",
  },
  {
    title: "Familie de Pisang",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 4,
    id: "g-4",
  },
  {
    title: "Hendrik Anton Zeurstra",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 5,
    id: "g-5",
  },
  {
    title: "Tonnie B. Abbelveel",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 6,
    id: "g-6",
  },
];

const vets = [
  {
    title: "Doktor harry",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    id: "v-1",
    location: 7,
  },
  {
    title: "doktor Janssen",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    id: "v-2",
    location: 8,
  },
  {
    title: "Doktor Snuffel",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    id: "v-3",
    location: 9,
  },
  {
    title: "Doktor Jaapstra",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    id: "v-4",
    location: 10,
  },
  {
    title: "Doktor Tuinstra",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 11,
    id: "v-5",
  },
  {
    title: "Doktor Terpstra",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 12,
    id: "v-6",
  },
];

const pensions = [
  {
    title: "Pension een",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 13,
    id: "p-1",
  },
  {
    title: "Pension twee",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 18,
    id: "p-2",
  },
  {
    title: "Pension 3",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 14,
    id: "p-3",
  },
  {
    title: "Pension 4",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    id: "p-4",
    location: 15,
  },
  {
    title: "Pension 5",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 16,
    id: "p-5",
  },
  {
    title: "Pension 6",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 17,
    id: "p-6",
  },
];

const owner = [
  {
    title: "Bertus Bever",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 19,
    id: "e-1",
  },
  {
    title: "Els Ezel",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 20,
    id: "e-2",
  },
  {
    title: "Tinus Turqoise",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 21,
    id: "e-3",
  },
  {
    title: "Machteld Mangaanknoop",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 22,
    id: "e-4",
  },
  {
    title: "Sebaldinus Sneekerburgertoren",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 23,
    id: "e-5",
  },
  {
    title: "Arie Achteropdefiets",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    location: 24,
    id: "e-6",
  },
];

const animals = [
  {
    title: "Kleine Wifwaf",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    id: "a-1",
    vetId: "v-1",
    ownerId: "e-1",
    locationType: "guest",
    locationId: "g-1",
  },
  {
    title: "Blub",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    id: "a-2",
    vetId: "v-2",
    ownerId: "e-2",
    locationType: "pension",
    locationId: "p-1",
  },
  {
    title: "Knabbel",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    id: "a-3",
    vetId: "v-3",
    ownerId: "e-3",
    locationType: "guest",
    locationId: "g-3",
  },
  {
    title: "Dikzak",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    id: "a-4",
    vetId: "v-4",
    ownerId: "e-4",
    locationType: "pension",
    locationId: "p-1",
  },
  {
    title: "Meur",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    id: "a-5",
    vetId: "v-5",
    ownerId: "e-5",
    locationType: "guest",
    locationId: "g-4",
  },
  {
    title: "Carola",
    text:
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    id: "a-6",
    vetId: "v-6",
    ownerId: "e-6",
    locationType: "guest",
    locationId: "g-5",
  },
];

module.exports = {
  locations,
  guests,
  vets,
  owner,
  pensions,
  animals,
};
