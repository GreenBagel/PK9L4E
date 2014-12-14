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
        ?>
        <form action="purchaseFlight.php" method="post">
            Please input your reservation code to make payment: 
            <input type="text" name="resCode">
            <input type="submit" value="submit">
        </form>
        <?php
        $resCode = (isset($_POST['resCode']) ? $_POST['resCode'] : null);
        if ($resCode != null) {
            try {
                $resCode = $reservations->GetReservationWithFieldsFilter($resCode, null, null, null, null, null, null, null, null);
                foreach ($resCode as $value) {
                    $flightNumber = $value[1];
                }

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
            } catch (Exception $e) {
                echo 'Wrong code inputted. Please check your code again';
            }
        }
        ?>
    </body>
</html>