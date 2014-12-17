var nameFormat = /^[a-zA-Z ]+$/;
var nricFormat = /^\d{6}-\d{2}-\d{4}$/;

function validate(){
	var name = document.forms["myForm"]["name"].value;
	var nric = document.forms["myForm"]["ICNumber"].value;
	var email = document.forms["myForm"]["email"].value;
	var contactNo = document.forms["myForm"]["phoneNum"].value;
	var message="";

	if(name.length == 0 || nric.length == 0 || email.length == 0 || contactNo.length == 0){
		if(name.length == 0)
			message += "Name is required!\n";

		if(nric.length == 0)
			message += "IC number is required!\n";

		if(email.length == 0)
			message += "Email is required!\n";

		if(contactNo.length == 0)
			message += "Phone number is required!\n";

		alert(message);
		return false;
	}
	else if(!nameFormat.test(name) || !nricFormat.test(nric)){
		if(!nameFormat.test(name))
			message += "Invalid name entered!\n";

		if(!nricFormat.test(nric))
			message += "Incorrect IC number format!\n";

		alert(message);
		return false;
	}
	else
		return false;
}