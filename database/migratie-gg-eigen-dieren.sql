INSERT INTO tablegroups (type, name) VALUES ('own_animal_type', 'Eigen-diersoorten')

INSERT INTO tables (tablegroup_id, description, description2, updated_at, created_at)
SELECT '100000', description, 'empty', NOW(), NOW()
FROM tables
WHERE tablegroup_id = '4';

UPDATE tables SET tablegroup_id = (SELECT id FROM tablegroups WHERE type = 'own_animal_type' LIMIT 1) WHERE tablegroup_id = '100000'; 