<?php
// ============================
// KONFIGURASI EMAIL DESA
// ============================

return [

  // EMAIL DESA (pengirim)
  'from_email' => 'dssusukan01@gmail.com',   // GANTI
  'from_name'  => 'Pemerintah Desa Susukan',

  // EMAIL TUJUAN (penerima keluhan)
  'to_email'   => 'dssusukan01@gmail.com',   // boleh sama
  'to_name'    => 'Admin Desa Susukan',

  // SMTP GMAIL (PALING MUDAH)
  'host'       => 'smtp.gmail.com',
  'port'       => 587,
  'username'   => 'dssusukan01@gmail.com',   // GANTI
  'password'   => 'mdoj tswh csmr vbcc',      // GANTI (WAJIB app password)
  'encryption' => 'tls',

];
