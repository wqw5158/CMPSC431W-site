DROP DATABASE IF EXISTS genericTeamDB;
CREATE DATABASE genericTeamDB;
USE genericTeamDB;

CREATE TABLE Users (
  	userid INT AUTO_INCREMENT,
  	email VARCHAR(100)NOT NULL,
  	password VARCHAR(32)NOT NULL,
  	address VARCHAR(100),
  	phonenumber VARCHAR(16),
    PRIMARY KEY( userid) );

CREATE TABLE Dealers (
  	userid INT,
  	dealershipname VARCHAR(64) NOT NULL,
  	personofcontact VARCHAR(64)NOT NULL,
  	revenue REAL,
  	dealershipcategory VARCHAR(64),
  	PRIMARY KEY(userid),
  	FOREIGN KEY(userid) REFERENCES users(userid) ON DELETE CASCADE );
 
CREATE TABLE InsCompany (
  	insurancecompanycode VARCHAR(10),
  	insurancecompanyname VARCHAR(64) NOT NULL,
  	PRIMARY KEY(insurancecompanycode) );
 
CREATE TABLE RegisteredUsers (
    userid INT NOT NULL,
  	name VARCHAR(64) NOT NULL,
  	age INT,
  	gender VARCHAR(1),
  	income REAL,
    insurancepolicyno VARCHAR(16),
    insurancecompanycode VARCHAR(10),
  	PRIMARY KEY(userid),
  	FOREIGN KEY(userid) REFERENCES users(userid) ON DELETE CASCADE,
  	FOREIGN KEY(insurancecompanycode) 
  	        REFERENCES InsCompany(insurancecompanycode) );
  	        
CREATE TABLE Banks (
  	routingno VARCHAR(20),
  	bankname VARCHAR(64),
  	PRIMARY KEY(routingno) );
 
CREATE TABLE BankAccount(
  	userid INT,
  	accountno VARCHAR(20),
    routingno VARCHAR(20),
    PRIMARY KEY(userid,accountno),
    FOREIGN KEY(userid) REFERENCES Users(userid) ON DELETE CASCADE );
 
CREATE TABLE vehicles (
  	vin VARCHAR(17),     
  	ownerid INT,
  	category VARCHAR(24),
  	make VARCHAR(64),
  	model VARCHAR(64),
  	year INT,
  	mileage INT,
  	description VARCHAR(255),
    image VARCHAR(32),
  	PRIMARY KEY(vin),
  	FOREIGN KEY(ownerid) REFERENCES Users(userid) ON DELETE CASCADE );
 
CREATE TABLE Auctions(
  	auctionid INT AUTO_INCREMENT,
  	vin VARCHAR(17) NOT NULL,
  	sellerid INT,
  	startdate DATE,
  	enddate DATE NOT NULL,
  	reserveprice REAL NOT NULL,
  	buynowprice REAL NOT NULL,
  	closedate DATE DEFAULT NULL,
  	PRIMARY KEY(auctionid),
  	FOREIGN KEY(vin) REFERENCES Vehicles(vin) ON DELETE CASCADE,
  	FOREIGN KEY(sellerid) REFERENCES users(userid) ON DELETE CASCADE );
 
CREATE TABLE Bids(
    bidid INT AUTO_INCREMENT,
    auctionid INT NOT NULL,
    bidderid INT NOT NULL,
    amount REAL,
    timestamp TIMESTAMP NOT NULL,
    PRIMARY KEY(bidid),
    FOREIGN KEY(bidderid) REFERENCES Users(userid) ON DELETE CASCADE,
    FOREIGN KEY(auctionid) REFERENCES Auctions(auctionid) ON DELETE CASCADE );
 
CREATE TABLE Transactions (
  	transactionid INT AUTO_INCREMENT,
  	bidid INT NOT NULL,
  	auctionid INT NOT NULL,
  	transactionstate VARCHAR(255) NOT NULL,
  	transactiondate DATE NOT NULL,
  	PRIMARY KEY (transactionid),
  	FOREIGN KEY (bidid) REFERENCES Bids(bidid) ON DELETE CASCADE,
  	FOREIGN KEY (auctionid) REFERENCES Auctions(auctionid) ON DELETE CASCADE );
 
