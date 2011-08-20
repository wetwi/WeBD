<?php 
	
	require_once 'webd.class.php';
	
	// Connect to BD and set default table
	$con = new weBD('tb_users');
	
	if ( $con->query ( "SELECT * FROM tb_users" ) ) echo "ok";
	

?>