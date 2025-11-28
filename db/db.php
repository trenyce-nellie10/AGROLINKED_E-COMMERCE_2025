<?php
// db/db.php
class DB {
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'agrolinked';
    protected $pdo;

    public function __construct(){
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }
    }

    protected function connect(){
        return $this->pdo;
    }
}
