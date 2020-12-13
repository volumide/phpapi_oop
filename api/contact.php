<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT, POST, GET, DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
   
    include '../config/Database.php';
    include '../models/Contacts.php';

    $db_table = (new Database())::connect();

    $fillables = [
        "firstname",
        "lastname",
        "assembly",
        "email",
        "phone",
        "confirmed"
    ];

    if (isset($_GET['update'])) {
        echo (new Contacts('users', $db_table , $fillables))->update();
        return;
    }
    
    if (isset($_GET['delete'])) {
        echo (new Contacts('users', $db_table))->delete();
        return;
    }

    if (isset($_GET['id'])) {
        echo (new Contacts('users', $db_table))->getSingle();
        return;
    }

    if(isset($_GET['all'])){
        echo (new Contacts('users', $db_table))->getMessage();
    }

    echo (new Contacts('users', $db_table , $fillables))->sendMessage();

