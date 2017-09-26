<?PHP
session_start();
$file=$_SESSION['id'].".json";
$p=array();
if(!file_exists($file)){ // no session exists
	return;
}
else { // session continue
	$p=json_decode(file_get_contents($file));
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
		echo $p->uname.": ".$p->num."<br>";
		if($p->creator==false) do_admin($file,$p);
	}
} 
closedir($handler);

function do_admin($file,&$p){
	if(strtotime(date('Y-m-d H:i:s'))-strtotime($p->last_act) >5)  // remove old file
		unlink($file); 
}
?>
