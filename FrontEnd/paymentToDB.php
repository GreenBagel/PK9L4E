<!DOCTYPE html>
<html>
    <body>
        <?php
        require_once('../database/database.php');
        require_once('../database/locations.php');
        require_once('../database/flights.php');
        require_once('../database/customers.php');
        require_once('../database/reservations.php');

        $database = new Database('root', '', 'sqm_fp');
        $locations = new Locations($database);
        $flights = new Flights($database);
        $customers = new Customers($database);
        $reservations = new Reservations($database);
        
        $resCode = $_POST['resCode'];
        $paymentMethod = $_POST['paymentMethod'];
        $paymentDetail = $_POST['paymentDetail'];
        $customers->ConfirmPayment($resCode, $paymentMethod, $paymentDetail);
        ?>
        <a href="/PK9L4E/index.php">Go back</a>
        
    </body>
</html>
