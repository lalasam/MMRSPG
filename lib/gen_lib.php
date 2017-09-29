<?PHP
function update($file,$fd,$val=null){
	$p=array();
	$p=json_decode(file_get_contents($file));
	$p->$fd=$val;
	file_put_contents($file,json_encode($p));
}
?>