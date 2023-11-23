CREATE DATABASE Marcazon;

USE Marcazon;

CREATE TABLE EstatComanda(estatC varchar(30) PRIMARY KEY);

CREATE TABLE EstatVenedor(estat varchar(10) PRIMARY KEY);

CREATE TABLE Zona (
    idZona int AUTO_INCREMENT PRIMARY KEY,
    nomZona varchar(50),
    longitud float,
    latitud float
);

CREATE TABLE Poblacio (
    nomPoble varchar (50),
    codiPostal int PRIMARY KEY,
    zona int,
    CONSTRAINT FOREIGN KEY (zona) REFERENCES Zona(idZona)
);

CREATE TABLE Venedor(
    idVen int AUTO_INCREMENT PRIMARY KEY,
    nom varchar(30) NOT NULL,
    nickname varchar(50) NOT NULL,
    pwd varchar(100) NOT NULL,
    estatVen varchar(10),
    CONSTRAINT FOREIGN KEY(estatVen) REFERENCES EstatVenedor(estat)
);

CREATE TABLE Controlador(
    idContr int AUTO_INCREMENT PRIMARY KEY,
    nom varchar(30) NOT NULL,
    nickname varchar(50) NOT NULL,
    pwd varchar(100) NOT NULL
);

CREATE TABLE Comprador(
    idCompr int AUTO_INCREMENT PRIMARY KEY,
    nom varchar(30) NOT NULL,
    nickname varchar(50) NOT NULL,
    pwd varchar(100) NOT NULL
);

CREATE TABLE Domicili (
    idDomicili int AUTO_INCREMENT PRIMARY KEY,
    numPis int,
    carrer varchar(50) NOT NULL,
    numCasa int NOT NULL,
    codiPostal int NOT NULL,
    propietari int,
    CONSTRAINT FOREIGN KEY(codiPostal) REFERENCES Poblacio(codiPostal),
    CONSTRAINT FOREIGN KEY(propietari) REFERENCES Comprador(idCompr)
);

CREATE TABLE Distribuidor(
    nomEntitat varchar(30),
    idDistr int AUTO_INCREMENT PRIMARY KEY
);

CREATE TABLE Repartidor(
    idRep int AUTO_INCREMENT PRIMARY KEY,
    nom varchar(30) NOT NULL,
    idDistr int,
    CONSTRAINT FOREIGN KEY(idDistr) REFERENCES Distribuidor(idDistr)
);

CREATE TABLE Categoria (cat varchar(20) PRIMARY KEY);

CREATE TABLE Producte(
    nomProd varchar(50) PRIMARY KEY,
    pathImg varchar(200)
);

CREATE TABLE ProdCat(
    nomProd varchar(50),
    cat varchar(20),
    PRIMARY KEY(nomProd, cat),
    CONSTRAINT FOREIGN KEY(cat) REFERENCES Categoria(cat),
    CONSTRAINT FOREIGN KEY(nomProd) REFERENCES Producte(nomProd)
);

CREATE TABLE Comanda(
    idCom int AUTO_INCREMENT PRIMARY KEY,
    idCompr int NOT NULL,
    idContr int NOT NULL,
    dataPagat DATE,
    haPagat boolean NOT NULL,
    estatC varchar(30) NOT NULL,
    idDistr int,
    CONSTRAINT FOREIGN KEY(idContr) REFERENCES Controlador(idContr),
    CONSTRAINT FOREIGN KEY(idCompr) REFERENCES Comprador(idCompr),
    CONSTRAINT FOREIGN KEY(estatC) REFERENCES EstatComanda(estatC),
    CONSTRAINT FOREIGN KEY(idDistr) REFERENCES Distribuidor(idDistr)
);

CREATE TABLE Stock (
    idStock int AUTO_INCREMENT PRIMARY KEY,
    propietari int NOT NULL,
    nomProd varchar(50) NOT NULL,
    qttProd int NOT NULL,
    preu int NOT NULL,
    CONSTRAINT FOREIGN KEY(propietari) REFERENCES Venedor(idVen),
    CONSTRAINT FOREIGN KEY(nomProd) REFERENCES Producte(nomProd)
);

CREATE TABLE Item (
    idItem int AUTO_INCREMENT PRIMARY KEY,
    idStock int NOT NULL,
    idCom int NOT NULL,
    dataArribMag date,
    CONSTRAINT FOREIGN KEY(idCom) REFERENCES Comanda(idCom),
    CONSTRAINT FOREIGN KEY(idStock) REFERENCES Stock(idStock)
);

CREATE TABLE Incidencia (estat varchar (40) PRIMARY KEY);

CREATE TABLE Avis (
    idContr int NOT NULL,
    idAvis int AUTO_INCREMENT PRIMARY KEY,
    descripcio varchar(250),
    idItem int NOT NULL,
    CONSTRAINT FOREIGN KEY(idItem) REFERENCES Item(idItem),
    CONSTRAINT FOREIGN KEY(idContr) REFERENCES Controlador(idContr)
);

CREATE TABLE DistrZona(
    idDistr int,
    idZona int,
    PRIMARY KEY(idDistr, idZona),
    CONSTRAINT FOREIGN KEY(idDistr) REFERENCES Distribuidor(idDistr),
    CONSTRAINT FOREIGN KEY(idZona) REFERENCES Zona(idZona)
);

CREATE TABLE Repartiment (
    idRepartiment int PRIMARY KEY,
    idRep int,
    idCom int NOT NULL,
    dataRepartir DATE,
    idDom int NOT NULL,
    estat varchar(40),
    CONSTRAINT FOREIGN KEY(idDom) REFERENCES Domicili(idDomicili),
    CONSTRAINT FOREIGN KEY(estat) REFERENCES Incidencia(estat),
    CONSTRAINT FOREIGN KEY(idCom) REFERENCES Comanda(idCom),
    CONSTRAINT FOREIGN KEY(idRep) REFERENCES Repartidor(idRep)
);