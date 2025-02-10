<?php
// Periksa apakah ekstensi pgsql terinstal
if (!extension_loaded('pgsql')) {
    die("Ekstensi PostgreSQL tidak terinstal. Silakan instal ekstensi pgsql terlebih dahulu dan aktifkan di php.ini");
}

// Konfigurasi koneksi Supabase PostgreSQL
$host = "aws-0-ap-southeast-1.pooler.supabase.com";
$port = "5432";
$dbname = "postgres";
$user = "postgres.hhfetohqcseicbspsbbi";
$password = "qw123qew132rr254";

try {
    // Buat string koneksi PostgreSQL
    $conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password sslmode=require";

    // Buat koneksi
    $conn = pg_connect($conn_string);

    if (!$conn) {
        throw new Exception("Connection failed: " . pg_last_error());
    }

    // Ambil data dari POST request
    if (!isset($_POST['idalat']) || !isset($_POST['speed']) || !isset($_POST['distance'])) {
        throw new Exception("Data tidak lengkap. Pastikan idalat, speed, dan distance terkirim.");
    }

    $idalat = $_POST['idalat'];
    $speed = (double)$_POST['speed'];
    $distance = (double)$_POST['distance'];

    // Query untuk insert data ke tabel monitoring
    $sql = "INSERT INTO monitoring (idalat, speed, distance) VALUES ($1, $2, $3)";
    $result = pg_query_params($conn, $sql, array($idalat, $speed, $distance));

    if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Data inserted successfully"
        ]);
    } else {
        throw new Exception("Error inserting data: " . pg_last_error($conn));
    }

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
} finally {
    // Tutup koneksi jika masih terbuka
    if (isset($conn) && $conn) {
        pg_close($conn);
    }
}
?>