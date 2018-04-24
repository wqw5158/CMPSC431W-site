USE genericTeamDB;

INSERT INTO Users(email,   password,  address,      phonenumber) 
VALUES ('bmw@psu.edu',     'bmw',     '229 sparks', '123-457-7891'),
       ('dch@psu.edu',     'dch',     'penn state', '211-222-3332'),
       ('william@psu.edu', 'william', '229 John Deere Dr. Moline, IL 18918', '311-222-3333'),
       ('wlee@psu.edu',    'wlee',    'EECS, Penn State', '411-222-3334'),
       ('fang@psu.edu',    'fang',    '112 Garden Pl. State College, PA 16801', '511-222-3335'),
       ('weilin@psu.edu',  'weilin',  '68 Main St. New York, NY 11071', '611-222-3336'),
       ('yuchen@psu.edu',  'yuchen',  '101 Nanjing Rd. Shanghai, China', '711-222-3337'),
       ('Kelly@psu.edu',  'kelly',    '14 Kenwood Av., Hazlet, PA 16811', '811-222-3338'),
       ('michael@psu.edu', 'michael', '114 Beer St. Linden, PA 18705', '911-222-3339'),
       ('jingrui@psu.edu', 'jingrui', '1414 Wutong St., Beijing, China', '011-222-3310');

INSERT INTO Dealers(userid,dealershipname,personofcontact,revenue,dealershipcategory)
VALUES (1, 'BMW Dealer', 'W WANG', 99999999, '4S'),
       (2, 'DCH Dealer',    'John',   10000000, '3c');

INSERT INTO InsCompany(insurancecompanycode, insurancecompanyname)
VALUES ('518', 'MetLife'),
       ('617', 'All State');
        
INSERT INTO RegisteredUsers(userid, name,age,gender,income,insurancepolicyno,insurancecompanycode)
VALUES (3, 'William', 20, 'M', 500000, 'MTL88182', '518'),
       (4, 'Dr. Lee',    45, 'M', 2000000, NULL, NULL),
       (5, 'Fang',    23, 'M', 130000, 'AST31-23', '617'),
       (6, 'WeiLin',     24, 'M', 90000,  NULL, NULL),
       (7, 'YuChen',   21, 'M', 150000, '74HL64673', '518'),
       (8, 'Kelly',   18, 'F', 70000,  NULL, NULL),
       (9, 'Michael',    25, 'M', 150000, '316K4566-1','617'),
       (10, 'JingRui',    30, 'M', 150000, '316K4566-1','617');
  
INSERT INTO Banks(routingno,bankname)
VALUES ('200041201', 'Bank of America'),
       ('300041201', 'PNC');
       
INSERT INTO BankAccount(userid,accountno,routingno)
VALUES (3, '368012385', '200041201'),
       (4, '468012385', '200041201'),
       (5, '568012385', '200041201'),
       (6, '668012385', '300041201'),
       (7, '768012385', '200041201'),
       (8, '868012385', '300041201'),
       (9, '968012385', '300041201'),
       (10, '168012385','200041201');

INSERT INTO Vehicles(vin,ownerid,category,make,model,year,mileage,description,image)
VALUES ('BMW238I168568', 3, 'Sedan', 'BMW', '328i','2016', 15000, 'Love it','bmw-1.jpg'),
       ('BMW168X368568', 3, 'SUV',   'BMW', 'X3',  '2017', 9800,  'Best','bmw-x3.jpg'),
       ('H238I16856831', 5, 'Truck', 'Honda', 'Xi','2017', 75000, 'Fair','honda-truck.jpg'),
       ('K238I16856831', 6, 'Mini Van', 'Volkswagen', 'Rio', '1958', 15000, 'Love it','volkswagen-van.jpg'),
       ('F538I16856831', 3, 'Sport', 'Ford', 'GT', '2018', 0, 'New','ford-sport.jpg'),
       ('D238I16856832', 4, 'Truck', 'Ford', 'F150', '2018', 5000, 'Good','ford-truck.jpg'),
       ('K238I16856833', 4, 'Sedan', 'Tesla', 'Tr', '2018', 1000, 'Loaner','tesla.jpg'),
       ('J238I16856834', 4, 'SUV',   'Audi', 'Q8', '2010', 85000, 'Ok','audi-suv.jpg'),
       ('F238I16856835', 7, 'Sport', 'Ford', 'MS', '1929', 125000, 'Fair','antique-1.jpg'),
       ('G238I16856836', 5, 'Mini Van', 'GMC', 'T10', '1997', 41, 'New','gmc-minivan.jpg');
       
INSERT INTO Auctions(vin,sellerid,startdate,enddate,reserveprice,buynowprice,closedate)
VALUES ('BMW238I168568',   3, '2018-2-21', '2018-5-21', 51000, 55000, NULL),
       ('BMW168X368568',   3, '2018-2-21', '2018-5-22', 55000, 57000, NULL),
       ('H238I16856831',   5, '2018-2-21', '2018-5-23', 5000, 7000, NULL),
       ('K238I16856831',   6, '2018-2-21', '2018-5-24', 55000, 57000, NULL),
       ('G238I16856836',   5, '2018-2-21', '2018-5-25', 25000, 27000, NULL),
       ('D238I16856832',   4, '2018-2-21', '2018-5-26', 25000, 27000, NULL),
       ('K238I16856833',   4, '2018-2-21', '2018-5-27', 55000, 57000, NULL),
       ('J238I16856834',   4, '2018-2-21', '2018-5-28', 5000, 7000, NULL),
       ('F238I16856835',   4, '2018-2-21', '2018-5-29', 12000, 18000, NULL),
       ('F538I16856831',   3, '2018-2-21', '2018-6-20', 25000, 27000, NULL);
       
