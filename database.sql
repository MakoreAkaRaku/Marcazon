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

insert into zona (nomZona, longitud, latitud) values
("Palma de Mallorca",123132,1231451),
("Es Pla",45747,2352352),
("Mitjorn",34533,54745),
("Es Raiguer",345723,343457),
("Serra de Tramuntana",23683,93453),
("Llevant",23576,43363);

insert into 

INSERT INTO producte ()


DELIMITER //
CREATE PROCEDURE avisa(IN idControler, IN idItem)
BEGIN
    declare varcursor CURSOR FOR
    SELECT idItem,data FROM comanda inner join item ON comanda.idCom = item.idCom
    WHERE DATEDIFF(data,NOW()) = 5;
    declare continue handler for not found set sortir=1;
    set sortir=0;
    open varcursor;
    iteracio: LOOP
    insert into avis (idContr,idItem,descripcio)
    VALUE (,,"Your item delivered to the main storage has already expired");
    END LOOP;
    CLOSE varcursor;
END;
DELIMITER ;

select item.idItem from Comanda
            join item on Comanda.pagat = true 
            AND item.idCom = Comanda.idCom 
            AND item.idItem NOT IN (SELECT idItem from Avis) -- Si l'item ja ha sigut avisat, no l'incloem...

delimiter //
create procedure afegeix_avis(IN idContr INT, IN idItem INT)
BEGIN
    declare item INT;
    
    select idItem into item 
    from item 
    join comanda on comanda.idCom = item.idCompr
    where item.idItem = idItem 
        and pagat = true 
        and DATEDIFF(comanda.dataPagat, NOW()) >= 5;

    if (item = idItem) then
        insert into avis (idContr, idItem, descripcio) 
        values (idContr, idItem, "Aquest item hauria d'haver sigut entregat al magatzem principal");
    end if;
END //
delimiter ;



/*
create procedure nota_final()
BEGIN
    declare superaminims,acabar int;
    declare actPonder float;
    declare actEstudiant int;
    declare notaAct float;
    declare varcursor CURSOR FOR
    select idProva,idEstudiant,nota,minim,pes from nota JOIN prova on prova.idProva = nota.idProva ORDER BY idEstudiant;
    declare continue handler for not found;
    set acabar=1;
    open varcursor;
    iteracio: LOOP
    set actEstudiant = -1;
    fetch varcursor into var_prova,var_estudiant,var_nota_estudiant,var_nota_min,var_pes;
    if acabar=1 THEN
        leave iteracio;
    end if;
    if (actEstudiant != var_estudiant && actEstudiant != -1)
        update estudiant notafinal=notaAct where idEstudiant=actEstudiant;
        notaAct = 0;
        actPonder = 0;
    else
        if (var_nota_estudiant < var_nota_min)
            superaminims = 0;
        end if;

    end if;
    actEstudiant = var_estudiant;
    END LOOP iteracio;
    close varcursor;
END;
*/

/*
CREATE PROCEDURE XXX() BEGIN VARIABLES; 
	variables;
	cursors;
	tractament d 'errors;
	    obrir cursor;
	    loop;
	    	llegir cursor;
	        controlar error cursor;
	        logica del programa;
	  	end loop;
	   	tancar cursor;
	end;

*/


/*
delimiter / /

CREATE PROCEDURE XXX() BEGIN DECLARE 
	DECLARE done INT DEFAULT FALSE;
	DECLARE a CHAR(16);
	DECLARE b, c INT;
	DECLARE participants_best_temps CURSOR FOR
	SELECT
	    idciclista,
	    sec_to_time(sum(time_to_sec(temps))) AS total,
	    etapa
	FROM ciclista
	    JOIN participa on ciclista.idciclista = participa.etapa
	)
	ORDER BY participa.temps ASC;
	tractament d 'errors;
	    obrir cursor;
	    classificat=1;
	    loop;
	    	llegir cursor;
	        controlar error cursor;
	        SEC_TO_TIME()
	        if classificat=1 then insereix sense dif;
	        else insereix amb dif;
	  	end loop;
	   	tancar cursor;
	end;


delimiter / /
*/