<?PHP
session_start();
require_once('lib/gen_lib.php');
$file=$_SESSION['id'].".json";
$me=array();
if(!file_exists($file)){ // initial login. Create json file
	new_player($me,$_POST['uname']);
	file_put_contents($file,json_encode($me));
}
else { // session continue
	$me=json_decode(file_get_contents($file));
	update($file,'last_act',date('Y-m-d H:i:s')); // update last_act
	if($me->status=='waiting' || $me->status=='playing'){
		$creator=get_creator($me);
		if(!$creator) { // creator left
			$me->status='idle';
			$me->game_id=null;
			update($file,'status','idle'); // update last_act
			update($file,'game_id',''); 
			update($file,'creator',false); 
		}
	}
}

header("Content-Type: application/json; charset=UTF-8");
echo file_get_contents($file);

function new_player(&$p,$uname){
	$p['uname']=$uname;
	$p['status']='idle';
	$p['last_act']=date('Y-m-d H:i:s');
	$p['creator']=false;
	$p['game_id']='';
	$p['num']='';
	$p['my_turn']=false;
}

function get_creator(&$me){
	$dir=".";
	$handler = opendir($dir); 
	while ($file = readdir($handler)) { 
		$ext = substr(strrchr($file,"."),1); 
		$ext = strtolower($ext); 
		if ($ext=="json") {
			if($file==$_SESSION['id'].".json") continue; // skip me
			$p=array();
			$p=json_decode(file_get_contents($file));
			if($p->game_id==$_SESSION['game_id'] && $p->creator==true){
				return $p; // creator is online and not idle
			}
		}
	} 
	closedir($handler);
	$me->status='idle';
	$me->game_id="";
	return null;
}
?>