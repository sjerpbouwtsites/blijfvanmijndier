INSERT INTO `addresses` (`uuid`, `street`, `house_number`, `postal_code`, `city`) 
 SELECT `address_id`, `street`, `house_number`, `postal_code`, `city` FROM `guests`;
INSERT INTO `addresses` (`uuid`, `street`, `house_number`, `postal_code`, `city`) 
 SELECT `address_id`, `street`, `house_number`, `postal_code`, `city` FROM `owners`;
INSERT INTO `addresses` (`uuid`, `street`, `house_number`, `postal_code`, `city`) 
 SELECT `address_id`, `street`, `house_number`, `postal_code`, `city` FROM `shelters`;
INSERT INTO `addresses` (`uuid`, `street`, `house_number`, `postal_code`, `city`) 
 SELECT `address_id`, `street`, `house_number`, `postal_code`, `city` FROM `locations`;
INSERT INTO `addresses` (`uuid`, `street`, `house_number`, `postal_code`, `city`) 
 SELECT `address_id`, `street`, `house_number`, `postal_code`, `city` FROM `vets`;

-- tabellen leeg gooien.
ALTER TABLE guests DROP COLUMN `street`;
ALTER TABLE guests DROP COLUMN `house_number`;
ALTER TABLE guests DROP COLUMN `postal_code`;
ALTER TABLE guests DROP COLUMN `city`;
ALTER TABLE owners DROP COLUMN `street`;
ALTER TABLE owners DROP COLUMN `house_number`;
ALTER TABLE owners DROP COLUMN `postal_code`;
ALTER TABLE owners DROP COLUMN `city`;
ALTER TABLE locations DROP COLUMN `street`;
ALTER TABLE locations DROP COLUMN `house_number`;
ALTER TABLE locations DROP COLUMN `postal_code`;
ALTER TABLE locations DROP COLUMN `city`;
ALTER TABLE shelters DROP COLUMN `street`;
ALTER TABLE shelters DROP COLUMN `house_number`;
ALTER TABLE shelters DROP COLUMN `postal_code`;
ALTER TABLE shelters DROP COLUMN `city`;
ALTER TABLE vets DROP COLUMN `street`;
ALTER TABLE vets DROP COLUMN `house_number`;
ALTER TABLE vets DROP COLUMN `postal_code`;
ALTER TABLE vets DROP COLUMN `city`;