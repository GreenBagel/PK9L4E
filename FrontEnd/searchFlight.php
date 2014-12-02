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
            <input type="text" name="originCity">
            Destination:
            <input type="text" name ="destCity">
            <input type="submit" value="submit"> 
        </form>
    </body>
</html>
