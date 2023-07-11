<?php

$arrayRoutes=explode("/",$_SERVER['REQUEST_URI']);

//echo "<pre>"; print_r($arrayRoutes); echo "<pre>";

if(isset($_GET["pagina"]) && is_numeric($_GET["pagina"])){

    $cursos = new ControladorCursos();
    $cursos->index1($_GET["pagina"]);

}else{

        if(count(array_filter($arrayRoutes))== 0){


        /*============================================
        Cuando no se hace ninguna petición a la API
        ============================================*/

        $json=array(
            "detalle"=>"no encontrado"
        );

        echo json_encode($json,true);

        return;

        }else{

        /*=================================================
        Cuando se pasa un indice en el array $arrayRutas
        =================================================*/

        if(count(array_filter($arrayRoutes))== 1){


            /*===================================
            Cuando se hace peticiondesde cursos
            ===================================*/

            if (array_filter($arrayRoutes)[1] == "cursos"){

                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){

                    /*=============
                    Capturar datos
                    =============*/
                    $datos = array("titulo" => $_POST["titulo"],
                                    "descripcion" => $_POST["descripcion"],
                                    "instructor" => $_POST["instructor"],
                                    "imagen" => $_POST["imagen"],
                                    "precio" => $_POST["precio"]);
                    //echo "<pre>"; print_r($datos); echo "<pre>";
            


                    $cursos = new ControladorCursos();
                    $cursos->create($datos);

                }
                
                else if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET"){

                    $cursos = new ControladorCursos();
                    $cursos->index1(null);

                }


            }
        }

        /*========================================
        Cuando se hace peticiones desde registro
        ========================================*/
        if(count(array_filter($arrayRoutes))== 1){

            if (array_filter($arrayRoutes)[1] == "registro"){

                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){

                    $datos = array("nombre" => $_POST["nombre"],
                                    "apellido" => $_POST["apellido"],
                                    "email" => $_POST["email"]);

                                    

                    $clientes = new ControladorClientes();
                    $clientes->create($datos);

                }
            }
        }else{

            if(array_filter($arrayRoutes)[1] == "cursos" && is_numeric(array_filter($arrayRoutes)[2])){

                        /*==============
                        Peticiones GET
                        ==============*/

                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET"){

                    $curso = new ControladorCursos();
                    $curso->show(array_filter($arrayRoutes)[2]);

                }

                        /*==============
                        Peticiones PUT
                        ==============*/
                if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "PUT"){

                        /*==============
                        Capturar datos
                        ==============*/
                        $datos = array();
                        parse_str(file_get_contents('php://input'), $datos);
                        //echo "<pre>"; print_r($datos); echo "<pre>";
                        //return;

                    $editarCurso = new ControladorCursos();
                    $editarCurso->update(array_filter($arrayRoutes)[2],$datos);

                }

                        /*================
                        Peticiones Delete
                        ================*/
                        if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "DELETE"){

                            $borrarCurso = new ControladorCursos();
                            $borrarCurso->delete(array_filter($arrayRoutes)[2]);
            
                        }


            }
        }
        }

}

?>