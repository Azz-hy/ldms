<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Forward Vercel requests to the reorganized Laravel index.php
require __DIR__ . '/../delivery-web-system/public/index.php';
