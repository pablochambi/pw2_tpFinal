<?php
class PerfilController extends BaseController
{
    public function __construct($model, $presenter)
    {
        session_start();
        parent::__construct($model, $presenter);
    }

    public function get()
    {
        $this->checkSession();

        $userId = $_SESSION["username"];
        $usuario = $this->model->obtenerUsuarioConNombrePaisPorId($userId['id']);
        $rol = $this->verificarDeQueRolEsElUsuario($userId['id']);

        $this->presenter->render("view/perfilUsuario.mustache", ["usuario" => $usuario, "rol" => $rol['rol']]);
    }

    public function mostrarPerfil()
    {
        $this->checkSession();

        if (!isset($_GET['username']))
            die('Usuario no especificado.');

        $username = $_GET['username'];
        $usuario = $this->model->obtenerUsuarioPorUsername($username);

        if ($usuario === null)
            die('Usuario no encontrado.');

        $anioNacimiento = $usuario['anio_nacimiento'];
        $anioActual = date("Y");
        $edad = $anioActual - $anioNacimiento;
        // calculo la edad del usuario para mostrarla
        $usuario['edad'] = $edad;

        include('third-party/phpqrcode/qrlib.php');
        $contenido = 'https://NOSE/usuario/' . $usuario['username'];
        $nombreArchivo = 'qrs/' . $usuario['username'] . '.png';

        if (!file_exists('qrs'))
            mkdir('qrs', 0777, true);

        QRcode::png($contenido, $nombreArchivo, QR_ECLEVEL_L, 10);

        $usuario['qr'] = $nombreArchivo;

        $this->presenter->render("view/perfiles.mustache", ['usuario' => $usuario]);
    }
}

