<!DOCTYPE html>
<html>
    <head>
        <title>Reserve flight</title>
        <link rel="stylesheet" href="../lib/bootstrap.min.css">
        <link rel="stylesheet" href="../lib/css/default2.css">
        <link rel="stylesheet" href="../lib/css/reserve.css">
        <script src="../lib/js/clockDisplay.js"></script>
        <script src="../lib/js/reserve.js"></script>
    </head>
    <body>
        <div id="container">
        <div id="header"><a href="../index.php"><h3>Airline Reservation System<img id="home-icon" src="../lib/image/home-icon.png" alt="Home"></h3></a></div>
        <div id="body">
        <div id="clockbox"></div>
        <h2>Reserve Flight</h2>
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
        ?>
        
        <div id="table-wrapper">
        <div class="information-wrapper">
            <h3>Flight Details</h3>
        <table class="table table-bordered" border="1">
            <!-- <tr>
                <th>Flight code</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Departure Date & Time</th>
                <th>Arrival Date & Time</th>
                <th>Seats available</th>
                <th>Price</th>
            </tr> -->
            <?php
            $tableHeader = ['Flight Code', 'Origin', 'Destination','Depature Date & Time','Arrival Date & Time','Seats availabe', 'Price'];
            $flightCode = $_POST['flightCode'];
            $results = $flights->GetFlightWithFieldsFilter($flightCode, null, null, null, null, null, null);
            foreach ($results as $result) {
                ?>
                <tr>
                    <th><?= $tableHeader[0] ?></th>
                    <td><?= $result[0] ?></td>
                </tr>
                <tr>
                    <th><?= $tableHeader[1] ?></th>
                    <td><?= $result[1] ?></td>
                </tr>
                <tr>
                    <th><?= $tableHeader[2] ?></th>
                    <td><?= $result[2] ?></td>
                </tr>
                <tr>
                    <th><?= $tableHeader[3] ?></th>
                    <td><?= $result[3] ?></td>
                </tr>
                <tr>
                    <th><?= $tableHeader[4] ?></th>
                    <td><?= $result[4] ?></td>
                </tr>
                <tr>
                    <th><?= $tableHeader[5] ?></th>
                    <td><?= $result[5] ?></td>
                </tr>
                <!-- <tr> -->
                    <!-- <td>Seat Number:</td> -->
                    <!-- <td> -->
                        
                    <!-- </td> -->
                <!-- </tr> -->
                <tr>
                    <!-- <td rowspan ='2'><input type='submit' value='Reserve'></td> -->
                <!-- <input type ='hidden' name='flightCode' value='<?= $flightCode ?>'> -->
                    <th><?= $tableHeader[6] ?></th>
                    <td><?= $result[6] ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        </div>
        </div>
        
        <div id="form-wrapper">
            <div class="information-wrapper">
            <h3>Please enter your personal details</h3>
        <form class ="form-horizontal" role="form" name="myForm" action='reserveToDB.php' onsubmit="javascript:return validate();" method='post'>
            <div class ="form-group">
                <label class="col-sm-3 control-label">Full Name:</label>
                <div class="col-sm-5">
                <input class="form-control" type='text' name='name'>
                </div>
            </div>

            <div class = "form-group">    
                    <label class="col-sm-3 control-label">IC number:</label>
                <div class="col-sm-5">
                    <input class="form-control" type='text' name ='ICNumber'>
                </div>
            </div>

            <div class = "form-group">
                    <label class="col-sm-3 control-label">Email address:</label>
                    <div class="col-sm-5">
                    <input class="form-control" type='email' name='email'>
                    </div>
            </div>

            <div class = "form-group">    
                    <label class="col-sm-3 control-label">Phone number:</label>
                    <div class="col-sm-5">
                    <input class="form-control" type='number' name='phoneNum'>
                    </div>
            </div>
            <div class = "form-group">
                    <label class="col-sm-3 control-label">Seat number:</label>
                    <div class="col-sm-5">
                        <select name ="seatNo">
                        <?php
                        $seat = array();


                        $maxSeat = $flights->GetMaxSeatNumber($result[0]);
                        $seatTaken = $reservations->GetOccupiedSeats($result[0]);

                        for ($i = 1; $i <= $maxSeat; $i++) {
                            $taken = true;
                            for ($j = 0; $j < count($seatTaken); $j++) {
                                if ($seatTaken[$j] == $i) {
                                    $taken = false;
                                }
                            }
                            if ($taken) {
                                array_push($seat, $i);
                            }
                        }
                        foreach ($seat as $values) {
                            echo '<option>' . $values . '</option>';
                        }                        
                        ?>
                        </select>
                    </div>
            </div>    
                <input class="btn btn-primary" type='submit' style="margin-left:45%;" value='Reserve'>
                <input type ='hidden' name='flightCode' value='<?= $flightCode ?>'>    
        </form>
        </div>
        </div>
        </div>
        </div>
    </body>
</html>
