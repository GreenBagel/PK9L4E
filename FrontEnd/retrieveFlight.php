<!DOCTYPE HTML>
<html>
    <head>
        <title>Retrieve Flight</title> 
        <link rel="stylesheet" href="../lib/bootstrap.min.css">
        <link rel="stylesheet" href="../lib/css/default2.css">
        <link rel="stylesheet" href="../lib/css/retrieve.css">
        <script src="../lib/js/clockDisplay.js"></script>
        <script src="../lib/js/retrieve.js"></script>
    </head>

    <body>
        <div id="container">
            <div id="header"><a href="../index.php"><h3>Airline Reservation System<img id="home-icon" src="../lib/image/home-icon.png" alt="Home"></h3></a></div>
            <div id="body">
                <div id="clockbox"></div>
                <h2>Retrieve Flight Details</h2>

                <!-- PHP -->
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
                $res = new Reservations($database);
                ?>

                <!-- Form -->
                <div id="form-wrapper">
                    <form class="horizontal-form" role="form" name="myForm" action="retrieveFlight.php" onsubmit="javascript:return validate();" method="post">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Enter Reservation Code: &nbsp </label>
                            <div class="col-sm-5">
                                <input class="form-control" type="text" name="code"> &nbsp
                            </div> 
                        </div>
                        <input class="btn btn-primary" type="submit" value="Retrieve">
                    </form>
                </div>

                

                <?php
                $reservation_code = (isset($_POST['code']) ? $_POST['code'] : null);
                if ($reservation_code == null) {
                    ?> 
                                 
                    <?php } else {
                    ?>

                        <?php
                        try {
                            $resInfo = $res->GetReservationWithFieldsFilter($reservation_code, null, null, null, null, null, null, null, null, null);
                            //$resInfo2 = $flights->GetFlightWithFieldsFilter($result[1], null, null, null, null, null, null, null, null);

                            foreach ($resInfo as $result) {
                                $resInfo2 = $flights->GetFlightWithFieldsFilter($result[1], null, null, null, null, null, null, null, null);
                                $hasPaid = $customers->HasPaid($result[0]);
                                if ($hasPaid) {
                                    foreach ($resInfo2 as $result2) {
                                        ?>
                                        <div id="table-wrapper">
                                        <table border="1" class="table table-bordered">
                                        <tr>
                                            <th>Flight code</th>
                                            <td> <?= $result[1] ?> </td> <!-- Flight Code -->
                                        </tr>
                                        <tr>
                                            <th>Origin</th>
                                            <td> <?= $result2[1] ?> </td> <!-- Origin -->
                                        </tr>
                                        <tr>    
                                            <th>Departure Date & Time</th>
                                            <td> <?= $result2[2] ?> </td>	<!-- Departure Date & Time -->
                                        </tr>
                                        <tr>    
                                            <th>Seat Number </th>
                                            <td> <?= $result[7] ?> </td> <!-- Seat Number -->
                                        </tr>
                                        <tr>    
                                            <th>Name</th>
                                            <td> <?= $result[3] ?> </td> <!-- Name -->
                                        </tr>
                                        <tr>
                                            <th>NRIC</th>
                                            <td> <?= $result[4] ?> </td> <!-- NRIC -->
                                        </tr>
                                        <tr>
                                            <th>Contact Number</th>
                                            <td> <?= $result[6] ?> </td> <!-- Contact Number -->
                                        </tr>
                                        <tr>
                                            <th>Email Address</th>
                                            <td> <?= $result[5] ?> </td> <!-- Email Address -->
                                        </tr>
                                        </table>
                                        </div>
                                        <?php
                                    }
                                } else {?>
                                    <div style="width:100%; float:left; margin-top:30px;">
                                    <p style="text-align:center;">The flight you search is not paid yet. Please make payment first before checking the result.<br>
                                    <a href="../FrontEnd/purchaseFlight.php">Make Payment</a></p>
                                    </div>
                                    <?php
                                }
                            }
                        } catch (Exception $e) { ?>
                        <div style="width:100%; float:left; margin-top:30px;">
                            <p style="text-align:center;">Invalid code provided. Please reconfirm you have entered your code correctly</p>
                        </div>
                        <?php
                        }
                    }
                    ?>
                
                <!-- </table> -->
            </div>
        </div>

    </body>

</html>