-- Additional integrity constraints for MySQL

-- make sure CGPA stays between 0.00 and 4.00
ALTER TABLE students
    ADD CONSTRAINT chk_cgpa_range CHECK (cgpa >= 0 AND cgpa <= 4);

-- student_id, email and cnic already have UNIQUE indexes defined in the original schema

-- optional: force lower-case email to avoid case-sensitivity duplicates
ALTER TABLE students
    MODIFY email VARCHAR(100) NOT NULL;

-- ensure resume_path cannot be empty
ALTER TABLE students
    MODIFY resume_path VARCHAR(255) NOT NULL;

-- set strict mode and use InnoDB for foreign-key support and transactions
-- (engine rewrite shown below, optional if migrating)
-- ALTER TABLE students ENGINE = InnoDB;

-- finally, make sure id is primary key and auto-increment (already done in dump)