INSERT INTO Bids(auctionid, bidderid, amount, timestamp)
VALUES (1, 5, 45000, '2018-2-21 18:19:03'),
       (2, 4, 55000, '2018-2-21 18:19:03'),
       (3, 2, 5000,  '2018-2-21 18:19:03'),
       (4, 1, 45000, '2018-2-21 18:19:03'),
       (5, 3, 15000, '2018-2-21 18:19:03'),
       (6, 5, 13000, '2018-2-21 18:19:03'),
       (7, 4, 52000, '2018-2-21 18:19:03'),
       (8, 6, 3000,  '2018-2-21 18:19:03'),
       (9, 4, 5000,  '2018-2-21 18:19:03'),
       (10, 7, 11000, '2018-2-21 18:19:03'),
       (9, 3,  13000, '2018-4-20 16:18:18');
       
INSERT INTO Reviews(reviewerid,sellerid,auctionid,reviewdate, review,rating)
VALUES (4, 3, 1, '2018-4-20', 'This seller is in my Database management class. The team has spent a lot of time and effort to develop this eAuction system as the course project. This eAuction system works beautifully. There are more than 10 million people using this system. I will gave this team a "A" for the final grade.', 5),
       (5, 3, 1, '2018-4-21', 'I am the TA for this seller. I enloyed to read and grade the eAuction design phase reports. This eAuction system demonstrated how well the team has applied the knowledge learned from CMPSC 431W to application development.', 5),
       (6, 3, 1, '2018-4-22', 'This seller is a vehicle lover. He has very deep knowledge about all different kind of vehicles. That is why he develop Vehicle eAuction system as his CMPSC 431W course project. I aslo enjoyed to read abd grade the team project reports, as well as to evaluate the final project.', 5),
       (7, 3, 1, '2018-4-23', 'I enjoyed to work with the seller to develop this eAuction system.', 5),
       (8, 3, 1, '2018-4-23', 'I bought a 2016 BMW 328xi from this seller. The vehicle is delivered to me in a nice and clean condition. Especially, the vehicle is certified by a certified mechenic technician.',4),
       (8, 3, 1, '2018-4-24', 'The data in this eAuction database is for demonstration only. The iamges and videos are download online.', 3),
       (9, 3, 1, '2018-4-24', 'This review is demonstrate rating less than 5.', 2),
       (10, 3, 1, '2018-4-25', 'Another review to demonstrate rating lower than 5.', 1),
       (3,  4, 6, '2018-4-25','This seller is my database management class professor. He is one of the best professors in Penn State. This eAuction system is developed under his guidance.',5);

INSERT INTO DisplayFloor(vin, youtubeid, certifiedby)
VALUES 
('BMW168X368568','fxFKwWLun7k', '!1m18!1m12!1m3!1d10527.91311453703!2d-74.31847874653307!3d40.345385658254344!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x703cc73ac90fea57!2sMcLaughlins+Auto+Service+Center!5e0!3m2!1sen!2sus!4v1524304956701'),
('BMW238I168568','qEOhhg8MJw8', '!1m18!1m12!1m3!1d96654.23020064064!2d-77.92658512459158!3d40.79622078739148!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6ffe2e9d730aac4b!2sKarch+Auto!5e0!3m2!1sen!2sus!4v1524305160690'),
('K238I16856833','6f5yRfKxEpc', '!1m18!1m12!1m3!1d2987.8053035591383!2d-90.5341691203621!3d41.508493796543426!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x87e233e49de258bd%3A0x84de4d82612b4558!2sJohn+Deere!5e0!3m2!1sen!2sus!4v1524305979576'),
('H238I16856831','P-C3NQgZhpM', '!1m18!1m12!1m3!1d97169.27671242793!2d-80.02184336509033!3d40.441185933223224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x3131a550256a5191!2sBaum+Boulevard+Automotive!5e0!3m2!1sen!2sus!4v1524306216782'),
('G238I16856836','UGcVmnPGI0I', '!1m18!1m12!1m3!1d62753.414054688605!2d-74.03699961391649!3d40.71045654718592!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xbe962e51dbfe68d7!2sManhattan+Auto+Care+Inc!5e0!3m2!1sen!2sus!4v1524306740892'),
('K238I16856831','wgfD6kPwQ88', '!1m18!1m12!1m3!1d28739.054535292104!2d-77.85895525309193!3d40.78795214186569!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xa68bea2071fed2c1!2sFirestone+Complete+Auto+Care!5e0!3m2!1sen!2sus!4v1524306411957'),
('D238I16856832','v4rRWSBJvPM', '!1m18!1m12!1m3!1d210779.29044080543!2d-74.0792150220552!3d40.80411986901826!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xa51f804e99bcd543!2sManhattan+Alignment+%26+Diagnostic+Center!5e0!3m2!1sen!2sus!4v1524306564044'),
('J238I16856834','rV41yQeRTK4', '!1m18!1m12!1m3!1d62753.414054688605!2d-74.03699961391649!3d40.71045654718592!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xbe962e51dbfe68d7!2sManhattan+Auto+Care+Inc!5e0!3m2!1sen!2sus!4v1524306740892'),
('F538I16856831','csf2Wnv-34s', '!1m18!1m12!1m3!1d62753.414054688605!2d-74.03699961391649!3d40.71045654718592!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xbe962e51dbfe68d7!2sManhattan+Auto+Care+Inc!5e0!3m2!1sen!2sus!4v1524306740892'),
('F238I16856835','b7AD9xRwhpQ', '!1m18!1m12!1m3!1d10527.91311453703!2d-74.31847874653307!3d40.345385658254344!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x703cc73ac90fea57!2sMcLaughlins+Auto+Service+Center!5e0!3m2!1sen!2sus!4v1524304956701');
