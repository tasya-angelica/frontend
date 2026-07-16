<?php
/**
 * ==========================================================
 *  KONFIG.PHP — Konfigurasi FRONTEND
 * ==========================================================
 * Taruh file ini di server FRONTEND. Isinya:
 *   1) API_BASE_URL — alamat backend API yang dipanggil frontend
 *      lewat cURL (server-to-server).
 *
 * Frontend TIDAK perlu tahu API_SECRET sama sekali — cukup backend saja
 * yang tahu, karena frontend hanya meneruskan username/password dari form
 * ke backend, lalu menyimpan token yang dikembalikan backend di session
 * PHP-nya sendiri ($_SESSION['token']).
 *
 * File ini di-include oleh:
 *   - frontend/includes/api_client.php (pakai API_BASE_URL)
 */

// Ganti host/IP/port ini sesuai alamat server backend kamu, contoh:
// define('API_BASE_URL', 'http://192.168.1.10/presensi-app/backend/api');
define('API_BASE_URL', 'http://localhost/presensi-app/backend/api');
