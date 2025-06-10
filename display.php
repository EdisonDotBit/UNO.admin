<?php 

include("main.php");
if ($_SESSION['username'] === "" || $_SESSION['username'] === null ) {
    header("location: front_page.php");
}

$username   = $_SESSION['username'];
$query      = "SELECT DBNAME FROM netman01.$username";
$fetch_db   = mysqli_query($key, $query);
$result_db  = mysqli_num_rows($fetch_db);

?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" type="image/gif" href="resource/1.png">
        <title>UNO.Admin</title>
        <link rel="stylesheet" id = "theme" type="text/css" href="css/main.css">
    </head>
    <body id="body">
        <?php

        if(count($_SESSION['notif']) > 0) {
            echo "
                <div class='notif' onclick='closeNotif(this)'>
                    <div class='alert_box'>
                        <h1>NOTIFICATION</h1>
                        <div class='alerts'>
                            <div class = 'sub'>";
                foreach($_SESSION['notif'] as $m) {
                    echo '<p> - '.array_pop($_SESSION['notif']).'</p>';
                }
            echo '
                            </div>

                        </div>
                        <p>Click anywhere to close </p>
                    </div>
                </div>';
        }
        ?>
        <div class="header">
            <img src="resource/5.png" class="nav_logo">
            <p class="user"> Welcome <b><?php echo $_SESSION['username']?></b>!</p>
            <svg class="nav_logout" viewBox="0 0 24 24" onclick="window.top.location.href='main.php?logout=true'">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M2 6.5C2 4.01472 4.01472 2 6.5 2H12C14.2091 2 16 3.79086 16 6V7C16 7.55228 15.5523 8 15 8C14.4477 8 14 7.55228 14 7V6C14 4.89543 13.1046 4 12 4H6.5C5.11929 4 4 5.11929 4 6.5V17.5C4 18.8807 5.11929 20 6.5 20H12C13.1046 20 14 19.1046 14 18V17C14 16.4477 14.4477 16 15 16C15.5523 16 16 16.4477 16 17V18C16 20.2091 14.2091 22 12 22H6.5C4.01472 22 2 19.9853 2 17.5V6.5ZM18.2929 8.29289C18.6834 7.90237 19.3166 7.90237 19.7071 8.29289L22.7071 11.2929C23.0976 11.6834 23.0976 12.3166 22.7071 12.7071L19.7071 15.7071C19.3166 16.0976 18.6834 16.0976 18.2929 15.7071C17.9024 15.3166 17.9024 14.6834 18.2929 14.2929L19.5858 13L11 13C10.4477 13 10 12.5523 10 12C10 11.4477 10.4477 11 11 11L19.5858 11L18.2929 9.70711C17.9024 9.31658 17.9024 8.68342 18.2929 8.29289Z"/>
            </svg>
            <svg class="nav_dev" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                <path d="M877.685565 727.913127l-0.584863-0.365539a32.898541 32.898541 0 0 1-8.041866-46.423497 411.816631 411.816631 0 1 0-141.829267 145.777092c14.621574-8.992268 33.62962-5.117551 43.645398 8.772944l0.146216 0.073108a30.412874 30.412874 0 0 1-7.968758 43.206751l-6.141061 4.020933a475.201154 475.201154 0 1 1 163.615412-164.419599 29.974227 29.974227 0 0 1-42.841211 9.357807z m-537.342843-398.584106c7.164571-7.091463 24.71046-9.650239 33.26408 0 10.600641 11.185504 7.164571 29.462472 0 37.138798l-110.612207 107.468569L370.901811 576.14119c7.164571 7.091463 8.114974 27.342343 0 35.384209-9.796455 9.723347-29.828011 8.188081-36.480827 1.535265L208.309909 487.388236a18.423183 18.423183 0 0 1 0-25.953294l132.032813-132.032813z m343.314556 0l132.032813 132.032813a18.423183 18.423183 0 0 1 0 25.953294L689.652124 613.133772c-6.652816 6.579708-25.587754 10.746857-36.553935 0-10.30821-10.235102-7.091463-31.290168 0-38.381632l108.345863-100.669537-111.855041-108.638294c-7.164571-7.676326-9.504023-26.611265 0-36.04218 9.284699-9.138484 26.903696-7.091463 34.068267 0z m-135.54199-26.318833c3.582286-9.504023 21.347498-15.498868 32.679217-11.258612 10.819965 4.020933 17.180349 19.008046 14.256035 28.512069l-119.896906 329.716493c-3.509178 9.504023-20.616419 13.305632-30.193551 9.723347-10.161994-3.509178-21.201282-17.545889-17.545888-26.976804l120.627985-329.716493z">
            </svg>
        </div>

        <div class="main_nav">
            <button class="main_nav_btn top_btn_a"><a href="dblist.php">DATABASE</a></button>
            <button class="main_nav_btn top_btn_b active" ><a href="">TABLE</a></button>
            <button class="main_nav_btn top_btn_c"><a href="insert.php">INSERT</a></button>
            <button class="main_nav_btn top_btn_d"><a href="query.php">SQL</a></button>
        </div>

        <div class="main_ui">
            
            <?php
            if($result_db == 0){
                array_push($_SESSION['notif'], "Please Create a Database First");
                array_push($_SESSION['notif'], "No Database Available");
                header("location: dblist.php");
            }

                $g_db     = $_SESSION['dbname'];
                $g_tbl    = $_SESSION['tblname'];

                $query  = "SHOW COLUMNS FROM `$g_db`.`$g_tbl`;";
                $fetch  = mysqli_query($key, $query);
                $col    = array();
                $header = mysqli_num_rows($fetch);
                echo '
            <div class="table_top">
                <p class="table_title">Table: '.$g_tbl.' </p>
                <svg class="table_edit" onclick="toggle_edit(this)" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier"> 
                        <path d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> 
                        <path d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> 
                    </g>
                </svg>
                <input id="table_search" class="table_search" onkeyup="search(this.value, this.id)" type="text" spellcheck="false" placeholder="Search...">
            </div>
            <p id="edit_n" class="table_edit_notif"> Edit mode: Click on any cell to edit.</p>
            <div class="table-container">
            <table>
                <tr>
                ';
                while($columns  = mysqli_fetch_assoc($fetch)) {
                    $current    = $columns['Field'];
                    array_push($col, $current);
                    echo '
                    <th>'.$current.'</th>
                    ';
                }
                echo '
                </tr>
                <tbody id="table_content">
                ';

                $query  = "SELECT * FROM `$g_db`.`$g_tbl`;";
                $fetch  = mysqli_query($key, $query);
                while ($rows   = mysqli_fetch_assoc($fetch)){
                    $i = 0;
                    echo '
                <tr>
                    ';
                    while ($i < $header) {
                        $c = $col[$i++];
                        echo '
                    <td spellcheck="false" class="" id="'.$c.'" onclick="edit(this)" onfocusout="reset(this)" onkeydown="stop(event, this)">'.$rows[$c].'</td>
                        ';

                    }
                    echo '
                </tr>
                    ';

                }
                echo '
                </tbody>
            </table>
                ';

            


            ?>
        </div>
        
        
        <footer></footer>
        <script>
            var temp    = "";
            var tbody   = document.getElementById("table_content");
            var content = tbody.innerHTML;
            var edit_t  = false;
            
            function closeNotif(i) {
                i.style.display = "none";
            }

            function search(str) {
                if (str.length == 0 || str == null) {
                    tbody.innerHTML = content;
                } else {
                    var fetch = new XMLHttpRequest();
                    fetch.open("POST", "main.php", true);
                    fetch.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                    fetch.send("q="+str);

                    fetch.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            var data = JSON.parse(this.responseText);
                            if (data.length > 0) {
                                const header = Object.keys(data[0]);

                                var html = "";
                                for (var a = 0; a < data.length; a++){
                                    html += "<tr>";
                                    for (var b = 0; b < header.length; b++){
                                        var temp = header[b];
                                        html += '<td spellcheck = "false" id = '+ temp.replace(" ", "_") +' onclick = "edit(this)" onfocusout = "reset(this)" onkeydown = "stop(event, this)">' + data[a][temp] + "</td>";
                                    }
                                    html += "</tr>";
                                }
                                document.getElementById("table_content").innerHTML = html;
                            } else {
                                html = "<tr><td colspan='5' style='text-align: center;'>No Match Found</td></tr>";
                                document.getElementById("table_content").innerHTML = html;
                            }
                        }
                    }
                }
            }

            function stop(evt, e) {
                if (evt.key === 'Enter') {
                    evt.preventDefault();
                    var old     = temp;
                    temp        = "";

                    var update  = new XMLHttpRequest();
                    var value   = e.innerHTML;
                    var key     = e.id.replace("_", " ");
                    e.id        = "temp";

                    update.open("POST", "main.php", true);
                    update.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                    update.send("update="+ value + "&key=" + key + "&old=" + old );
                    
                    update.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200 && this.responseText != "true") {
                            var text = "<p>" + this.responseText + "</p>";
                            var html = `
                <div id='notif' class='notif' onclick='closeNotif(this)'>
                    <div class='alert_box'>
                        <h1>NOTIFICATION</h1>
                        <div class='alerts'>
                            <div id='alert' class = 'sub'>
                                ` + text + `
                            </div>
                        </div>
                        <p>Click anywhere to close </p>
                    </div>
                </div>
                            `;

                            var notif = document.getElementById('notif');

                            if (!notif){
                                document.getElementById("body").innerHTML += html;
                            } else {
                                notif.style = "display: flex;"
                                document.getElementById('alert').innerHTML = text;
                            }
                            document.getElementById("temp").innerHTML = old;
                        }
                    document.getElementById("temp").id = key; 
                    e.removeAttribute('contenteditable');
                    content = tbody.innerHTML;
                    }
                }
            }

            function reset(e) {
                if (temp != "") {
                    e.innerHTML = temp;
                    temp = "";
                }
            }

            function toggle_edit(e) {
                if (edit_t == false) {
                    e.style = `border: 1px solid #1f3e55; background-color: #34707d;`;
                    document.getElementById("edit_n").style = "display: inherit";
                    edit_t  = true;
                } else {
                    e.style = `border: none; background-color: none;`;
                    document.getElementById("edit_n").style = "display: none";
                    edit_t  = false;
                }
            }

            function edit(e) {
                if (edit_t == true) {
                    temp = e.innerHTML;
                    e.setAttribute("contenteditable", true);
                    e.focus();
                    console.log(e.id, temp);
                }

            }
        </script>



    </body>
</html>

