<?php
    require_once('database.php');
    require_once('statement.php');

    class Customers
    {
        private $mysqli;

        public function __construct(&$database)
        {
            $this->mysqli = $database->GetMySQLI();
        }

        public function ConfirmPayment($reservation_code, $name, $nric, $email, $contact_no, $payment_method, $payment_details)
        {
            if(is_numeric($contact_no) === FALSE)
            {
                throw new InvalidArgumentException('Invalid contact number.');
            }

            if(is_numeric($payment_details) === FALSE)
            {
                throw new InvalidArgumentException('Invalid payment details.');
            }

            $arguments = array(&$reservation_code, &$name, &$nric, &$email, &$contact_no, &$payment_method, &$payment_details);

            $statement = new Statement($this->mysqli, 'INSERT INTO customers(reservation_code, name, nric, email, contact_no, payment_method, payment_details) VALUES(?, ?, ?, ?, ?, ?, ?)', 'ssissss', $arguments);

            return $statement->GetAffectedRowCount();
        }

        public function UpdateDetails($reservation_code, $name, $nric, $email, $contact_no, $payment_method, $payment_details)
        {
            if(is_numeric($contact_no) === FALSE)
            {
                throw new InvalidArgumentException('Invalid contact number.');
            }

            if(is_numeric($payment_details) === FALSE)
            {
                throw new InvalidArgumentException('Invalid payment details.');
            }

            $arguments = array(&$name, &$nric, &$email, &$contact_no, &$payment_method, &$payment_details, &$reservation_code);

            $statement = new Statement($this->mysqli, 'UPDATE customers SET name = ?, nric = ?, email = ?, contact_no = ?, payment_method = ?, payment_details = ? WHERE reservation_code = ?', 'sisssss', $arguments);

            return $statement->GetAffectedRowCount();
        }

        public function GetCustomerWithIndex($index)
        {
            $statement = new Statement($this->mysqli, "SELECT * FROM customers LIMIT {$index}, 1");

            $row = $statement->GetRow(0);

            return $row;
        }

        public function GetCustomerWithFieldsFilter($reservation_code, $name, $nric, $email, $contact_no, $payment_method, $payment_details)
        {
            if(is_numeric($contact_no) === FALSE)
            {
                throw new InvalidArgumentException('Invalid contact number.');
            }

            if(is_numeric($payment_details) === FALSE)
            {
                throw new InvalidArgumentException('Invalid payment details.');
            }

            $first_filter_added = FALSE;
            $query = 'SELECT * FROM customers';
            $bound_argument_type = NULL;
            $filters;

            if($reservation_code !== NULL)
            {
                $query = $query . ' WHERE reservation_code = ?';
                $bound_argument_type = $bound_argument_type . 's';
                $filters[] = &$reservation_code;
                $first_filter_added = TRUE;
            }

            $this->GetCustomerWithFieldsFilter2($name, 'name', $bound_argument_type, 's', $filters, $first_filter_added, $query);
            $this->GetCustomerWithFieldsFilter2($nric, 'nric', $bound_argument_type, 'i', $filters, $first_filter_added, $query);
            $this->GetCustomerWithFieldsFilter2($email, 'email', $bound_argument_type, 's', $filters, $first_filter_added, $query);
            $this->GetCustomerWithFieldsFilter2($contact_no, 'contact_no', $bound_argument_type, 's', $filters, $first_filter_added, $query);
            $this->GetCustomerWithFieldsFilter2($payment_method, 'payment_method', $bound_argument_type, 's', $filters, $first_filter_added, $query);
            $this->GetCustomerWithFieldsFilter2($payment_details, 'payment_details', $bound_argument_type, 's', $filters, $first_filter_added, $query);

            $statement = new Statement($this->mysqli, $query, $bound_argument_type, $filters);

            $rows = $statement->GetAllRows();

            return $rows;
        }

        public function GetCustomerCount()
        {
            $statement = new Statement($this->mysqli, 'SELECT COUNT(*) FROM customers');

            $row = $statement->GetRow(0);

            return $row[0];
        }

        public function HasPaid($reservation_code)
        {
            $arguments = array(&$reservation_code);

            $statement = new Statement($this->mysqli, 'SELECT reservation_code FROM customers WHERE reservation_code = ? LIMIT 1', 's', $arguments);

            return $statement->GetRowCount() === 1;
        }

        private function GetCustomerWithFieldsFilter2(&$argument, $argument_name, &$bound_argument_type, $bound_argument_type_str, &$filters, &$first_filter_added, &$query)
        {
            if($argument !== NULL)
            {
                $_query = $argument_name . ' = ?';

                $this->GetCustomerWithFieldsFilter3($first_filter_added, $query, $_query);

                $bound_argument_type = $bound_argument_type . $bound_argument_type_str;
                $filters[] = &$argument;
            }
        }

        private function GetCustomerWithFieldsFilter3(&$first_filter_added, &$query, &$_query)
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