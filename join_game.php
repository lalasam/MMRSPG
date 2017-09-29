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

$creator=array();
$creator_name=$_POST['pname'];

// get creator's data
$dir=".";
$handler = opendir($dir); 
while ($tfile = readdir($handler)) { 
	$ext = substr(strrchr($tfile,"."),1); 
	$ext = strtolower($ext); 
	if ($ext=="json") {
		if($tfile==$_SESSION['id'].".json") continue; // skip me
		$p=array();
		$p=json_decode(file_get_contents($tfile));
		if($p->uname==$creator_name){
			$creator=$p;
			$creator->fname=$tfile;
			break;
		}
	}
} 
closedir($handler);

if($me->status=='playing') return; // don't join cuz me is playing a game already
if($creator->status!='create_waiting' ) return; // don't join cuz the creator is not creating & waiting

$me->game_id=$creator->game_id;
$me->status='waiting';
$me->creator=false;
$_SESSION['game_id']=$me->game_id;
file_put_contents($file,json_encode($me));
?>