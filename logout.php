<?php
require_once 'bootstrap.php';
session_unset();
session_destroy();
header("Location: login.php?msg=Sei uscito correttamente. A presto!");
exit;
?>