CREATE TABLE locations
(
    location VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    CONSTRAINT pk_location PRIMARY KEY(location)
);

INSERT INTO locations(location) VALUES
('Auckland (AKL)'),
('Bangkok (BKK)'),
('Colombo (CMB)'),
('Darwin (DRW)'),
('Dubai (DXB)'),
('Frankfurt (FRA)'),
('Guangzhou (CAN)'),
('Hong Kong (HKT)'),
('Instanbul (IST)'),
('Jakarta (CGK)'),
('Kuala Lumpur (KUL)'),
('Kuching (KCH)'),
('London (LHR)'),
('Mumbai (BOM)'),
('Perth (PER)'),
('Seoul (ICN)'),
('Sydney (SYD)'),
('Taipei (TPE)'),
('Tokyo (Narita Airport) (NRT)'),
('Xiamen (XMN)'),
('Yangon (RGN)');

CREATE TABLE flights
(
    flight_code         VARCHAR(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    origin              VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    departure_date_time DATETIME(0) NOT NULL,
    destination         VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    arrival_date_time   DATETIME(0) NOT NULL,
    no_of_seats         SMALLINT(3) UNSIGNED NOT NULL,
    price               DECIMAL(7, 2) UNSIGNED NOT NULL,
    CONSTRAINT pk_flight_code PRIMARY KEY(flight_code),
    CONSTRAINT fk_origin      FOREIGN KEY(origin) REFERENCES locations(location),
    CONSTRAINT fk_destination FOREIGN KEY(destination) REFERENCES locations(location)
);

INSERT INTO flights(flight_code, origin, departure_date_time, destination, arrival_date_time, no_of_seats, price) VALUES
('ZT133',  'Kuala Lumpur (KUL)', '2016-12-25 08:55:00', 'Auckland (AKL)',               '2016-12-26 00:05:00', 1, 4266.00),
('ZT782',  'Kuala Lumpur (KUL)', '2017-02-11 15:15:00', 'Bangkok (BKK)',                '2017-02-11 16:20:00', 2, 399.00),
('ZT185',  'Kuala Lumpur (KUL)', '2017-03-05 11:00:00', 'Colombo (CMB)',                '2017-03-05 11:55:00', 3, 1210.00),
('ZT145',  'Kuala Lumpur (KUL)', '2017-02-19 21:35:00', 'Darwin (DRW)',                 '2017-02-20 04:10:00', 4, 1176.00),
('ZT162',  'Kuala Lumpur (KUL)', '2016-12-31 15:15:00', 'Dubai (DXB)',                  '2016-12-31 18:50:00', 5, 2355.00),
('ZT6',    'Kuala Lumpur (KUL)', '2017-01-09 23:59:00', 'Frankfurt (FRA)',              '2017-01-10 06:10:00', 6, 2265.00),
('ZT376',  'Kuala Lumpur (KUL)', '2017-01-17 09:30:00', 'Guangzhou (CAN)',              '2017-01-17 13:35:00', 7, 834.00),
('ZT432',  'Kuala Lumpur (KUL)', '2017-04-10 16:00:00', 'Hong Kong (HKT)',              '2017-04-10 19:35:00', 8, 712.00),
('ZT30',   'Kuala Lumpur (KUL)', '2017-07-04 00:30:00', 'Instanbul (IST)',              '2017-07-04 06:00:00', 9, 2081.00),
('ZT849',  'Kuala Lumpur (KUL)', '2017-05-23 20:35:00', 'Jakarta (CGK)',                '2017-05-23 21:35:00', 10, 291.00),
('ZT2522', 'Kuala Lumpur (KUL)', '2017-09-03 17:55:00', 'Kuching (KCH)',                '2017-09-03 19:40:00', 11, 171.00),
('ZT2',    'Kuala Lumpur (KUL)', '2017-06-30 23:55:00', 'London (LHR)',                 '2017-07-01 05:35:00', 12, 2424.00),
('ZT194',  'Kuala Lumpur (KUL)', '2016-12-15 20:20:00', 'Mumbai (BOM)',                 '2016-12-15 22:55:00', 13, 1123.00),
('ZT127',  'Kuala Lumpur (KUL)', '2017-03-13 20:00:00', 'Perth (PER)',                  '2017-03-14 01:30:00', 14, 1247.00),
('ZT66',   'Kuala Lumpur (KUL)', '2017-03-21 23:35:00', 'Seoul (ICN)',                  '2017-03-22 06:45:00', 15, 2415.00),
('ZT123',  'Kuala Lumpur (KUL)', '2017-02-07 23:30:00', 'Sydney (SYD)',                 '2017-02-08 10:45:00', 16, 1212.00),
('ZT366',  'Kuala Lumpur (KUL)', '2017-08-01 09:25:00', 'Taipei (TPE)',                 '2017-08-01 14:10:00', 17, 1467.00),
('ZT70',   'Kuala Lumpur (KUL)', '2017-07-29 10:50:00', 'Tokyo (Narita Airport) (NRT)', '2017-07-29 18:30:00', 18, 2761.00),
('ZT390',  'Kuala Lumpur (KUL)', '2017-03-31 09:40:00', 'Xiamen (XMN)',                 '2017-03-31 13:50:00', 19, 1035.00),
('ZT742',  'Kuala Lumpur (KUL)', '2017-10-20 13:50:00', 'Yangon (RGN)',                 '2017-10-20 15:00:00', 20, 312.00);

CREATE TABLE reservations
(
    reservation_code      VARCHAR(8) NOT NULL,
    flight_code           VARCHAR(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    seat_number           SMALLINT(3) UNSIGNED NOT NULL,
    reservation_date_time DATETIME(0) NOT NULL,
    paid                  BOOLEAN NOT NULL DEFAULT 0,
    CONSTRAINT pk_reservation_code PRIMARY KEY(reservation_code),
    CONSTRAINT fk_flight_code      FOREIGN KEY(flight_code) REFERENCES flights(flight_code)
);

CREATE TABLE customers
(
    reservation_code    VARCHAR(8) NOT NULL,
    name                VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    nric                BIGINT UNSIGNED NOT NULL,
    email               VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    contact_no          VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, -- Restrict input to numbers only in PHP
    payment_method      ENUM('Credit Card', 'Bank Transfer') NOT NULL,
    payment_details     VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, -- Restrict input to numbers only in PHP
    CONSTRAINT pk_reservation_code PRIMARY KEY(reservation_code),
    CONSTRAINT fk_reservation_code FOREIGN KEY(reservation_code) REFERENCES reservations(reservation_code)
);