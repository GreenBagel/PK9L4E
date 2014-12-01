<?php
    require_once('database.php');
    require_once('statement.php');

    class Flights
    {
        private $mysqli;

        public function __construct(&$database)
        {
            $this->mysqli = $database->GetMySQLI();
        }

        public function GetFlightWithIndex($index)
        {
            $statement = new Statement($this->mysqli, "SELECT * FROM flights LIMIT {$index}, 1");

            $row = $statement->GetRow(0);

            return $row;
        }

        public function GetFlightWithFieldsFilter($flight_code, $origin, $departure_date_time, $destination, $arrival_date_time, $no_of_seats, $price, $input_date_time_format = Database::DATE_TIME_NO_FORMAT, $output_date_time_format = Database::DATE_TIME_NO_FORMAT)
        {
            $first_filter_added = FALSE;
            $query;
            $bound_argument_type = NULL;
            $filters;

            switch($output_date_time_format)
            {
                case Database::DATE_TIME_FORMAT_TO_DATE_ONLY:
                {
                    $query = 'SELECT flight_code, origin, DATE_FORMAT(departure_date_time, \'%Y-%m-%d\'), destination, DATE_FORMAT(arrival_date_time, \'%Y-%m-%d\'), no_of_seats, price FROM flights';
                    break;
                }
                case Database::DATE_TIME_FORMAT_TO_TIME_ONLY:
                {
                    $query = 'SELECT flight_code, origin, DATE_FORMAT(departure_date_time, \'%H:%i:%s\'), destination, DATE_FORMAT(arrival_date_time, \'%H:%i:%s\'), no_of_seats, price FROM flights';
                    break;
                }
                case Database::DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP:
                {
                    $query = 'SELECT flight_code, origin, UNIX_TIMESTAMP(departure_date_time), destination, UNIX_TIMESTAMP(arrival_date_time), no_of_seats, price FROM flights';
                    break;
                }
                default:
                {
                    $query = 'SELECT * FROM flights';
                }
            }

            if($flight_code !== NULL)
            {
                $query = $query . ' WHERE flight_code = ?';
                $bound_argument_type = $bound_argument_type . 's';
                $filters[] = &$flight_code;
                $first_filter_added = TRUE;
            }

            if($origin !== NULL)
            {
                $_query = 'origin = ?';

                $this->GetFlightWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . 's';
                $filters[] = &$origin;
            }

            if($departure_date_time !== NULL)
            {
                $_query;

                switch($input_date_time_format)
                {
                    case Database::DATE_TIME_FORMAT_TO_DATE_ONLY:
                    {
                        $_query = 'DATE_FORMAT(departure_date_time, \'%Y-%m-%d\') = ?';
                        break;
                    }
                    case Database::DATE_TIME_FORMAT_TO_TIME_ONLY:
                    {
                        $_query = 'DATE_FORMAT(departure_date_time, \'%H:%i:%s\') = ?';
                        break;
                    }
                    case Database::DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP:
                    {
                        $_query = 'UNIX_TIMESTAMP(departure_date_time) = ?';
                        break;
                    }
                    default:
                    {
                        $_query = 'departure_date_time = ?';
                    }
                }

                $this->GetFlightWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . ($input_date_time_format === Database::DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP ? 'i' : 's');
                $filters[] = &$departure_date_time;
            }

            if($destination !== NULL)
            {
                $_query = 'destination = ?';

                $this->GetFlightWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . 's';
                $filters[] = &$destination;
            }

            if($arrival_date_time !== NULL)
            {
                $_query;

                switch($input_date_time_format)
                {
                    case Database::DATE_TIME_FORMAT_TO_DATE_ONLY:
                    {
                        $_query = 'DATE_FORMAT(arrival_date_time, \'%Y-%m-%d\') = ?';
                        break;
                    }
                    case Database::DATE_TIME_FORMAT_TO_TIME_ONLY:
                    {
                        $_query = 'DATE_FORMAT(arrival_date_time, \'%H:%i:%s\') = ?';
                        break;
                    }
                    case Database::DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP:
                    {
                        $_query = 'UNIX_TIMESTAMP(arrival_date_time) = ?';
                        break;
                    }
                    default:
                    {
                        $_query = 'arrival_date_time = ?';
                    }
                }

                $this->GetFlightWithFieldsFilter2($first_filter_added, $query, $_query);
                $bound_argument_type = $bound_argument_type . ($input_date_time_format === Database::DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP ? 'i' : 's');

                $filters[] = &$arrival_date_time;
            }

            if($no_of_seats !== NULL)
            {
                $_query = 'no_of_seats = ?';

                $this->GetFlightWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . 'i';
                $filters[] = &$no_of_seats;
            }

            if($price !== NULL)
            {
                $_query = 'price = ?';

                $this->GetFlightWithFieldsFilter2($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . 'd';
                $filters[] = &$price;
            }

            $statement = new Statement($this->mysqli, $query, $bound_argument_type, $filters);

            $rows = $statement->GetAllRows();

            return $rows;
        }

        public function GetMaxSeatNumber($flight_code)
        {
            $argument = array(&$flight_code);

            $statement = new Statement($this->mysqli, 'SELECT no_of_seats FROM flights WHERE flight_code = ? LIMIT 1', 's', $argument);

            $row = $statement->GetRow(0);

            return $row[0];
        }

        public function GetFlightCount()
        {
            $statement = new Statement($this->mysqli, 'SELECT COUNT(*) FROM flights');

            $row = $statement->GetRow(0);

            return $row[0];
        }

        private function GetFlightWithFieldsFilter2(&$first_filter_added, &$query, &$_query)
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