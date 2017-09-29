<?PHP
session_start();
require_once('lib/gen_lib.php');
$file=$_SESSION['id'].".json";
$me=array();
if(!$_SESSION['id'] || !file_exists($file)){ // no session exists
	return;
}
else { // session continue
	$me=json_decode(file_get_contents($file));
}

$me->status='idle';
$me->game_id="";
$me->creator=false;
$_SESSION['game_id']="";

file_put_contents($file,json_encode($me));
?>