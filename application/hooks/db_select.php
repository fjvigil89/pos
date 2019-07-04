<?php
//Crea una sesion con el nombre de la base de datos utilizada en el Login
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    
    function db_select()
    {
        if( !isset($_SESSION['db_name'] ))
        {
            //Nombre de Base de datos
            $_SESSION['db_name'] = 'login';
        }
        if( !isset($_SESSION['db_name_api'] ))
        {
            //Nombre de Base de datos
            $_SESSION['db_name_api'] = 'inpos_phppos2';
        }
    }

