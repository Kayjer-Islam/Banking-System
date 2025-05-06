<?php
session_start();
session_unset();
session_destroy();
header("Location: User autho.php"); // Change to match your login page filename
exit();
