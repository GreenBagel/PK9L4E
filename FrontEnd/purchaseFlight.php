<!DOCTYPE html>
<html>
    <head>
        <title>Purchase flight</title>
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
                <div id="form-wrapper">
                    <form action="purchaseFlight.php" method="post">
                        Please input your reservation code to make payment: 
                        <input type="text" name="resCode">
                        <input type="submit" value="submit">
                    </form>
                </div>
                <?php
                $resCode = (isset($_POST['resCode']) ? $_POST['resCode'] : null);
                if ($resCode != null) {
                    try {
                        $resCode = $reservations->GetReservationWithFieldsFilter($resCode, null, null, null, null, null, null, null, null);
                        $hasPaid;

                        foreach ($resCode as $value) {
                            $hasPaid = $customers->HasPaid($value[0]);
                            $flightNumber = $value[1];
                        }
                        if (!$hasPaid) {
                            $flightData = $flights->GetFlightWithFieldsFilter($flightNumber, null, null, null, null, null, null);
                            ?>
                            <h3>Here is your Flight Details</h3><br>
                            <table border="1">
                                <tr>
                                    <th>Flight code</th>
                                    <th>Origin</th>
                                    <th>Destination</th>
                                    <th>Departure Date & Time</th>
                                    <th>Arrival Date & Time</th>
                                    <th>Seats available</th>
                                    <th>Price</th>
                                </tr>
                                <?php
                                foreach ($flightData as $result) {
                                    ?>
                                    <tr>
                                        <td><?= $result[0] ?></td>
                                        <td><?= $result[1] ?></td>
                                        <td><?= $result[2] ?></td>
                                        <td><?= $result[3] ?></td>
                                        <td><?= $result[4] ?></td>
                                        <td><?= $result[5] ?></td>
                                        <td><?= $result[6] ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                            <h3>Here is your personal Detail</h3>
                            <table border ="1">
                                <tr>
                                    <th>Name</th>
                                    <th>IC Number</th>
                                    <th>email</th>
                                    <th>Phone No</th>
                                    <th>Seat Number</th>
                                </tr>
                                <?php
                                foreach ($resCode as $value) {
                                    ?>
                                    <tr>
                                        <td><?= $value[3] ?></td>
                                        <td><?= $value[4] ?></td>
                                        <td><?= $value[5] ?></td>
                                        <td><?= $value[6] ?></td>
                                        <td><?= $value[7] ?></td>
                                    </tr>
                                <?php }
                                ?>
                            </table>
                            <h3>Please Input your payment details</h3>
                            <form action="paymentToDB.php" method="post">
                                <table>
                                    <tr>
                                        <td>Payment type:</td>
                                        <td><select name="paymentMethod">
                                                <option value="bank Transfer">Bank Transfer</option>
                                                <option value="Credit Card">Credit Card</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td>Payment Details:</td>
                                        <td><input type="text" name="paymentDetail"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="submit" value="submit"></td>
                                    <input type="hidden" name="resCode" value="<?= $value[0] ?>">
                                    </tr>
                                </table>
                            </form>
                            <?php
                        } else {
                            echo 'Your flight has been paid<br>';
                            echo '<a href="../index.php">Go back</a>';
                        }
                    } catch (Exception $e) {
                        echo 'Wrong code inputted. Please check your code again';
                    }
                }
                ?>
            </div>
            <!-- <div id="footer"><p>G53SQM - Group A &copy 2014</p></div> -->
        </div>
    </body>
</html>