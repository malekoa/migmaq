<?php
// models/Lesson.php

class Lesson
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getBySection(int $sectionId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM lessons WHERE section_id = ? ORDER BY position ASC");
        $stmt->execute([$sectionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM lessons WHERE id = ?");
        $stmt->execute([$id]);
        $lesson = $stmt->fetch(PDO::FETCH_ASSOC);
        return $lesson ?: null;
    }

    public function save(array $data): void
    {
        if (!empty($data['id'])) {
            // Update existing lesson
            $stmt = $this->pdo->prepare("
                UPDATE lessons
                SET title = :title, body = :body, status = :status
                WHERE id = :id
            ");
            $stmt->execute([
                ':title' => $data['title'],
                ':body' => $data['body'],
                ':status' => $data['status'],
                ':id' => $data['id']
            ]);
        } else {
            // Insert new lesson
            $stmt = $this->pdo->prepare("
                INSERT INTO lessons (section_id, title, body, status)
                VALUES (:section_id, :title, :body, :status)
            ");
            $stmt->execute([
                ':section_id' => $data['section_id'],
                ':title' => $data['title'],
                ':body' => $data['body'],
                ':status' => $data['status']
            ]);
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM lessons WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function updatePosition(int $id, int $position): void
    {
        $stmt = $this->pdo->prepare("UPDATE lessons SET position = :position WHERE id = :id");
        $stmt->execute([
            ':position' => $position,
            ':id' => $id
        ]);
    }

    public function updatePositions(array $positions): void
    {
        $stmt = $this->pdo->prepare("UPDATE lessons SET position = :position WHERE id = :id");
        foreach ($positions as $pos) {
            $stmt->execute([
                ':position' => $pos['position'],
                ':id' => $pos['id']
            ]);
        }
    }

    public function getAdjacentLessons(int $lessonId): array
    {
        $stmt = $this->pdo->prepare("SELECT section_id, position FROM lessons WHERE id = ?");
        $stmt->execute([$lessonId]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$current) return ['prev' => null, 'next' => null];

        // Get previous lesson
        $stmt = $this->pdo->prepare("
        SELECT id, title FROM lessons
        WHERE section_id = ? AND position < ?
        ORDER BY position DESC LIMIT 1
    ");
        $stmt->execute([$current['section_id'], $current['position']]);
        $prev = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get next lesson
        $stmt = $this->pdo->prepare("
        SELECT id, title FROM lessons
        WHERE section_id = ? AND position > ?
        ORDER BY position ASC LIMIT 1
    ");
        $stmt->execute([$current['section_id'], $current['position']]);
        $next = $stmt->fetch(PDO::FETCH_ASSOC);

        return ['prev' => $prev, 'next' => $next];
    }
}
