<!DOCTYPE html>
<html>
    <head>
        <title>Success</title>
        <link rel="stylesheet" href="../lib/bootstrap.min.css">
        <link rel="stylesheet" href="../lib/css/default2.css">
        <link rel="stylesheet" href="../lib/css/search.css">
        <script src="../lib/js/clockDisplay.js"></script>
    </head>
    <body>
        <div id="container">
        <div id="header"><a href="../index.php"><h3>Airline Reservation System<img id="home-icon" src="../lib/image/home-icon.png" alt="Home"></h3></a></div>
        <div id="body">
        <div id="clockbox"></div>
        <h2>Payment successful!</h2>
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
        <p style="text-align:center;">
            Your payment has been registered.
            <a href="../index.php">Return to main page</a></p>
        </div>
        </div>
    </body>
</html>
