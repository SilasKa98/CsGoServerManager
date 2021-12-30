
<?php
    $server = $_POST["server"];
    chdir('D:\csServerAconix\serverController\htdocs\Aconix\serverCommander\Servers\\'.$server);
    exec('c:\WINDOWS\system32\cmd.exe /c START start.bat');
?>