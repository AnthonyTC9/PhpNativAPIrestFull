<?php 

class ControladorCursos{

    public function index1($pagina){

        /*===============================
        Validar credenciales del cliente
        ===============================*/

        //$user = $_SERVER['PHP_AUTH_USER'];
        //$pass = $_SERVER['PHP_AUTH_PW'];

        $clientes = ModeloClientes::index("clientes");

        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

            foreach($clientes as $key => $value){

                if(base64_decode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_decode($value["id_cliente"].":".$value["llave_secreta"])){

                    if($pagina !=null){

                        $cantidad = 10;
                        $desde = ($pagina-1)*$cantidad;

                        $cursos = modeloCurso::index("cursos","clientes",$cantidad, $desde);

                    }else{
                        $cursos = modeloCurso::index("cursos","clientes",null,null);
                    }


                    $json=array(
                        "status"=>200,
                        "total_registros"=>count($cursos),
                        "detalle"=>$cursos,
                    );
                    
                    echo json_encode($json,true);    
                    return;

                }

            }




        }


    }

    public function create($datos){

        /*===============================
        Validar credenciales del cliente
        ===============================*/

        $clientes = ModeloClientes::index("clientes");

        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

            foreach($clientes as $key => $valueCliente){

                if(base64_decode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_decode($valueCliente["id_cliente"].":".$valueCliente["llave_secreta"])){

                    /*============
                    Validar datos
                    ============*/
                    foreach ($datos as $key => $valueDatos) {

                        if(isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)){

                            $json=array(
                                "status"=>404,
                                "detalle"=>"Error en el campo".$key
                            );
                            
                            echo json_encode($json,true);    
                            return;

                        }
                    }

                    /*=========================================================
                    Validar que el titulo o la descripcion no esten repetidos
                    =========================================================*/

                    $cursos = modeloCurso::index("cursos","clientes",null,null);

                    foreach ($cursos as $key => $value){

                        if($value->titulo == $datos["titulo"]){
                            $json = array(
                                "status" => 404,
                                "detalle"=> "El titulo ya existe en la base de datos"
                            );
                            echo json_encode($json, true);

                            return;
                        }

                        if($value->descripcion == $datos["descripcion"]){
                            $json = array(
                                "status" => 404,
                                "detalle"=> "La descripción ya existe en la base de datos"
                            );
                            echo json_encode($json, true);

                            return;
                        }

                    }

                    /*=====================
                    Llevar datos al modelo
                    =====================*/
                    $datos = array( "titulo"=>$datos["titulo"],
                                    "descripcion"=>$datos["descripcion"],
                                    "instructor"=>$datos["instructor"],
                                    "imagen"=>$datos["imagen"],
                                    "precio"=>$datos["precio"],
                                    "id_creador"=>$valueCliente["id"],
                                    "created_at"=>date('Y-m-d h:i:s'),
                                    "updated_at"=>date('Y-m-d h:i:s'));

                    $create = modeloCurso::create("cursos", $datos);

                    /*=====================
                    Respuesta del modelo
                    =====================*/

                    if($create == "ok"){
                        $json = array(
                            "status"=>200,
                            "detalle"=>"Registro exitoso, su curso ha sido guardado"
                        );
                        echo json_encode($json, true);
                        return;
                    }
                }

            }

        }



        $json=array(
            "detalle"=>"Estas en la vista crear"
        );
        
        echo json_encode($json,true);    
        return;

    }


    public function show($id){

        /*===============================
        Validar credenciales del cliente
        ===============================*/
        $clientes = ModeloClientes::index("clientes");

        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

            foreach($clientes as $key => $value){

                if(base64_decode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_decode($value["id_cliente"].":".$value["llave_secreta"])){


                    /*========================
                    Mostrar todos los cursos
                    ========================*/
                    $curso= modeloCurso::show("cursos","clientes", $id);

                    if(!empty($curso)){
                        $json=array(
                            "status"=>200,
                            "detalle"=>$curso
                        );
                        
                        echo json_encode($json,true);    
                        return;
                    }else{
                        $json=array(
                            "status"=>200,
                            "total_registros"=>0,
                            "detalle"=>"No hay ningun curso registrado"
                        );
                        
                        echo json_encode($json,true);    
                        return;
                    }

                }
            }

        }
    }


    public function update($id,$datos){

        $clientes = ModeloClientes::index("clientes");

        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

            foreach($clientes as $key => $valueCliente){

                if(base64_decode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_decode($valueCliente["id_cliente"].":".$valueCliente["llave_secreta"])){

                    /*============
                    Validar datos
                    ============*/
                    foreach ($datos as $key => $valueDatos) {

                        if(isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)){

                            $json=array(
                                "status"=>404,
                                "detalle"=>"Error en el campo".$key
                            );
                            
                            echo json_encode($json,true);    
                            return;

                        }
                    }
                    /*=================
                    Validar id creador
                    =================*/

                    $curso = modeloCurso::show("cursos","clientes", $id);

                    foreach ($curso as $key => $valueCurso){

                        if($valueCurso->id_creador == $valueCliente["id"]){

                            /*=====================
                            Llevar datos al modelo
                            =====================*/

                            $datos = array( "id"=>$id,
                                    "titulo"=>$datos["titulo"],
                                    "descripcion"=>$datos["descripcion"],
                                    "instructor"=>$datos["instructor"],
                                    "imagen"=>$datos["imagen"],
                                    "precio"=>$datos["precio"],
                                    "updated_at"=>date('Y-m-d h:i:s'));

                            $update = modeloCurso::update("cursos",$datos);

                            if($update == "ok"){
                                $json = array(
                                    "status" => 200,
                                    "detalle"=> "Registro exitoso, su curso ha sido actualizado"
                                );
                                echo json_encode($json, true);
    
                                return;                                

                            }else{
                                $json = array(
                                    "status" => 404,
                                    "detalle"=> "No estas autorizado para modificar este curso"
                                );
                                echo json_encode($json, true);
    
                                return;
                            }


                        }



                    }


                }
            
            }
        }




    }


    public function delete($id){

        /*===============================
        Validar credenciales del cliente
        ===============================*/
        $clientes = modeloClientes::index("clientes");

        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

            foreach($clientes as $key => $valueCliente){

                if(base64_decode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == base64_decode($valueCliente["id_cliente"].":".$valueCliente["llave_secreta"])){

                    /*=================
                    Validar id creador
                    =================*/

                    $curso = modeloCurso::show("cursos","clientes", $id);

                    foreach($curso as $key => $valueCurso){

                        if($valueCurso->id_creador == $valueCliente["id"]){

                            /*=====================
                            Llevar datos al modelo
                            =====================*/
                            $delete = modeloCurso::delete("cursos",$id);

                            if($delete = "ok"){

                                $json = array(
                                    "status" => 200,
                                    "detalle"=> "Se ha borrado el curso correctamente"
                                );
                                echo json_encode($json, true);
    
                                return;

                            }

                        }

                    }



                }
            }
        }

    }
}


?>