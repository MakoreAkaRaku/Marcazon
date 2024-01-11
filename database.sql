CREATE DATABASE Marcazon;

USE Marcazon;

CREATE TABLE EstatComanda(estatC varchar(30) PRIMARY KEY);

INSERT INTO
    EstatComanda (estatC)
VALUES
    ('Comprant'),
    ('Entregat'),
    ('Cancelat'),
    ('En Repartiment'),
    ('Enviat'),
    ('Pendent de enviar'),
    ('Pendent de pagar'),
    ('Pagat'),
    ('No rebut'),
    ('Enviant-se');

CREATE TABLE EstatVenedor(estat varchar(10) PRIMARY KEY);

INSERT INTO
    EstatVenedor (estat)
VALUES
    ('BO'),
    ('DOLENT'),
    ('SOSPITOS');

CREATE TABLE Zona (
    idZona int AUTO_INCREMENT PRIMARY KEY,
    nomZona varchar(50),
    longitud float,
    latitud float
);

CREATE TABLE Poblacio (
    nomPoble varchar (50),
    codiPostal int AUTO_INCREMENT PRIMARY KEY,
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
    idCom INT AUTO_INCREMENT PRIMARY KEY,
    idCompr INT NOT NULL,
    idContr INT,
    dataPagat DATE,
    haPagat boolean NOT NULL,
    estatC varchar(30) NOT NULL,
    idDistr INT,
    dataModif DATE,
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
    preu float NOT NULL,
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

DELIMITER //
CREATE TRIGGER afegeix_Com BEFORE INSERT ON Comanda 
FOR EACH ROW
BEGIN
    DECLARE numActiveCom INT;
    SELECT COUNT(Comanda.idCom) INTO numActiveCom FROM Comanda 
        WHERE Comanda.idCompr = NEW.idCompr AND Comanda.estatC = 'Comprant';
    IF (numActiveCom > 0) THEN
        signal sqlstate '45000' set message_text = 'Ja te una compra en curs';
    END IF;
    SET NEW.dataModif = NOW();
    SET NEW.estatC = 'Comprant';
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER modif_Com BEFORE UPDATE ON Comanda FOR EACH ROW 
    BEGIN
        SET NEW.dataModif = NOW();
        IF (NEW.estatC = 'Pendent de pagar') THEN
            IF (NEW.haPagat = TRUE) THEN
                SET NEW.dataPagat = NOW();
                SET NEW.estatC = 'Pagat';
            END IF;
        END IF;
    END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER crea_item BEFORE INSERT ON Item FOR EACH ROW 
    BEGIN
        UPDATE Comanda SET Comanda.dataModif = NOW()
            WHERE Comanda.idCom = NEW.idCom;
        UPDATE Stock SET qttProd = qttProd-1 
            WHERE stock.idStock = NEW.idStock;
    END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER elimina_item BEFORE DELETE ON Item FOR EACH ROW 
    BEGIN
        UPDATE Comanda SET Comanda.dataModif = NOW()
            WHERE Comanda.idCom = OLD.idCom;
        UPDATE Stock SET qttProd = qttProd+1 
            WHERE stock.idStock = OLD.idStock;
    END //
DELIMITER ;

INSERT INTO zona (idZona,nomZona, longitud, latitud) VALUES
    (   1,
        "Palma de Mallorca",
        123132,
        1231451
    ),
    (2,"Es Pla", 45747, 2352352),
    (3,"Mitjorn", 34533, 54745),
    (4,"Es Raiguer", 345723, 343457),
    (5,"Serra de Tramuntana",23683,93453),
    (6,"Llevant", 23576, 43363);

INSERT INTO Distribuidor (nomEntitat) VALUES
('Uber Delivery'),('UPS'),('CTT'),('Correos Esp'),('DHL'),('SEUR');

INSERT INTO Categoria (cat) VALUES
('Tecnologia'),
('Bellesa'),
('Videojoc'),
('Lectura'),
('Medicina'),
('Electronica'),
('Carpinteria'),
('Moda'),
('Roba'),
('Ordinadors'),
('Televisors'),
('Smartphones'),
('Portatils'),
('Jocs de taula'),
('Decoracio de la llar'),
('Mobles'),
('Electrodomestics'),
('Iluminacio'),
('Jardineria'),
('Productes de Mascota'),
('Informatica'),
('Accessoris'),
('Comestibles'),
('Eines'),
('Esports'),
('Musica'),
('Utensilis'),
('Oci');

INSERT INTO Producte (nomProd,pathImg) VALUES
('The Legend of Zelda Phantom Hourglass','tlozph.png'),
('Huawei Favicom','huaweifavicom.png'),
('Carregador tipus C','carregadorc.png'),
('Microones','microones.png'),
('Joc de cartes "Cuando el Pueblo duerme"','cuandopuebloduerme.png'),
('Puzzle de Hanna Montana','hanna.png'),
('Pokemon Diamante','pkmnd.png'),
('Portatil Asus 2000','asus2000.png'),
('Pokemon Perla','pkmnp.png'),
('Pokemon Esmeralda','pkmnesmeralda.png'),
('Anell 9.25k plata','anell925k.png'),
('Penjoll alafeuria','penjollalafeuria.png'),
('Televisor de plasma','tlplasma.png'),
('Nevera Samsung 231I40','neverasamsung.png'),
('Ordinador Pro Gaming MSI 3200K','ordgaming3200k.png'),
('Memoria SSD 32GB','memoriassd32gb.png'),
('Magic the Gathering Edicio Phyrexia 30 sobres','mtgphyrexia.png'),
('Monopoly','monopoly.png'),
('Catan','catan.png'),
('Memoria SSD 1TB','memoriassd1tb.png'),
('Destornillador Alfa','destAlpha.png'),
('Taça veneciana','tasaveneciana.png')
;

INSERT INTO ProdCat (nomProd,cat) VALUES 
('The Legend of Zelda Phantom Hourglass','Videojoc'),
('The Legend of Zelda Phantom Hourglass','Oci'),
('Huawei Favicom','Informatica'),
('Huawei Favicom','Tecnologia'),
('Huawei Favicom','Electrodomestics'),
('Huawei Favicom','Portatils'),
('Carregador tipus C','Informatica'),
('Carregador tipus C','Tecnologia'),
('Carregador tipus C','Electronica'),
('Microones','Electrodomestics'),
('Microones','Tecnologia'),
('Joc de cartes \"Cuando el Pueblo duerme\"','Jocs de taula'),
('Joc de cartes \"Cuando el Pueblo duerme\"','Oci'),
('Puzzle de Hanna Montana','Jocs de taula'),
('Puzzle de Hanna Montana','Oci'),
('Pokemon Diamante','Oci'),
('Pokemon Diamante','Videojoc'),
('Portatil Asus 2000','Tecnologia'),
('Portatil Asus 2000','Portatils'),
('Portatil Asus 2000','Ordinadors'),
('Pokemon Perla','Oci'),
('Pokemon Perla','Videojoc'),
('Pokemon Esmeralda','Oci'),
('Pokemon Esmeralda','Videojoc'),
('Anell 9.25k plata','Accessoris'),
('Anell 9.25k plata','Moda'),
('Penjoll alafeuria','Accessoris'),
('Penjoll alafeuria','Moda'),
('Televisor de plasma','Electrodomestics'),
('Televisor de plasma','Tecnologia'),
('Nevera Samsung 231I40','Tecnologia'),
('Nevera Samsung 231I40','Electrodomestics'),
('Ordinador Pro Gaming MSI 3200K','Ordinadors'),
('Ordinador Pro Gaming MSI 3200K','Electrodomestics'),
('Ordinador Pro Gaming MSI 3200K','Informatica'),
('Ordinador Pro Gaming MSI 3200K','Oci'),
('Memoria SSD 32GB','Informatica'),
('Memoria SSD 32GB','Tecnologia'),
('Memoria SSD 32GB','Electronica'),
('Magic the Gathering Edicio Phyrexia 30 sobres','Jocs de taula'),
('Magic the Gathering Edicio Phyrexia 30 sobres','Oci'),
('Monopoly','Jocs de taula'),
('Monopoly','Oci'),
('Catan','Oci'),
('Catan','Jocs de taula'),
('Memoria SSD 1TB','Informatica'),
('Memoria SSD 1TB','Tecnologia'),
('Memoria SSD 1TB','Electronica'),
('Destornillador Alfa','Eines'),
('Taça veneciana','Decoracio de la llar'),
('Taça veneciana','Utensilis')
;

INSERT INTO Venedor (nom,nickname,pwd,estatVen) VALUES
('Kiko Rivera','kikete12','1122','BO'),
('Guillem Altamir','venedor1','1122','BO'),
('Anselm Turmeda','venedor2','1122','BO'),
('Miquel Angel','venedor3','1122','BO'),
('Aranta Navarro','venedor4','1122','BO'),
('Esteve Miramer','venedor5','1122','BO'),
('Toni Andreu','venedor6','1122','BO'),
('Arnau Sacabu','venedor7','1122','BO'),
('Tonyeta Rivera','venedor8','1122','BO'),
('Artensi Filiguer','venedor9','1122','BO'),
('Marc Roman','venedor10','1122','BO');

INSERT INTO Controlador (nom,nickname,pwd) VALUES
('Anselm Turmeda','controlador2','1122'),
('Guillem Altamir','controlador3','1122'),
('Anselm Turmeda','controlador4','1122'),
('Miquel Angel','controlador5','1122'),
('Aranta Navarro','controlador6','1122'),
('Esteve Miramer','controlador7','1122'),
('Toni Andreu','controlador8','1122'),
('Joan Barcelo','controlador9','1122'),
('Andreu Mesquida','controlador10','1122'),
('Tomeu Tortella','controlador11','1122'),
('Marc Roman','controlador12','1122');

INSERT INTO Comprador (nom,nickname,pwd) VALUES
('Anselm Turmeda','Anselmo','1122'),
('Guillem Altamir','guillaume21','1122'),
('Anselm Turmeda','curucall','1122'),
('Miquel Angel','fiolet12','1122'),
('Aranta Navarro','aretxa','1122'),
('Esteve Miramer','aravabo','1122'),
('Toni Andreu','1carreter','1122'),
('Joan Barcelo','altamir','1122'),
('Andreu Mesquida','pixeris21','1122'),
('Tomeu Tortella','tortella','1122'),
('Marc Roman','mrc939','1122');

INSERT INTO Poblacio (nomPoble,zona) VALUES
('Mancor de la Vall',FLOOR(RAND()*(6)+1)),
('Inca',FLOOR(RAND()*(6)+1)),
('Son Servera',FLOOR(RAND()*(6)+1)),
('Palma',FLOOR(RAND()*(6)+1)),
('Manacor',FLOOR(RAND()*(6)+1)),
('Valldemossa',FLOOR(RAND()*(6)+1)),
('Soller',FLOOR(RAND()*(6)+1)),
('Sineu',FLOOR(RAND()*(6)+1)),
('Santa Margalida',FLOOR(RAND()*(6)+1)),
('Alcudia',FLOOR(RAND()*(6)+1)),
('Pollença',FLOOR(RAND()*(6)+1)),
('Arta',FLOOR(RAND()*(6)+1)),
('Alaior',FLOOR(RAND()*(6)+1)),
('Alaro',FLOOR(RAND()*(6)+1)),
('Consell',FLOOR(RAND()*(6)+1)),
('Marratxi',FLOOR(RAND()*(6)+1)),
('Santa Maria del Cami',FLOOR(RAND()*(6)+1)),
('Ses Salines',FLOOR(RAND()*(6)+1)),
('PuigPunyent',FLOOR(RAND()*(6)+1)),
('Sencelles',FLOOR(RAND()*(6)+1)),
('Selva',FLOOR(RAND()*(6)+1)),
('Petra',FLOOR(RAND()*(6)+1)),
('Porreres',FLOOR(RAND()*(6)+1)),
('Sa Pobla',FLOOR(RAND()*(6)+1)),
('Calvia',FLOOR(RAND()*(6)+1)),
('Binissalem',FLOOR(RAND()*(6)+1)),
('Banyalbufar',FLOOR(RAND()*(6)+1)),
('Ariany',FLOOR(RAND()*(6)+1)),
('Andratx',FLOOR(RAND()*(6)+1)),
('Algaida',FLOOR(RAND()*(6)+1)),
('Escorca',FLOOR(RAND()*(6)+1)),
('Esporles',FLOOR(RAND()*(6)+1)),
('Estellencs',FLOOR(RAND()*(6)+1)),
('Felanitx',FLOOR(RAND()*(6)+1)),
('Lloseta',FLOOR(RAND()*(6)+1)),
('Llubi',FLOOR(RAND()*(6)+1)),
('Lloret de Vistalegre',FLOOR(RAND()*(6)+1)),
('Llucmajor',FLOOR(RAND()*(6)+1)),
('Montuiri',FLOOR(RAND()*(6)+1))
;

INSERT INTO Domicili (numPis,carrer,numCasa,codiPostal,propietari) VALUES
(1,"C.Massanella",1,2,6),
(0,"C.Bisbe",2,2,2),
(3,"C.Bananero",32,2,3),
(1,"C.Son Morro",6,3,5),
(4,"C.Maria sa Farinera",40,2,1),
(4,"C.Eusebi Cortes",10,4,1)
;

INSERT INTO Stock (propietari,nomProd,qttProd,preu) VALUES
(FLOOR(RAND()*(11)+1),'Taça Veneciana',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Destornillador Alfa',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Memoria SSD 1TB',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Memoria SSD 32GB',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Ordinador Pro Gaming MSI 3200K',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Catan',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Pokemon Diamante',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Pokemon Esmeralda',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Pokemon Perla',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Televisor de plasma',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'The Legend of Zelda Phantom Hourglass',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Huawei Favicom',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Carregador Tipus C',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Huawei Favicom',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Magic the Gathering Edicio Phyrexia 30 sobres',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Monopoly',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Portatil Asus 2000',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Penjoll alafeuria',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Anell 9.25k plata',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Microones',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Magic the Gathering Edicio Phyrexia 30 sobres',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Joc de cartes "Cuando el Pueblo duerme"',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Memoria SSD 1TB',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Taça veneciana',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Catan',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Taça veneciana',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Puzzle de Hanna Montana',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Monopoly',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Joc de cartes "Cuando el Pueblo duerme"',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Televisor de plasma',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Monopoly',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Portatil Asus 2000',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Nevera Samsung 231I40',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Magic the Gathering Edicio Phyrexia 30 sobres',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Memoria SSD 1TB',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Ordinador Pro Gaming MSI 3200K',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Televisor de plasma',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Catan',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2)),
(FLOOR(RAND()*(11)+1),'Microones',FLOOR(RAND()*(100)+1),FLOOR(RAND()*(50)+1)+ROUND(RAND()*100,2))
;

