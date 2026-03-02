<?php
try {
            $conn = new PDO('mysql:host=localhost;dbname=campus_it','campus_user', 'BusiKenMae1');
        }catch(Exception $e){
            die($e->getmessage());
        }
?>
