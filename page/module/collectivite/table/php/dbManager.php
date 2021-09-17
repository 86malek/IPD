<?php
include '../../../../../config/dbc.php';
page_protect();

if(isset($_GET['action'])){
    //start saving or loading
    switch( $_GET['action']){
        case "save": if(isset($_POST['state'] )) saveState($_POST["name"],$_POST["state"],$bdd);break;
        case "load": loadState($_POST["name"],$bdd);break;
    }
}

?>