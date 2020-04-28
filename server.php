<?php

    //GET -> /pessoas
    //GET[SELECT] -> /pessoas/ID
    //POST -> /pessoas/NOME/NOME_CATEGORIA/EMAIL
    //DELETE -> /pessoas/ID
    //PUT -> /pessoas/ID/NOME/NOME_CATEGORIA/EMAIL

    //CORS
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Cache-Control: no-cache, must-revalidate"); 
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

    $way = explode('/', $_GET["pagina"]); 
    $conteudo = file_get_contents('db.json');

    $method = $_SERVER["REQUEST_METHOD"];

    header("Content-type: aplication/json");

    $json = json_decode($conteudo, true); //TRUE PARA ME ROTONAR UM AMTRIZ -> resolve o erro do stdclass

    if($method == 'GET'){
        if(isset($way[0]) == TRUE AND isset($way[1]) == TRUE){
            $id = $way[1];
            $position = -1;
            foreach ($json[$way[0]] as $key => $value) {
                if($value['id'] == $id){
                    $position = $key;
                }
            }
            if($position >= 0){
                echo json_encode($json[$way[0]][$position]);
            }else{
                echo json_encode(['error' => 'nothing found or invalid id']);
            }
        }else{
            if(isset($json[$way[0]]) == TRUE){
                echo json_encode($json[$way[0]]);
            }else{
                echo json_encode(["error" => 'nothind found']);
            } 
        } 
    }

    if($method == 'POST'){
        if(isset($json[$way[0]]) == TRUE){
            $body = json_encode(["id" => time(), "nome" => $way[1], "categoria_notice" => $way[2], "email" => $way[3]]);
            $jsonbody = json_decode($body, TRUE);
            $json[$way[0]][] = $jsonbody;
            echo json_encode($jsonbody);
            file_put_contents('db.json', json_encode($json));
        }
    }

    if($method == 'PUT'){
        if(isset($way[0]) == TRUE AND isset($way[1]) == TRUE){
            $id = $way[1];
            $position = -1;
            foreach($json[$way[0]] as $key => $value){ 
                if($value['id'] == $id){
                    $position = $key;
                }
            }
            if($position >= 0){
                if(isset($way[2]) == TRUE AND isset($way[3]) == TRUE AND isset($way[4]) == TRUE){
                    $json[$way[0]][$position]['nome'] = $way[2];
                    $json[$way[0]][$position]['categoria_notice'] = $way[3];
                    $json[$way[0]][$position]['e-mail'] = $way[4];
                    echo json_encode($json[$way[0]][$position]);
                    file_put_contents('db.json', json_encode($json));
                }
            }else{
                echo json_encode(['error' => 'nothing found or invalid id']);
            }
        }else{
            echo json_encode(['error' => 'empty param']);
        }
    }

    if($method == 'DELETE'){
        if(isset($way[0]) == TRUE AND isset($way[1]) == TRUE){
            $id = $way[1];
            $position = -1;
            foreach ($json[$way[0]] as $key => $value) {
                if($value['id'] == $id){
                    $position = $key;
                }
            }
            if($position >= 0){
                echo json_encode($json[$way[0]][$position]);
                unset($json[$way[0]][$position]);
                file_put_contents('db.json', json_encode($json));
            }else{
                echo json_encode(['error' => 'nothing found or invalid id']);
            }
        }else{
            if(isset($json[$way[0]]) == TRUE){
                echo json_encode(["Warning" => 'Please put a valid id for delete']);
            }
        }
    }

