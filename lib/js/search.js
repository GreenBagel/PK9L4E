

function validate(){
	var date = document.forms["myForm"]["deptDate"].value;
	var origin = document.forms["myForm"]["originCity"].value;
	var destination = document.forms["myForm"]["destCity"].value;
	// alert(date.length + " " + origin.length + " " + destination.length);
	var message="";

	if(date.length == 0 || origin.length == 0 || destination.length == 0){
		if(date.length == 0)
			message += "Date not chosen!\n";

		if(origin.length == 0)
			message += "Origin not chosen!\n";

		if(destination.length == 0)
			message += "Destination not chosen!\n";

		alert(message);
		return false;
	}
	else
		return true;
}