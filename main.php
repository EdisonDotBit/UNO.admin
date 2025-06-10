<?php

session_start();

// declares session variables if variable is not set
$var = array("username", "password", "dbname", "tblname", "create_db", "create_tbl", "column_num");
foreach($var as $x) {
    if(!isset($_SESSION[$x])){
        $_SESSION[$x] = "";
    }
}

// initializing variables
$username   = "";
$password   = "";

if(!isset($_SESSION['notif'])){
    $_SESSION['notif']  = array();
}

// root login
$root       = mysqli_connect('localhost', 'root', '');
$netMan     = "CREATE DATABASE IF NOT EXISTS netman01";
mysqli_query($root, $netMan);


//key set
if(!isset($key)){
    if ($_SESSION['username'] != ""  && $_SESSION['password'] != "") {
        $key = mysqli_connect('localhost', $_SESSION['username'], $_SESSION['password']);
    }
}

// user registration
if (isset($_POST['register'])) {
    $username   =  $_POST['username'];
    $password   =  $_POST['password'];

    // check if a user with the same username already exists
    $query  = "SELECT user FROM mysql.user WHERE user = '$username' LIMIT 1";
    $check  = mysqli_query($root, $query);
    $result = mysqli_num_rows($check);

    if ($result > 0) {
        array_push($_SESSION['notif'], "User already Exist");
        header("location: front_page.php");
    } else {
        $query = "CREATE TABLE netman01.`$username` (DBNAME VARCHAR(999));";
        mysqli_query($root, $query);

        $query = "CREATE USER `$username`@localhost IDENTIFIED BY '$password';";
        mysqli_query($root, $query);
        $query = "GRANT ALL PRIVILEGES ON *.* TO `$username`@localhost REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;";
        mysqli_query($root, $query);

        array_push($_SESSION['notif'], "Welcome to UNO.Admin ".$username);
        array_push($_SESSION['notif'], "User Successfully Created");
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header('location: dblist.php ');
        
    }
}


// user login
if (isset($_POST['login'])) {
    $username   =  $_POST['username'];
    $password   =  $_POST['password'];

    // check if user exists
    $query  = "SELECT user FROM mysql.user WHERE user = '$username' LIMIT 1";
    $check  = mysqli_query($root, $query);
    $result = mysqli_num_rows($check);

    if ($result == 0) {
        array_push($_SESSION['notif'], "User ".$username." does not exist");
        header("location: front_page.php");
    } else {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header('location: dblist.php');
       }    
}

// user logout
if(isset($_GET['logout'])){
    if($_GET['logout'] == 'true') {
        $_SESSION['username'] = "";
        $_SESSION['password'] = "";
    }
    session_destroy();
    header("location: front_page.php");
}

// MAIN

// CREATE DATABASE
if (isset($_POST['c_db'])) {
    $dbname     = $_POST['dbname'];
    $username   = $_SESSION['username'];
    
    $query  = "USE netman01;";
    $check  = mysqli_query($root, $query);

    $query  = "SELECT * FROM `$username` WHERE DBNAME LIKE '$dbname';";
    $check  = mysqli_query($root, $query);
    $result = mysqli_num_rows($check);
    
    if ($result > 0 ) {
        array_push($_SESSION['notif'], "Database with the name \"".$dbname."\" already exist.");
        header("location: dblist.php");
    }

    else {
        $query = "INSERT INTO netman01.`$username` (DBNAME) VALUES ('$dbname');";
        mysqli_query($root, $query);

        $query = "CREATE DATABASE `$dbname`;";
        mysqli_query($key, $query);

        array_push($_SESSION['notif'], "Database successfully created.");
        header('location: dblist.php');
    }
}

//CREATE TABLE
if (isset($_POST['c_table'])) {
    $dbname     = $_POST['dbname'];
    $temp       = $_POST['table_name'];
    $query      = "SHOW TABLES FROM `$dbname` LIKE '$temp'";
    $fetch_db   = mysqli_query($key, $query);
    $result_db  = mysqli_num_rows($fetch_db);

    if ($result_db == 0) {
        $_SESSION['dbname']     = $_POST['dbname'];
        $_SESSION['create_tbl'] = $_POST['table_name'];
        $_SESSION['column_num'] = $_POST['column'];
    } else {
        array_push($_SESSION['notif'], "A Table with the name ".$_POST['table_name']." already exists");
    }
    header('location: tblist.php');
}

