<?php
function dbConnect($usertype, $connectionType = 'mysqli') {
    $host = 'mysql.briankallie.com';
    $db = 'blog42';
    if ($usertype  == 'read') {
        $user = 'briankallie';
        $pwd = 'cDVFr-ZJw*o4fp-Po4Qc';
    } elseif ($usertype == 'write') {
        $user = 'briankallie';
        $pwd = 'cDVFr-ZJw*o4fp-Po4Qc';
    } else {
        exit('Unrecognized user');
    }
    if ($connectionType == 'mysqli') {
        $conn = @ new mysqli($host, $user, $pwd, $db);
        if ($conn->connect_error) {
            exit($conn->connect_error);
        }
        $conn->set_charset('utf8mb4');
        return $conn;
    } else {
        try {
            return new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}