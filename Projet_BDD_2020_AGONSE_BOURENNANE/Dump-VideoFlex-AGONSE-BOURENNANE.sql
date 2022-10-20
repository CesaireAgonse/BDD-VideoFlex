DROP TABLE IF EXISTS client CASCADE;
DROP TABLE IF EXISTS utilisateur CASCADE;
DROP TABLE IF EXISTS artiste CASCADE;
DROP TABLE IF EXISTS oeuvre CASCADE;
DROP TABLE IF EXISTS labbel CASCADE;
DROP TABLE IF EXISTS saga CASCADE;
DROP TABLE IF EXISTS regarde CASCADE;
DROP TABLE IF EXISTS note CASCADE;
DROP TABLE IF EXISTS suggestion CASCADE;
DROP TABLE IF EXISTS joue CASCADE;
DROP TABLE IF EXISTS appartient CASCADE;

-------------------------
---tables principales:---
-------------------------


CREATE TABLE utilisateur(
	idUtilisateur int PRIMARY KEY,
	pseudo varchar(25),
    ageU numeric(3) CHECK(ageU >= 0),
    photoprofileU text

);

INSERT INTO utilisateur(idUtilisateur,pseudo,ageU,photoprofileU) VALUES
(1,'Picsou',95,'public/img/pp/picsou.jpg'),
(2,'Riri',8,'public/img/pp/riri.jpg'),
(3,'Fifi',8,'public/img/pp/fifi.jpg'),
(4,'Loulou',8,'public/img/pp/loulou.jpg'),
(5,'Donald',25,''),
(6,'Mickey',25,'');

INSERT INTO utilisateur(idUtilisateur) VALUES
(11),
(12),
(13),
(14);

CREATE TABLE client(
	idClient int PRIMARY KEY,
    mdp varchar(25) NOT NULL,
	nomC varchar(25) NOT NULL,
    prenomC varchar(25) NOT NULL,
    dateFin date NOT NULL,
	adresse varchar(100),
	courriel varchar(100) NOT NULL,
    premium boolean default FALSE NOT NULL,
    Fils1 int REFERENCES utilisateur(idUtilisateur) UNIQUE NOT NULL,
    Fils2 int REFERENCES utilisateur(idUtilisateur) UNIQUE default NULL,
    Fils3 int REFERENCES utilisateur(idUtilisateur) UNIQUE default NULL,
    Fils4 int REFERENCES utilisateur(idUtilisateur) UNIQUE default NULL
);

INSERT INTO client(idClient,mdp,nomC,prenomC,dateFin,adresse,courriel,premium,Fils1,Fils2, Fils3, Fils4) VALUES
(1,'ViveLArgent','Balthazar','Picsou','2021/12/12','42 bis Avenue coffre Fort DonaldVille','picsou@disney.com',TRUE,1,2,3,4),
(2,'RSA','Duck','Donald','2021/12/12','42 bis Avenue RSA DonaldVille','donald@disney.com',FALSE,5,NULL,NULL,NULL),
(3,'monopole','Mouse','Mickey','2019/12/12','666 Avenue pognon DonaldVille','mickey@disney.com',FALSE,6,NULL,NULL,NULL);

-- Picsou est riche et a acheté vidéoflex premium et heberge les comptes de riri fifi et loulou.
-- Donald est pauvre et n'a pas le premium.
-- Mickey sert d'exemple de compte expiré.


