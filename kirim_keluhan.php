<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

// Load config SMTP
$config = require __DIR__ . '/smtp_config.php';

// Ambil & amankan data form
function clean($v) {
  return trim(htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'));
}

$nama   = clean($_POST['nama']);
$no_hp  = clean($_POST['no_hp']);
$alamat = clean($_POST['alamat']);
$jenis  = clean($_POST['jenis']);
$pesan  = clean($_POST['pesan']);

// Validasi wajib
if (!$nama || !$alamat || !$jenis || !$pesan) {
  header("Location: contactus.html?error=1");
  exit;
}

$mail = new PHPMailer(true);

try {

  // ===== SET SMTP =====
  $mail->isSMTP();
  $mail->Host       = $config['host'];
  $mail->SMTPAuth   = true;
  $mail->Username   = $config['username'];
  $mail->Password   = $config['password'];
  $mail->Port       = $config['port'];

  if ($config['encryption'] === 'tls') {
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  } else {
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  }

  // ===== PENGIRIM & TUJUAN =====
  $mail->setFrom($config['from_email'], $config['from_name']);
  $mail->addAddress($config['to_email'], $config['to_name']);

  // ===== ISI EMAIL =====
  $mail->isHTML(true);
  $mail->Subject = "Keluhan Warga Desa Susukan - $jenis";

  $mail->Body = "
    <h2>Keluhan Warga</h2>
    <table border='1' cellpadding='8' cellspacing='0' style='border-collapse:collapse'>
      <tr><td><b>Nama</b></td><td>$nama</td></tr>
      <tr><td><b>No HP</b></td><td>".($no_hp ?: '-')."</td></tr>
      <tr><td><b>Alamat/Dusun</b></td><td>$alamat</td></tr>
      <tr><td><b>Jenis</b></td><td>$jenis</td></tr>
      <tr><td><b>Pesan</b></td><td>".nl2br($pesan)."</td></tr>
      <tr><td><b>Waktu</b></td><td>".date('Y-m-d H:i:s')."</td></tr>
    </table>
    <p><i>Email otomatis dari Website Profil Desa Susukan</i></p>
  ";

  // Versi text biasa (backup)
  $mail->AltBody =
    "Keluhan Warga Desa Susukan\n\n".
    "Nama: $nama\n".
    "No HP: ".($no_hp ?: '-')."\n".
    "Alamat: $alamat\n".
    "Jenis: $jenis\n".
    "Pesan: $pesan\n".
    "Waktu: ".date('Y-m-d H:i:s');

  // ===== KIRIM =====
  $mail->send();

  header("Location: contactus.html?success=1");
  exit;

} catch (Exception $e) {
  header("Location: contactus.html?error=2&msg=" . urlencode($mail->ErrorInfo));
  exit;
}
