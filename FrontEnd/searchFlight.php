<!DOCTYPE html>
<html>
    <head>
        <title>Search flight</title>
        <link rel="stylesheet" href="../lib/bootstrap.min.css">
        <link rel="stylesheet" href="../lib/css/default2.css">
        <link rel="stylesheet" href="../lib/css/search.css">
        <script src="../lib/js/clockDisplay.js"></script>
        <script src="../lib/js/search.js"></script>
    </head>
    <body>
        <div id="container">
            <div id="header"><a href="../index.php"><h3>Airline Reservation System<img id="home-icon" src="../lib/image/home-icon.png" alt="Home"></h3></a></div>
            <div id="body">
                <div id="clockbox"></div>
                <?php
                require_once('../database/database.php');
                require_once('../database/locations.php');
                require_once('../database/flights.php');
                require_once('../database/reservations.php');

                $database = new Database('root', '', 'sqm_fp');
                $locations = new Locations($database);
                $flights = new Flights($database);
                $reservations = new Reservations($database);
                ?>
                <h2>Search Flight</h2>
                <div id="form-wrapper">
                    <form class="form-inline form-horizontal" role="form" name="myForm" action="searchFlight.php" onsubmit="javascript:return validate();" method="post">   
                        <div class="form-group">
                            <div class ="input-group">
                                <label class="col-sm-2 control-label">Date:</label>
                                <div class="col-sm-10">
                                    <input id="date" type="date" class="form-control" name="deptDate">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class ="input-group">
                                <label class="col-sm-2 control-label">Origin:</label>
                                <div class="col-sm-10">
                                    <select id="originCity" name ="originCity" class="form-control">
                                        <?php
                                        $location_array = $locations->GetLocationWithFieldsFilter(NULL);?>
                                        <option value="">Select Origin</option>
                                        <?php
                                        foreach ($location_array as $location) {
                                        ?>
                                            <option value="<?= $location[0] ?>"><?php echo $location[0] ?></option>
                                        <?php
                                        }
                                        ?>    
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <label class="col-sm-3 control-label">Destination:</label>
                                <div class="col-sm-9">
                                    <select id="destCity" name ="destCity" class="form-control">
                                        <?php
                                        $location_array = $locations->GetLocationWithFieldsFilter(NULL);?>
                                        <option value="">Select Destination</option>
                                        <?php
                                        foreach ($location_array as $location) {
                                        ?>
                                            <option value="<?= $location[0] ?>"><?php echo $location[0] ?></option>
                                        <?php
                                        }
                                        ?> 
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-primary" value="submit"> 
                    </form>
                </div>

                <?php
                $date = (isset($_POST['deptDate']) ? $_POST['deptDate'] : null);
                $originCity = (isset($_POST['originCity']) ? $_POST['originCity'] : null);
                $destCity = (isset($_POST['destCity']) ? $_POST['destCity'] : null);
                if (strpos($originCity, 'Select') !== false || strpos($destCity, 'Select') !== false) {
                    $originCity = null;
                    $destCity = null;
                }

                if ($date == null and $originCity == null and $destCity == null) {
                    ?>
                    <?php
                } else if ($date == null or $originCity == null or $destCity == null) {
                    echo 'Please try again with all the criteria inputted';
                } else{
                    ?>
                    <div id="table-wrapper">
                        <table class="table table-bordered" border="1">
                            <tr>
                                <th>Flight code</th>
                                <th>Origin</th>
                                <th>Destination</th>
                                <th>Departure Date & Time</th>
                                <th>Arrival Date & Time</th>
                                <th>Seats Number</th>
                                <th>Price</th>
                                <th>Book Now!</th>
                            </tr>
                            <?php
                            try {
                                $flightResult = $flights->GetFlightWithFieldsFilter(null, $originCity, $date, $destCity, null, null, null, Database::DATE_TIME_FORMAT_TO_DATE_ONLY, null);


                                foreach ($flightResult as $result) {
                                    $full = $reservations->IsFlightFull($result[0]);
                                    echo $full;
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
                                            <?php
                                            if (!$full) {
                                                ?>
                                                <td><input type="submit" class="btn btn-default" value="Book"</td>
                                            <input type="hidden" name="flightCode" value="<?= $result[0] ?>">
                                            <?php
                                        } else {
                                            echo ('<td>Full</td>');
                                        }
                                        ?>


                                        </tr>
                                    </form>
                                    <?php
                                }
                            } catch (Exception $e) {

                                echo '<tr>';
                                echo '<td colspan="7">No flights on that date, please try another date and click search</td></tr>';
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
            <!-- <div id="footer"><p>G53SQM - Group A &copy 2014</p></div> -->
        </div>
    </body>
</html>
