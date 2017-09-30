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
$idle_list=null; // players with idle or in other game status
$in_game_list=null; // players with wait or in play status in my game

while ($file = readdir($handler)) { 
	$ext = substr(strrchr($file,"."),1); 
	$ext = strtolower($ext); 
	if ($ext=="json") {
		if($file==$_SESSION['id'].".json") continue; // skip me
		$p=array();
		$p=json_decode(file_get_contents($file));
		if(is_in_my_game($me,$p)) {
			$in_game_list.=$p->uname.": ".$p->status;
			if($p->creator && $me->status=='waiting' && $me->game_id==$p->game_id)
				$in_game_list.= " <input type=button value='leave' onClick=\"leave_game('".$p->uname."');\">";
			$in_game_list.="<br>";
		}
		else {
			$idle_list.=$p->uname.": ".$p->status;
		  // if me is not playing, show join button
			if($p->status=='create_waiting'){
				if($me->status=='idle')
					$idle_list.= " <input type=button value='join' onClick=\"join('".$p->uname."');\">";
				else if($me->status=='waiting' && $me->game_id==$p->game_id)
					$idle_list.= " <input type=button value='leave' onClick=\"leave_game('".$p->uname."');\">";
			}
			$idle_list.="<br>";
		}
		do_admin($file,$p);
	}
} 
closedir($handler);
if($in_game_list) echo "<b><font color='blue'>Joined Players</font></b><br>".$in_game_list;
echo "<br><b><font color='green'>Players in the channel</font></b><br>".$idle_list;

function do_admin($file,&$p){
	if(strtotime(date('Y-m-d H:i:s'))-strtotime($p->last_act) >5)  // remove old file
		unlink($file); 
}
function is_in_my_game($me,$p){
	if($me->game_id && $me->game_id==$p->game_id) return true; 
	else return false;
}
?>
