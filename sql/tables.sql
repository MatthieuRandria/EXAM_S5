CREATE database fournisseur_identite;
\c fournisseur_identite

CREATE TABLE Role(
   idRole SERIAL,
   name VARCHAR(50) ,
   PRIMARY KEY(idRole)
);

CREATE TABLE Genre(
   idGenre SERIAL,
   type VARCHAR(50) ,
   PRIMARY KEY(idGenre)
);

CREATE TABLE Session(
   idSession SERIAL,
   duration INTEGER check (duration>0),
   PRIMARY KEY(idSession)
);

CREATE TABLE Users(
   idUser SERIAL,
   mail VARCHAR(255) ,
   password VARCHAR(255) ,
   name VARCHAR(150),
   date_birth DATE,
   date_inscription TIMESTAMP default now(),
   tentative_connexion INTEGER,
   idGenre INTEGER NOT NULL,
   idRole INTEGER NOT NULL,
   PRIMARY KEY(idUser),
   FOREIGN KEY(idGenre) REFERENCES Genre(idGenre),
   FOREIGN KEY(idRole) REFERENCES Role(idRole)
);

CREATE TABLE Token(
   idToken SERIAL,
   token VARCHAR(255) ,
   databaseate_created TIMESTAMP,
   idUser INTEGER NOT NULL,
   PRIMARY KEY(idToken),
   FOREIGN KEY(idUser) REFERENCES Users(idUser)
);

CREATE TABLE Tentative_max(
   idSession SERIAL,
   tentative_max INTEGER check (tentative_max>0),
   PRIMARY KEY(idSession)
);
