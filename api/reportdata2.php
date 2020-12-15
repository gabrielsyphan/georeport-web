<?php

    // Headers de acesso
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    header('Accept: application/json');
    
    // Conexão com o banco
    require("../connection.php");
    
    function subtypeVerify($stype){
        if($stype == 1){
            $subtype = "Estabelecimento irregular";
        }else if($stype == 2){
            $subtype = "Descarte irregular de lixo";
        }else if($stype == 3){
            $subtype = "Buraco na via";
        }else if($stype == 4){
            $subtype = "Lampada queimada";
        }else if($stype == 5){
            $subtype = "Calçada irregular";
        }else{
            $subtype = "Houve um erro ao definir o tipo da irregularidade.";
        }
        return $subtype;
    }    
    
    // Verifica se a requisição foi feita por GET
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        // Recebe os valores enviados pelo sistema do usuário
        $id = intval($_GET['id']);
        $app = intval($_GET['app']);
        $data = [];
        
        // Verifica se a chamada foi feita para uma denúncia feita por aplicativo
        if($app == 1){
            // Consulta os dados da denúncia no banco
            $result = mysqli_query($connection, "SELECT id, subtype, description, user_registration, imagebig, imagebig2, imagebig3, imagebig4, imagebig5, latitude, longitude from maceiogeoreports WHERE id = '". $id ."'");
            while($row = mysqli_fetch_assoc($result)){
                $reportsubtype = subtypeVerify($row['subtype']);
                // Consulta o nome e a matrícula do usuário que cadastrou a denúncia para utilizar futuramente no download do anexo
                $result2 = mysqli_query($connection, "SELECT registration, name from maceiogeousers WHERE registration = '". $row['user_registration'] ."'");
                while($row2 = mysqli_fetch_assoc($result2)){
                    $name = $row2['name'];
                    $registration = $row2['registration'];
                }
                
                // Verifica se existem anexos vinculados a denúncia
                $fileName = "../upload/". $id ."_". $app ."_". $registration ."_uploadedFile.";
                if(file_exists($fileName . "pdf") or file_exists($fileName . "docx") or file_exists($fileName . "txt") or file_exists($fileName . "xml") or file_exists($fileName . "png") or file_exists($fileName . "jpg") or file_exists($fileName . "jpeg")){
                    $fileExist = 1;    
                }else{
                    $fileExist = 0;
                }
                
                // Monta um array um os dados da denúncia
                $data[] = array('user'=> $name, 'registration'=> $registration, 'subtype'=> $reportsubtype, 'description'=> $row['description'], 'imagebig'=> $row['imagebig'], 'imagebig2'=> $row['imagebig2'], 'imagebig3'=> $row['imagebig3'], 'imagebig4'=> $row['imagebig4'], 'imagebig5'=> $row['imagebig5'], 'latitude'=> $row['latitude'], 'longitude'=> $row['longitude'], 'fileExist' => $fileExist);
            }
        }
        
        // Verifica se a chamada foi feita para uma denúncia feita internamente
        else if($app == 2){
            // Consulta os dados da denúncia no banco
            $result = mysqli_query($connection, "SELECT id, subtype, description, user_registration, process, latitude, longitude from maceiogeousersreports WHERE id = '". $id ."'");
            while($row = mysqli_fetch_assoc($result)){
                $reportsubtype = subtypeVerify($row['subtype']);
                // Consulta o nome e a matrícula do usuário que cadastrou a denúncia para utilizar futuramente no download do anexo
                $result2 = mysqli_query($connection, "SELECT registration, name from maceiogeousers WHERE registration = '". $row['user_registration'] ."'");
                while($row2 = mysqli_fetch_assoc($result2)){
                    $name = $row2['name'];
                    $registration = $row2['registration'];
                }
                // Monta um array um os dados da denúncia
                $data[] = array('description'=> $row['description'], 'user'=> $name, 'registration'=> $registration, 'subtype'=> $reportsubtype, 'process'=> $row['process'], 'latitude'=> $row['latitude'], 'longitude'=> $row['longitude']);
            }          
        }
        
        // Consulta as chamadas vinculadas a denúncia
        $called = mysqli_query($connection, "SELECT status, created, user_name, title, description FROM maceiogeoeditreports WHERE report_id = ". $id ." AND  report_type = ". $app ."");
        // Verifica se houve retorno da consulta e adiciona ao array os dados correspondentes
        $num_rows = mysqli_num_rows($called);
        if($num_rows >= 1){
            while($row = mysqli_fetch_assoc($called)){
                $data[] = array('response' => 1, 'created' => utf8_encode($row['created']), 'user_name' => $row['user_name'], 'title' => $row['title'], 'description' => $row['description']);
            }
        }else{
            $data[] = array('response' => 0, 'responseMessage' => "Não há atualizações de chamado para essa denúncia.");
        }
        
        // Json retornado para o app    
        echo (json_encode($data));
    
    }
    
    // Verifica se a requisição foi feita por POST - Função para inserir uma nova foto
    else if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
        $input_json = file_get_contents("php://input");
        $input_array = json_decode($input_json, true);
        
        // Verifica se foi de uma denúncia feita pelo app
        if($input_array['app'] == 1){
            // Consulta as imagens já cadastradas e adiciona todas em um array (Mesmo se for null)
            $result = mysqli_query($connection, "SELECT `id`, `imagebig2`, `imagebig3`, `imagebig4`, `imagebig5` FROM `maceiogeoreports` WHERE `ID` = '". $input_array['id'] ."'");
            while($row = mysqli_fetch_assoc($result)){
                $array = array($row['imagebig2'], $row['imagebig3'], $row['imagebig4'], $row['imagebig5']);
            }
            
            // Faz uma contagem para definir o nome da coluna no banco de dados que será inserida a imagem
            $count = 1;
            for($i = 0; $i <= count($array); $i++){
                if($array[$i] !== ""){
                    $count = $count + 1;
                }
            }
            
            // Define o nome da imagem
            $newImage = "imagebig". $count;
            
            // Atualiza o campo da tabela para a imagem enviada
            $response = mysqli_query($connection, "UPDATE `maceiogeoreports` SET `" . $newImage . "`= '" . $input_array['image'] . "' WHERE `id` = " . $input_array['id'] . " LIMIT 1");
        }
        
        // Retorno em JSON para o usuário
        $response = array(responseHeader => "Sucesso", responseMessage => "A imagem foi inserida a denúncia.");
		echo (json_encode($response));        
        
    }else{
        // Mensagem de erro caso a requisição não se enquadre em nenhuma das anteriores
        $response = array(responseHeader => "Erro", responseMessage => "Não foi possível enviar a imagem.");
		echo (json_encode($response));
    }
    
    // Fecha a conexão com o banco de dados
    mysqli_close($connection);
?>