<?php

include "db_connector.php";

if($_POST["method"] == "changeServerSettings"){
    $map = $_POST["map"];
    $port = $_POST["port"];
    $ip = $_POST["ip"];
    $gslt = $_POST["gslt"];
    $server = $_POST["server"];
    $gameMode = $_POST["gameMode"];
    $serverPw = $_POST["serverPw"];
    $rconPw = $_POST["rconPw"];
    $file0 = 'Servers/'.$server.'/start.bat';
    $file1 = 'Servers/'.$server.'/csgo/cfg/server.cfg';
    
    if (file_exists($file1)) {
        unlink($file1);
    }
    $fp1 = fopen($file1, "a+");
    fwrite($fp1,'hostname "'.$server.' Aconix"'."\n");
    fwrite($fp1,'rcon_password "'.$rconPw.'"'."\n");
    fwrite($fp1,'sv_password "'.$serverPw.'"'."\n");
    fwrite($fp1,'sv_setsteamaccount "'.$gslt.'"'."\n");
    fwrite($fp1,"\n");
    fwrite($fp1,"\n");
    fwrite($fp1,'sv_downloadurl ""'."\n");
    fwrite($fp1,'sv_allowdownload 1'."\n");
    fwrite($fp1,'sv_allowupload 1'."\n");
    fwrite($fp1,"\n");
    fwrite($fp1,"\n");
    fwrite($fp1,'mp_freezetime 5'."\n");
    fwrite($fp1,'mp_join_grace_time 15'."\n");
    fwrite($fp1,'mp_match_end_restart 0'."\n");
    fwrite($fp1,'mp_overtime_enable 1'."\n");
    fwrite($fp1,'sv_cheats 0'."\n");
    fwrite($fp1,'sv_lan 0'."\n");
    fwrite($fp1,"\n");
    fwrite($fp1,"\n");
    fwrite($fp1,'fps_max 0'."\n");
    fwrite($fp1,'sv_minrate 128000'."\n");
    fwrite($fp1,'sv_maxrate 0'."\n");
    fwrite($fp1,'sv_mincmdrate 128'."\n");
    fwrite($fp1,"\n");
    fwrite($fp1,"\n");
    fwrite($fp1,'// write out any bans'."\n");
    fwrite($fp1,'writeid'."\n");
    fwrite($fp1,'writeip'."\n");
    fwrite($fp1,"\n");
    fwrite($fp1,"\n");
    fwrite($fp1,'sv_region 0'."\n");
    fwrite($fp1,'log 1'."\n");

    if (file_exists($file0)) {
        unlink($file0);
    }

    $fp0 = fopen($file0, "a+");
    fwrite($fp0,'srcds -game csgo -console -usercon +game_type 0 +game_mode '.$gameMode.' +mapgroup mg_active -tickrate 128 +map '.$map.' +sv_setsteamaccount '.$gslt.' -ip '.$ip.' -port '.$port.'');
    


    $sql = "select * from ServerVerwaltung where Name=?;";
    $stmt = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($stmt, $sql)){
    echo "SQL Statement failed";
    }else{
        mysqli_stmt_bind_param($stmt, "s", $server);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result->num_rows == 0) {
            $sql3 ="insert into ServerVerwaltung (Name,Map,Port,IP,GSLT,GameMode,ServerPw,RconPw) values (?,?,?,?,?,?,?,?);";
            $stmt3 = mysqli_stmt_init($connection);
            if(!mysqli_stmt_prepare($stmt3, $sql3)){
                echo "SQL error";
            }else{
                mysqli_stmt_bind_param($stmt3, "ssississ", $server,$map,$port,$ip,$gslt,$gameMode,$serverPw,$rconPw);
                mysqli_stmt_execute($stmt3);
            }
        }else{
            $sql3 ="update ServerVerwaltung set Map=?, Port=?, IP=?, GSLT=?, GameMode=?,ServerPw=?,RconPw=? where Name=?;";
            $stmt3 = mysqli_stmt_init($connection);
            if(!mysqli_stmt_prepare($stmt3, $sql3)){
                echo "SQL error";
            }else{
                mysqli_stmt_bind_param($stmt3, "sississs", $map,$port,$ip,$gslt,$gameMode,$serverPw,$rconPw,$server);
                mysqli_stmt_execute($stmt3);
            }
        }
    }   


}
?>