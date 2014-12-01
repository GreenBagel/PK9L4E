<html>

<head>
<title>Home</title>
<link rel="stylesheet" href="./lib/css/default.css">

<script type="text/javascript">
tday=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
tmonth=new Array("January","February","March","April","May","June","July","August","September","October","November","December");

function GetClock(){
var d=new Date();
var nday=d.getDay(),nmonth=d.getMonth(),ndate=d.getDate(),nyear=d.getYear(),nhour=d.getHours(),nmin=d.getMinutes(),nsec=d.getSeconds(),ap;

if(nhour==0){ap=" AM";nhour=12;}
else if(nhour<12){ap=" AM";}
else if(nhour==12){ap=" PM";}
else if(nhour>12){ap=" PM";nhour-=12;}

if(nyear<1000) nyear+=1900;
if(nmin<=9) nmin="0"+nmin;
if(nsec<=9) nsec="0"+nsec;

document.getElementById('clockbox').innerHTML=""+tday[nday]+", "+tmonth[nmonth]+" "+ndate+", "+nyear+" "+nhour+":"+nmin+":"+nsec+ap+"";
}

window.onload=function(){
GetClock();
setInterval(GetClock,1000);
}
</script>

</head>

<body>
<div id="header"><h3>Airline Reservation System</h3></div>

<div id="container">
	<div id="clockbox"></div>
	<div id="menu">
		<div id="link-wrapper">
		<div id="link1"><a class='link' href="./index.php">Search flight</a></div>
		<div id="link2"><a class='link' href="./index.php">Make payment</a></div>
		<div id="link3"><a class='link' href="./index.php">Retrieve flight details</a></div>
		</div>	
	</div>
</div>

<div id="footer"><p>G53SQM - Group A &copy 2014</p></div>
</body>

</html>









