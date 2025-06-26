<?php
session_start();
session_destroy();
header('Location: /'); // กลับไปหน้าแรก
exit;
?>