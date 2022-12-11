/* Dump 'import.sql' before this sql file */

-- Create user table
DROP TABLE IF EXISTS user;

CREATE TABLE user (
    ID INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT TRUE
);

-- Insert default user
INSERT INTO user (username, password) VALUES ('admin', 'letmein');