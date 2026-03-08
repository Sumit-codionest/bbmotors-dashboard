USE car_dealership;
INSERT INTO Country_Master (Country_Name, Country_Code) VALUES ('USA','US'),('India','IN');
INSERT INTO State_Master (Country_Id, State_Name) VALUES (1,'California'),(1,'Texas'),(2,'Maharashtra');
INSERT INTO City_Master (State_Id, City_Name) VALUES (1,'Los Angeles'),(2,'Austin'),(3,'Mumbai');
INSERT INTO Company_Master (Company_Name, Country_Id, State_Id, City_Id, Address) VALUES
('BB Motors HQ',1,1,1,'Downtown LA'),('BB Motors Austin',1,2,2,'Central Austin'),('BB Motors Mumbai',2,3,3,'Andheri East');

INSERT INTO Role_Master (Role_Name) VALUES ('Admin'),('Manager'),('Sales'),('Viewer');
INSERT INTO Role_Permissions (Role_Id, Resource, Action)
SELECT r.Role_Id, res.resource, act.action FROM Role_Master r
JOIN (SELECT 'cars' resource UNION SELECT 'users' UNION SELECT 'brands' UNION SELECT 'models' UNION SELECT 'features' UNION SELECT 'companies') res
JOIN (SELECT 'create' action UNION SELECT 'read' UNION SELECT 'update' UNION SELECT 'delete') act
WHERE r.Role_Name IN ('Admin','Manager');
INSERT INTO Role_Permissions (Role_Id, Resource, Action)
SELECT r.Role_Id, 'cars', 'read' FROM Role_Master r WHERE r.Role_Name='Sales';
INSERT INTO Role_Permissions (Role_Id, Resource, Action)
SELECT r.Role_Id, 'cars', 'read' FROM Role_Master r WHERE r.Role_Name='Viewer';

INSERT INTO User_Master (Username, Password, Email, Phone, Role_Id, Status)
VALUES ('admin', '$2y$12$8kwhDgzCg8zTioH2FQ6S5ODyL4.Ke8HWOG7mYpNUE04cxlYv1cOL.', 'admin@bbmotors.com','+10000000000',1,'ACTIVE');

INSERT INTO Code_Header (Code_Name, Description) VALUES
('FUEL_TYPE','Fuel Type'),('TRANSMISSION_TYPE','Transmission Type'),('COLOR','Vehicle Color'),('ITEM_STATUS','Inventory Status'),('USER_STATUS','User Status');
INSERT INTO Code_Details (Header_Id, Code_Value, Code_Label, Sort_Order) VALUES
(1,'PETROL','Petrol',1),(1,'DIESEL','Diesel',2),(1,'EV','Electric',3),
(2,'MANUAL','Manual',1),(2,'AUTO','Automatic',2),
(3,'WHITE','White',1),(3,'BLACK','Black',2),(3,'RED','Red',3),(3,'BLUE','Blue',4),
(4,'AVAILABLE','Available',1),(4,'SOLD','Sold',2),(4,'RESERVED','Reserved',3),
(5,'ACTIVE','Active',1),(5,'INACTIVE','Inactive',2);

INSERT INTO Brand_Master (Brand_Name) VALUES ('Toyota'),('Honda'),('Ford'),('BMW'),('Tesla');
INSERT INTO Item_Model_Master (Brand_Id, Model_Name) VALUES
(1,'Corolla'),(1,'Camry'),(2,'Civic'),(2,'Accord'),(3,'Mustang'),(3,'F-150'),(4,'X5'),(4,'3 Series'),(5,'Model 3'),(5,'Model Y');
INSERT INTO Feature_Master (Feature_Name) VALUES
('ABS'),('Airbags'),('Sunroof'),('Leather Seats'),('Reverse Camera'),('Bluetooth'),('Navigation'),('Cruise Control'),('Parking Sensors'),('Alloy Wheels');

INSERT INTO item_Details (Company_Id, Model_Id, Registration_No, Make_Year, Registration_Year, Km_Driven, Price, Color_Code, Owner_Count, Status_Code, Fuel_Type_Code, Transmission_Code)
VALUES
(1,1,'REG001',2019,2019,45000,18000,'WHITE',1,'AVAILABLE','PETROL','MANUAL'),
(1,2,'REG002',2020,2020,35000,23000,'BLACK',1,'SOLD','PETROL','AUTO'),
(1,3,'REG003',2018,2018,60000,15000,'RED',2,'AVAILABLE','DIESEL','MANUAL'),
(1,4,'REG004',2021,2021,22000,28000,'BLUE',1,'AVAILABLE','PETROL','AUTO'),
(2,5,'REG005',2017,2017,70000,32000,'BLACK',2,'SOLD','PETROL','MANUAL'),
(2,6,'REG006',2022,2022,15000,41000,'WHITE',1,'AVAILABLE','DIESEL','AUTO'),
(2,7,'REG007',2020,2020,30000,52000,'BLUE',1,'AVAILABLE','DIESEL','AUTO'),
(2,8,'REG008',2019,2019,38000,46000,'RED',1,'RESERVED','PETROL','AUTO'),
(3,9,'REG009',2023,2023,8000,49000,'WHITE',1,'AVAILABLE','EV','AUTO'),
(3,10,'REG010',2024,2024,2000,62000,'BLACK',1,'AVAILABLE','EV','AUTO'),
(1,1,'REG011',2016,2016,85000,12000,'BLUE',3,'AVAILABLE','DIESEL','MANUAL'),
(1,2,'REG012',2015,2015,90000,11000,'RED',3,'SOLD','PETROL','MANUAL'),
(1,3,'REG013',2021,2021,18000,25000,'WHITE',1,'AVAILABLE','PETROL','AUTO'),
(1,4,'REG014',2022,2022,14000,29000,'BLACK',1,'AVAILABLE','PETROL','AUTO'),
(2,5,'REG015',2018,2018,55000,31000,'BLUE',2,'RESERVED','PETROL','MANUAL'),
(2,6,'REG016',2020,2020,33000,39000,'WHITE',1,'AVAILABLE','DIESEL','AUTO'),
(2,7,'REG017',2019,2019,41000,50000,'RED',1,'SOLD','DIESEL','AUTO'),
(3,8,'REG018',2023,2023,7000,47000,'BLACK',1,'AVAILABLE','PETROL','AUTO'),
(3,9,'REG019',2022,2022,10000,51000,'WHITE',1,'AVAILABLE','EV','AUTO'),
(3,10,'REG020',2021,2021,12000,59000,'BLUE',1,'SOLD','EV','AUTO');
