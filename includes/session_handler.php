<?php
class DatabaseSessionHandler implements SessionHandlerInterface {
    private $pdo;
    private $tableCreated = false;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    private function ensureTableExists(): void {
        if ($this->tableCreated) return;
        try {
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS sessions (
                    id VARCHAR(128) NOT NULL PRIMARY KEY,
                    data MEDIUMTEXT NOT NULL,
                    timestamp INT(10) UNSIGNED NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
            $this->tableCreated = true;
        } catch (\Throwable $e) {
            // Silently ignore if table creation fails
        }
    }
    
    public function open(string $path, string $name): bool {
        return true;
    }
    
    public function close(): bool {
        return true;
    }
    
    public function read(string $id): string|false {
        try {
            $stmt = $this->pdo->prepare("SELECT data FROM sessions WHERE id = ?");
            $stmt->execute([$id]);
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return (string)$row['data'];
            }
            return '';
        } catch (\Throwable $e) {
            $this->ensureTableExists();
            try {
                $stmt = $this->pdo->prepare("SELECT data FROM sessions WHERE id = ?");
                $stmt->execute([$id]);
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    return (string)$row['data'];
                }
            } catch (\Throwable $e2) {
                // Return empty string so PHP session_start() never throws a fatal error
            }
            return '';
        }
    }
    
    public function write(string $id, string $data): bool {
        try {
            $stmt = $this->pdo->prepare("REPLACE INTO sessions (id, data, timestamp) VALUES (?, ?, ?)");
            return $stmt->execute([$id, $data, time()]);
        } catch (\Throwable $e) {
            $this->ensureTableExists();
            try {
                $stmt = $this->pdo->prepare("REPLACE INTO sessions (id, data, timestamp) VALUES (?, ?, ?)");
                return $stmt->execute([$id, $data, time()]);
            } catch (\Throwable $e2) {
                return false;
            }
        }
    }
    
    public function destroy(string $id): bool {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\Throwable $e) {
            return false;
        }
    }
    
    public function gc(int $max_lifetime): int|false {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE timestamp < ?");
            $stmt->execute([time() - $max_lifetime]);
            return $stmt->rowCount();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
