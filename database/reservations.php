<?php
    require_once('database.php');
    require_once('statement.php');
    require_once('flights.php');

    class Reservations
    {
        private $database;
        private $mysqli;

        public function __construct(&$database)
        {
            $this->database = $database;
            $this->mysqli = $database->GetMySQLI();
        }

        public function Add($flight_code, $seat_number = NULL)
        {
            if(!$this->IsFlightFull($flight_code))
            {
                if($seat_number !== NULL)
                {
                    if(!$this->IsSeatAvailable($flight_code, $seat_number))
                    {
                        throw new InvalidArgumentException('Seat is unavailable.');
                    }
                }
                else
                {
                    $occupied_seats = $this->GetOccupiedSeats($flight_code);

                    $flights = new Flights($this->database);

                    $max_seat_number = $flights->GetMaxSeatNumber($flight_code);

                    $seat_number = $this->GetFreeSeat($occupied_seats, $max_seat_number);
                }

                $reservation_date_time = time();

                $reservation_code = $this->GetNewReservationCode($flight_code, $seat_number, $reservation_date_time);

                $paid = 0;

                $arguments = array(&$reservation_code, &$flight_code, &$seat_number, &$reservation_date_time, &$paid);

                $statement = new Statement($this->mysqli, 'INSERT INTO reservations(reservation_code, flight_code, seat_number, reservation_date_time, paid) VALUES(?, ?, ?, FROM_UNIXTIME(?, \'%Y-%m-%d %H:%i:%s\'), ?)', 'ssiii', $arguments);

                return $reservation_code;
            }
            else
            {
                throw new LogicException('Flight is full.');
            }
        }

        public function IsSeatAvailable($flight_code, $seat_number)
        {
            $flights = new Flights($this->database);

            $max_seat_number = $flights->GetMaxSeatNumber($flight_code);

            assert('$seat_number >= 1 && $seat_number <= $max_seat_number');

            $arguments = array(&$flight_code);

            $statement = new Statement($this->mysqli, 'SELECT flight_code FROM flights WHERE flight_code = ? LIMIT 1', 's', $arguments);

            $statement->GetRow(0);

            $arguments = array(&$flight_code, &$seat_number);

            $statement = new Statement($this->mysqli, 'SELECT seat_number FROM reservations WHERE flight_code = ? AND seat_number = ? LIMIT 1', 'si', $arguments);

            $row_count = $statement->GetRowCount();

            return $row_count === 0;
        }

        public function IsFlightFull($flight_code)
        {
            $argument = array(&$flight_code);

            $statement = new Statement($this->mysqli, 'SELECT flight_code FROM flights WHERE flight_code = ? LIMIT 1', 's', $argument);

            $statement->GetRow(0);

            $argument = array(&$flight_code);

            $statement = new Statement($this->mysqli, 'SELECT COUNT(seat_number), no_of_seats FROM flights, reservations WHERE reservations.flight_code = flights.flight_code AND flights.flight_code = ?', 's', $argument);

            $row = $statement->GetRow(0);

            return $row[0] >= $row[1];
        }

        public function GetOccupiedSeats($flight_code)
        {
            $argument = array(&$flight_code);

            $statement = new Statement($this->mysqli, 'SELECT seat_number FROM reservations WHERE flight_code = ?', 's', $argument);

            $occupied_seat_count = $statement->GetRowCount();

            $occupied_seats = array();

            for($row_index = 0; $row_index < $occupied_seat_count; ++$row_index)
            {
                $row = $statement->GetRow($row_index);
                $occupied_seats[] = $row[0];
            }

            return $occupied_seats;
        }

        private function GetNewReservationCode($flight_code, $seat_number, $unix_timestamp)
        {
            $arguments = array(&$flight_code, &$seat_number, &$unix_timestamp);

            return $this->Hash($arguments);
        }

        private function GetFreeSeat(&$occupied_seats, $upper_bound)
        {
            assert('$upper_bound >= 1');

            if(!empty($occupied_seats))
            {
                sort($occupied_seats);
            }
            else
            {
                return 1;
            }

            $selected_seat = 1;

            foreach($occupied_seats as $occupied_seat)
            {
                if($selected_seat >= $occupied_seat)
                {
                    $selected_seat += 1;
                }
                else
                {
                    break;
                }
            }

            return $selected_seat;
        }

        private function Hash($arguments)
        {
            $string = '';

            foreach($arguments as $argument)
            {
                $string = $string . $argument;
            }

            return hash('crc32', $string);
        }
    }
?>