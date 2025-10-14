<?php
class Database {
    // Instanța singleton
    private static $instance = null;
    // Conexiunea PDO
    private $pdo;

    // Constructorul privat previne instanțierea externă
    private function __construct() {
        $host     = '10.13.11.6';
        $dbname   = 'ac790_proiect';
        $username = 'ac790';
        $password = 'vxsy621t';
        $dsn      = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $username, $password);
            // Configurăm modul de eroare la excepții și modul de fetch
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Oprirea execuției și afișarea erorii
            die("Eroare la conexiunea cu baza de date: " . $e->getMessage());
        }
    }

    // Returnează instanța singleton a clasei Database
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Metodă publică pentru a obține conexiunea PDO
    public function getConnection() {
        return $this->pdo;
    }
}
?>
