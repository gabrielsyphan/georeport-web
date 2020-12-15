<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    header('Accept: application/json');
    
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');    
    
    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
        require("connection.php");
        
        $input_json = file_get_contents("php://input");
        $input_array = json_decode($input_json, true);
        
        $image = $input_array["image"];
        $imageheader = strtok($image,",");
        $imagebody=strtok("");
        $data = base64_decode($imagebody);
        $im = imagecreatefromstring($data);
        $width = imagesx($im);
        $height = imagesy($im);
        $newwidth = 250;
        $newheight = round($height * 250 / $width);
        $thumb = @imagecreatetruecolor($newwidth, $newheight)  or die("Cannot Initialize new GD image stream");
        
        // Resize
        imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        ob_start();
        imagejpeg($thumb, NULL, 30);
        $imagedata =  base64_encode(ob_get_clean());
        $finalimage = $imageheader.",".$imagedata;
		
		mysqli_query($connection, "INSERT INTO geo_notifications(report_id, status, user_registration, user_name, title, description, image) VALUES ('". $input_array['id'] ."', 0, '". $input_array['user_registration'] ."', '". $input_array['user_name'] ."', '". $input_array['title'] ."', '". $input_array['description'] ."', '". $finalimage ."')");
		
        if(mysqli_error($connection)){
            echo json_encode(mysqli_error($connection));
            exit();
        }
        
		mysqli_query($connection, "UPDATE geo_reports SET edited = (edited + 1) WHERE id = ". $input_array['id'] ."");
		
        if(mysqli_error($connection)){
            echo json_encode(mysqli_error($connection));
            exit();
        }
		
		$update = mysqli_query($connection, "UPDATE geo_users SET called = (called + 1) WHERE registration = ". $input_array['user_registration'] ."");
		
		$response = 1;
		echo (json_encode($response));
    }
    
    mysqli_close($connection);
?>