CREATE TABLE artiste(
	idArtiste int PRIMARY KEY,
	nomA varchar(25),
    prenomA varchar(25),
    ageA numeric(3) default 18 CHECK(ageA >= 0),
    photoprofileA text

);
INSERT INTO artiste(idArtiste,nomA,prenomA,photoprofileA) VALUES
(1,'Columbus','Chris','public/img/acteur/Chris_Colombus.jpg'),
(2,'Cuaron','Alfonso','public/img/acteur/Alfonso_Cuaron.jpg'),
(3,'Newell','Mike','public/img/acteur/Mike_Newell.jpg'),
(4,'Yates','David','public/img/acteur/David_Yates.jpg'),
(5,'Tachikawa','Yuzuru','public/img/acteur/tachikawa_yusuru.jpg'),
(6,'Radcliff','Daniel','public/img/acteur/Daniel_Radcliff.jpg'),
(7,'Watson','Emma','public/img/acteur/emma_Watson.jpg'),
(8,'Fiennes','Ralph','public/img/acteur/Ralph_Fiennes.jpg'),
(9,'Ito','Setsuo','public/img/acteur/ito_setsuo.jpg'),
(10,'Fiennes-Tiffin','Hero','public/img/acteur/hero_Fiennes.jpg'),
(11,'Dillane','Frank','public/img/acteur/Frank_Dillane.jpg'),
(12,'Coulson','Christian','public/img/acteur/christian_coulsan.jpg'),
(13,'Phillips','Todd','public/img/acteur/Todd_Philipps.jpg'),
(14,'Cooper','Bradley','public/img/acteur/Bradley_Cooper.jpg');


CREATE TABLE saga(
	idSaga int PRIMARY KEY,
	nomS varchar(50),
    opus int default 0 CHECK (opus >= 0)
);

INSERT INTO saga(idSaga,nomS,opus) VALUES
(1,'Harry Potter',NULL),
(2,'Mob Psycho 100',1),
(3,'Mob Psycho 100',2),
(4,'joker',NULL),
(5,'Very Bad Trip',NULL),
(6,'A Star is Born',NULL);

-- harry potter saga de film
-- mob psycho 100 une serie avec 2 saisons


CREATE TABLE oeuvre(
	idOeuvre SERIAL PRIMARY KEY,
	nomO varchar(100),
    estSerie boolean NOT NULL,
    realisateur int,
    dateSort date,
    duree numeric(5),
    noteGlobale numeric(3) default 50,
    idSaga int,
    numOpus int default 0 CHECK (numOpus >= 0),
    affiche text default '',
    synopsis text default 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...',

    CONSTRAINT uniqueOeuvreSagaNumOpus UNIQUE(idSaga,numOpus),
    FOREIGN KEY (realisateur) REFERENCES artiste(idArtiste)
    ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (idSaga) REFERENCES saga(idSaga)
    ON DELETE SET NULL ON UPDATE CASCADE
);

