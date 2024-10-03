<?php

session_start();

session_destroy();

echo "<center>Signing 0ut...</center>";
header("refresh:2; url='index.php'");