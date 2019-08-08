<?php
require 'read.php';
$dealer = R::dispense('dealer');
$dealer['name'] = 'admin';
$dealer['email'] = 'jonasbruus@hotmail.com';
$dealer['password'] = password_hash('Adminlog', PASSWORD_DEFAULT);
$dealer['admin'] = true;
R::store($dealer);