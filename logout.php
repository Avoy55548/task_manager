<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php?login_needed=1");
exit();
?>