-- Database creation script

CREATE DATABASE IF NOT EXISTS contact_manager;
USE contact_manager;

CREATE TABLE IF NOT EXISTS users (
  id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(254) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  CONSTRAINT users_pkey PRIMARY KEY (id),
  UNIQUE (username),
  UNIQUE (email)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS contacts (
  id INT NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  phone_number VARCHAR(30) NOT NULL,
  email VARCHAR(254) NOT NULL,
  creation_date DATE DEFAULT (CURRENT_DATE()),
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT contacts_pkey PRIMARY KEY (id)
) ENGINE = InnoDB;
