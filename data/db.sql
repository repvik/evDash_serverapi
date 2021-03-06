CREATE TABLE "users" (
	"iduser"	INTEGER NOT NULL,
	"apikey"	TEXT NOT NULL,
	"IP"	TEXT NOT NULL,
	"timezone"	TEXT NOT NULL DEFAULT 'UTC',
	"notifications"	INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY("iduser")
);

CREATE TABLE "data" (
	"iddata"	INTEGER NOT NULL,
	"user"	INTEGER NOT NULL,
	"timestamp"	DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"IP"	TEXT DEFAULT NULL,
	"carType"	INTEGER DEFAULT NULL,
	"ignitionOn"	INTEGER DEFAULT NULL,
	"socPerc"	REAL DEFAULT NULL,
	"sohPerc"	REAL DEFAULT NULL,
	"batPowerKw"	REAL DEFAULT NULL,
	"batPowerAmp"	REAL DEFAULT NULL,
	"batVoltage"	REAL DEFAULT NULL,
	"auxVoltage"	REAL DEFAULT NULL,
	"auxAmp"	REAL DEFAULT NULL,
	"batMinC"	REAL DEFAULT NULL,
	"batMaxC"	REAL DEFAULT NULL,
	"batInletC"	REAL DEFAULT NULL,
	"batFanStatus"	REAL DEFAULT NULL,
	"speedKmh"	REAL DEFAULT NULL,
	"odoKm"	REAL DEFAULT NULL,
	"cumulativeEnergyChargedKWh"	REAL DEFAULT NULL,
	"cumulativeEnergyDischargedKWh"	REAL DEFAULT NULL,
	"gpsLat"	REAL DEFAULT NULL,
	"gpsLon"	REAL DEFAULT NULL,
	"gpsAlt"	REAL DEFAULT NULL,
	CONSTRAINT "userid_fk" FOREIGN KEY("user") REFERENCES `users` (`iduser`),
	PRIMARY KEY("iddata")
);

