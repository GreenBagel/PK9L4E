<?php
    require_once('database.php');
    require_once('statement.php');

    class Locations
    {
        private $mysqli;

        public function __construct(&$database)
        {
            $this->mysqli = $database->GetMySQLI();
        }

        public function GetLocationWithIndex($index)
        {
            $statement = new Statement($this->mysqli, "SELECT location FROM locations LIMIT {$index}, 1");

            return $statement->GetRow(0);
        }

        public function GetLocationWithFieldsFilter($location)
        {
            $query = 'SELECT location FROM locations';

            $statement = NULL;

            if($location === NULL)
            {
                $statement = new Statement($this->mysqli, $query);
            }
            else
            {
                $query = $query . ' WHERE location = ?';
                $filters[] = &$location;
                $statement = new Statement($this->mysqli, $query, 's', $filters);
            }

            return $statement->GetAllRows();
        }

        public function GetLocationCount()
        {
            $statement = new Statement($this->mysqli, 'SELECT COUNT(location) FROM locations');

            $row = $statement->GetRow(0);

            return $row[0];
        }
    }
?>