INSERT INTO oeuvre(nomO,estSerie,realisateur,dateSort,duree,idSaga,numOpus,affiche) VALUES
('Harry Potter à l''ecole des sorciers',FALSE,1,'2001/01/15',3600,1,1,'public/img/affiche/harry_potter_1.jpg'),
('Harry Potter et la Chambre des Secrets',FALSE,1,'2002/01/15',3600,1,2,'public/img/affiche/harry_potter_2.jpg'),
('Harry Potter et le prisonier d''Askaban',FALSE,2,'2004/01/15',3600,1,3,'public/img/affiche/harry_potter_3.jpg'),
('Harry Potter et la coupe de feu',FALSE,3,'2005/01/15',3600,1,4,'public/img/affiche/harry_potter_4.jpg'),
('Harry Potter et l''ordre du Phoenix',FALSE,4,'2007/01/15',3600,1,5,'public/img/affiche/harry_potter_5.jpg'),
('Harry Potter et le prince de sang-mele',FALSE,4,'2009/01/15',3600,1,6,'public/img/affiche/harry_potter_6.jpg'),
('Harry Potter et les reliques de la mort partie 1',FALSE,4,'2010/01/15',3600,1,7,'public/img/affiche/harry_potter_7.jpg'),
('Harry Potter et les reliques de la mort partie 2',FALSE,4,'2011/01/15',3600,1,8,'public/img/affiche/harry_potter_8.jpg'),
('Arataka Reigen, autoproclamé Médium de génie ~ et Mob ~',TRUE,5,'2016/07/11',3600,2,1,'public/img/affiche/mob_psycho_100_S1.jpg'),
('Doutes d''ado ~ Entrée du club des télépathes ~',TRUE,5,'2016/07/18',3600,2,2,'public/img/affiche/mob_psycho_100_S1.jpg'),
('Invitation ~ En gros, devenir populaire ~',TRUE,5,'2016/07/25',3600,2,3,'public/img/affiche/mob_psycho_100_S1.jpg'),
('Rendez-vous pour idiots ~ Semblables ~',TRUE,5,'2016/08/01',3600,2,4,'public/img/affiche/mob_psycho_100_S1.jpg'),
('Samouraï déserteur ~ Un Médium et moi ~',TRUE,5,'2016/08/08',3600,2,5,'public/img/affiche/mob_psycho_100_S1.jpg'),
('Discordance ~ Pour le devenir ~',TRUE,5,'2016/08/15',3600,2,6,'public/img/affiche/mob_psycho_100_S1.jpg'),
('Exaltation ~ J''ai obtenu la perte ~',TRUE,5,'2016/08/22',3600,2,7,'public/img/affiche/mob_psycho_100_S1.jpg'),
('Le frère s''incline ~ L''intention de détruire ~',TRUE,5,'2016/08/29',3600,2,8,'public/img/affiche/mob_psycho_100_S1.jpg'),
('La griffe ~ 7e section ~',TRUE,5,'2016/01/05',3600,2,9,'public/img/affiche/mob_psycho_100_S1.jpg'),
('Aura maléfique ~ Le cerveau ~',TRUE,5,'2016/01/12',3600,2,10,'public/img/affiche/mob_psycho_100_S1.jpg'),
('Mon maître ~ Le leader ~',TRUE,5,'2016/01/19',3600,2,11,'public/img/affiche/mob_psycho_100_S1.jpg'),
('Mob et Reigen',TRUE,5,'2016/01/26',3600,2,12,'public/img/affiche/mob_psycho_100_S1.jpg'),
('En mille morceaux ~ Quelqu''un regarde ~',TRUE,5,'2016/01/15',3600,3,1,'public/img/affiche/mob_psycho_100_S2.jpg'),
('Légendes urbaines ~ Rencontre avec une légende ~',TRUE,5,'2016/01/15',3600,3,2,'public/img/affiche/mob_psycho_100_S2.jpg'),
('Un tas de dangers ~ Anormalité ~',TRUE,5,'2016/01/15',3600,3,3,'public/img/affiche/mob_psycho_100_S2.jpg'),
('A l’intérieur ~ Esprit maléfique ~',TRUE,5,'2016/01/15',3600,3,4,'public/img/affiche/mob_psycho_100_S2.jpg'),
('Discorde ~ Le choix ~',TRUE,5,'2016/01/15',3600,3,5,'public/img/affiche/mob_psycho_100_S2.jpg'),
('Une solitude blanchâtre',TRUE,5,'2016/01/15',3600,3,6,'public/img/affiche/mob_psycho_100_S2.jpg'),
('Au pied du mur ~ La vérité ~',TRUE,5,'2016/01/15',3600,3,7,'public/img/affiche/mob_psycho_100_S2.jpg'),
('Quand bien même ~ Aller de l''avant ~',TRUE,5,'2016/01/15',3600,3,8,'public/img/affiche/mob_psycho_100_S2.jpg'),
('Faites vos preuves ~ Rassemblement ~',TRUE,5,'2016/01/15',3600,3,9,'public/img/affiche/mob_psycho_100_S2.jpg'),
('Frictions ~ La catégorie musclée ~',TRUE,5,'2016/01/15',3600,3,10,'public/img/affiche/mob_psycho_100_S2.jpg'),
('Aux commandes ~ Le pouvoir de détection ~',TRUE,5,'2016/01/15',3600,3,11,'public/img/affiche/mob_psycho_100_S2.jpg'),
('Réinsertion sociale ~ L''amitié ~',TRUE,5,'2016/01/15',3600,3,12,'public/img/affiche/mob_psycho_100_S2.jpg'),
('Le combat final ~ L''ultime lueur ~',TRUE,5,'2016/01/15',3600,3,13,'public/img/affiche/mob_psycho_100_S2.jpg');

