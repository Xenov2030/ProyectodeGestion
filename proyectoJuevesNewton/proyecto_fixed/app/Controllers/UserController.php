<?php
// app/Controllers/UserController.php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Session;
use app\Models\User;
use app\Core\Database;
use app\Models\AuditLog;

class UserController extends Controller
{

//Para referenciar views>users/index.php
    public function index(): void
    {
        Session::checkRole(['admin', 'directivo']);

        $rolFiltro = $_GET['rol'] ?? null;
        $paginaAct = max(1, (int) ($_GET['pagina'] ?? 1));
        $porPagina = 10;

        $usuarios = User::paginados($porPagina, $paginaAct, $rolFiltro);
        $total = User::contar($rolFiltro);
        $totalPags = (int) ceil($total / $porPagina);

        $db = Database::getInstancia();
        $roles = $db->query("SELECT * FROM roles")->fetchAll();

        $this->render('users/index', [
            'usuarios' => $usuarios,
            'roles' => $roles,
            'rolFiltro' => $rolFiltro,
            'paginaAct' => $paginaAct,
            'totalPags' => $totalPags,
            'total' => $total,
        ]);
    }

    //Para referenciar views>users/create.php
    public function create(): void
{
    Session::checkRole(['admin', 'directivo']);
    $db    = Database::getInstancia();
    $roles = $db->query("SELECT * FROM roles")->fetchAll();
    $this->render('users/create', ['roles' => $roles]);
}

//Para referenciar la data de los formularios de create y edit, y procesar la creación y actualización de usuarios en la base de datos:
    public function store(): void
    {
        Session::checkRole(['admin', 'directivo']);
        $this->validateCsrf();

        $data = [
            'empresa_id' => Session::get('empresa_id'),
            'rol_id' => (int) ($_POST['rol_id'] ?? 0),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
            'estado' => $_POST['estado'] ?? 'activo',
        ];

        $db = Database::getInstancia();
        $roles = $db->query("SELECT * FROM roles")->fetchAll();

        if (empty($data['nombre']) || empty($data['email']) || empty($data['password'])) {
            $this->render('users/create', [
                'roles' => $roles,
                'error' => 'Nombre, email y contraseña son obligatorios.'
            ]);
            return;
        }

        if ($data['password'] !== $data['password_confirm']) {
            $this->render('users/create', [
                'roles' => $roles,
                'error' => 'Las contraseñas no coinciden.'
            ]);
            return;
        }

        if (!in_array($data['estado'], ['activo', 'inactivo'], true)) {
            $data['estado'] = 'activo';
        }

        if (User::findByEmail($data['email'])) {
            $this->render('users/create', [
                'roles' => $roles,
                'error' => 'Ya existe un usuario con ese email.'
            ]);
            return;
        }

        if (User::create($data)) {
            AuditLog::registrar(Session::get('user_id'), 'Crear', 'Usuarios', "Creó al usuario: {$data['nombre']} ({$data['email']})");
            redirect('users');
        } else {
            $this->render('users/create', [
                'roles' => $roles,
                'error' => 'Error al crear el usuario. Intente nuevamente.'
            ]);
        }
    }

    //Para referenciar views>users/edit.php
    public function edit(): void
    {
        Session::checkRole(['admin', 'directivo']);
        $id = (int) ($_GET['id'] ?? 0);

        $usuario = User::findById($id);
        if (!$usuario) {
            redirect('users');
            return;
        }

        $db = Database::getInstancia();
        $roles = $db->query("SELECT * FROM roles")->fetchAll();
        $this->render('users/edit', ['usuario' => $usuario, 'roles' => $roles]);
    }

    public function update(): void
    {
        Session::checkRole(['admin', 'directivo']);
        $this->validateCsrf();
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

        $data = [
            'rol_id' => (int) ($_POST['rol_id'] ?? 0),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo',
        ];

        //mejora la seguridad de la actualización de contraseña: solo se actualiza si se completa el campo en la edición, y se hashea antes de guardarla

        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        if (User::update($id, $data)) {
            AuditLog::registrar(Session::get('user_id'), 'Editar', 'Usuarios', "Editó al usuario ID: $id - {$data['nombre']}");
            redirect('users');
        } else {
            die("Error al actualizar el usuario.");
        }
    }
//Para referenciar la eliminación de usuarios
    public function delete(): void
    {
        Session::checkRole(['admin', 'directivo']);
        $id = (int) ($_POST['id'] ?? 0);
        
        $usuario = User::findById($id);
        if ($usuario) {
            User::delete($id);
            AuditLog::registrar(Session::get('user_id'), 'Eliminar', 'Usuarios', "Eliminó al usuario: {$usuario['nombre']} (ID: $id)");
        }
        
        redirect('users');
    }
}
