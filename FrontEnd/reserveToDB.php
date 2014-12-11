<!DOCTYPE html>
<html>
    <body>
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
        
        $reservationCode = $reservations->Add($flightCode, $name, $ICNumber, $email, $phoneNum, null);
        
        if ($reservationCode != null){
            //$reservations->UpdateDetails($reservationCode, $name, $ICNumber, $email, $phoneNum);
            ?>
        Your booking is successful. Here is your booking code: <?= $reservationCode?>
        <br> Please write this down because you need this code to make payment.<br>
        Go to <a href="/PK9L4E/index.php">Home Page</a> to make payment.
                
                <?php
        }else{
            echo 'Something went wrong please try again';
        }
        ?>
        
    </body>
</html>