INSERT INTO oeuvre(nomO,estSerie,realisateur,dateSort,duree,idsaga,numOpus,affiche) VALUES
('Joker',FALSE,13,'2019/09/27',3600,4,NULL,'public/img/affiche/joker.jpg'),
('Very Bad Trip',FALSE,13,'2019/09/27',3600,5,1,'public/img/affiche/Very_bad_trip.jpg'),
('Very Bad Trip 2',FALSE,13,'2019/09/27',3600,5,2,'public/img/affiche/Very_bad_trip2.jpg'),
('Very Bad Trip 3',FALSE,13,'2019/09/27',3600,5,3,'public/img/affiche/Very_bad_trip3.jpg'),
('A star is born',FALSE,14,'2018/09/27',3600,6,NULL,'public/img/affiche/A_star_is_born.png');


UPDATE oeuvre
set synopsis = 'Orphelin, le jeune Harry Potter peut enfin quitter ses tyranniques oncle et tante Dursley lorsqu''un curieux messager lui révèle qu''il est un sorcier. À 11 ans, Harry va enfin pouvoir intégrer la légendaire école de sorcellerie de Poudlard, y trouver une famille digne de ce nom et des amis, développer ses dons, et préparer son glorieux avenir.' 
WHERE idOeuvre=1;
UPDATE oeuvre 
set synopsis = 'L''elfe Dobby a bien tenté d''empêcher Harry de retourner à l''École des Sorciers, frappée d''une terrible malédiction, mais Harry n''est pas près de laisser choir ses amis. Après une fugue et une rentrée scolaire plutôt chaotique, voici notre valeureux sorcier intégré à Poudlard. Les forces maléfiques n''ont qu''à bien se tenir.' 
WHERE idOeuvre=2;
UPDATE oeuvre 
set synopsis = 'Sirius Black, un dangereux sorcier criminel, s''échappe de la sombre prison d''Azkaban avec un seul et unique but : se venger d''Harry Potter, entré avec ses amis Ron et Hermione en troisième année à l''école de sorcellerie de Poudlard, où ils auront aussi à faire avec les terrifiants Détraqueurs.'
WHERE idOeuvre=3;
UPDATE oeuvre 
set synopsis = 'La quatrième année à l''école de Poudlard est marquée par le Tournoi des trois sorciers. Les participants sont choisis par la fameuse coupe de feu qui est à l''origine d''un scandale. Elle sélectionne Harry Potter alors qu''il n''a pas l''âge légal requis !'
WHERE idOeuvre=4;
UPDATE oeuvre 
set synopsis = 'Alors qu''il entame sa cinquième année d''études à Poudlard, Harry Potter découvre que la communauté des sorciers ne semble pas croire au retour de Voldemort, convaincue par une campagne de désinformation orchestrée par le Ministre de la Magie Cornelius Fudge.'
WHERE idOeuvre=5;
UPDATE oeuvre 
set synopsis = 'L''étau démoniaque de Voldemort se resserre sur l''univers des `Moldus'' et le monde de la sorcellerie. Poudlard a cessé d''être un havre de paix, le danger rode au coeur du château. Cependant, Dumbledore est plus décidé que jamais à préparer Harry à son combat final, désormais imminent.'
WHERE idOeuvre=6;
UPDATE oeuvre
set synopsis ='Le dénouement des aventures du jeune sorcier, commencées sept éditions plus tôt, approche. Plus terrifiant et puissant que jamais, Voldemort, accompagné de ses fidèles servants, les Mangemorts, contrôle quasiment l''ensemble du monde des sorciers. Pour le vaincre, Harry, Ron et Hermione n''ont d''autre choix que de détruire les Horcruxes, garants de l''immortalité du Seigneur des Ténèbres. Seulement, leur mission n''est pas sans risques.'
WHERE idOeuvre=7;
UPDATE oeuvre
set synopsis ='Dans la 2e Partie de cet épisode final, le combat entre les puissances du bien et du mal de l''univers des sorciers se transforme en guerre sans merci. Les enjeux n''ont jamais été si considérables et personne n''est en sécurité. Mais c''est Harry Potter qui peut être appelé pour l''ultime sacrifice alors que se rapproche l''ultime épreuve de force avec Voldemort.'
WHERE idOeuvre=8;


