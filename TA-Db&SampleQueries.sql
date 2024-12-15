DROP DATABASE TravelAgencyDb;
CREATE DATABASE TravelAgencyDb;
USE TravelAgencyDb;

-- Agents table
CREATE TABLE Agent (
    agentID VARCHAR(4) PRIMARY KEY,
    agentName VARCHAR(100) NOT NULL,
    agentSex CHAR(1) NOT NULL,
    agentDOB DATE NOT NULL,
    agentPhone INT
);

-- Customers table
CREATE TABLE Customer (
    customerID VARCHAR(4) PRIMARY KEY,
    customerFName VARCHAR(30) NOT NULL,
    customerLName VARCHAR(30) NOT NULL,
    customerSex CHAR(1) NOT NULL,
    customerDOB DATE,
    customerAddress VARCHAR(30),
    customerEmail VARCHAR(50) NOT NULL UNIQUE,
    customerPassword VARCHAR(100),
    customerPhone INT NOT NULL
);

ALTER TABLE Customer
DROP COLUMN customerAddress,
DROP COLUMN customerPassword;

-- Payments table
CREATE TABLE Payment (
    paymentID VARCHAR(4) PRIMARY KEY,
    paymentType VARCHAR(20) NOT NULL,
    paymentPrice INT NOT NULL
);

-- Destinations table
CREATE TABLE Destination (
    destinationID VARCHAR(4) PRIMARY KEY,
    destinationContinent VARCHAR(15) NOT NULL,
    destinationCountry VARCHAR(30) NOT NULL,
    destinationCity VARCHAR(30) NOT NULL
);

-- Itineraries table
CREATE TABLE Itinerary (
    itineraryID VARCHAR(4),
    itineraryDay INT NOT NULL,
    itineraryActivity VARCHAR(40) NOT NULL,
    itineraryTransport VARCHAR(20) NOT NULL,
    PRIMARY KEY (itineraryID, itineraryDay)
);

-- Packages table
CREATE TABLE Package (
    packageID VARCHAR(4) PRIMARY KEY,
    packageName VARCHAR(100) NOT NULL,
    destinationID VARCHAR(4),
    packageTransport VARCHAR(20),
    packageSDate DATE NOT NULL,
    packageEDate DATE NOT NULL,
    packageTDays INT NOT NULL,
    itineraryID VARCHAR(4),
    itineraryDay INT,
    packageAccommodation VARCHAR(1) NOT NULL,
    paymentID VARCHAR(4),
    packagePrice INT NOT NULL,
    FOREIGN KEY (destinationID) REFERENCES Destination(destinationID),
    FOREIGN KEY (itineraryID, itineraryDay) REFERENCES Itinerary(itineraryID, itineraryDay),
    FOREIGN KEY (paymentID) REFERENCES Payment(paymentID)
);

-- Bookings table
CREATE TABLE Booking (
    bookingID VARCHAR(4) PRIMARY KEY,
    bookedDate DATE NOT NULL
);

ALTER TABLE Booking
ADD COLUMN agentID VARCHAR(4),
ADD CONSTRAINT fk_booking_agentID
FOREIGN KEY (agentID) REFERENCES Agent(agentID),
ADD COLUMN customerID VARCHAR(4),
ADD CONSTRAINT fk_booking_customerID
FOREIGN KEY (customerID) REFERENCES Customer(customerID),
ADD COLUMN packageID VARCHAR(4),
ADD CONSTRAINT fk_booking_packageID
FOREIGN KEY (packageID) REFERENCES Package(packageID);

-- Insert data into Agent table
INSERT INTO Agent (agentID, agentName, agentSex, agentDOB, agentPhone)
VALUES
('A001', 'Agus Salim', 'M', '1980-07-15', 812345678),
('A002', 'Michelle Tjandra', 'F', '2002-03-22', 812345679),
('A003', 'Siti Kurniawan', 'F', '1996-11-30', 812345680),
('A004', 'Budi Santoso', 'M', '2000-05-16', 812345681),
('A005', 'Putri Hartanto', 'F', '1992-08-25', 812345682);

-- Insert data into Customer table
INSERT INTO Customer (customerID, customerFName, customerLName, customerSex, customerDOB, customerEmail, customerPhone)
VALUES
('C001', 'Kevin', 'Hartono', 'M', '1997-02-17', 'kevinhartono123@gmail.com', 812345683),
('C002', 'Sari', 'Nur Salim', 'F', '2001-09-12', 'sar1r0t1@gmail.com', 812345684),
('C003', 'Ahmad', 'Wahyudi', 'M', '1992-07-24', 'ahmad.wahyudi@gmail.com', 812345685),
('C004', 'Ayu', 'Lestari', 'F', '2003-01-05', 'ayutenan@gmail.com', 812345686),
('C005', 'Gregory', 'Saputra', 'M', '1988-06-11', 'saputragregory@gmail.com', 812345687);