CREATE TABLE Delivered(
  	userid INT,
  	transactionid INT,
  	deliveredDate DATE NOT NULL,
  	PRIMARY KEY (userid, transactionid),
  	FOREIGN KEY (userid) REFERENCES Users(userid) ON DELETE CASCADE,
  	FOREIGN KEY (transactionid) REFERENCES Transactions(transactionid) 
  	        ON DELETE CASCADE );

CREATE TABLE Returned(
  	userid INT,
  	transactionid INT,
  	returnedDate date NOT NULL,
  	PRIMARY KEY (userid, transactionid),
  	FOREIGN KEY (userid) REFERENCES Users(userid) ON DELETE CASCADE,
  	FOREIGN KEY (transactionid) REFERENCES Transactions(transactionid) 
  	        ON DELETE CASCADE );
  	
CREATE TABLE Reviews (
  	reviewid INT AUTO_INCREMENT,
  	reviewerid INT,
  	sellerid INT,
  	auctionid INT,
  	reviewdate date,
  	review VARCHAR(1024),
  	rating INT,
  	PRIMARY KEY( reviewid ),
    FOREIGN KEY(reviewerid) REFERENCES Users(userid) ON DELETE NO ACTION,
    FOREIGN KEY(sellerid) REFERENCES Users(userid) ON DELETE CASCADE,
  	FOREIGN KEY(auctionid) REFERENCES Auctions(auctionid) ON DELETE NO ACTION );
 
CREATE TABLE DisplayFloor(
  	displayid INT AUTO_INCREMENT,
    userid INT,
  	vin VARCHAR(17) NOT NULL,
  	youtubeid VARCHAR(64),
  	certifiedby VARCHAR(1024),
  	PRIMARY KEY(displayid),
  	UNIQUE(vin),
  	FOREIGN KEY(vin) REFERENCES Vehicles(vin) ON DELETE CASCADE);
 
CREATE TABLE Media (
  	mediaid INT AUTO_INCREMENT,
  	userid INT,
  	displayid INT,
  	filetype VARCHAR(16),
  	filename VARCHAR(255),
  	PRIMARY KEY (mediaid),
  	FOREIGN KEY (userid) REFERENCES Users(userid) ON DELETE CASCADE,
  	FOREIGN KEY (displayid) REFERENCES DisplayFloor(displayid) 
  	        ON DELETE NO ACTION);

CREATE TABLE BelongsTo (
  	mediaid INT,
  	displayid INT,
  	PRIMARY KEY (mediaid, displayid),
  	FOREIGN KEY (mediaid) REFERENCES Media(mediaid) ON DELETE CASCADE,
  	FOREIGN KEY (displayid) REFERENCES Displayfloor(displayid) 
  	        ON DELETE CASCADE);
 
CREATE TABLE Events(
  	eventid INT AUTO_INCREMENT,
  	organizerid INT NOT NULL,
  	eventdate date NOT NULL,
  	location VARCHAR(255) NOT NULL,
  	description VARCHAR(255)NOT NULL,
  	PRIMARY KEY(eventid),
  	FOREIGN KEY (organizerid) REFERENCES Users(userid) ON DELETE CASCADE);
 
CREATE TABLE AttendingEvent(
  	userid INT,
  	eventid INT,
  	PRIMARY KEY(userid, eventid),
  	FOREIGN KEY(userid) REFERENCES Users(userid) ON DELETE CASCADE,
  	FOREIGN KEY(eventid) REFERENCES Events(eventid) ON DELETE CASCADE );
 
CREATE TABLE Discussions (
  	discussionid INT AUTO_INCREMENT,
  	vin VARCHAR(17) NOT NULL,
  	userid INT NOT NULL,
  	discussion VARCHAR(255) NOT NULL,
  	timestamp TIMESTAMP NOT NULL,
  	PRIMARY KEY (discussionid),
  	FOREIGN KEY (vin) REFERENCES Vehicles(vin) ON DELETE CASCADE,
  	FOREIGN KEY (userid) REFERENCES Users(userid) ON DELETE CASCADE);
