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

        <form action="searchFlight.php" method="post">
            <h2>Search Flight</h2>
            Date:
            <input type="date" name="deptDate">

            Origin:
            <select name ="originCity">
                <?php
                $location_array = $locations->GetLocationWithFieldsFilter(NULL);
                echo '<option>Select Origin</option>';
                foreach ($location_array as $location) {
                    echo '<option>' . $location[0] . '</option>';
                }
                ?>    
            </select>

            Destination:
            <select name ="destCity">
                <?php
                $location_array = $locations->GetLocationWithFieldsFilter(NULL);
                echo '<option value=\"\">Select Destination</option>';
                foreach ($location_array as $location) {
                    echo '<option>' . $location[0] . '</option>';
                }
                ?> 
            </select>
            <input type="submit" value="submit"> 
        </form>
        <table border="1">
            <tr>
                <th>Flight code</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Departure Date & Time</th>
                <th>Arrival Date & Time</th>
                <th>Seats available</th>
                <th>Price</th>
                <th>Book Now!</th>
            </tr>
            <?php
            $date = (isset($_POST['deptDate']) ? $_POST['deptDate'] : null);
            $originCity = (isset($_POST['originCity']) ? $_POST['originCity'] : null);
            $destCity = (isset($_POST['destCity']) ? $_POST['destCity'] : null);

            if ($date == null || $originCity == null || $destCity == null) {
                ?>
                <tr> 
                    <td  colspan ="7">Click submit to begin searching using all the criteria</td>
                </tr>
                <?php
            } else {
                $flightResult = $flights->GetFlightWithFieldsFilter(null, $originCity, null, $destCity, null, null, null);
                foreach ($flightResult as $result) {
                    ?>
                <form action="reserveFlight.php" method="post">
                    <tr>
                        <td><?= $result[0] ?></td>
                        <td><?= $result[1] ?></td>
                        <td><?= $result[2] ?></td>
                        <td><?= $result[3] ?></td>
                        <td><?= $result[4] ?></td>
                        <td><?= $result[5] ?></td>
                        <td><?= $result[6] ?></td>
                        <td><input type="submit" value="book"</td>
                    <input type="hidden" name="flightCode" value="<?= $result[0]?>">
                    
                    </tr>
                </form>
                    <?php
                }
            }
            ?>
        </table>
    </body>
</html>
