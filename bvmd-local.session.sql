INSERT INTO tables (tablegroup_id, description)
SELECT ('own_animal_type', description)
FROM tables
WHERE tablegroup_id = '4'; 