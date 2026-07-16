<?php
require_once __DIR__ . '/includes/api_client.php';
session_destroy();
header('Location: login.php');
exit;
