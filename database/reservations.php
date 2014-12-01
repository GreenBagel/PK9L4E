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

        public function GetReservationWithIndex($index)
        {
            $statement = new Statement($this->mysqli, "SELECT * FROM reservations LIMIT {$index}, 1");

            $row = $statement->GetRow(0);

            return $row;
        }

        public function GetReservationWithFieldsFilter($reservation_code, $flight_code, $seat_number, $reservation_date_time, $paid, $input_date_time_format = Database::DATE_TIME_NO_FORMAT, $output_date_time_format = Database::DATE_TIME_NO_FORMAT)
        {
            $first_filter_added = FALSE;
            $query;
            $bound_argument_type = NULL;
            $filters;

            switch($output_date_time_format)
            {
                case Database::DATE_TIME_FORMAT_TO_DATE_ONLY:
                {
                    $query = 'SELECT reservation_code, flight_code, seat_number, DATE_FORMAT(reservation_date_time, \'%Y-%m-%d\'), paid FROM reservations';
                    break;
                }
                case Database::DATE_TIME_FORMAT_TO_TIME_ONLY:
                {
                    $query = 'SELECT reservation_code, flight_code, seat_number, DATE_FORMAT(reservation_date_time, \'%H:%i:%s\'), paid FROM reservations';
                    break;
                }
                case Database::DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP:
                {
                    $query = 'SELECT reservation_code, flight_code, seat_number, UNIX_TIMESTAMP(reservation_date_time), paid FROM reservations';
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

            if($seat_number !== NULL)
            {
                $_query = 'seat_number = ?';

                $this->GetReservationWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . 'i';
                $filters[] = &$seat_number;
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

            if($paid !== NULL)
            {
                $_query = 'paid = ?';

                $this->GetReservationWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . 'i';
                $filters[] = &$paid;
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

        public function SetPaid($reservation_code, $has_paid)
        {
            $has_paid_int = $has_paid === TRUE ? 1 : 0;
            $not_has_paid_int = $has_paid === TRUE ? 0 : 1;

            $arguments = array(&$has_paid_int, &$reservation_code, &$not_has_paid_int);

            $statement = new Statement($this->mysqli, 'UPDATE reservations SET paid = ? WHERE reservation_code = ? AND paid = ?', 'isi', $arguments);
        }

        public function RemoveExpiredReservations()
        {
            $statement = new Statement($this->mysqli, 'DELETE FROM reservations WHERE UNIX_TIMESTAMP(reservation_date_time) + 86400 < UNIX_TIMESTAMP(NOW()) AND paid = 0');
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