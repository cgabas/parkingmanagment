-- CREATED ON 21/8/2024 ON WEDNESDAY NIGHT
-- BY CGabas(Abas)
-- GITHUB: https://github.com/cgabas
-- All tables was already normalized by using an average human brain


-- please run this without actually trying to create a new database for your self
-- this code will create a new, reliable and appropriate database for you

-- CREATE DATABASE
CREATE DATABASE parkdb;

-- SWITCH DATABASE
USE parkdb;

-- I. user table
CREATE TABLE user (
    userID VARCHAR(12) NOT NULL PRIMARY KEY,
    fullname VARCHAR(128) NOT NULL,
    surname VARCHAR(64) NOT NULL,
    birthOfDate DATE NOT NULL,
    licenseType VARCHAR(16) NOT NULL,
    gender CHAR(1) NOT NULL,
    phoneNumber VARCHAR(16) NOT NULL,
    email VARCHAR(64) COMMENT "OPTIONAL"
);

-- II. registered_car table
CREATE TABLE registered_car (
    regisNum VARCHAR(16) NOT NULL PRIMARY KEY,
    userID VARCHAR(12) NOT NULL,
    vehicleType VARCHAR(16) NOT NULL COMMENT "MUST ALL CAPITAL",
    vehicleBrand VARCHAR(16) NOT NULL,
    vehicleModel VARCHAR(64) NOT NULL,
    engineHP INT UNSIGNED NOT NULL,
    isSuspended BOOL NOT NULL,
    CONSTRAINT FK_userID FOREIGN KEY (userID) REFERENCES user(userID)
);

-- III. recorded_parking table
CREATE TABLE recorded_parking (
    couponID VARCHAR(16) NOT NULL PRIMARY KEY,
    regisNum VARCHAR(16) NOT NULL,
    time_start TIME NOT NULL,
    time_end TIME NOT NULL,
    issued_date DATE NOT NULL,
    CONSTRAINT FK_regisNum FOREIGN KEY (regisNum) REFERENCES registered_car(regisNum)  -- Corrected Foreign Key Reference
);

-- Value Insertion Test

-- user
INSERT INTO user (
    userID,
    fullname,
    surname,
    birthOfDate,
    licenseType,
    gender,
    phoneNumber,
    email
) VALUES (
    "123456789012",
    "BARNABAS EVANEXAL",
    "Abas",
    "2007-09-30",
    "P",
    "L",
    "+601158838477",
    "barnabasevanexal300@gmail.com"
);

-- registered_car
INSERT INTO registered_car (
    regisNum,
    userID,
    vehicleType,
    vehicleBrand,
    vehicleModel,
    engineHP,
    isSuspended
) VALUES (
    "ABC1234",
    "123456789012",
    "SEDAN",
    "Audi",
    "RS7",
    621,
    0
);
