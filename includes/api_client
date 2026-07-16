<?php
/**
 * Lapisan komunikasi ke Backend API, versi PHP (pakai cURL).
 * Ini pengganti peran fetch() di frontend JS pada arsitektur sebelumnya.
 */

// API_BASE_URL diambil dari file konfigurasi khusus frontend.
require_once __DIR__ . '/../konfig.php';

session_start();

/**
 * Info IP & hostname PC/server FRONTEND yang sedang menangani request ini.
 * Dipakai untuk ditampilkan di header/login (bantu debugging saat frontend
 * dan backend dijalankan di beberapa mesin berbeda).
 */
function infoServerFrontend(): array
{
    $hostname = gethostname() ?: 'tidak diketahui';
    $ip = $_SERVER['SERVER_ADDR'] ?? (gethostbyname($hostname) ?: 'tidak diketahui');

    return ['hostname' => $hostname, 'ip' => $ip];
}

function callApi(string $endpoint, string $method = 'GET', array $body = null, bool $auth = false): array
{
    $url = API_BASE_URL . '/' . $endpoint;

    $headers = ['Content-Type: application/json'];
    if ($auth && isset($_SESSION['token'])) {
        $headers[] = 'Authorization: Bearer ' . $_SESSION['token'];
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 10,
    ]);

    if ($body !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        return ['success' => false, 'message' => 'Gagal terhubung ke server API: ' . $curlError, 'data' => null];
    }

    $decoded = json_decode($response, true);

    if ($httpCode === 401) {
        // Sesi/token tidak valid -> paksa logout
        session_destroy();
        header('Location: login.php?expired=1');
        exit;
    }

    return $decoded ?? ['success' => false, 'message' => 'Response API tidak valid.', 'data' => null];
}

function isLoggedIn(): bool
{
    return isset($_SESSION['token'], $_SESSION['username']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function currentUser(): ?string
{
    return $_SESSION['username'] ?? null;
}

/**
 * Format tanggal hari ini ke Bahasa Indonesia, misal: "Kamis, 16 Juli 2026"
 */
function tanggalIndonesia(?string $tanggal = null): string
{
    $hariList = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];
    $bulanList = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    $timestamp = $tanggal ? strtotime($tanggal) : time();

    $hari = $hariList[(int) date('w', $timestamp)];
    $tgl = (int) date('j', $timestamp);
    $bulan = $bulanList[(int) date('n', $timestamp)];
    $tahun = date('Y', $timestamp);

    return "$hari, $tgl $bulan $tahun";
}
