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
        <div id="header"><h3>Airline Reservation System</h3></div>
        <div id="body">
        <div id="clockbox"></div>
        <h2>Reservation successful!</h2>
        <?php
        require_once('../database/database.php');
        require_once('../database/locations.php');
        require_once('../database/flights.php');
        require_once('../database/reservations.php');


        $database = new Database('root', '', 'sqm_fp');
        $locations = new Locations($database);
        $flights = new Flights($database);
        $reservations = new Reservations($database);
        
        
        $flightCode = $_POST['flightCode'];
        
        $name =$_POST['name'];
        $ICNumber =$_POST['ICNumber'];
        $email =$_POST['email'];
        $phoneNum =$_POST['phoneNum'];
        $seatNo = $_POST['seatNo'];
        
        $reservationCode = $reservations->Add($flightCode, $name, $ICNumber, $email, $phoneNum, $seatNo);
        
        if ($reservationCode != null){
            //$reservations->UpdateDetails($reservationCode, $name, $ICNumber, $email, $phoneNum);
            ?>
        Your booking is successful. Here is your booking code: <?= $reservationCode?>
        <br> Please write this down because you need this code to make payment.<br>
        Go to <a href="../index.php">Home Page</a> to make payment.
                
                <?php
        }else{
            echo 'Something went wrong please try again';
        }
        ?>
        </div>
        </div>
    </body>
</html>