DELIMITER //
CREATE PROCEDURE afegir_prod_carret(IN stock INT, IN qtt INT,IN comanda INT)
BEGIN
    DECLARE qttProd INT;
    SET qttProd = 0;
    START TRANSACTION;
    -- Cercam la qtt de productes que queden de l'stock.
    SELECT Stock.qttProd INTO qttProd from Stock
        WHERE Stock.idStock = stock;
    IF (qtt > qttProd) THEN
        ROLLBACK;
        signal sqlstate '45000' set message_text = 'La quantitat de productes demanats es mes gran de la que el venedor disposa';
    END IF;
    -- Com que hem fet un trigger, ja es va descontant a mesura que feim un insert
    createItem: LOOP            
        IF (qtt = 0) THEN
            LEAVE createItem;
        END IF;
        SET qtt = qtt - 1;
        INSERT INTO Item (idStock,idCom) VALUES (stock,comanda);
    END LOOP createItem;
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE modif_prod_carret(IN stock INT, IN qtt INT,IN comanda INT)
BEGIN
    DECLARE qttProdCom,tmp_item INT;
    DECLARE var_cursor CURSOR FOR SELECT Item.idItem FROM Item WHERE Item.idCom = comanda AND Item.idStock = stock;
    SET qttProdCom = 0;
    SET tmp_item = -1;
    START TRANSACTION;
    -- Cercam la qtt de productes que hi ha dins la comanda.
    SELECT COUNT(Item.idItem) INTO qttProdCom FROM Item
        WHERE Item.idCom = comanda AND Item.idStock = stock;
    IF (qttProdCom <= 0) THEN
        ROLLBACK;
        signal sqlstate '45000' set message_text = 'Intentes esborrar productes que no existeixen a la comanda';
    END IF;
    IF(qtt <= qttProdCom) THEN
        SET qtt = qttProdCom-qtt;
        -- Com que hem fet un trigger, ja es va descontant a mesura que feim un insert
        OPEN var_cursor;
        deleteItem: LOOP            
            IF (qtt = 0) THEN
                LEAVE deleteItem;
            END IF;
            SET qtt = qtt - 1;
            FETCH var_cursor INTO tmp_item;
            DELETE FROM Item WHERE item.idItem = tmp_item;
        END LOOP deleteItem;
        CLOSE var_cursor;
    END IF;
    IF (qtt > qttProdCom) THEN
        -- Vol dir que n'hem d'afegir mes.
        SET qtt = qtt-qttProdCom;
        CALL afegir_prod_carret(stock,qtt,comanda);
    END IF;
    COMMIT;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE tanca_comanda(IN comanda INT,IN domicili INT)
