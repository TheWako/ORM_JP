<?php

require('./src/Entity/User.php');

$user = new User();
$user->FName = 'NO';
$user->LName = 'One';
$user->Age = 24;
$user->add();
echo 'Saved changes successfully';