<?php
// Archivo de modelo para manejar la lógica de negocio relacionada con los tickets en el sistema de gestión profesional. Este modelo se encarga de interactuar con la base de datos para obtener, crear o actualizar información sobre los tickets asociados a cada usuario.
require_once __DIR__ . '/../config/db.php';

class TicketModel {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function obtenerTickets() {
        $sql = "SELECT * FROM tickets ORDER BY id_ticket DESC";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerTicketsConCliente() {
        $sql = "SELECT t.*, u.nombre AS cliente_nombre, u.email AS cliente_email FROM tickets t LEFT JOIN usuarios u ON t.usuario_id = u.id ORDER BY t.id_ticket DESC";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerTicketPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE id_ticket = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizarEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE tickets SET estado = ? WHERE id_ticket = ?");
        $stmt->bind_param("si", $estado, $id);
        return $stmt->execute();
    }

    public function crearTicket($titulo, $desc, $usuario_id, $adjunto = null) {
        $stmt = $this->db->prepare("INSERT INTO tickets (titulo, descripcion, usuario_id, adjunto) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $titulo, $desc, $usuario_id, $adjunto);
        return $stmt->execute();
    }

    public function obtenerEstadisticas() {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN estado = 'abierto' THEN 1 ELSE 0 END) as abiertos,
                    SUM(CASE WHEN estado = 'resuelto' THEN 1 ELSE 0 END) as resueltos
                    FROM tickets";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_assoc() : ['total' => 0, 'abiertos' => 0, 'resueltos' => 0];
    }

    public function obtenerPorCliente($clienteId) {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE usuario_id = ? ORDER BY id_ticket DESC");
        $stmt->bind_param("i", $clienteId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function contarEstadosPorCliente($clienteId) {
        $stmt = $this->db->prepare("SELECT 
                    SUM(CASE WHEN estado = 'abierto' THEN 1 ELSE 0 END) as abiertos,
                    SUM(CASE WHEN estado = 'resuelto' THEN 1 ELSE 0 END) as resueltos
                    FROM tickets WHERE usuario_id = ?");
        $stmt->bind_param("i", $clienteId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ?: ['abiertos' => 0, 'resueltos' => 0];
    }
}
