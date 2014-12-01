<?php
    require_once('database.php');
    require_once('locations.php');
    require_once('flights.php');

    $database = new Database('user1', '123', 'flight');
    $locations = new Locations($database);
    $flights = new Flights($database);
?>
<!DOCTYPE html>
    <head>
        <title>Sample Usage</title>
        <meta charset="UTF-8" />
    </head>
    <body>
        <?php
            $location = $locations->GetLocationWithIndex(2);
            echo '$locations->GetLocationWithIndex(2) <br />' . $location;
        ?>
        <br /><br />
        <?php
            $location_array = $locations->GetLocationWithFieldsFilter('Guangzhou (CAN)');
            echo '$locations->GetLocationWithFieldsFilter(\'Guangzhou (CAN)\') <br />' . $location_array[0][0];
        ?>
        <br /><br />
        <?php
            $location_array = $locations->GetLocationWithFieldsFilter(NULL);
            echo '$locations->GetLocationWithFieldsFilter(NULL)';
            foreach($location_array as $location)
            {
                echo '<br />' . $location[0];
            }
        ?>
        <br /><br />
        <?php
            echo '$locations->GetLocationCount() <br />' . $locations->GetLocationCount();
        ?>
        <br /><br />
        <?php
            $flight = $flights->GetFlightWithIndex(0);
            echo '$flight = $flights->GetFlightWithIndex(0) <br />';
            echo $flight[0] . ' ' . $flight[1] . ' ' . $flight[2] . ' ' . $flight[3] . ' ' . $flight[4] . ' ' . $flight[5] . ' ' . $flight[6];
        ?>
        <br /><br />
        <?php
            $flights_array = $flights->GetFlightWithFieldsFilter(NULL, NULL, NULL, NULL, NULL, NULL, NULL, Database::DATE_TIME_NO_FORMAT, Database::DATE_TIME_FORMAT_TO_DATE_ONLY);
            echo '$flights->GetFlightWithFieldsFilter(NULL, NULL, NULL, NULL, NULL, NULL, NULL, Database::DATE_TIME_NO_FORMAT, Database::DATE_TIME_FORMAT_TO_DATE_ONLY) <br />';
            foreach($flights_array as &$flight)
            {
                echo $flight[0] . ' ' . $flight[1] . ' ' . $flight[2] . ' ' . $flight[3] . ' ' . $flight[4] . ' ' . $flight[5] . ' ' . $flight[6] . '<br />';
            }
        ?>
        <br /><br />
        <?php
            $flights_array = $flights->GetFlightWithFieldsFilter(NULL, NULL, NULL, NULL, NULL, NULL, NULL, Database::DATE_TIME_NO_FORMAT, Database::DATE_TIME_FORMAT_TO_TIME_ONLY);
            echo '$flights->GetFlightWithFieldsFilter(NULL, NULL, NULL, NULL, NULL, NULL, NULL, Database::DATE_TIME_NO_FORMAT, Database::DATE_TIME_FORMAT_TO_TIME_ONLY) <br />';
            foreach($flights_array as &$flight)
            {
                echo $flight[0] . ' ' . $flight[1] . ' ' . $flight[2] . ' ' . $flight[3] . ' ' . $flight[4] . ' ' . $flight[5] . ' ' . $flight[6] . '<br />';
            }
        ?>
        <br /><br />
        <?php
            $flights_array = $flights->GetFlightWithFieldsFilter(NULL, NULL, NULL, NULL, NULL, NULL, NULL, Database::DATE_TIME_NO_FORMAT, Database::DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP);
            echo '$flights->GetFlightWithFieldsFilter(NULL, NULL, NULL, NULL, NULL, NULL, NULL, Database::DATE_TIME_NO_FORMAT, Database::DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP) <br />';
            foreach($flights_array as &$flight)
            {
                echo $flight[0] . ' ' . $flight[1] . ' ' . $flight[2] . ' ' . $flight[3] . ' ' . $flight[4] . ' ' . $flight[5] . ' ' . $flight[6] . '<br />';
            }
        ?>
        <br /><br />
        <?php
            $flights_array = $flights->GetFlightWithFieldsFilter(NULL, NULL, '15:15:00', NULL, NULL, NULL, NULL, Database::DATE_TIME_FORMAT_TO_TIME_ONLY, Database::DATE_TIME_NO_FORMAT);
            echo '$flights->GetFlightWithFieldsFilter(NULL, NULL, \'15:15:00\', NULL, NULL, NULL, NULL, Database::DATE_TIME_FORMAT_TO_TIME_ONLY, Database::DATE_TIME_NO_FORMAT) <br />';
            foreach($flights_array as &$flight)
            {
                echo $flight[0] . ' ' . $flight[1] . ' ' . $flight[2] . ' ' . $flight[3] . ' ' . $flight[4] . ' ' . $flight[5] . ' ' . $flight[6] . '<br />';
            }
        ?>
        <br /><br />
        <?php
            $flights_array = $flights->GetFlightWithFieldsFilter(NULL, NULL, '2017-03-21', 'Seoul (ICN)', NULL, 15, 2415.00, Database::DATE_TIME_FORMAT_TO_DATE_ONLY, Database::DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP);
            echo '$flights->GetFlightWithFieldsFilter(NULL, NULL, \'2015-03-21\', \'Seoul (ICN)\', NULL, 15, 2415.00, Database::DATE_TIME_FORMAT_TO_DATE_ONLY, Database::DATE_TIME_FORMAT_TO_UNIX_TIMESTAMP) <br />';
            foreach($flights_array as &$flight)
            {
                echo $flight[0] . ' ' . $flight[1] . ' ' . $flight[2] . ' ' . $flight[3] . ' ' . $flight[4] . ' ' . $flight[5] . ' ' . $flight[6] . '<br />';
            }
        ?>
        <br /><br />
        <?php
            echo '$flights->GetMaxSeatNumber(\'ZT185\') <br />';
            echo $flights->GetMaxSeatNumber('ZT185');
        ?>
        <br /><br />
        <?php
            echo '$flights->GetFlightCount() <br />';
            echo $flights->GetFlightCount();
        ?>
    </body>
</html>