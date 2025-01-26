<?php

$router = new Router();
$router->get('/', function (){
    header("Content-type: html");
    echo "Welcome to guziiuchyk@gmail.com API!";
});