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
        'grade' => '8th Grade',
        'role' => 'Lead UI/UX & Catalog Architect',
        'icon' => 'user-check',
        'badge' => 'Grade 8 Lead'
    ],
    [
        'name' => 'Siddharth',
        'grade' => '8th Grade',
        'role' => 'PDF Database & Digital Books Manager',
        'icon' => 'cpu',
        'badge' => 'Grade 8 Architect'
    ],
    [
        'name' => 'Aadi',
        'grade' => '7th Grade',
        'role' => 'Comic Collections & Audio Curator',
        'icon' => 'zap',
        'badge' => 'Grade 7 Specialist'
    ]
];
