<?php
session_start();
session_destroy();
header('Location: index.html'); // กลับไปหน้าแรก
exit;
?>