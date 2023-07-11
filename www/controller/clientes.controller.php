<?php

class ControladorClientes{

    public function create($datos){

        //echo "<pre>"; print_r($datos); echo "<pre>";

            /*======================================================================================
            Validando nombre, si contiene algo !preg_match(Osea diferente a letras entra en el if)
            ======================================================================================*/

        if(isset($datos["nombre"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/' , $datos["nombre"])){

            $json=array(
                "status"=>404,
                "detalle"=>"Error en el campo nombre, solo se permiten letras"
            );
            
            echo json_encode($json,true);
        
            return;

        }

            /*======================================================================================
            Validando Apellido, si contiene algo !preg_match(Osea diferente a letras entra en el if)
            ======================================================================================*/

        if(isset($datos["apellido"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/' , $datos["apellido"])){

            $json=array(
                "status"=>404,
                "detalle"=>"Error en el campo apellido, solo se permiten letras"
            );
            
            echo json_encode($json,true);
        
            return;

        }

            /*======================================================================================
            Validando Correo, si contiene algo !preg_match(Osea diferente a letras entra en el if)
            ======================================================================================*/

        if(isset($datos["email"]) && !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/' , $datos["email"])){

            $json=array(
                "status"=>404,
                "detalle"=>"Error en el campo email, sintaxis no valida"
            );
            
            echo json_encode($json,true);
        
            return;

        }


            /*=========================================
            Validandar email repetido en Base de Datos
            =========================================*/

        $clientes = modeloClientes::index("clientes");

        foreach ($clientes as $key => $value){

            if($value["email"] == $datos["email"]){

                $json=array(
                    "status"=>404,
                    "detalle"=>"El correo ya existe en el sistema, pruebe con otro"
                );
                echo json_encode($json,true);
    
                return;

            }
        }
        
        /*==============================
        Generar credenciales de cliente
        ==============================*/

        $id_cliente = str_replace("$","c",crypt($datos["nombre"].$datos["apellido"].$datos["email"], '$2a$07$afartwetsdAD52356FEDGsfhsd$'));


        $llave_secreta = str_replace("$","a",crypt($datos["email"].$datos["apellido"].$datos["nombre"], '$2a$07$afartwetsdAD52356FEDGsfhsd$'));

        $datos = array("nombre"=>$datos["nombre"],
                    "apellido"=>$datos["apellido"], 
                    "email"=>$datos["email"], 
                    "id_cliente"=>$id_cliente,
                    "llave_secreta"=>$llave_secreta, 
                    "created_at"=>date('Y-m-d h:i:s'),
                    "updated_at"=>date('Y-m-d h:i:s')
                    );


        $create = modeloClientes::create("clientes", $datos);


        if($create == "ok"){

            $json=array(
                "status"=>404,
                "detalle"=>"Sus credenciales han sido creadas correctamente",
                "id_cliente"=>$id_cliente,
                "llave_secreta"=>$llave_secreta
            );
            echo json_encode($json,true);

            return;

        }







    }


}


?>