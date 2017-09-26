<?PHP
session_start();
$file=$_SESSION['id'].".json";
$p=array();
if(!file_exists($file)){ // initial login. Create json file
	new_player($p,$_POST['uname']);
	file_put_contents($file,json_encode($p));
}
else { // session continue
	$p=json_decode(file_get_contents($file));
	update($file,'last_act',date('Y-m-d H:i:s')); // update last_act
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

function update($file,$fd,$val=null){
	$p=array();
	$p=json_decode(file_get_contents($file));
	$p->$fd=$val;
	file_put_contents($file,json_encode($p));
}
?>
