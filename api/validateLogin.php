<?php
    error_reporting(0);
    
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    header('Accept: application/json');

    require("connection.php");
    
    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
        $input_json = file_get_contents("php://input");
        $input_array = json_decode($input_json, true);
		
		$result = mysqli_query($connection, "SELECT name, registration, password FROM geo_users WHERE registration = '". $input_array['registration'] ."' AND password = '". md5($input_array['password']) ."'");
		$row = mysqli_fetch_array($result);
		if($row){
		    $array = array(response => 1, name => $row['name'], responseHeader => "Sucesso!", responseMessage => "Seu login foi realizado.");
		    echo (json_encode($array));
		}else{
		    $array = array(response => 0, responseHeader => "Erro", responseMessage => "Os dados informados estão incorretos.");
		    echo (json_encode($array));
		}
    }
    
    mysqli_close($connection);
?>