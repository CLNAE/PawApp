cat > db.php <<'EOF'
<?php
class Database {
    private static $instance = null;
    private $connection;

    private $host = 'db';              // numele serviciului MySQL din docker-compose.yml
    private $db_name = 'ac790_proiect';
    private $username = 'user';
    private $password = 'pass';

    // Constructor privat – nu permite instanțierea directă
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Eroare la conexiunea cu baza de date: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>
EOF