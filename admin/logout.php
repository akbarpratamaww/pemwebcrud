<?php
session_start();
session_destroy();
header("Location: ../admin/loginadmin.php");
exit();
?>