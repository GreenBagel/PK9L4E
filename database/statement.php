<?php
    class Statement
    {
        private $mysqli_stmt;
        private $mysqli_result = NULL;

        public function __construct($mysqli, $query, $bound_argument_type = NULL, &$arguments = NULL)
        {
            $this->mysqli_stmt = $mysqli->stmt_init();

            if($this->mysqli_stmt->prepare($query) === FALSE)
            {
                throw new Exception($this->mysqli_stmt->error, $this->mysqli_stmt->errno);
            }

            if($bound_argument_type !== NULL)
            {
                array_unshift($arguments, $bound_argument_type);

                if(call_user_func_array(array($this->mysqli_stmt, 'bind_param'), $arguments) === FALSE)
                {
                    throw new Exception($this->mysqli_stmt->error, $this->mysqli_stmt->errno);
                }
            }

            if($this->mysqli_stmt->execute() === FALSE)
            {
                throw new Exception($this->mysqli_stmt->error, $this->mysqli_stmt->errno);
            }

            $this->mysqli_result = $this->mysqli_stmt->get_result();

            if($this->mysqli_result === FALSE)
            {
                $this->mysqli_result = NULL;
            }
        }

        public function GetRow($index)
        {
            $this->SeekToRow($index);

            $row = $this->mysqli_result->fetch_row();

            if($row === NULL)
            {
                throw new Exception('Unable to retrieve row.');
            }

            $this->SeekToRow(0);

            return $row;
        }

        public function GetAllRows()
        {
            $rows = $this->mysqli_result->fetch_all();

            if(empty($rows))
            {
                throw new Exception('Unable to retrieve row.');
            }

            $this->SeekToRow(0);

            return $rows;
        }

        public function GetRowCount()
        {
            return $this->mysqli_result->num_rows;
        }

        public function GetColumnCount()
        {
            return $this->mysqli_result->num_fields;
        }

        private function SeekToRow($index)
        {
            if($this->mysqli_result->data_seek($index) === FALSE)
            {
                throw new Exception('Unable to seek to index');
            }
        }

        public function __destruct()
        {
            if($this->mysqli_result !== NULL)
            {
                $this->mysqli_result->free();
            }

            assert($this->mysqli_stmt->close() === TRUE);
        }
    }
?>