BEGIN
    DECLARE estat varchar(30);
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION ROLLBACK;
    START TRANSACTION;
    SELECT Comanda.estatC INTO estat FROM Comanda
        WHERE Comanda.idCom = comanda;
    IF (estat = 'Comprant') THEN
        INSERT INTO Repartiment (idCom,idDom) VALUES(comanda,domicili);
        UPDATE Comanda SET Comanda.estatC = 'Pendent de pagar' WHERE Comanda.idCom = comanda;
    END IF;
    COMMIT;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE afegeix_avis(IN idContr INT, IN idItem INT)
BEGIN 
	DECLARE v_item INT;
	DECLARE numAvisos, idVenedor INT;
	DECLARE v_estatVen VARCHAR(10);
    -- declaram un handler per a excepcions sql que desfa la transaccio en cas d'error.
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION ROLLBACK;
	-- Primer observam si l'item compleix amb els requisits per ser avisat.
	SELECT item.idItem INTO v_item FROM item
	    JOIN comanda ON comanda.idCom = item.idCompr
            WHERE item.idItem = idItem
            AND comanda.haPagat = TRUE
            AND DATEDIFF(comanda.dataPagat, CURDATE()) >= 5;
	-- si son iguals i no existeix ja l'avís per a aquest item, vol dir que afegim avis
	IF (v_item = idItem) THEN
        START TRANSACTION; -- a partir d'aqui s'afegeixen dades i s'alteren files, per tant, per mantenir la coherència es procedeix a fer una transaction.
        INSERT INTO avis (avis.idContr,avis.idItem,avis.descripcio)
	        VALUES (idContr,idItem,"Aquest item hauria d'haver sigut entregat al magatzem principal");
	    -- Obtenim l'identificador del venedor d'aquest item
	    SELECT idVen INTO idVenedor FROM stock
	    JOIN item ON item.idStock = stock.idStock
            WHERE item.idItem = idItem;
	    -- Contam el nombre d'avisos que té.
	    SELECT COUNT(avis.idAvis) INTO numAvisos FROM avis
	    JOIN item ON item.idItem = avis.idItem
	    JOIN stock ON stock.idItem = item.idItem
	        WHERE stock.idVen = idVenedor;
	    -- Extreim l'estat actual del venedor.
	    SELECT estatVen INTO v_estatVen FROM venedor
	    WHERE idVen = idVenedor;
	    -- Procedim a observar si amb el nou avís hem de canviar el seu estat.
	    IF (numAvisos >= 6 AND v_estatVen = 'SOSPITOS') THEN -- Si ja era SOSPITOS i duu 6 avisos, ara es DOLENT
	        UPDATE venedor SET estatVen = 'DOLENT' WHERE idVen = idVenedor;
	    ELSEIF (numAvisos >= 3 AND v_estatVen = 'BO') THEN -- Si era BO i duu 3 avisos, ara es SOSPITOS
	        UPDATE venedor SET estatVen = 'SOSPITOS' WHERE idVen = idVenedor;
	    END IF;
        COMMIT;
    END IF;
