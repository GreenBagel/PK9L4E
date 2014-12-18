<!DOCTYPE html>
<html>
    <head>
        <title>Purchase flight</title>
        <link rel="stylesheet" href="../lib/bootstrap.min.css">
        <link rel="stylesheet" href="../lib/css/default2.css">
        <link rel="stylesheet" href="../lib/css/purchase.css">  
        <script src="../lib/js/clockDisplay.js"></script>
        <script src="../lib/js/purchase.js"></script>
    </head>
    <body>
        <div id="container">
            <div id="header"><a href="../index.php"><h3>Airline Reservation System<img id="home-icon" src="../lib/image/home-icon.png" alt="Home"></h3></a></div>
            <div id="body">
                <div id="clockbox"></div>
                <h2>Make Payment</h2>
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
                    <form class="horizontal-form" name="myForm" action="purchaseFlight.php" onsubmit="javascript:return validateReserveCode();" method="post">
                        <div class="form-group">
                        <label class="col-sm-5 control-label">Please input your reservation code to make payment:</label>
                        <div class="col-sm-3"> 
                        <input class="form-control" type="text" name="resCode">
                        </div>
                        </div>
                        <input class="btn btn-primary" type="submit" value="Get details">
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
                            
                            <div id="flight-details">
                            <div class="information-wrapper">
                            <h3>Flight Details</h3><br>
                            <table class="table table-bordered" border="1">
        
                                <?php
                                foreach ($flightData as $result) {
                                    ?>
                                    <tr>
                                        <th>Flight code</th>
                                        <td><?= $result[0] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Origin</th>
                                        <td><?= $result[1] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Destination</th>    
                                        <td><?= $result[2] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Departure Date & Time</th>   
                                        <td><?= $result[3] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Arrival Date & Time</th>
                                        <td><?= $result[4] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Seats available</th>
                                        <td><?= $result[5] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Price</th>
                                        <td><?= $result[6] ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                            </div>
                            </div>
                            <div id="personal-details">
                            <div class="information-wrapper">
                            <h3>Personal Details</h3><br>
                            <table class="table table-bordered" border ="1">
                                
                                <?php
                                foreach ($resCode as $value) {
                                    ?>
                                    <tr>
                                        <th>Name</th>
                                        <td><?= $value[3] ?></td>
                                    </tr>
                                    <tr>
                                        <th>IC Number</th>
                                        <td><?= $value[4] ?></td>
                                    </tr>
                                    <tr>
                                        <th>email</th>
                                        <td><?= $value[5] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Phone No</th>
                                        <td><?= $value[6] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Seat Number</th>
                                        <td><?= $value[7] ?></td>
                                    </tr>
                                <?php }
                                ?>
                            </table>
                            </div>
                            </div>
                            
                            <div id="payment-details">
                            <div class="information-wrapper-2">
                            <h3>Please provide your payment details</h3>
                            <form class ="form-horizontal" role="form" style="margin-top:30px;" name="myForm2" action="paymentToDB.php" onsubmit="javascript:return validatePayment();" method="post">
                                
                                    <div class = "form-group">
                                        <label class ="col-sm-2 control-label">Payment Method:</label>
                                        <div class="col-sm-5">
                                        <select name="paymentMethod">
                                                <option value="bank Transfer">Bank Transfer</option>
                                                <option value="Credit Card">Credit Card</option>
                                        </select>
                                        </div>
                                    </div>
                                    
                                    <div class = "form-group">
                                        <label class="col-sm-2 control-label">Payment Details:</label>
                                        <div class = "col-sm-3">
                                        <input class="form-control" type="text" name="paymentDetail">
                                        </div>
                                    </div>
        
                                    <input class="btn btn-warning" type="submit" value="Purchase">
                                    <input type="hidden" name="resCode" value="<?= $value[0] ?>">
                                    
                                
                            </form>
                            </div>
                            </div>
                            <?php
                        } else {?>
                        <div style="width:100%; float:left; margin-top:30px;">
                            <p style="text-align:center;">
                                This flight has already been paid.<br>
                                <a href="../index.php">Go back</a>
                            </p>
                        </div>
                    <?php
                        }
                    } catch (Exception $e) {?>
                        <div style="width:100%; float:left; margin-top:30px;">
                            <p style="text-align:center;">Invalid code provided. Please reconfirm you have entered your code correctly</p>
                        </div>
                    <?php
                    }
                }
                ?>
            </div>
            <!-- <div id="footer"><p>G53SQM - Group A &copy 2014</p></div> -->
        </div>
    </body>
</html>