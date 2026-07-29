<?php
class DatabaseSessionHandler implements SessionHandlerInterface {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function open(string $path, string $name): bool {
        return true;
    }
    
    public function close(): bool {
        return true;
    }
    
    public function read(string $id): string|false {
        $stmt = $this->pdo->prepare("SELECT data FROM sessions WHERE id = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return (string)$row['data'];
        }
        return '';
    }
    
    public function write(string $id, string $data): bool {
        $stmt = $this->pdo->prepare("REPLACE INTO sessions (id, data, timestamp) VALUES (?, ?, ?)");
        return $stmt->execute([$id, $data, time()]);
    }
    
    public function destroy(string $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function gc(int $max_lifetime): int|false {
        $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE timestamp < ?");
        $stmt->execute([time() - $max_lifetime]);
        return $stmt->rowCount();
    }
}
