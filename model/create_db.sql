-- R3.01 - SQLite3 Database
-- Creating the database tables
-- Author: Noé Pierre & Oscar Pavoine

-- User Table
CREATE TABLE User (
    userId INTEGER PRIMARY KEY AUTOINCREMENT, -- Primary key, auto-incremented
    lastName TEXT NOT NULL, 
    firstName TEXT NOT NULL,
    birthDate TEXT NOT NULL,
    gender TEXT CHECK (gender IN ('M', 'F', 'Other')) NOT NULL, 
    height REAL NOT NULL,
    weight REAL NOT NULL,
    email TEXT CHECK (email LIKE '%@%.%') UNIQUE, -- Email constraint
    password TEXT CHECK (LENGTH(password) >= 6) NOT NULL -- Password constraint
);

-- Activity Table
CREATE TABLE Activity (
    activityId INTEGER PRIMARY KEY AUTOINCREMENT, -- Primary key, auto-incremented
    userId INTEGER NOT NULL,
    date TEXT NOT NULL,
    description TEXT NOT NULL,
    time TEXT NOT NULL,
    distance REAL NOT NULL,
    averageSpeed REAL NOT NULL,
    maxSpeed REAL NOT NULL,
    totalAltitude REAL NOT NULL,
    averageHeartRate INTEGER NOT NULL,
    maxHeartRate INTEGER NOT NULL,
    minHeartRate INTEGER NOT NULL,
    FOREIGN KEY (userId) REFERENCES User(userId) -- Foreign key constraint
    CHECK (time LIKE '__:__:__')
);

-- Data Table
CREATE TABLE Data (
    dataId INTEGER PRIMARY KEY AUTOINCREMENT, -- Primary key, auto-incremented
    activityId INTEGER NOT NULL,
    date TEXT NOT NULL,
    description TEXT NOT NULL,
    time TEXT NOT NULL,
    heartRate INTEGER NOT NULL,
    latitude REAL NOT NULL,
    longitude REAL NOT NULL,
    altitude REAL NOT NULL,
    FOREIGN KEY (activityId) REFERENCES Activity(activityId) -- Foreign key constraint
    CHECK (time LIKE '__:__:__')
);