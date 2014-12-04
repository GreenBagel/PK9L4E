<html>
    <body>
        <?php
        require_once('../database/database.php');
        require_once('../database/locations.php');
        require_once('../database/flights.php');


        $database = new Database('root', '', 'sqm_fp');
        $locations = new Locations($database);
        $flights = new Flights($database);
        ?>
        <h3>This is the detail of the flight you've choosen</h3>
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
            $flightCode = $_POST['flightCode'];
            $result = $flights->GetFlightWithFieldsFilter($flightCode, null, null, null, null, null, null);
            foreach ($result as $result) {
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
        <h3>Please enter your personal data</h3>
        <form action='' method='post'>
            <table>
                <tr>
                    <td>Full Name:</td>
                    <td><input type='text' name='name'</td>
                </tr>
                <tr>
                    <td>IC number:</td>
                    <td><input type='text' name ='ICNumber'</td>
                </tr>
                <tr>
                    <td>Email address:</td>
                    <td><input type='email' name='email'</td>
                </tr>
                <tr>
                    <td>Phone number:</td>
                    <td><input type='number' name='phoneNum'</td>
                </tr>
                <tr>
                    <td rowspan ='2'><input type='submit' value='Reserve'></td>
                    <input type ='hidden' name='flightCode' value='<?= $flightCode?>'>
                </tr>
            </table>
        </form>
    </body>
</html>
