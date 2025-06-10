<?php include("main.php") ?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" type="image/gif" href="resource/1.png">
        <title>UNO.Admin</title>
        <link rel="stylesheet" id = "theme" type="text/css" href="css/main.css">
    </head>
    <body>
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
        
        <div class="main" id="main">
            <img src="resource/5.png" class="login_logo"/>
            <button class="side_btn btn_a" id="side_btn_a" onclick="SwitchLogin()"><h1 class="side_btn_a">LOGIN</h1></button>
            <button class="side_btn btn_b" id="side_btn_b" onclick="SwitchReg()"><h1 class="side_btn_b">REGISTER</h1></button>
            
            <div id="a_container" class="displayed" >
            <form class="form a_container" id="a-form" method="post" action="main.php">
                <!-- <h1 class="form_close" onclick="ToggleLogin()">X</h1> -->
                <h2 class="form_title a_container">Sign in to UNO.Admin</h2>
                <input class="form_input a_container" type="text" name="username" placeholder="Username" required>
                <input class="form_input a_container" type="password" id="password1" onkeypress="show_icon()" onblur="hide_icon()" onfocus="show_icon()" name="password" placeholder="Password" required>
                <img src="resource/show.png" class="show_pass" id="show_pass1" onclick="toggle_pass1()">
                <button class="form_button a_container" name="login" type="submit" style="color:white;">SIGN IN</button>
            </form>
            </div>

            <div class="hidden" id="b_container">
            <form class="form" id="a-form" method="post" action="main.php">
                <!-- <h1 class="form_close" onclick="ToggleReg()">X</h1> -->
                <h2 class="form_title">Create new User</h2>
                <input class="form_input" type="text" name="username" placeholder="Username" required>
                <input class="form_input" type="password" id="password2" onkeypress="show_icon()" onblur="hide_icon()" onfocus="show_icon()" name="password" placeholder="Password" required>
                <img src="resource/show.png" class="show_pass" id="show_pass2" onclick="toggle_pass2()">
                <button class="form_button button submit-btn" name="register" type="submit" style="color:white;">REGISTER</button>
            </form>
            </div>
        </div>

        <script>

            const a = document.getElementById("a_container");
            const b = document.getElementById("b_container");
            const c = document.getElementById("side_btn_a");
            const d = document.getElementById("side_btn_b");

            function SwitchLogin() {
                // Displays Login Form
                a.setAttribute('class', 'displayed');
                // Hides Register Form
                b.setAttribute('class', 'hidden');

                document.getElementById("side_btn_a").style.backgroundColor = "#2f4e65";
                document.getElementById("side_btn_a").style.color = "#f9f9f9";

                document.getElementById("side_btn_b").style.backgroundColor = "#f5f5f5";
                document.getElementById("side_btn_b").style.color = "#666";
                
            }

            function SwitchReg() {
                // Displays Register Form
                b.setAttribute('class', 'displayed');
                // Hides Login Form
                a.setAttribute('class', 'hidden');

                document.getElementById("side_btn_b").style.backgroundColor = "#2f4e65";
                document.getElementById("side_btn_b").style.color = "#f9f9f9";

                document.getElementById("side_btn_a").style.backgroundColor = "#f5f5f5";
                document.getElementById("side_btn_a").style.color = "#666";
            }

            function hide_icon() {
                window.onclick = function (event) {
                    var icon1 = document.getElementById("show_pass1");

                    if (event.target !== icon1) {
                        document.getElementById("show_pass1").style.display = "none";
                        var p1 = document.getElementById("password1");
                        document.getElementById("show_pass1").setAttribute("src", "resource/show.png");
                        p1.setAttribute("type", "password");
                    }
                    var icon2 = document.getElementById("show_pass2");

                    if (event.target !== icon2) {
                        document.getElementById("show_pass2").style.display = "none";
                        var p2 = document.getElementById("password2");
                        document.getElementById("show_pass2").setAttribute("src", "resource/show.png");
                        p2.setAttribute("type", "password");
                    }
                }
    
            }

            function show_icon() {
                window.onclick = null;
                document.getElementById("show_pass1").style.display = "flex";
                document.getElementById("show_pass2").style.display = "flex";
            }

            function toggle_pass1() {
                var p1 = document.getElementById("password1");
                if (p1.getAttribute("type") == "password") {
                    p1.setAttribute("type", "text");
                    document.getElementById("show_pass1").setAttribute("src", "resource/hide.png");
                }

                else {
                    p1.setAttribute("type", "password");
                    document.getElementById("show_pass1").setAttribute("src", "resource/show.png");
                
                }
            }

            function toggle_pass2() {
                var p2 = document.getElementById("password2");
                if (p2.getAttribute("type") == "password") {
                    p2.setAttribute("type", "text");
                    document.getElementById("show_pass2").setAttribute("src", "resource/hide.png");
                }

                else {
                    p2.setAttribute("type", "password");
                    document.getElementById("show_pass2").setAttribute("src", "resource/show.png");
                
                }
            }

            function closeNotif(i) {
                i.style.display = "none";
            }
        </script>

    </body>
</html>