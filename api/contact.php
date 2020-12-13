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
        echo (new Controller('users', $db_table , $fillables))->update();
        return;
    }
    
    if (isset($_GET['delete'])) {
        echo (new Controller('users', $db_table))->delete();
        return;
    }

    if (isset($_GET['id'])) {
        echo (new Controller('users', $db_table))->readSingle();
        return;
    }

    if(isset($_GET['all'])){
        echo (new Controller('users', $db_table))->read();
    }

    echo (new Controller('users', $db_table , $fillables))->create();