-- Insert data into Payment table
INSERT INTO Payment (paymentID, paymentType, paymentPrice)
VALUES
('P001', 'Credit Card Permata', 5000000),
('P002', 'Cash', 6000000),
('P003', 'Debit Card OCBC', 7500000),
('P004', 'Bank Transfer BCA', 8000000),
('P005', 'QRIS Mandiri', 7000000);

-- Insert data into Destination table
INSERT INTO Destination (destinationID, destinationContinent, destinationCountry, destinationCity)
VALUES
('D001', 'Asia', 'Indonesia', 'Yogjakarta'),
('D002', 'Asia', 'Malaysia', 'Kuala Lumpur'),
('D003', 'Asia', 'Indonesia', 'Bali'),
('D004', 'Asia', 'Vietnam', 'Hanoi'),
('D005', 'Asia', 'Thailand', 'Bangkok');

-- Insert data into Itinerary table
INSERT INTO Itinerary (itineraryID, itineraryDay, itineraryActivity, itineraryTransport)
VALUES
('I001', 1, 'Batik Making', 'Shuttle Bus'),  
('I001', 2, 'City Tour', 'Shuttle Bus'),      
('I002', 1, 'Museum Visit', 'Rental Bike'),   
('I002', 2, 'City Walk', 'Rental Bike'),       
('I003', 1, 'Mountain Hiking', 'Rental Motorcycle'), 
('I003', 2, 'Local Market', 'Shuttle Bus'),    
('I004', 1, 'Beach Day', 'Rental Van'),        
('I004', 2, 'Boat Tour', 'Boat'),               
('I005', 1, 'Local Cuisine', 'Rental Car'),    
('I005', 2, 'Historical Tour', 'Shuttle Bus'); 

-- Insert data into Package table 
INSERT INTO Package (packageID, packageName, destinationID, packageTransport, packageSDate, packageEDate, packageTDays, itineraryID, 
itineraryDay, packageAccommodation, paymentID, packagePrice)
VALUES
('PKG1', 'Yogjakarta Culture', 'D001', 'Plane', '2023-12-01', '2023-12-07', 7, 'I001', 1, 'T', 'P001', 5000000),  
('PKG2', 'Kuala Lumpur Experience', 'D002', 'Plane', '2023-01-10', '2023-01-17', 8, 'I002', 1, 'T', 'P002', 6000000),  
('PKG3', 'Bali Adventure', 'D003', 'Plane', '2023-01-15', '2023-01-21', 7, 'I003', 1, 'T', 'P003', 7500000),  
('PKG4', 'Hanoi Highlights', 'D004', 'Plane', '2023-02-05', '2023-02-12', 8, 'I004', 1, 'T', 'P004', 8000000),  
('PKG5', 'Bangkok History', 'D005', 'Plane', '2023-03-20', '2023-03-28', 9, 'I005', 1, 'T', 'P005', 7000000);  

-- Insert data into Booking table
INSERT INTO Booking (bookingID, agentID, customerID, packageID, bookedDate)
VALUES
('B001', 'A001', 'C001', 'PKG1', '2023-12-01'),
('B002', 'A002', 'C002', 'PKG2', '2023-01-10'),
('B003', 'A003', 'C003', 'PKG3', '2023-01-15'),
('B004', 'A004', 'C004', 'PKG4', '2023-02-05'),
('B005', 'A005', 'C005', 'PKG5', '2023-03-20');

-- show customer table sorted by name alphabetically
SELECT * FROM Customer ORDER BY customerFName ASC, customerLName ASC;
-- show agent names who have been born before 2000
SELECT agentName FROM Agent WHERE agentDOB < '2000-01-01';
-- join bookingID, bookedDate, customerFName, customerLName, packageName, and packagePrice sorted by the most expensive package
SELECT Booking.bookingID, Booking.bookedDate, Customer.customerID, Customer.customerFName, 
Customer.customerLName, Package.packageName, Package.packagePrice
FROM Booking
JOIN Customer ON Booking.customerID = Customer.customerID
JOIN Package ON Booking.packageID = Package.packageID
ORDER BY Booking.bookingID;
