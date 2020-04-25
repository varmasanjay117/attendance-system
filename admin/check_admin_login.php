<?php
include ('database_connection.php');
include ('class.DBConnManager.php');
session_start();
$admin_user_name = '';
$admin_password = '';
$error_admin_user_name ='';
$error_admin_password ='';
$error = 0;

if(empty($_POST['admin_user_name'])){
	$error_admin_user_name = 'Username Required';
	$error ++;
}else{
	$admin_user_name = $_POST['admin_user_name'];
}

if(empty($_POST['admin_password'])){
	$error_admin_password = 'Password Required';
	$error ++;
}else{
	$admin_password = $_POST['admin_password'];
}

if($error ==0){
	$DBMan = new DBConnManager();
    $connection =  $DBMan->getConnInstance();
	$query ="SELECT * FROM `atte_login` WHERE `admin_name` = '{$admin_user_name}' ";
	$statement =$connection->query($query);
	if($statement){
		if($statement->num_rows > 0){
			$result = $statement->fetch_assoc();
				if(password_verify($admin_password,$result["admin_password"])){
					$_SESSION['admin_id']=$result["admin_id"];
				}else{
					$error_admin_password ='Wrong Password';
					$error ++;
				}
		}else{
			$error_admin_user_name = 'Wrong UserName';
			$error ++;
		}
	}
}

if($error > 0){
	$output=array(
		'error' => true,
		'error_admin_password' => $error_admin_password,
		'error_admin_user_name' => $error_admin_user_name,
	);
}else{
	$output=array(
		'success'=>true,
	);
}

echo json_encode($output);
?>