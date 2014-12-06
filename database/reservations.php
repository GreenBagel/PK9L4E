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

            $this->RemoveExpiredReservations();
        }

        /*!
        Try to add a new reservation. If seat_number is not specified, a rondom seat is given.
        */
        public function Add($flight_code, $name, $nric, $email, $contact_no, $seat_number = NULL)
        {
            if(is_numeric($contact_no) === FALSE)
            {
                throw new InvalidArgumentException('Invalid contact number.');
            }

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

                $arguments = array(&$reservation_code, &$flight_code, &$reservation_date_time, &$name, &$nric, &$email, &$contact_no, &$seat_number);

                $statement = new Statement($this->mysqli, 'INSERT INTO reservations(reservation_code, flight_code, reservation_date_time, name, nric, email, contact_no, seat_number) VALUES(?, ?, FROM_UNIXTIME(?, \'%Y-%m-%d %H:%i:%s\'), ?, ?, ?, ?, ?)', 'ssisissi', $arguments);

                return $reservation_code;
            }
            else
            {
                throw new LogicException('Flight is full.');
            }
        }

        public function UpdateDetails($reservation_code, $name, $nric, $email, $contact_no)
        {
            if(is_numeric($contact_no) === FALSE)
            {
                throw new InvalidArgumentException('Invalid contact number.');
            }

            $arguments = array(&$name, &$nric, &$email, &$contact_no, &$reservation_code);

            $statement = new Statement($this->mysqli, 'UPDATE reservations SET name = ?, nric = ?, email = ?, contact_no = ? WHERE reservation_code = ?', 'sisss', $arguments);

            return $statement->GetAffectedRowCount();
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

        public function GetReservationWithIndex($index)
        {
            $statement = new Statement($this->mysqli, "SELECT * FROM reservations LIMIT {$index}, 1");

            $row = $statement->GetRow(0);

            return $row;
        }

        public function GetReservationWithFieldsFilter($reservation_code, $flight_code, $reservation_date_time, $name, $nric, $email, $contact_no, $seat_number, $input_date_time_format = Database::DATE_TIME_NO_FORMAT, $output_date_time_format = Database::DATE_TIME_NO_FORMAT)
        {
            $first_filter_added = FALSE;
            $query;
            $bound_argument_type = NULL;
            $filters;

            switch($output_date_time_format)
            {
                case Database::DATE_TIME_FORMAT_TO_DATE_ONLY:
                {
                    $query = 'SELECT reservation_code, flight_code, DATE_FORMAT(reservation_date_time, \'%Y-%m-%d\'), name, nric, email, contact_no, seat_number FROM reservations';
                    break;
                }
                case Database::DATE_TIME_FORMAT_TO_TIME_ONLY:
                {
                    $query = 'SELECT reservation_code, flight_code, DATE_FORMAT(reservation_date_time, \'%H:%i:%s\'), name, nric, email, contact_no, seat_number FROM reservations';
                    break;
                }
                case Database::DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP:
                {
                    $query = 'SELECT reservation_code, flight_code, UNIX_TIMESTAMP(reservation_date_time), name, nric, email, contact_no, seat_number FROM reservations';
                    break;
                }
                default:
                {
                    $query = 'SELECT * FROM reservations';
                }
            }

            if($reservation_code !== NULL)
            {
                $query = $query . ' WHERE reservation_code = ?';
                $bound_argument_type = $bound_argument_type . 's';
                $filters[] = &$reservation_code;
                $first_filter_added = TRUE;
            }

            if($flight_code !== NULL)
            {
                $_query = 'flight_code = ?';

                $this->GetReservationWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . 's';
                $filters[] = &$flight_code;
            }

            if($reservation_date_time !== NULL)
            {
                $_query;

                switch($input_date_time_format)
                {
                    case Database::DATE_TIME_FORMAT_TO_DATE_ONLY:
                    {
                        $_query = 'DATE_FORMAT(reservation_date_time, \'%Y-%m-%d\') = ?';
                        break;
                    }
                    case Database::DATE_TIME_FORMAT_TO_TIME_ONLY:
                    {
                        $_query = 'DATE_FORMAT(reservation_date_time, \'%H:%i:%s\') = ?';
                        break;
                    }
                    case Database::DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP:
                    {
                        $_query = 'UNIX_TIMESTAMP(reservation_date_time) = ?';
                        break;
                    }
                    default:
                    {
                        $_query = 'reservation_date_time = ?';
                    }
                }

                $this->GetReservationWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . ($input_date_time_format === Database::DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP ? 'i' : 's');
                $filters[] = &$reservation_date_time;
            }

            if($name !== NULL)
            {
                $_query = 'name = ?';

                $this->GetReservationWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . 's';
                $filters[] = &$name;
            }

            if($nric !== NULL)
            {
                $_query = 'nric = ?';

                $this->GetReservationWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . 'i';
                $filters[] = &$nric;
            }

            if($email !== NULL)
            {
                $_query = 'email = ?';

                $this->GetReservationWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . 's';
                $filters[] = &$email;
            }

            if($contact_no !== NULL)
            {
                if(is_numeric($contact_no) === FALSE)
                {
                    throw new InvalidArgumentException('Invalid contact number.');
                }

                $_query = 'contact_no = ?';

                $this->GetReservationWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . 's';
                $filters[] = &$contact_no;
            }

            if($seat_number !== NULL)
            {
                $_query = 'seat_number = ?';

                $this->GetReservationWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . 'i';
                $filters[] = &$seat_number;
            }

            $statement = new Statement($this->mysqli, $query, $bound_argument_type, $filters);

            $rows = $statement->GetAllRows();

            return $rows;
        }

        public function GetReservationCount()
        {
            $statement = new Statement($this->mysqli, 'SELECT COUNT(*) FROM reservations');

            $row = $statement->GetRow(0);

            return $row[0];
        }

        public function RemoveReservation($reservation_code)
        {
            $arguments = array(&$reservation_code);

            $statement = new Statement($this->mysqli, 'DELETE FROM reservations WHERE reservation_code = ?', 's', $arguments);

            return $statement->GetAffectedRowCount();
        }

        public function RemoveExpiredReservations()
        {
            $statement = new Statement($this->mysqli, 'CREATE TEMPORARY TABLE t AS SELECT reservation_code FROM reservations WHERE NOT EXISTS(SELECT reservation_code FROM customers WHERE customers.reservation_code = reservations.reservation_code)');

            $statement = new Statement($this->mysqli, 'DELETE FROM reservations WHERE reservation_code IN (SELECT * FROM t) AND UNIX_TIMESTAMP(reservation_date_time) + 86400 < UNIX_TIMESTAMP(NOW())');

            return $statement->GetAffectedRowCount();
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

        private function GetReservationWithFieldsFilter2(&$first_filter_added, &$query, &$_query)
        {
            if($first_filter_added)
            {
                $query = $query . ' AND ' . $_query;
            }
            else
            {
                $query = $query . ' WHERE ' . $_query;
                $first_filter_added = TRUE;
            }
        }
    }
?>