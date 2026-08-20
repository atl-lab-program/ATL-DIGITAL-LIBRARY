<?php
/**
 * ATL DIGITAL LIBRARY - GLOBAL CONFIGURATION
 * Educational PHP Configuration File for 7th & 8th Grade Developers
 */

define('SITE_NAME', 'ATL Digital Library');
define('SITE_TAGLINE', 'Interactive Digital Library & Softcopy Catalog');
define('SITE_VERSION', '3.0.0');

// Base URL detection
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
define('BASE_URL', $protocol . '://' . $host . '/');

// Student Creators Registry
$STUDENT_CREATORS = [
    [
        'name' => 'Suhaira',
        'grade' => '7th Grade',
        'role' => 'Lead UI/UX & Catalog Architect',
        'icon' => 'user-check',
        'badge' => 'Grade 7 Lead'
    ],
    [
        'name' => 'Siddharth',
        'grade' => '7th Grade',
        'role' => 'Digital Books Manager',
        'icon' => 'cpu',
        'badge' => 'Grade 7 Architect'
    ],
    [
        'name' => 'Aadi',
        'grade' => '7th Grade',
        'role' => 'Comic Collections, PDF Database',
        'icon' => 'zap',
        'badge' => 'Grade 7 Specialist'
    ]
];