CREATE TABLE labbel(
	idLabbel int PRIMARY KEY,
	designation varchar(25)
);

INSERT INTO labbel(idLabbel,designation) VALUES
(1,'aventure'),
(2,'magie'),
(3,'manga'),
(4,'action'),
(5,'horreur'),
(6,'classique'),
(7,'drame'),
(8,'comedie'),
(9,'suspens'),
(10,'policier');

-- differents labbels proposés de base.

---------------------------------------
---tables secondaires/ associations:---
---------------------------------------

CREATE TABLE regarde(
	idUtilisateur int REFERENCES utilisateur(idUtilisateur), 
    idOeuvre int REFERENCES oeuvre(idOeuvre),
    dateVisionnage date NOT NULL,
    dateDerVisionnage date CHECK (dateVisionnage <= dateDerVisionnage) NOT NULL,
    timeCode int default 0 CHECK( timecode >= 0),
    
    PRIMARY KEY (idUtilisateur,idOeuvre)
);

INSERT INTO regarde(idUtilisateur,idOeuvre,dateVisionnage,dateDerVisionnage,timeCode) VALUES
(5,1,'2020/11/22','2020/11/22',2485),
(5,2,'2020/11/23','2020/11/23',3200),
(5,3,'2020/11/24','2020/11/24',3600),
(5,4,'2020/11/25','2020/11/25',360),
(5,5,'2020/11/26','2020/11/26',1234),
(5,6,'2020/11/27','2020/11/27',666),
(5,7,'2020/11/28','2020/11/28',2020),
(5,8,'2020/11/29','2020/11/29',2745),
(5,35,'2020/11/29','2020/11/29',2745),
(5,36,'2020/11/29','2020/11/29',2745),
(5,37,'2020/11/29','2020/11/29',2745),
(5,34,'2020/11/29','2020/11/29',2745);
--INSERT INTO regarde(idutilisateur,idoeuvre,datevisionnage,datedervisionnage,timeCode) VALUES (5,38,'2000/12/12','2000/12/12',1234);

UPDATE regarde set dateDerVisionnage = '2020/12/24',timeCode = 3500 WHERE idOeuvre=3;
---Donald a regardé tout harry potter et a reregardé le 3 car c'est le meilleur de la saga.


CREATE TABLE note(
	idUtilisateur int REFERENCES utilisateur(idUtilisateur),
    idOeuvre int REFERENCES oeuvre(idOeuvre),
    noteUtilisateur int CHECK (noteUtilisateur >= 0),
    
    PRIMARY KEY (idUtilisateur,idOeuvre)
);

INSERT INTO note(idUtilisateur,idOeuvre,noteUtilisateur) VALUES
(5,1,100),
(5,2,100),
(5,3,100),
(5,4,100),
(5,5,100),
(5,6,100),
(5,7,100),
(5,8,100),
(2,9,75),
(2,10,75),
(2,11,72),
(2,12,82),
(2,13,76),
(2,14,78),
(2,15,68),
(2,16,75),
(2,17,40),
(2,18,42),
(2,19,69),
(3,9,75),
(3,10,72),
(3,11,78),
(3,12,82),
(3,13,76),
(3,14,40),
(3,15,68),
(3,16,75),
(3,17,69),
(3,18,99),
(3,19,42);


