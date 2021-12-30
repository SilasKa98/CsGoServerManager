<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div id='allWrapper'>
    <center><img src="media/Aconix-Logo.png" id='headerImg'></center>
    <?php
    
        include "db_connector.php";
        error_reporting(E_ERROR | E_PARSE);
        $fetchedMaps = [];
        $fetchedPort = [];
        $fetchedIP = [];
        $fetchedGSLT = [];
        $fetchedGameMode = [];
        $fetchedServerPw = [];
        $fetchedRconPw = [];
        $sql = "select * from ServerVerwaltung";
        $stmt = mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "SQL Statement failed";
        }else{
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($row = $result->fetch_assoc()) {
                array_push($fetchedMaps,$row["Map"]);
                array_push($fetchedPort,$row["Port"]);
                array_push($fetchedIP,$row["IP"]);
                array_push($fetchedGSLT,$row["GSLT"]);
                array_push($fetchedGameMode,$row["GameMode"]);
                array_push($fetchedServerPw,$row["ServerPw"]);
                array_push($fetchedRconPw,$row["RconPw"]);
            }
        }

        $avServers = scandir("Servers");
        $filteredAvServers = array_values(array_diff($avServers, array('.', '..')));
        print "<div id='allServerWrapper'>";
            print "<h1>Counter Strike Global Offensive Server Settings</h1>";
            for($i=0;$i<count($filteredAvServers);$i++){
                print "<div class='serverWrapper'>";
                print "<h2>Aconix ".$filteredAvServers[$i]."</h2>";
                print "
                    <div id='formWrapper'>
                        <label>Map: 
                        <select class='inputServer'>
                            <option value=\"de_cbble\""; if($fetchedMaps[$i] == 'de_cbble'){print "selected";} print">Cobblestone</option>
                            <option value=\"de_inferno\""; if($fetchedMaps[$i] == 'de_inferno'){print "selected";} print">Inferno</option>
                            <option value=\"de_lake\""; if($fetchedMaps[$i] == 'de_lake'){print "selected";} print">Lake</option>
                            <option value=\"de_overpass\""; if($fetchedMaps[$i] == 'de_overpass'){print "selected";} print">Overpass</option>
                            <option value=\"de_shortdust\""; if($fetchedMaps[$i] == 'de_shortdust'){print "selected";} print">Shortdust</option>
                            <option value=\"de_shortnuke\""; if($fetchedMaps[$i] == 'de_shortnuke'){print "selected";} print">Nuke</option>
                            <option value=\"de_train\""; if($fetchedMaps[$i] == 'de_train'){print "selected";} print">Train</option>
                            <option value=\"de_vertigo\""; if($fetchedMaps[$i] == 'de_vertigo'){print "selected";} print">Vertigo</option>
                            <option value=\"lobby_mapveto\""; if($fetchedMaps[$i] == 'lobby_mapveto'){print "selected";} print">Map Vote</option>
                        </select></label>
                        <label>Port:<input type='number' class='inputServer' value='".$fetchedPort[$i]."'></label>
                        <label>IP-Adresse: <input type='text' id='serverIP' class='inputServer' value='".$fetchedIP[$i]."'></label>
                        <label>GSLT<a class='gsltAnker'href='https://steamcommunity.com/dev/managegameservers'  target='_blank'>(Server Token):</a> <input type='text' class='inputServer' id='serverToken' value='".$fetchedGSLT[$i]."'></label>
                        <button class=\"collapsible\">Advanced Settings</button>
                        <div class=\"advancedSettings\">
                            <label style='margin-top:10px'>GameType: 
                                <select class='inputServer'>
                                    <option value=\"2\""; if($fetchedGameMode[$i] == '2'){print "selected";} print">Wingman 2vs2</option>
                                    <option value=\"1\""; if($fetchedGameMode[$i] == '1'){print "selected";} print">Competitive 5vs5</option>
                                </select>
                            </label>
                            <label>Server Password: <input type='text' id='serverPW' class='inputServer' value='".$fetchedServerPw[$i]."'></label>
                            <label>Rcon Password: <input type='text' id='RconPW' class='inputServer' value='".$fetchedRconPw[$i]."'></label>
                        </div>
                        <button onclick=\"createServer(this)\" class='saveBtn'>Speichern</button>
                        <input type='hidden' value='".$filteredAvServers[$i]."'>
                        <button onclick=\"launchServer(this)\" class='saveBtn'>Server Starten</button>";
                        $directConnectIP = "connect ".$fetchedIP[$i].":".$fetchedPort[$i];
                        print"<label id='csgoConnectCmd'>Connect command: <span id='csgoConnectCmdSpan'>".$directConnectIP."</span></label>
                    </div>
                ";

                print "</div>";
            }
        print "</div>";
print "</div>";
    ?>
</body>
<script>
function createServer(e){
    let map = e.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.lastChild.value;
    let port = e.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.lastChild.value;
    let ip = e.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.lastChild.value;
    let gslt = e.previousElementSibling.previousElementSibling.previousElementSibling.lastChild.value;
    let gameMode = e.previousElementSibling.childNodes[1].childNodes[1].value;
    let serverPw = e.previousElementSibling.childNodes[3].childNodes[1].value;
    let rconPw = e.previousElementSibling.childNodes[5].childNodes[1].value;
    let server = e.nextElementSibling.value;

    $.ajax({
        type: "POST",
        url: "createFiles.php",
        data: {
            method: "changeServerSettings",
            map: map,
            port: port,
            ip: ip,
            gslt: gslt,
            gameMode: gameMode,
            serverPw: serverPw,
            rconPw: rconPw,
            server: server
        },
        success: function(response, message, result) {
            console.log(response);
            console.log(message);
            console.log(result);
            //location.reload();
        }
    });
}

function launchServer(e){
    let server = e.previousElementSibling.value;
    $.ajax({
        type: "POST",
        url: "launchServer.php",
        data: {
            server: server
        },
        success: function(response, message, result) {
            console.log(response);
            console.log(message);
            console.log(result);
            //location.reload();
        }
    });
}



var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  });
}
</script>
</html>