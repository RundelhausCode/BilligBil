<?php
require 'read.php';
$brand = R::dispense('brand');
$brand['name'] = 'skoda';
R::store($brand);

$category = R::dispense('category');
$category['type'] = 'car';
R::store($category);

$fuel = R::dispense('fuel');
$fuel['name'] = 'petrol';
R::store($fuel);

$color = R::dispense('color');
$color['color'] = 'green';
R::store($color);

$dealer = R::dispense('dealer');
$dealer['name'] = 'odense dealer';
$dealer['email'] = 'info@odl.dk';
$dealer['password'] = password_hash('Aa123456&', PASSWORD_DEFAULT);
$dealer['admin'] = false;
R::store($dealer);

$model = R::dispense('model');
$model['name'] = 'Fabia';
$model['engine'] = 3.1;
$model['doors'] = 5;
$model->brand =$brand;
$model->category = $category;
$model->fuel = $fuel;
R::store($model);

$car = R::dispense('car');
$car->model = $model;
$car['picture'] = 'pic/Skoda_Fabia.jpg';
$car['km'] = '20000';
$car['price'] = '150000';
$car['age'] = '2018-01-01';
$car['sold'] = false;
$car->color = $color;
$car->dealer = $dealer;
R::store($car);

$comments = R::dispense('comments');
$comments['text'] = 'this is a very long exampel whit lots of text for my car side ond ohter stuf in the fyter';
$comments['name'] = 'Jake testname';
$comments['approved'] = false;
$comments->car = $car;
R::store($comments);