--j'arrive pas à update la note global d'une oeuvre en faisant sa moyenne. :/

CREATE TABLE suggestion(
	idUtilisateur int REFERENCES utilisateur(idUtilisateur),
    idOeuvre int REFERENCES oeuvre(idOeuvre),
    idLabbel int REFERENCES labbel(idLabbel),
    
    CONSTRAINT PasDeSpam UNIQUE(idUtilisateur,idOeuvre,idlabbel)
);

INSERT INTO suggestion(idUtilisateur,idOeuvre,idLabbel) VALUES
(3,7,9),
(3,7,5),
(3,7,1);

--fifi recommande 3 nouveaux genres sur harry potter 7.

CREATE TABLE joue(
	idArtiste int REFERENCES artiste(idArtiste),
    idOeuvre int REFERENCES oeuvre(idOeuvre),
    roleArtiste varchar(25),

    CONSTRAINT acteurMemeRole UNIQUE(idArtiste,idOeuvre,roleArtiste)
);

INSERT INTO joue(idArtiste,IdOeuvre,roleArtiste) VALUES
(6,1,'Harry Potter'),
(6,2,'Harry Potter'),
(6,3,'Harry Potter'),
(6,4,'Harry Potter'),
(6,5,'Harry Potter'),
(6,6,'Harry Potter'),
(6,7,'Harry Potter'),
(6,8,'Harry Potter'),
(7,1,'Hermionne Granger'),
(7,2,'Hermionne Granger'),
(7,3,'Hermionne Granger'),
(7,4,'Hermionne Granger'),
(7,5,'Hermionne Granger'),
(7,6,'Hermionne Granger'),
(7,7,'Hermionne Granger'),
(7,8,'Hermionne Granger'),
(8,1,'Lord Voldemort'),
(8,4,'Lord Voldemort'),
(8,5,'Lord Voldemort'),
(8,7,'Lord Voldemort'),
(8,8,'Lord Voldemort'),
(9,10,'Mob'),
(9,11,'Mob'),
(9,12,'Mob'),
(9,13,'Mob'),
(9,14,'Mob'),
(9,15,'Mob'),
(9,16,'Mob'),
(9,17,'Mob'),
(9,18,'Mob'),
(9,19,'Mob'),
(9,20,'Mob'),
(9,21,'Mob'),
(9,22,'Mob'),
(9,23,'Mob'),
(9,24,'Mob'),
(9,25,'Mob'),
(9,26,'Mob'),
(9,27,'Mob'),
(9,28,'Mob'),
(9,29,'Mob'),
(9,30,'Mob'),
(9,31,'Mob'),
(9,32,'Mob'),
(9,33,'Mob'),
(10,6,'Tom Jedusor enfant'),
(11,6,'Tom Jedusor'),
(12,2,'Tom Jedusor'),
(14,38,'le bourré'),
(14,35,'Phil'),
(14,36,'phil'),
(14,37,'Phil');

-- petit casting
-- avec tom jedusor incarné par différents acteurs.



CREATE TABLE appartient(
	idLabbel int REFERENCES Labbel(idLabbel),
    idOeuvre int REFERENCES oeuvre(idOeuvre) UNIQUE,
    
    PRIMARY KEY (idLabbel,idOeuvre)
);

INSERT INTO appartient(idLabbel,idOeuvre) VALUES
(2,1),
(2,2),
(2,3),
(2,4),
(2,5),
(2,6),
(2,7),
(2,8),
(3,9),
(3,10),
(3,11),
(3,12),
(3,13),
(3,14),
(3,15),
(3,16),
(3,17),
(3,18),
(3,19),
(3,20),
(3,21),
(3,22),
(3,23),
(3,24),
(3,25),
(3,26),
(3,27),
(3,28),
(3,29),
(3,30),
(3,31),
(3,32),
(3,33),
(7,34),
(8,35),
(8,36),
(8,37),
(7,38);

-- les labbels officiels des oeuvres.