--Ally

-- 5 Customers min just did 1 employee-- INSERT FOR USER 
INSERT INTO User VALUES('U1234', 'Alex', 'Barnes', '345 Altern Ave Pierre SD 62946', '9920349367966379', 'alexbarnes@yahoo.com'); 
INSERT INTO User VALUES('U2345', 'Audrey', 'Carpenter', '43 Cuban Rd Boston MA 45619', '2869201584635895', 'aubreycarp@gmail.com');
INSERT INTO User VALUES('U3456', 'Vanessa', 'Hughes', '313 Grant Ave Chicago IL 89765', '7183519056615896', 'hugesvan@yahoo.com');
INSERT INTO User VALUES('U1343', 'Max', 'Downs', '678 Elk Rd Anchorage AK 91854', '3892807341952651', 'maxdowns@gmail.com');
INSERT INTO User VALUES('U4567', 'Zack', 'Brown', '876 Mulholland Dr Seattle WA 54234', '2526823852817545', 'brownzack@yahoo.com');
INSERT INTO User VALUES('E1243', 'Tate', 'Lloyd', '61 Hollywood Ave Austin TX 32987', '2443816250266677', 'tatelloyd@gmail.com');
INSERT INTO User VALUES('O1234', 'Alyssa', 'Anderson', '76 Oakland Ave Austin TX 32977', '2157438910244456', 'alyssahughes@gmail.com');

--Employee Table
INSERT INTO Employee VALUES('E1243');

--Owner Table
INSERT INTO Owners VALUES('O1234');

--Customer Table
INSERT INTO Customer VALUES('U1234'); -- Alex
INSERT INTO Customer VALUES('U2345'); -- Audrey
INSERT INTO Customer VALUES('U3456'); -- Vanessa
INSERT INTO Customer VALUES('U1343'); -- Max
INSERT INTO Customer VALUES('U4567'); -- Zack

--Shopping Cart Table
INSERT INTO Cart VALUES('24689', '0.00', 'U1234'); -- Alex
INSERT INTO Cart VALUES('26587', '0.00', 'U2345'); -- Audrey
INSERT INTO Cart VALUES('29874', '0.00', 'U3456'); -- Vanessa
INSERT INTO Cart VALUES('20954', '0.00', 'U1343'); -- Max
INSERT INTO Cart VALUES('20984', '0.00', 'U4567'); -- Zack

--Inventory Table
INSERT INTO Inventory Values('12340', 'Music');
INSERT INTO Inventory Values('12341', 'Movie');
INSERT INTO Inventory Values('12342', 'Game');

--Product Table -- Added a default column because need to have a value in other table using prod_id as a foreign key 
INSERT INTO Product VALUES('0000000000', 'Def', '0.00', 'Def', 'Def'); -- Default? Empty?
INSERT INTO Product VALUES('1926090274', 'Mezmerize', '19.99', 'Vinyl', 'Metal');
INSERT INTO Product VALUES('6569558771', 'OKComputer', '19.99', 'Vinyl', 'AltRock');
INSERT INTO Product VALUES('2271888804', 'Purple', '19.99', 'Vinyl', '90sRock');
INSERT INTO Product VALUES('5681457561', 'Big Lebowski', '6.99', 'Movie', 'Comedy/Crime');
INSERT INTO Product VALUES('3000451120', 'Donnie Darko', '8.99', 'Movie', 'Sci-fi');
INSERT INTO Product VALUES('3823637662', 'The Prestige', '9.99', 'Movie', 'Thriller/Sci-fi');
INSERT INTO Product VALUES('7215084108', 'God of War', '49.99', 'Game', 'Action/Adventure');
INSERT INTO Product VALUES('3217454108', 'Elden Ring', '59.99', 'Game', 'Action/RPG');
INSERT INTO Product VALUES('3063184236', 'RimWorld', '34.99', 'Game', 'Survival/Sim');
INSERT INTO Product VALUES('1948709850', 'La La Land', '8.99', 'Movie', 'Romantic/Comedy');
INSERT INTO Product VALUES('7415304205', 'Parasite', '14.99', 'Movie', 'Horror/Thriller');
INSERT INTO Product VALUES('8700958003', 'Train to Busan', '7.99', 'Movie', 'Zombie/Horror');
INSERT INTO Product VALUES('2341487508', 'Baldurs Gate 3', '59.99', 'Game', 'RPG');
INSERT INTO Product VALUES('7267548852', 'Phasmophobia', '13.99', 'Game', 'Horror/Investigation');
INSERT INTO Product VALUES('3773245407', 'Stardew Valley', '14.99', 'Game', 'Farming/Sim');
INSERT INTO Product VALUES('7577360094', 'Monster Hunter: World', '29.99', 'Game', 'Action/RPG');
INSERT INTO Product VALUES('1898751726', 'Project Zomboid', '19.99', 'Game', 'Survival/RPG');
INSERT INTO Product VALUES('1480577991', 'Powerwash Simulator', '24.99', 'Game', 'Sim');
INSERT INTO Product VALUES('7926870520', 'Omori', '19.99', 'Game', 'JRPG');
INSERT INTO Product VALUES('8257397556', 'Frostpunk', '29.99', 'Game', 'Survival/Sim');

