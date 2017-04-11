<?php
require("config/config.php");
session_start();
$_SESSION['manager']=null;
echo "
<script>
location='$web_path/login.php';
</script>
";
?>
