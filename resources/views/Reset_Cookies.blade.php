<?php
 setcookie("user_name","",time()-2000);
 setcookie("user_pass","",time()-2000);
 header("location:Login.php");
?>