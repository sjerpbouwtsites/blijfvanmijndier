
CREATE TABLE `addresses` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) DEFAULT (uuid()),
  `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
  `street` text ,
  `house_number` text ,
  `postal_code` text ,
  `city` text,
  `lattitude` VARCHAR(255) NOT NULL DEFAULT "53.0547312",
  `longitude` VARCHAR(255) NOT NULL DEFAULT "4.7437617",
  PRIMARY KEY (`id`)
);

-- allemaal minstens een waarde geven zodat uuid aangemaakt kan worden.
UPDATE `guests` SET street = '' WHERE street IS NULL;
UPDATE `guests` SET house_number = '' WHERE house_number IS NULL;
UPDATE `guests` SET city = '' WHERE city IS NULL;
UPDATE `guests` SET postal_code = '' WHERE postal_code IS NULL;
UPDATE `locations` SET street = '' WHERE street IS NULL;
UPDATE `locations` SET house_number = '' WHERE house_number IS NULL;
UPDATE `locations` SET city = '' WHERE city IS NULL;
UPDATE `locations` SET postal_code = '' WHERE postal_code IS NULL;
UPDATE `owners` SET street = '' WHERE street IS NULL;
UPDATE `owners` SET house_number = '' WHERE house_number IS NULL;
UPDATE `owners` SET city = '' WHERE city IS NULL;
UPDATE `owners` SET postal_code = '' WHERE postal_code IS NULL;
UPDATE `shelters` SET street = '' WHERE street IS NULL;
UPDATE `shelters` SET house_number = '' WHERE house_number IS NULL;
UPDATE `shelters` SET city = '' WHERE city IS NULL;
UPDATE `shelters` SET postal_code = '' WHERE postal_code IS NULL;
UPDATE `vets` SET street = '' WHERE street IS NULL;
UPDATE `vets` SET house_number = '' WHERE house_number IS NULL;
UPDATE `vets` SET city = '' WHERE city IS NULL;
UPDATE `vets` SET postal_code = '' WHERE postal_code IS NULL;

-- aanmaken uuids
ALTER TABLE `guests` ADD address_id TEXT default NULL;
UPDATE `guests` SET address_id = SHA2(CONCAT(street, house_number, postal_code), 256);
ALTER TABLE `locations` ADD address_id TEXT default NULL;
UPDATE `locations` SET address_id = SHA2(CONCAT(street, house_number, postal_code), 256);
ALTER TABLE `owners` ADD address_id TEXT default NULL;
UPDATE `owners` SET address_id = SHA2(CONCAT(street, house_number, postal_code), 256);
ALTER TABLE `shelters` ADD address_id TEXT default NULL;
UPDATE `shelters` SET address_id = SHA2(CONCAT(street, house_number, postal_code), 256);
ALTER TABLE `vets` ADD address_id TEXT default NULL; 
UPDATE `vets` SET address_id = SHA2(CONCAT(street, house_number, postal_code), 256);

