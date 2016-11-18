<?php

require('./src/Entity/User.php');

$newEntity = new User();
$newEntity->FName = 'NO';
$newEntity->LName = 'One';
$newEntity->Age = 24;
$newEntity->add();
echo 'Saved changes successfully';