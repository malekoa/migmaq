<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dbFile = __DIR__ . '/data.db';
$pdo = new PDO('sqlite:' . __DIR__ . '/../data/data.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create users table
$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL
    );
");

// Create units table
$pdo->exec("
    CREATE TABLE IF NOT EXISTS units (
        id         INTEGER PRIMARY KEY AUTOINCREMENT,
        title      TEXT NOT NULL,
        body       TEXT NOT NULL,
        status     TEXT NOT NULL DEFAULT 'draft',
        position   INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
");

// Create audios table
$pdo->exec("
    CREATE TABLE IF NOT EXISTS audios (
        id       INTEGER PRIMARY KEY AUTOINCREMENT,
        filename TEXT NOT NULL,
        mime     TEXT NOT NULL,
        data     BLOB NOT NULL
    );
");

// (Optional) Create sections table if you're moving forward with it soon
/*
$pdo->exec("
    CREATE TABLE IF NOT EXISTS sections (
        id       INTEGER PRIMARY KEY AUTOINCREMENT,
        unit_id  INTEGER NOT NULL,
        title    TEXT NOT NULL,
        body     TEXT NOT NULL,
        position INTEGER DEFAULT 0,
        FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE
    );
");
*/
