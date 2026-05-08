<?php
// app/Controllers/UserController.php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Session;
use app\Models\User;
use app\Core\Database;

class UserController extends Controller {

    public function index(): void {
        Session::checkRole(['admin', 'directivo', 'administrativo']);
        // Corregido: era User::getAll() que no existe. El metodo correcto es User::all()
        $usuarios = User::all();
        $this->render('users/index', ['usuarios' => $usuarios]);
    }

    public function create(): void {
        Session::checkRole(['admin', 'directivo']);
        $db    = Database::getInstancia();
        $roles = $db->query("SELECT * FROM roles")->fetchAll();
        $this->render('users/create', ['roles' => $roles]);
    }

    public function store(): void {
        Session::checkRole(['admin', 'directivo']);
        $this->validateCsrf();

        $data = [
            'empresa_id' => Session::get('empresa_id'),
            'rol_id'     => (int)($_POST['rol_id']   ?? 0),
            'nombre'     => trim($_POST['nombre']     ?? ''),
            'email'      => trim($_POST['email']      ?? ''),
            'password'   => $_POST['password']        ?? '',
            'estado'     => $_POST['estado']          ?? 'activo',
        ];

        if (empty($data['nombre']) || empty($data['email']) || empty($data['password'])) {
            $db    = Database::getInstancia();
            $roles = $db->query("SELECT * FROM roles")->fetchAll();
            $this->render('users/create', [
                'roles' => $roles,
                'error' => 'Nombre, email y contrasena son obligatorios.'
            ]);
            return;
        }

        if (User::create($data)) {
            redirect('users');
        } else {
            die("Error al crear el usuario.");
        }
    }

    public function edit(): void {
        Session::checkRole(['admin', 'directivo']);
        $id = (int)($_GET['id'] ?? 0);

        $usuario = User::findById($id);
        if (!$usuario) {
            redirect('users');
            return;
        }

        $db    = Database::getInstancia();
        $roles = $db->query("SELECT * FROM roles")->fetchAll();
        $this->render('users/edit', ['usuario' => $usuario, 'roles' => $roles]);
    }

    public function update(): void {
        Session::checkRole(['admin', 'directivo']);
        $this->validateCsrf();
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

        $data = [
            'rol_id'  => (int)($_POST['rol_id'] ?? 0),
            'nombre'  => trim($_POST['nombre']  ?? ''),
            'email'   => trim($_POST['email']   ?? ''),
            'estado'  => $_POST['estado']       ?? 'activo',
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        if (User::update($id, $data)) {
            redirect('users');
        } else {
            die("Error al actualizar el usuario.");
        }
    }

    public function delete(): void {
        Session::checkRole(['admin', 'directivo']);
        $id = (int)($_POST['id'] ?? 0);
        User::delete($id);
        redirect('users');
    }
}
