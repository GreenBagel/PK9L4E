
var dateFormat = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
var nricFormat = /^\d{6}-\d{2}-\d{4}$/;
var contactNoFormat = /^\d{3}-\d{9,10}$/;
var emailFormat = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var paymentFormat = /^\d{4}-\d{4}-\d{4}$/;

/*********************************************
Global functions
*/
//validate empty input
function validateEmpty(x){
	if(x.length == 0)
		return false;
	else
		return true;
}
/***********************************************/

/*********************************************
Start of search search flight screen functions
*/

//validate date
function validateDate(){
	if(!dateFormat.test(date))
		return false;
	else
		return true;
}

//validate origin/destination
function validateOriginDestination(){
	for(i = 0; i < locations.length; i++){
		if(locations[i] == location.toLowerCase())
			return true;
	}
	return false;
}

/*
End of search flight screen functions
***********************************************/



/**********************************************
Start of reservation screen functions 
*/


//validate name
function validateName(i,o,n){
	if(!/^[a-zA-Z ]+$/.test(o.val())){
		o.parent().addClass("has-error");
		updateTips(i,n+" not a valid name!");
		return false;
	}
	else
		return true; 
}

//validate nric
function validateNRIC(){
	if(!nricFormat.test(nric))
		return false;
	else
		return true;
}

//validate contact number
function validateContactNo(){
	if(!contactNoFormat.test(contactNo))
		return false;
	else
		return true;
}

//validate email address
function validateEmail(){
	if(!emailFormat.test(email))
		return false;
	else
		return true;
}


/*
End of reservation screen functions
**********************************************/



/**********************************************
Start of purchase screen functions
*/

function validatePayment(){
	if(!paymentFormat.test(payment))
		return false;
	else
		return true;
}

/*
End of purchase screen functions
**********************************************/