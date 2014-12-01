<?php
    require_once('statement.php');

    class Database
    {
        const DATE_TIME_NO_FORMAT = 0;
        const DATE_TIME_FORMAT_TO_DATE_ONLY = 1;
        const DATE_TIME_FORMAT_TO_TIME_ONLY = 2;
        const DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP = 3;

        private $mysqli;

        public function __construct($username, $password, $database_name, $port = 3306, $host = NULL)
        {
            $this->mysqli = new mysqli($host, $username, $password, $database_name, $port);

            $error_number = $this->mysqli->connect_errno;

            if($error_number !== 0)
            {
                throw new Exception($this->mysqli->connect_error, $error_number);
            }
        }

        public function GetMySQLI()
        {
            return $this->mysqli;
        }

        public function __destruct()
        {
            assert($this->mysqli->close() === TRUE);
        }
    }
?>