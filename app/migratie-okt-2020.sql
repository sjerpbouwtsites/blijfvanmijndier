-- @block Bookmarked query
-- @group Ungrouped
-- @name create addresses
-- adres tabel maken
CREATE TABLE `addresses` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `street` text NOT NULL,
  `house_number` text NOT NULL,
  `postal_code` text NOT NULL,
  `city` text,
  `latitude` text,
  `longitude` text,
  PRIMARY KEY (`id`)
);
-- alle niet null van street, postal_code en house_number op herkenbare STRING
-- ook nodig voor verhasing ivm concat.
UPDATE vets
SET street = 'niet-ingevoerd'
WHERE street IS NULL;
UPDATE vets
SET house_number = 'niet-ingevoerd'
WHERE house_number IS NULL;
UPDATE vets
SET postal_code = 'niet-ingevoerd'
WHERE postal_code IS NULL;
UPDATE shelters
SET street = 'niet-ingevoerd'
WHERE street IS NULL;
UPDATE shelters
SET house_number = 'niet-ingevoerd'
WHERE house_number IS NULL;
UPDATE shelters
SET postal_code = 'niet-ingevoerd'
WHERE postal_code IS NULL;
UPDATE owners
SET street = 'niet-ingevoerd'
WHERE street IS NULL;
UPDATE owners
SET house_number = 'niet-ingevoerd'
WHERE house_number IS NULL;
UPDATE owners
SET postal_code = 'niet-ingevoerd'
WHERE postal_code IS NULL;
UPDATE locations
SET street = 'niet-ingevoerd'
WHERE street IS NULL;
UPDATE locations
SET house_number = 'niet-ingevoerd'
WHERE house_number IS NULL;
UPDATE locations
SET postal_code = 'niet-ingevoerd'
WHERE postal_code IS NULL;
UPDATE guests
SET street = 'niet-ingevoerd'
WHERE street IS NULL;
UPDATE guests
SET house_number = 'niet-ingevoerd'
WHERE house_number IS NULL;
UPDATE guests
SET postal_code = 'niet-ingevoerd'
WHERE postal_code IS NULL;
-- vets, shelters, owners, locations, guests allemaal address_id geven op basis van hash.
ALTER TABLE vets
ADD address_id TEXT default NULL;
UPDATE vets
SET address_id = SHA2(CONCAT(street, house_number, postal_code), 256);
ALTER TABLE shelters
ADD address_id TEXT default NULL;
UPDATE shelters
SET address_id = SHA2(CONCAT(street, house_number, postal_code), 256);
ALTER TABLE owners
ADD address_id TEXT default NULL;
UPDATE owners
SET address_id = SHA2(CONCAT(street, house_number, postal_code), 256);
ALTER TABLE locations
ADD address_id TEXT default NULL;
UPDATE locations
SET address_id = SHA2(CONCAT(street, house_number, postal_code), 256);
ALTER TABLE guests
ADD address_id TEXT default NULL;
UPDATE guests
SET address_id = SHA2(CONCAT(street, house_number, postal_code), 256);
-- gegevens vets, shelters, owners, locations, guests overzetten naar address tabel
INSERT INTO addresses (uuid, street, house_number, postal_code, city)
SELECT address_id,
  street,
  house_number,
  postal_code,
  city
FROM vets;
INSERT INTO addresses (uuid, street, house_number, postal_code, city)
SELECT address_id,
  street,
  house_number,
  postal_code,
  city
FROM shelters;
INSERT INTO addresses (uuid, street, house_number, postal_code, city)
SELECT address_id,
  street,
  house_number,
  postal_code,
  city
FROM owners;
INSERT INTO addresses (uuid, street, house_number, postal_code, city)
SELECT address_id,
  street,
  house_number,
  postal_code,
  city
FROM locations;
INSERT INTO addresses (uuid, street, house_number, postal_code, city)
SELECT address_id,
  street,
  house_number,
  postal_code,
  city
FROM guests;
-- en nu brontabellen kolommen street, postal_code, house_number en city vewijderen
ALTER TABLE vets DROP COLUMN street;
ALTER TABLE vets DROP COLUMN house_number;
ALTER TABLE vets DROP COLUMN postal_code;
ALTER TABLE vets DROP COLUMN city;
ALTER TABLE shelters DROP COLUMN street;
ALTER TABLE shelters DROP COLUMN house_number;
ALTER TABLE shelters DROP COLUMN postal_code;
ALTER TABLE shelters DROP COLUMN city;
ALTER TABLE owners DROP COLUMN street;
ALTER TABLE owners DROP COLUMN house_number;
ALTER TABLE owners DROP COLUMN postal_code;
ALTER TABLE owners DROP COLUMN city;
ALTER TABLE locations DROP COLUMN street;
ALTER TABLE locations DROP COLUMN house_number;
ALTER TABLE locations DROP COLUMN postal_code;
ALTER TABLE locations DROP COLUMN city;
ALTER TABLE guests DROP COLUMN street;
ALTER TABLE guests DROP COLUMN house_number;
ALTER TABLE guests DROP COLUMN postal_code;
ALTER TABLE guests DROP COLUMN city;