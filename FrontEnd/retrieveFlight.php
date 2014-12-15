<!DOCTYPE HTML>
<html>
<head>
	<title>Retrieve Flight</title>
    <link rel="stylesheet" href="../lib/bootstrap.min.css">
    <link rel="stylesheet" href="../lib/css/default2.css">
    <link rel="stylesheet" href="../lib/css/search.css">
    <script src="../lib/js/clockDisplay.js"></script>
</head>

<body>
	<h3 align="center", id="header">Retrieve Flight</h3>

	<!-- PHP -->
	<?php
        require_once('../database/database.php');
        require_once('../database/locations.php');
        require_once('../database/flights.php');
        require_once('../database/reservations.php');

        $database = new Database('root', '', 'sqm_fp');
        $locations = new Locations($database);
        $flights = new Flights($database);
        $res = new Reservations($database);
    ?>

    <!-- Form -->
    <form class="form-inline form-horizontal" role="form" action="retrieveFlight.php" method="post">
    	Reservation Code: &nbsp
    	<input type="text" name="code"> &nbsp
    	<input type="submit" class="btn btn-default" value="submit">
    </form>

    <br/> <br/> <br/>

     <table border="1">

     		<!-- Flight Code -->
            <tr>
                <th>Flight code</th>

                <?php
                $reservation_code = (isset($_POST['code']) ? $_POST['code'] : null);
                if ($reservation_code == null) {
                ?> 
                    <td> </td>
             <?php   
            } else {
            	try {
            		$resInfo = $res->GetReservationWithFieldsFilter($reservation_code, null, null, null, null, null, null, null, null, null);

            		foreach ($resInfo as $result) {
            		?>
            			<td> <?= $result[1] ?> </td>
            		<?php
            		}
            	}
            	catch(Exception $e) {
            		echo 'Invalid Reservation Code!';
            	}
            } 
            	?>
            </tr>


            <!-- Origin -->
            <tr>
                <th>Origin</th>

                <?php 
                if ($reservation_code == null) {
                ?>
                	<td> </td>
               	<?php 
               	} else {
               	?>
                	<td> <?= $result[2]?> </td>
                <?php
            	} ?>
            </tr>

            <!-- Departure Date & Time -->
            <tr>
                <th>Departure Date & Time</th>

                <?php 
                if ($reservation_code == null) {	
                ?>
                	<td> </td>
                <?php
            	} else {
            	?>
            		<td> <?= $result[2]?> </td>
            	<?php
            	} ?>
            </tr>

            <!-- Price -->
            <tr>
                <th>Price</th>

                <?php 
                if ($reservation_code == null) {	
                ?>
                	<td> </td>
                <?php
            	} else {
            	?>
            		<td> <?= $result[2]?> </td>
            	<?php
            	} ?>
            </tr>
        </table>

    <br/>

        <table border="1">

        	<!-- Name -->
            <tr>
                <th>Name</th>

                <?php 
                if ($reservation_code == null) {	
                ?>
                	<td> </td>
                <?php
            	} else {
            	?>
            		<td> <?= $result[3]?> </td>
            	<?php
            	} ?>
            </tr>

            <!-- NRIC -->
            <tr>
                <th>NRIC</th>

                <?php 
                if ($reservation_code == null) {	
                ?>
                	<td> </td>
                <?php
            	} else {
            	?>
            		<td> <?= $result[4]?> </td>
            	<?php
            	} ?>

            <!-- Contact Number -->
            </tr>
                <th>Contact Number</th>
                
                <?php 
                if ($reservation_code == null) {	
                ?>
                	<td> </td>
                <?php
            	} else {
            	?>
            		<td> <?= $result[6]?> </td>
            	<?php
            	} ?>

            <!-- Email -->
            <tr>
                <th>Email Address</th>
                
                <?php 
                if ($reservation_code == null) {	
                ?>
                	<td> </td>
                <?php
            	} else {
            	?>
            		<td> <?= $result[5]?> </td>
            	<?php
            	} ?>
            </tr>
        </table>


</body>

</html>