<?php
include 'logger.php';

header( "refresh:5;url=verlog.php" );
if (isset($_GET['cleanlog'])) {
    unlink(ERROR_LOG_FILE);
    header("Location: verlog.php");
}
echo '<h3> Last call '.date('d-m-Y H:i:s').'</h3>';

if (file_exists(ERROR_LOG_FILE)) {
    echo '<p style="text-align:right"><A href="?cleanlog">Limpiar Log</A></p>';
    echo '<pre>';
    readfile(ERROR_LOG_FILE);
    echo '</pre>';
    echo '<p style="text-align:right"><A href="?cleanlog">Limpiar Log</A></p>';
}
else
{
    echo '<h1>No log file!</h1>';
}