<?php
function isAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function getSetting(PDO $pdo, string $key, string $default = ''): string {
    $stmt = $pdo->prepare("SELECT value FROM settings WHERE key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetchColumn();
    return $result !== false ? $result : $default;
}

function setSetting(PDO $pdo, string $key, string $value): void {
    $stmt = $pdo->prepare("REPLACE INTO settings (key, value) VALUES (?, ?)");
    $stmt->execute([$key, $value]);
}

function getAllSettings(PDO $pdo): array {
    $stmt = $pdo->query("SELECT `key`, `value` FROM settings");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
