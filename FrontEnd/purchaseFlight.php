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
            $resCode = $reservations->GetReservationWithFieldsFilter($resCode, null, null, null, null, null, null, null, null);
            if ($resCode == null) {
                echo 'Wrong code inputted. Please check your code again';
            } else {
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
                Here is your personal Detail
                <table border ="1">
                    <tr>
                        <th>Name</th>
                        <th>IC Number</th>
                        <th>email</th>
                        <th>Phone No</th>
                    </tr>
                    <?php
                    foreach ($resCode as $value) {
                        ?>
                        <tr>
                            <td><?= $value[3] ?></td>
                            <td><?= $value[4] ?></td>
                            <td><?= $value[5] ?></td>
                            <td><?= $value[6] ?></td>
                        </tr>
                    <?php }
                    ?>
                </table>

                <?php
            }
        }
        ?>
    </body>
</html>