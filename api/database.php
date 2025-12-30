<?php
/**
 * VISHVAVIRAT SECURITY - Database Handler
 *
 * Secure database connection and query functions with prepared statements
 */

define('SECURE_ACCESS', true);
require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $connection;

    /**
     * Private constructor for singleton pattern
     */
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
            ];

            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);

        } catch (PDOException $e) {
            $this->logError('Database connection failed: ' . $e->getMessage());
            throw new Exception('Database connection failed');
        }
    }

    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get PDO connection
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Execute a prepared statement
     *
     * @param string $query SQL query with placeholders
     * @param array $params Parameters to bind
     * @return PDOStatement
     */
    public function execute($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->logError('Query execution failed: ' . $e->getMessage());
            throw new Exception('Database query failed');
        }
    }

    /**
     * Insert a record and return the last insert ID
     *
     * @param string $query SQL INSERT query
     * @param array $params Parameters to bind
     * @return int Last insert ID
     */
    public function insert($query, $params = []) {
        $this->execute($query, $params);
        return $this->connection->lastInsertId();
    }

    /**
     * Fetch a single row
     *
     * @param string $query SQL SELECT query
     * @param array $params Parameters to bind
     * @return array|false
     */
    public function fetchOne($query, $params = []) {
        $stmt = $this->execute($query, $params);
        return $stmt->fetch();
    }

    /**
     * Fetch all rows
     *
     * @param string $query SQL SELECT query
     * @param array $params Parameters to bind
     * @return array
     */
    public function fetchAll($query, $params = []) {
        $stmt = $this->execute($query, $params);
        return $stmt->fetchAll();
    }

    /**
     * Begin transaction
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit() {
        return $this->connection->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback() {
        return $this->connection->rollBack();
    }

    /**
     * Check if table exists
     *
     * @param string $tableName
     * @return bool
     */
    public function tableExists($tableName) {
        try {
            $result = $this->connection->query("SHOW TABLES LIKE '$tableName'");
            return $result->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Log errors to file
     *
     * @param string $message
     */
    private function logError($message) {
        $logFile = __DIR__ . '/error.log';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message\n";
        error_log($logMessage, 3, $logFile);
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * Prevent unserialization
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Helper function to get database instance
 */
function getDB() {
    return Database::getInstance();
}
