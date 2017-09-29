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

$me->game_id=rand(1,1000000);
$me->status='create_waiting';
$me->creator=true;
$_SESSION['game_id']=$me->game_id;

file_put_contents($file,json_encode($me));
?>