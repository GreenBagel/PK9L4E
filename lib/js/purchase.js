function validateReserveCode(){
	var code = document.forms["myForm"]["resCode"].value;
	
	var message="";

	if(code.length == 0 ){
		message += "Reservation code not provided!\n";
		alert(message);
		return false;
	}
	else
		return true;
}

function validatePayment(){
	var paymentMethod = document.forms["myForm2"]["paymentMethod"].value;
	var paymentDetail = document.forms["myForm2"]["paymentDetail"].value;
	
	var message="";

	if(paymentMethod.length == 0 || paymentDetail.length == 0 ){
		if(paymentMethod.length == 0 )
		message += "Payment method not provided!\n";

		if(paymentDetail.length == 0 )
		message += "Payment detail not provided!\n";

		alert(message);
		return false;
	}
	else
		return true;
}