<?php
/**
 * Simple Router for PHP Built-in Server
 * Redirects root URL to index.html
 */

// If accessing root, show index.html
if ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '') {
    include 'index.html';
    exit;
}

// For all other requests, let PHP's built-in server handle them
return false;
