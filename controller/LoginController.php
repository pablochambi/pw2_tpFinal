<?php
class LoginController
{
    private $presenter;
    private $model;

    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get()
    {
        /* // Verificar si hay una sesión activa antes de iniciar o destruir
        if (session_status() == PHP_SESSION_NONE) {
        session_start();
        }*/

        if (isset($_SESSION['username'])) {
            $user = $_SESSION['username'];
            $this->presenter->render("view/perfilUsuario.mustache", ['usuario' => $user]);
        } else {
            header("Location: /login");
        }

        //if (session_status() == PHP_SESSION_ACTIVE)
           // session_destroy();
    }

    public function procesarLogeo(){

        if (isset($_POST["email"]) && isset($_POST["password"])){

            $email = $_POST["email"];
            $password= $_POST["password"];

            // aca llamo a procesarInicioSesion del modelo y guardo el resultado (verdadero si el inicio de sesion fue exitoso)
            $inicioSesionExitoso = $this->model->procesarInicioSesion($email, $password);

            if ($inicioSesionExitoso) {

                $user = $this->model->agarrarUsuarioDeLaBaseDeDatosPorEmail($email);

               $usuario = [
                   'nombre_completo' => $user['nombre_completo'],
                    'username' => $user['username'],
                    'foto' => $user['foto'],
                    'anio_nacimiento' => $user['anio_nacimiento'],
                    'pais' => $user['pais'],
                    'ciudad' => $user['ciudad']
                ];

                $_SESSION['username'] = $user;

              header("Location: /homeUsuario");

            } else{
                header("Location: /login");
            }

        } else {
            header("Location: /login");
        }

    }
}