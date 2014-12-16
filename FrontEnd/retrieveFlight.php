<!DOCTYPE HTML>
<html>
    <head>
        <title>Retrieve Flight</title>
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
        <h2>Retrieve flight</h2>

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
        <div align = 'center'>
            <form class="form-inline form-horizontal" role="form" action="retrieveFlight.php" method="post">
                <div class="form-group">
                <label class="col-sm-4 label-control">Reservation Code: &nbsp </label>
                <div class="col-sm-4">
                <input class="form-control" type="text" name="code"> &nbsp
                </div>
                <div class="col-sm-4">
                <input type="submit" class="btn btn-default" value="Submit">
                </div>
                </div>
            </form>
        </div>

        <table border="1" class="table table-bordered">

            <!-- Flight Code -->
            <tr>
                <th align="center">Flight code</th>
                <th align="center">Origin</th>
                <th align="center">Departure Date & Time</th>
                <th align="center">Seat Number </th>
                <th align="center">Name</th>
                <th align="center">NRIC</th>
                <th align="center">Contact Number</th>
                <th align="center">Email Address</th>

            </tr>

            <?php
            $reservation_code = (isset($_POST['code']) ? $_POST['code'] : null);
            if ($reservation_code == null) {
                ?> 
                <tr> 
                    <td  colspan ="8" align="center">Flight information will be diplayed here.</td>
                </tr>             
                <?php
            } else {
                try {
                    $resInfo = $res->GetReservationWithFieldsFilter($reservation_code, null, null, null, null, null, null, null, null, null);
                    //$resInfo2 = $flights->GetFlightWithFieldsFilter($result[1], null, null, null, null, null, null, null, null);

                    foreach ($resInfo as $result) {
                        $resInfo2 = $flights->GetFlightWithFieldsFilter($result[1], null, null, null, null, null, null, null, null);
                        $hasPaid = $customers->HasPaid($result[0]);
                        if ($hasPaid) {
                            foreach ($resInfo2 as $result2) {
                                ?>
                                <tr>
                                    <td align="center"> <?= $result[1] ?> </td> <!-- Flight Code -->
                                    <td align="center"> <?= $result2[1] ?> </td> <!-- Origin -->
                                    <td align="center"> <?= $result2[2] ?> </td>	<!-- Departure Date & Time -->
                                    <td align="center"> <?= $result[7] ?> </td> <!-- Seat Number -->
                                    <td align="center"> <?= $result[3] ?> </td> <!-- Name -->
                                    <td align="center"> <?= $result[4] ?> </td> <!-- NRIC -->
                                    <td align="center"> <?= $result[6] ?> </td> <!-- Contact Number -->
                                    <td align="center"> <?= $result[5] ?> </td> <!-- Email Address -->
                                </tr>
                                <?php
                            }
                        }else{
                            echo 'The flight you search is not paid yet. Please make payment first before checking the result<br>';
                            echo '<a href="../FrontEnd/purchaseFlight.php">Make Payment</a>';
                        }
                    }
                } catch (Exception $e) {
                    echo '<td colspan="8" align="center"> Invalid Reservation Code </td>';
                }
            }
            ?>

        </table>
        </div>
        </div>

    </body>

</html>