CREATE TABLE Vehicle (
	vehicleID varchar(255) NOT NULL,
	tagID varchar(255) NOT NULL UNIQUE,
	tagProvider varchar(255) NOT NULL,
	providerAbbr varchar(255) NOT NULL,
	lisenceYear int(10) NOT NULL,
	PRIMARY KEY (vehicleID)
);

CREATE TABLE Station (
	stationID varchar(255) NOT NULL,
	stationProvider varchar(255) NOT NULL,
	stationName varchar(255) NOT NULL,
	PRIMARY KEY (stationID)
);

CREATE TABLE Pass (
	passID varchar(255) NOT NULL,
	timestamp timestamp NOT NULL,
	charge numeric(5, 3) NOT NULL,
	vehicleRef varchar(255) NOT NULL,
	stationRef varchar(255) NOT NULL,
	PRIMARY KEY (passID)
);

CREATE TABLE Charge(
	passID varchar(255) NOT NULL, operatorCred varchar(255) NOT NULL, operatorDeb varchar(255) NOT NULL, status varchar(255) NOT NULL, settlementID int(10), PRIMARY KEY (passID)
);


CREATE TABLE Operator (
	operatorName varchar(255) NOT NULL,
	operatorAbbr varchar(255) NOT NULL UNIQUE,
	PRIMARY KEY (operatorName)
);


ALTER TABLE Vehicle
	ADD CONSTRAINT FKVehicle842085 FOREIGN KEY (tagProvider) REFERENCES OPERATOR (operatorName) ON
	UPDATE
		CASCADE
	ON DELETE CASCADE;

ALTER TABLE Pass
	ADD CONSTRAINT FKPass594622 FOREIGN KEY (vehicleRef) REFERENCES Vehicle (vehicleID) ON
	UPDATE
		CASCADE
	ON DELETE CASCADE;

ALTER TABLE Pass
	ADD CONSTRAINT FKPass96112 FOREIGN KEY (stationRef) REFERENCES Station (stationID) ON
	UPDATE
		CASCADE
	ON DELETE CASCADE;

ALTER TABLE Charge
	ADD CONSTRAINT FKCharge918009 FOREIGN KEY (passID) REFERENCES Pass (passID) ON
	UPDATE
		CASCADE
	ON DELETE CASCADE;

ALTER TABLE Station
	ADD CONSTRAINT FKStation842085 FOREIGN KEY (stationProvider) REFERENCES OPERATOR (operatorName) ON
	UPDATE
		CASCADE
	ON DELETE CASCADE;
