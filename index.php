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

</script>
</head>

<body>
	<form name='form1' method='POST' onsubmit='return check_login(this);'>
	<input type='hidden' name='operation'>
	<h2>Welcome to MIU IT Massive Multi-RSP Game!</h2>
<?
session_start();
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
	print_others_data();
	print_polling();
}
?>
	
	</form>	
 </body>
</html>

<?
	function print_get_my_data(){
		//echo "<button type='button' onclick='loadMyData()'>Get My Data</button>";
		echo "<button type='button' onclick='logout()'>Logout</button>";
		echo "<br>ME
			<div id='div_my'></div>
		";
	}
	
	function print_others_data(){
		echo "
			<br>OTHERS
			<div id='div_others'></div>
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
