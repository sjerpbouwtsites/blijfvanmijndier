ALTER TABLE guests ADD COLUMN disabled TINYINT DEFAULT 0;
ALTER TABLE guests ADD COLUMN disabled_untill DATETIME DEFAULT NOW();
ALTER TABLE guests ADD COLUMN disabled_from DATETIME DEFAULT NOW();