END //
DELIMITER ;

SET GLOBAL event_scheduler=ON;

DELIMITER //
CREATE EVENT backup_comandes
ON SCHEDULE EVERY 1 DAY
    DO
    BEGIN
        DECLARE v_pagat,finished BOOLEAN;
        DECLARE v_distr,v_Com,v_Compr,v_Contr,lastbCom INT;
        DECLARE estat varchar(30);
        DECLARE v_dPagat,darreraModifB,darreraModif DATE;
        
        DECLARE comanda_cursor CURSOR FOR SELECT idCom,idCompr,idContr,dataPagat,haPagat,estatC,idDistr,dataModif FROM Comanda ORDER BY idCom DESC;
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = TRUE;
        DECLARE CONTINUE HANDLER FOR SQLEXCEPTION ROLLBACK;
        
        CREATE TABLE IF NOT EXISTS backup_comanda LIKE comanda;
        
        START TRANSACTION;
        SELECT MAX(backup_comanda.idCom) INTO lastbCom FROM backup_comanda;
        SET finished = FALSE;
        
        OPEN comanda_cursor;
        -- Afegim els nous elements que no té el backup.
        addNewElements: LOOP
            FETCH comanda_cursor INTO v_Com,v_Compr,v_Contr,v_dPagat,v_pagat,estat,v_distr,darreraModif;
            IF( finished = TRUE) THEN
                LEAVE addNewElements;
            END IF;
            IF (v_Com = lastbCom) THEN
                LEAVE addNewElements;
            END IF;
            INSERT INTO backup_comanda (idCom,idCompr,idContr,dataPagat,haPagat,estatC,idDistr,dataModif) 
                VALUES(v_Com,v_Compr,v_Contr,v_dPagat,v_pagat,estat,v_distr,darreraModif);
        END LOOP addNewElements;
        -- Modificam els elements ja existents que han sofrit canvis.
        updateModifiedComanda: LOOP
            IF(finished = TRUE) THEN
                LEAVE updateModifiedComanda;
            END IF;
            SELECT backup_comanda.dataModif INTO darreraModifB FROM backup_comanda WHERE backup_comanda.idCom = v_Com;
            IF (darreraModifB <= darreraModif) THEN
                UPDATE backup_comanda 
                SET idCompr = v_Compr,
                    idContr = v_Contr,
                    dataPagat = v_dPagat,
                    haPagat = v_pagat,
                    estatC = estat,
                    idDistr = v_distr,
                    dataModif = darreraModif
                WHERE idCom = v_Com;
            END IF;
            FETCH comanda_cursor INTO v_Com,v_Compr,v_Contr,v_dPagat,v_pagat,estat,v_distr,darreraModif;
        END LOOP updateModifiedComanda;
        CLOSE comanda_cursor;     
        COMMIT;
    END //
DELIMITER ;