-- Orders Table -- Default 0 values for all because nothing is placed yet (only non default is employee ID)
INSERT INTO Orders VALUES('00000000000000000000', 'Default Address', '0000000000000000', '0/00/0000', 'No notes on this order', 'Default', 'E1243');

INSERT INTO InCart VALUES ('24689', '0000000000', null, '0.00'); -- Alex
INSERT INTO InCart VALUES ('26587', '0000000000', null, '0.00'); -- Audrey
INSERT INTO InCart VALUES ('29874', '0000000000', null, '0.00'); -- Vanessa
INSERT INTO InCart VALUES ('20954', '0000000000', null, '0.00'); -- Max
INSERT INTO InCart VALUES ('20984', '0000000000', null, '0.00'); -- Zack

--PlacingOrder 
INSERT INTO PlacingOrder VALUES ('U1234', '24689', '00000000000000000000', '00000000000000000000', '0000.00'); -- Alex
INSERT INTO PlacingOrder VALUES ('U2345', '26587', '00000000000000000000', '00000000000000000000', '0000.00'); -- Audrey
INSERT INTO PlacingOrder VALUES ('U3456', '29874', '00000000000000000000', '00000000000000000000', '0000.00'); -- Vanessa
INSERT INTO PlacingOrder VALUES ('U1343', '20954', '00000000000000000000', '00000000000000000000', '0000.00'); -- Max
INSERT INTO PlacingOrder VALUES ('U4567', '20984', '00000000000000000000', '00000000000000000000', '0000.00'); -- Zack

--Holds -- prod_id, inv_id, and quantity in stock
INSERT INTO Holds VALUES ('1926090274', '12340', '100'); -- Mesmerize
INSERT INTO Holds VALUES ('6569558771', '12340', '100'); -- OKComputer
INSERT INTO Holds VALUES ('2271888804', '12340', '100'); -- Purple
INSERT INTO Holds VALUES ('5681457561', '12341', '100'); -- Big Lebowski
INSERT INTO Holds VALUES ('3000451120', '12341', '100'); -- Donnie Darko
INSERT INTO Holds VALUES ('3823637662', '12341', '100'); -- The Prestige
INSERT INTO Holds VALUES ('7215084108', '12342', '100'); -- God of War
INSERT INTO Holds VALUES ('3217454108', '12342', '100'); -- Elden Ring
INSERT INTO Holds VALUES ('3063184236', '12342', '100'); -- RimWorld
INSERT INTO Holds VALUES ('1948709850', '12341', '100'); -- La La Land
INSERT INTO Holds VALUES ('7415304205', '12341', '100'); -- Parasite
INSERT INTO Holds VALUES ('8700958003', '12341', '100'); -- Train to Busan
INSERT INTO Holds VALUES ('2341487508', '12342', '100'); -- Baldurs Gate 3
INSERT INTO Holds VALUES ('7267548852', '12342', '100'); -- Phasmophobia
INSERT INTO Holds VALUES ('3773245407', '12342', '100'); -- Stardew Valley
INSERT INTO Holds VALUES ('7577360094', '12342', '100'); -- Monster Hunter: World
INSERT INTO Holds VALUES ('1898751726', '12342', '100'); -- Project Zomboid
INSERT INTO Holds VALUES ('1480577991', '12342', '100'); -- Powerwash Simulator
INSERT INTO Holds VALUES ('7926870520', '12342', '100'); -- Omori
INSERT INTO Holds VALUES ('8257397556', '12342', '100'); -- Frostpunk

-- ProductOrder Table prod_id, ord_num, qty of each product
INSERT INTO ProductOrder VALUES ('0000000000', '00000000000000000000', '0000');