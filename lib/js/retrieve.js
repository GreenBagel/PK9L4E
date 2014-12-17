
function validate(){
	var code = document.forms["myForm"]["code"].value;
	
	var message="";

	if(code.length == 0 ){
		message += "Reservation code not provided!\n";
		alert(message);
		return false;
	}
	else
		return true;
}