if (isset($_POST['create_tbl'])){
    $username   = $_SESSION['username'];
    $dbname     = $_SESSION['dbname'];
    $tblname    = $_SESSION['create_tbl'];
    $i          = $_SESSION['column_num'];
    $desc       = "";

    while ($i > 0) {
        $extra  = "";
        if(isset($_POST['tbl_extra_'.$i]) && $_POST['tbl_index_'.$i] != ""){
            $extra   = $_POST['tbl_extra_'.$i];
        }
        if($_POST['tbl_type_'.$i] == 'DATE' || $_POST['tbl_length_'.$i] == ''){
            $typeLen = $_POST['tbl_type_'.$i];
        } else {
            $typeLen = $_POST['tbl_type_'.$i].'('.$_POST['tbl_length_'.$i].')';
        }
        $data    = '`'.$_POST['tbl_name_'.$i].'` '.$typeLen.' '.$_POST['tbl_index_'.$i].' '.$extra;
        if($i > 1){
            $desc   = $desc.$data.', ';
        } else {
            $desc   = $desc.$data;
        }
        $i--;
        echo $desc.'<br>';
    }

    $query  = "CREATE TABLE `$dbname`.`$tblname` ($desc);";
    mysqli_query($key, $query);

    $_SESSION['create_tbl'] = "";
    $_SESSION['column_num'] = "";
    array_push($_SESSION['notif'], '"'.$username.'" connected successfully');
    array_push($_SESSION['notif'], '"'.$tblname.'" table created successfully');
    header("location: tblist.php");
}

if (isset($_GET['clear'])) {
    $_SESSION['create_tbl'] = "";
    $_SESSION['column_num'] = "";
    header("location: tblist.php");
}

// Display Data
if (isset($_GET['show'])) {
    $_SESSION['dbname']     = $_GET['current_db'];
    $_SESSION['tblname']    = $_GET['show'];
    header("location: display.php");
}

//Insert Data
if (isset($_GET['newtable'])) {
    $_SESSION['tblname'] = $_GET['newtable'];
    header('location: insert.php');
}

if (isset($_GET['newdb'])) {
    $_SESSION['dbname']     = $_GET['newdb'];
    $_SESSION['tblname']   = "";
    header('location: insert.php');
}

if (isset($_POST['insert'])) {
    $db     = $_SESSION['dbname'];
    $tbl    = $_SESSION['tblname'];
    $query  = "SHOW COLUMNS FROM `$db`.`$tbl` ;";
    $fetch  = mysqli_query($key, $query);
    $col    = array();
    $insert = array();
    $header = mysqli_num_rows($fetch);
    while($columns  = mysqli_fetch_assoc($fetch)) {
        if ($columns['Key']!="PRI") {
            $current    = $columns['Field'];
            array_push($col, '`'.$current.'`');
            array_push($insert, $_POST[str_replace(' ', '_', $columns['Field'])]);
        }
    }

    $str1   = "";
    $str2   = "";

    while (count($col) > 1) {
        $str1   = $str1.array_pop($col).', ';
        $str2   = $str2."'".array_pop($insert)."'".', ';
    }

    $str1   = $str1.array_pop($col);
    $str2   = $str2."'".array_pop($insert)."'";

    $query  = "INSERT INTO `$db`.`$tbl` ($str1) VALUES ($str2)";
    mysqli_query($key, $query);

    array_push($_SESSION['notif'], "Data saved successfully");
    header("location: insert.php");
    
}

// Update
if (isset($_POST['update'])) {
    $val    = $_POST['update'];
    $col    = $_POST['key'];
    $old    = $_POST['old'];
    $db     = $_SESSION['dbname'];
    $tbl    = $_SESSION['tblname'];

    $query  = "UPDATE `$db`.`$tbl` SET `$col` = '$val' WHERE `$col` = '$old'";
    if (!mysqli_query($key, $query)) {
        echo mysqli_errno($key);
    } else {
        echo "true";
    }
}

// Search
if (isset($_POST['q'])) {
    $db     = $_SESSION['dbname'];
    $tbl    = $_SESSION['tblname'];
    $q      = $_POST['q'];

    $query  = "SHOW COLUMNS FROM `$db`.`$tbl`;";
    $fetch  = mysqli_query($key, $query);
    $rows   = array();
    while ($col = mysqli_fetch_assoc($fetch)) {
        array_push($rows, $col['Field']);
    }

    $str    = '`'.array_pop($rows).'` LIKE "%'.$q.'%"';
    while (count($rows) > 0){
        $str    = $str.'OR `'.array_pop($rows).'` LIKE "%'.$q.'%"';
    }

    // echo $db.'<br />'.$tbl.'<br />'.$str.'<br />';
    $data   = array();
    $query  = "SELECT * FROM `$db`.`$tbl` WHERE $str";
    $fetch  = mysqli_query($key, $query);
    while ($row = mysqli_fetch_object($fetch)) {
        array_push($data, $row);
    }

    echo json_encode($data);
}

if (isset($_POST['sql'])) {
    $query  = $_POST['query'];
    $sql    = mysqli_query($key, $query);
    if ($sql){
        if (mysqli_num_rows($sql) > 0) {
            $_SESSION['query'] = $query;
            header("location: query.php");

        } else {
            array_push($_SESSION['notif'], "Success");
            header("location: query.php");
        }
    } else {
        array_push($_SESSION['notif'], "Success");
        header("location: query.php");
    }
}



?>