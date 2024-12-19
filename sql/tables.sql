CREATE database fournisseur_identite;
\c fournisseur_identite

CREATE TABLE Role(
   id_role SERIAL,
   name VARCHAR(50) ,
   PRIMARY KEY(id_role)
);

CREATE TABLE Genre(
   id_genre SERIAL,
   type VARCHAR(50) ,
   PRIMARY KEY(id_genre)
);

CREATE TABLE Session(
   id_session SERIAL,
   duration INTEGER check (duration>0),
   PRIMARY KEY(id_session)
);

CREATE TABLE Users(
   id_user SERIAL,
   mail VARCHAR(255) unique,
   password VARCHAR(255) ,
   name VARCHAR(150),
   date_birth DATE,
   date_inscription TIMESTAMP default now(),
   tentative_connexion INTEGER default 0,
   id_genre INTEGER NOT NULL,
   id_role INTEGER NOT NULL,
   PRIMARY KEY(id_user),
   FOREIGN KEY(id_genre) REFERENCES Genre(id_genre),
   FOREIGN KEY(id_role) REFERENCES Role(id_role)
);

CREATE TABLE Token(
   id_token SERIAL,
   token VARCHAR(255) ,
   expiration_date TIMESTAMP,
   id_user INTEGER NOT NULL,
   PRIMARY KEY(id_token),
   FOREIGN KEY(id_user) REFERENCES Users(id_user)
);

CREATE TABLE Max_attempt(
   id_attempt SERIAL,
   max_attempt INTEGER check (max_attempt>0),
   PRIMARY KEY(id_attempt)
);
