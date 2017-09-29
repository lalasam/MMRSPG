<?PHP
session_start();
$file=$_SESSION['id'].".json";
$me=array();
if(!file_exists($file)){ // no session exists
	return;
}
else { // session continue
	$me=json_decode(file_get_contents($file));
}

$dir=".";
$handler = opendir($dir); 
while ($file = readdir($handler)) { 
	$ext = substr(strrchr($file,"."),1); 
	$ext = strtolower($ext); 
	if ($ext=="json") {
		if($file==$_SESSION['id'].".json") continue; // skip me
		$p=array();
		$p=json_decode(file_get_contents($file));
		echo $p->uname.": ".$p->status;
	  // if me is not playing, show join button
		if($p->status=='create_waiting'){
			if($me->status=='idle')
				echo " <input type=button value='join' onClick=\"join('".$p->uname."');\">";
			else if($me->status=='waiting' && $me->game_id==$p->game_id)
				echo " <input type=button value='leave' onClick=\"leave_game('".$p->uname."');\">";
		}
		echo "<br>";
		if($p->creator==false) do_admin($file,$p);
	}
} 
closedir($handler);

function do_admin($file,&$p){
	if(strtotime(date('Y-m-d H:i:s'))-strtotime($p->last_act) >5)  // remove old file
		unlink($file); 
}
?>