<!DOCTYPE html>
<html>
<head>
<script>
function loadMyData() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		//document.getElementById("div_my").innerHTML=this.responseText;
		var jobj=JSON.parse(this.responseText);
		document.getElementById("div_my").innerHTML=""; //clear text
		for(var i in jobj){
		  document.getElementById("div_my").innerHTML+= i+":"+jobj[i]+"<br>";
		}
		if(jobj['status']=='idle') 
			document.getElementById('div_create_g').style.display='inline-block';
		else 
			document.getElementById('div_create_g').style.display='none';
		if(jobj['status']=='create_waiting') 
			document.getElementById('div_cancel_create').style.display='inline-block';
		else 
			document.getElementById('div_cancel_create').style.display='none';
    }
  };
  xhttp.open("POST", "get_my_data.php", true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send("uname=<?=$_POST['uname']?>");
}

function loadOthersData() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		document.getElementById("div_others").innerHTML=this.responseText;
    }
  };
  xhttp.open("POST", "get_others_data.php", true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send("uname=<?=$_POST['uname']?>");
}

function logout(){
	document.form1.operation.value='LOGOUT';
	document.form1.submit();
}

function check_login(f){
	if(f.uname.value=='') {
		alert("Enter User name!");
		f.uname.getfocus();
		return false
	}
	f.operation.value='LOGIN';
	return true;
}

function create_game() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		document.getElementById("div_others").innerHTML=this.responseText;
    }
  };
  xhttp.open("POST", "create_game.php", true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send("uname=<?=$_POST['uname']?>");
}

function join(pname) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		document.getElementById("div_others").innerHTML=this.responseText;
    }
  };
  xhttp.open("POST", "join_game.php", true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send("uname=<?=$_POST['uname']?>&pname="+pname);
}

function leave_game(pname) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		document.getElementById("div_others").innerHTML=this.responseText;
    }
  };
  xhttp.open("POST", "leave_game.php", true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send("uname=<?=$_POST['uname']?>&pname="+pname);
}

function cancel_create() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
	  ;
  };
  xhttp.open("POST", "cancel_create.php", true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send("uname=<?=$_POST['uname']?>");
}

function join(pname) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
		document.getElementById("div_others").innerHTML=this.responseText;
    }
  };
  xhttp.open("POST", "join_game.php", true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send("uname=<?=$_POST['uname']?>&pname="+pname);
}
</script>
</head>

<body>
	<form name='form1' method='POST' onsubmit='return check_login(this);'>
	<input type='hidden' name='operation'>
	<h2>Welcome to MIU IT Massive Multi-RSP Game!</h2>
<?
session_start(); // $_SESSION['id'] is used for maintaining a user's session
if($_POST['operation']=='LOGIN'){
	if(check_name_conflict($_POST['uname'])){ // name conflict
		echo "<script>alert('".$_POST['uname']." already exists!');</script>";
	}
	else // name is ok
		$_SESSION['id']=rand(1,1000000);
}
else if($_POST['operation']=='LOGOUT'){
	session_destroy();
	$_SESSION['id']=null;
}
if(!$_SESSION['id']) { // user login required
	print_user_login();	
}
else { // user logged in already
	print_get_my_data();
	print_polling();
	print_create_game();
	print_cancel_create();	
	print_others_data();
}	
?>
	</form>	
 </body>
</html>

<?
	function print_get_my_data(){
		//echo "<button type='button' onclick='loadMyData()'>Get My Data</button>";
		echo "<button type='button' onclick='logout()'>Logout</button>";
		echo "<br><b>ME</b>
			<div id='div_my'></div>
		";
	}
	
	function print_others_data(){
		echo "
			<p><div id='div_others'></div></p>
		";
	}
	
	function print_user_login(){
		echo "User Name: ";
		echo "<input type=text name='uname'> ";		
		echo "<input type=submit> ";		
		echo "<input type=reset>";		
	}
	
	function print_polling(){
		echo "<script>
			var game_handle1, game_handle2;
			window.onload=function(){
				game_handle1=setInterval(loadMyData,1000); // polling
				game_handle2=setInterval(loadOthersData,1000); // polling
			}
			</script>
		";
	}
	
	function print_create_game(){
		echo "<input type=button id ='div_create_g' style='display:none;' value='create a game' onClick='create_game();'> ";
	}
	function print_cancel_create(){
		echo "<input type=button id ='div_cancel_create' style='display:none;' value='Cancel Creating Game' onClick='cancel_create();'> ";
	}
	
	function check_name_conflict($uname){
		$dir=".";
		$handler = opendir($dir); 
		while ($file = readdir($handler)) { 
			$ext = substr(strrchr($file,"."),1); 
			$ext = strtolower($ext); 
			if ($ext=="json") {
				$p=array();
				$p=json_decode(file_get_contents($file));
				if($p->uname==$uname) { // name conflict
					closedir($handler); 
					return true;
				}
			}
		} 
		closedir($handler); 
		return false;
	}	

?>
