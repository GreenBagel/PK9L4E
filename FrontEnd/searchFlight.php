<html>
    <body>

        <?php
        require_once('../database/database.php');
        require_once('../database/locations.php');
        require_once('../database/flights.php');

        $database = new Database('root', '', 'flight');
        $locations = new Locations($database);
        $flights = new Flights($database);
        ?>

        <form action="" method="post">
            <h2>Search Flight</h2>
            Date:
            <input type="date" name="deptDate">

            Origin:
            <select name ="originCity">
                <?php
                $location_array = $locations->GetLocationWithFieldsFilter(NULL);
                echo '<option>Select Origin</option>';
                foreach($location_array as $location)
                {
                    echo '<option>'.$location[0].'</option>';
                }   
                ?>    
            </select>

            Destination:
            <select name ="destCity">
                <?php
                $location_array = $locations->GetLocationWithFieldsFilter(NULL);
                echo '<option value=\"\">Select Destination</option>';
                foreach($location_array as $location)
                {
                    echo '<option>'.$location[0].'</option>';
                }   
                ?> 
            </select>
            <input type="submit" value="submit"> 
        </form>
    </body>
</html>
