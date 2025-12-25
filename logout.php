<?php
session_start();
session_destroy();
header("Location: index.php"); // Balik ke halaman depan publik
exit;
?>