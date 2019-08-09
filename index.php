<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BilligBil</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/styler.css">
    <link rel="stylesheet" href="css/fun.scss">
    <link rel="stylesheet" type="text/css" href="Table/datatables.css"/>
</head>
<body>
<?php
require 'read.php';
session_start();
?>
<script src="js/script.js"></script>
<div class="top-bar">
    <div class="top-bar-left">
        <ul class="dropdown menu" data-dropdown-menu>
            <li class="menu-text">BilligBil</li>
            <li><a href="?home">Hjem</a></li>
            <li>
                <a href="?acar">Alle  Biller</a>
                <ul class="menu vertical">
                    <li><a href="?pcar">Personbil</a></li>
                    <li><a href="?vcar">Varevogne</a></li>
                    <li><a href="?dcar">Vragt</a></li>
                </ul>
            </li>
            <li><a id="logbot" href="?login">Login/Logout</a></li>
        </ul>
    </div>
    <div class="top-bar-right">
        <?php
        if($_SESSION['admin'] == 1){
        echo '<ul class="dropdown menu" data-dropdown-menu>';

        echo '<li>';
        echo '<a>Admin</a>';
        echo '<ul class="menu vertical">';
        echo '<li><a href="?ncar">Ny bil</a></li>';
        echo '<li><a class="" href="?chat">godkend eller slet kommentarer</a></li>';
        echo '</ul>';
        echo '</li>';
        echo '</ul>';
        }
        ?>
    </div>
</div>
<div id="main">
    
    <?php
    if (isset($_GET['home'])){
        home();
    }
    if (isset($_GET['login'])){
        login();
    }
    function acar(){
        $m = R::findAll('model');
        datain($m);
    }
    if (isset($_GET['card'])){
        carDetails($_GET);
    }
    if (isset($_GET['acar'])){
        acar();
    }
    function pcar(){
        $m = R::find('model','category_id = 1');
        datain($m);
    }
    if (isset($_GET['pcar'])){
        pcar();
    }
    function vcar(){
        $m = R::find('model','category_id = 3');
        datain($m);
    }
    if (isset($_GET['vcar'])){
        vcar();
    }

    function dcar(){
        $m = R::find('model','category_id = 2');
        datain($m);
    }
    if (isset($_GET['dcar'])){
        vcar();
    }
    if(array_key_exists('logs',$_POST)){
        logs($_POST);
    }
    if (isset($_GET['chat'])){
        chatadmin();
    }
    if (isset($_GET['ncar'])){
        newcar();
    }
    if(isset($_GET['rcar'])){
        carste($_GET);
    }
    if(isset($_POST['ncwm']))
    {
        NewCarWModel($_POST);
    }
    if(isset($_POST['ncom']))
    {
        NewCarOModel($_POST);
    }
    if (isset($_POST['ced'])){
        editcar($_POST);
    }
    function home(){
        echo '<h1 class="text-center">Velkommen til Billigbil</h1>';
        echo '<div class="grid-container">';
        echo '<div class="grid-x grid-padding-x small-up-2 medium-up-3">';
        $forary = array(1,2,3);
        foreach ($forary as &$value){
            $m = R::findOne('model','category_id ='.$value.' ORDER BY rand()');
            $c = R::findOne('car','model_id ='.$m->id.' ORDER BY rand()');
            card(''.R::findOne('brand','id='.$m->brand_id)->name.' '.$m->name,'pic/'.$c->picture,''.$c->price,''.$c->id);
        }
        echo '</div>';
        echo '</div>';
    }
    function card($name,$pic,$price,$id){
        echo '<div class="cell">';
        echo '<div class="card">';
        echo '<a href="?card='.($id).'"><img src="'.$pic.'"></a>';
        echo '<div class="card-section">';
        echo '<h4>'.$name.' '.$id.'</h4>';
        if($_SESSION['loggedin'] == true){
            echo '<p>price: '.$price/100*90 .' kr.</p>';
        }
        else{
            echo '<p>price: '.$price.' kr.</p>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    function login(){

        if (!$_SESSION['loggedin'] == true)
        {
            echo '<form class="log-in-form" method="post">';
            echo '<h4 class="text-center">Log in med din email</h4>';
            echo '<label>Email';
            echo '<input type="text" placeholder="exempel@exempel.dk" name="email">';
            echo '</label>';
            echo '<label>Password';
            echo '<input type="password" placeholder="Kode" id="pass" name="pass">';
            echo '</label>';
            echo '<input id="show-password" type="checkbox" onclick="pws()"><label for="show-password"  >Vis kode</label>';
            echo '<p><input type="submit" class="button expanded" name="logs" id="logs" value="Log in"></input></p>';
            echo '</form>';
            echo '<script type="text/javascript">',
            ' logch();',
            '</script>'
            ;


        }
        else
        {
            $_SESSION = session_destroy();

            header("Location: ?home");
        }

    }
    function logs($data){
        $use = R::findOne('dealer','email = ?',[$data['email']]);
        if(is_null($use)){
            echo '<font color="red"><p>wrong email</p></font>';
        }
        elseif(password_verify($data['pass'],$use->password)){


            $_SESSION['loggedin'] = true;
            $_SESSION['name'] = $use->name;
            $_SESSION['id'] = $use->id;
            $_SESSION['admin'] = $use->admin;
            echo '<script type="text/javascript">',
            ' logch();',
            '</script>'
            ;
            header('Location:?home');

        }
        else{
            echo '<font color="red"><p>wrong password</p></font>';
        }
    }



    function datain($models){
        echo '<table id="data" class="display" style="width:100%">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>car id</th>';
        echo '<th>Model</th>';
        echo '<th>Brand</th>';
        echo '<th>engine</th>';
        echo '<th>color</th>';
        echo '<th>Age</th>';
        echo '<th>Doors</th>';
        echo '<th>km. drived</th>';
        echo '<th>price</th>';
        echo '<th>type</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($models as $modle) {
            $cars = R::find('car','model_id ='.$modle->id);
            foreach ($cars as $car) {
                echo '<tr>';
                echo '<td>'.$car->id.'</td>';
                echo '<td>'.$modle->name.'</td>';
                echo '<td>'.R::findOne('brand','id='.$modle->brand_id)->name.'</td>';
                echo '<td>'.$modle->engine.'</td>';
                echo '<td>'.R::findOne('color','id='.$car->color_id)->color.'</td>';
                echo '<td>'.$car->age.'</td>';
                echo '<td>'.$modle->doors.'</td>';
                echo '<td>'.$car->km.'</td>';
                if($_SESSION['loggedin'] == true){echo '<td>'.$car->price/100*90 .'</td>';}
                else{echo '<td>'.$car->price .'</td>';}
                echo '<td>'.R::findOne('category','id='.$modle->category_id)->type.'</td>';
                echo '</tr>';
            }
        }
        echo '</tbody>';
        echo '</table>';
    }
    function carDetails($data){
        $car = R::findOne('car','id='.$data['card']);
        $model = R::findOne('model','id='.$car->model_id);
        $color = R::findOne('color','id='.$car->color_id)->color;
        $dealer = R::findOne('dealer','id='.$car->dealer_id);
        $brand = R::findOne('brand','id='.$model->brand_id)->name;
        $type = R::findOne('category','id='.$model->category_id);
        $fuel = R::findOne('fuel','id='.$model->fuel_id)->name;
        $comments = R::find('comments','car_id='.$car->id);


        echo '<div class="grid-x grid-margin-x">';
        echo '<div class="cell small-2">';
        if ($_SESSION['admin'] == 1){echo '<a class="button" href="?rcar='.$car->id .'">Rediger/sælge/slet bil</a>';}

        echo '</div>';
        echo '<div class="cell small-6">';
        echo '<h1><center>'.$brand.' '.$model->name.'</center></h1>';
        echo '<div><img class="car-pic"  src="pic/'.$car->picture.'"></div>';
        echo '</div>';
        echo '<div class="cell small-4">';
        echo '<div style="padding-top: 70px">';
        echo '<table>';
        echo '<tbody>';
        echo '<tr>';
        echo '<td><b>km køret:</b></td>';
        echo '<td>'.$car->km.'</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td><b>alder:</b></td>';
        echo '<td>'.$car->age.'</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td><b>fave:</b></td>';
        echo '<td>'.$color.'</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td><b>dealer:</b></td>';
        echo '<td>'.$dealer->name.'</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td><b>mærke:</b></td>';
        echo '<td>'.$brand.'</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td><b>motor:</b></td>';
        echo '<td>'.$model->engine.'</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td><b>døre:</b></td>';
        echo '<td>'.$model->doors.'</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td><b>branstof type:</b></td>';
        echo '<td>'.$fuel.'</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td><b>pris:</b></td>';
        if($_SESSION['loggedin'] == true){echo '<td>'.$car->price/100*90 .'</td>';}
        else{echo '<td>'.$car->price.'</td>';}
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="grid-x grid-margin-x">';
        echo '<div class="cell small-6">';
        echo '<h3>comentare</h3>';
        foreach ($comments as $c){
            if ($c->approved == 1){
                echo '<p class="comment-box">'.$c->name.': '.$c->text.'</p>';
            }
        }

        echo '</div>';
        echo '<div class="cell small-6">';
        echo '<h3>skrive comenta</h3>';
        echo '<form action="" id="com" method="post">';
        echo ' name: <input type="text" name="name">';
        echo '</form>';
        echo 'comenta: <textarea rows="4" cols="50" name="comment" form="com">';
        echo '</textarea>';
        echo '<input type="submit" class="button" value="slå op">';
        echo '</div>';


    }
    function chatadmin(){
        if($_SESSION['admin'] == 1){
            echo 'admin log in';
        }else{header('Location: ?home');}

    }
    function newcar(){
        if($_SESSION['admin'] == 1){
            echo '<form action="" method="post" id="form1" enctype="multipart/form-data">';
            echo '<h2>Oprat ny bil</h2>';
            echo '<h3>eksterne model</h3>';
            echo '<div class="grid-x grid-margin-x">';
            echo '<div class="cell small-6">';
            echo '<label >model: ';
            echo '<select form="form1" name="model" style="width: auto">';
            echo '<option disabled selected value> -- vælge en model-- </option>';
            $model = R::findAll('model');
            foreach ($model as $m){
                echo '<option value="'.$m->id.'">'.$m->name.'</option>';
            }
            echo '</select>';
            echo '</label>';
            echo '<label> Bilede: ';
            echo '<input type="file" name="pic" style=" width: 200px; display: inline;">';
            echo '</label>';

            echo '<label> kørte kilometer: ';
            echo '<input type="number" name="km" style=" width: 200px; display: inline;">';
            echo '</label>';

            echo '<label> Pris: ';
            echo '<input type="number" name="price" style=" width: 200px; display: inline;">';
            echo '</label>';
            echo '</div>';
            echo '<div class="cell small-6">';
            echo '<label> Alger ';
            echo '<input type="month" name="age" style=" width: 200px; display: inline;">';
            echo '</label>';

            echo '<label >Fave: ';
            echo '<select form="form1" name="color" style="width: auto">';
            echo '<option disabled selected value> -- vælge en fave -- </option>';
            $color = R::findAll('color','ORDER BY color');
            foreach ($color as $c){
                echo '<option value="'.$c->id.'">'.$c->color.'</option>';
            }
            echo '</select>';
            echo '</label>';

            echo '<label >Sælge: ';
            echo '<select form="form1" name="dealer" style="width: auto">';
            echo '<option disabled selected value> -- vælge en sælge -- </option>';
            $dealer = R::findAll('dealer','ORDER BY name');
            foreach ($dealer as $d){
                echo '<option value="'.$d->id.'">'.$d->name.'</option>';
            }
            echo '</select>';
            echo '</label>';
            echo '<input type="submit" class="button" value="oprat bil" name="ncom" id="ncom">';
            echo '</div>';
            echo '</div>';
            echo '</form>';
            ////////////////new model//////////////////////

            echo '<hr>';
            echo '<h3>Ny model</h3>';
            echo '<form action="" method="post" id="form2" enctype="multipart/form-data">';
            echo '<div class="grid-x grid-margin-x">';

            echo '<div class="cell small-6">';

            echo '<label >Model navn: ';
            echo '<input type="text" name="name" style=" width: 200px; display: inline;">';
            echo '</label>';

            echo '<label >bil type: ';
            echo '<select name="type" form="form2" style="width: auto">';
            echo '<option disabled selected value> -- vælge en type -- </option>';
            $type = R::findAll('category');
            foreach ($type as $t){
                echo '<option value="'.$t->id.'">'.$t->type.'</option>';
            }
            echo '</select>';
            echo '</label>';

            echo '<label >bil Mærke: ';
            echo '<select name="brand" form="form2" style="width: auto">';
            echo '<option disabled selected value> -- vælge et mærke-- </option>';
            $brand = R::findAll('brand');
            foreach ($brand as $b){
                echo '<option value="'.$b->id.'">'.$b->name.'</option>';
            }
            echo '<option value="-1">--Nyt Mærke--</option>';
            echo '</select>';
            echo 'Nyt mærke navn: <input type="text" name="nbrand" style=" width: 200px; display: inline;">';
            echo '</label>';


            echo '<label> Mortor: ';
            echo '<input type="number" step="0.01" min="0" max="10" name="engine" style=" width: 200px; display: inline;">';
            echo '</label>';

            echo '<label> Døre: ';
            echo '<input type="number" name="doors" style=" width: 200px; display: inline;">';
            echo '</label>';

            echo '<label >Branstof type: ';
            echo '<select name="fuel" form="form2" style="width: auto">';
            echo '<option disabled selected value> -- vælge et Branstof-- </option>';
            $fuel = R::findAll('fuel');
            foreach ($fuel as $f){
                echo '<option value="'.$f->id.'">'.$f->name.'</option>';
            }
            echo '</select>';
            echo '</label>';
            echo '</div>';
            echo '<div class="cell small-6">';
            echo '<label> Bilede: ';
            echo '<input type="file" name="pic" style=" width: 200px; display: inline;">';
            echo '</label>';

            echo '<label> kørte kilometer: ';
            echo '<input type="number" name="km" style=" width: 200px; display: inline;">';
            echo '</label>';

            echo '<label> Pris: ';
            echo '<input type="number" name="price" style=" width: 200px; display: inline;">';
            echo '</label>';


            echo '<label> Alger ';
            echo '<input type="month" name="age" style=" width: 200px; display: inline;">';
            echo '</label>';

            echo '<label >Fave: ';
            echo '<select form="form2" name="color" style="width: auto">';
            echo '<option disabled selected value> -- vælge en fave -- </option>';
            $color = R::findAll('color','ORDER BY color');
            foreach ($color as $c){
                echo '<option value="'.$c->id.'">'.$c->color.'</option>';
            }
            echo '</select>';
            echo '</label>';

            echo '<label >Sælge: ';
            echo '<select form="form2" name="dealer" style="width: auto">';
            echo '<option disabled selected value> -- vælge en sælge -- </option>';
            $dealer = R::findAll('dealer','ORDER BY name');
            foreach ($dealer as $d){
                echo '<option value="'.$d->id.'">'.$d->name.'</option>';
            }
            echo '</select>';
            echo '</label>';
            echo '<input type="submit" class="button" value="oprat bil" name="ncwm" id="ncwm">';
            echo '</div>';
            echo '</div>';
            echo '</form>';



        }else{header('Location: ?home');}
    }
    function NewCarWModel($data){
        var_dump($data);
        if($_SESSION['admin'] == 1){
            $model = R::dispense('model');
            $model['name'] = $data['name'];
            $model['engine'] = $data['engine'];
            $model['doors'] = $data['doors'];
                if($data['brand'] == -1){
                    $brand = R::dispense('brand');
                    $brand['name'] = $data['nbrand'];
                    $bId = R::store($brand);
                    $model['brand_id'] = $bId;
                }
                else{
                    $model['brand_id'] = $data['brand'];
                }

            $model['category_id'] = $data['type'];
            $model['fuel_id'] = $data['fuel'];
            $mId =R::store($model);


            $car = R::dispense('car');
            $car['model_id'] = $mId;
            $car['picture'] = $_FILES['pic']['name'];
            $car['km'] = $data['km'];
            $car['price'] = $data['price'];
            $car['age'] = $data['age'].'-01';
            $car['sold'] = false;
            $car['color_id'] = $data['color'];
            $car['dealer_id'] = $data['dealer'];
            R::store($car);
            uploadpic();
        }
    }
    function NewCarOModel($data){
        var_dump($data);
    if($_SESSION['admin'] == 1){
        $car = R::dispense('car');
        $car['model_id'] = $data['model'];
        $car['picture'] = $_FILES['pic']['name'];
        $car['km'] = $data['km'];
        $car['price'] = $data['price'];
        $car['age'] = $data['age'].'-01';
        $car['sold'] = false;
        $car['color_id'] = $data['color'];
        $car['dealer_id'] = $data['dealer'];
        R::store($car);
        uploadpic();
    }

    }

    function uploadpic(){
        if (($_FILES['pic']['name']!="")){
// Where the file is going to be stored
            $target_dir = "pic/";
            $file = $_FILES['pic']['name'];
            $path = pathinfo($file);
            $filename = $path['filename'];
            $ext = $path['extension'];
            $temp_name = $_FILES['pic']['tmp_name'];
            $path_filename_ext = $target_dir.$filename.".".$ext;

// Check if file already exists
            if (file_exists($path_filename_ext)) {
                echo "Sorry, file already exists.";
            }else{
                move_uploaded_file($temp_name,$path_filename_ext);

            }

        }
    }
    function carste($data){
        if($_SESSION['admin'] == 1){
            $car = R::findOne('car','id='.$data['rcar']);
            echo '<h1>opdatering af bilens data</h1>';
            echo '<form method="post" id="foed" enctype="multipart/form-data">';
            echo '<label>kørte km.: <input type="number" name="km" style="display: inline; width: 200px;" value="'.$car->km.'">';
            echo '<label>pris: <input type="number" name="price" style="display: inline; width: 200px;" value="'.$car->price.'">';
            echo '<label>alder: <input type="month" name="age" style="display: inline; width: 200px;" value="'.$car->age.'">';
            echo '<label><p><b>nu værene model = '.R::findOne('model','id='.$car->model_id)->name.'</b></p>';
            echo '<label >model: ';
            echo '<select form="foed" name="model" style="width: auto">';
            echo '<option disabled selected value> -- vælge en model-- </option>';
            $model = R::findAll('model');
            foreach ($model as $m){
                echo '<option value="'.$m->id.'">'.$m->name.'</option>';
            }
            echo '</select>';
            echo '</label>';
            echo '<label><p><b>nu værene fave = '.R::findOne('color','id='.$car->color_id)->color.'</b></p>';
            echo '<label >Fave: ';
            echo '<select form="foed" name="color" style="width: auto">';
            echo '<option disabled selected value> -- vælge en fave -- </option>';
            $color = R::findAll('color','ORDER BY color');
            foreach ($color as $c){
                echo '<option value="'.$c->id.'">'.$c->color.'</option>';
            }
            echo '</select>';
            echo '</label>';
            echo '<label><p><b>nu værene sælger = '.R::findOne('dealer','id='.$car->dealer_id)->name.'</b></p>';
            echo '<label >Sælge: ';
            echo '<select form="foed" name="dealer" style="width: auto">';
            echo '<option disabled selected value> -- vælge en sælge -- </option>';
            $dealer = R::findAll('dealer','ORDER BY name');
            foreach ($dealer as $d){
                echo '<option value="'.$d->id.'">'.$d->name.'</option>';
            }
            echo '</select></label>';
            echo '<label>solgt: ';
            if ($car->sold == '0'){
                echo '<input name="sold" type="checkbox">';
            }else{ echo '<input name="sold" type="checkbox" checked>';}
            echo '</label>';
            echo '<button class="button" type="submit" name="ced" id="ced" value="'.$car->id.'"> ænder data</button>';
            echo '</form>';





        }else{header('Location: ?home');}
        function editcar($data){
            $car = R::loadForUpdate('car',''.$data['ced']);
            $car['km'] = $data['km'];
            $car['price'] = $data['price'];
            $car['age'] = $data['age'];
            if ($data['model'] >0 ){
                $car['model_id'] = $data['model'];
            }
            if ($data['color'] >0 ){
                $car['color_id'] = $data['color'];
            }
            if ($data['dealer'] >0 ){
                $car['dealer_id'] = $data['dealer'];
            }
            if ($data['sold'] == 'on'){
                $car['sold'] = true;
            }else{$car['sold'] = false;}
            R::store($car);
            
        }
        function sold($id){
            echo '<h1>Test</h1>';
            var_dump($id);
            $car = R::loadForUpdate('car',''.$id['sold']);
            $car['sold'] = 1;
            R::store($car);
        }
    }
    ?>

</div>
<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/what-input.js"></script>
<script src="js/vendor/foundation.js"></script>
<script src="js/app.js"></script>
<script type="text/javascript" src="Table/datatables.js"></script>
<script src="js/script.js"></script>
<script type="text/javascript"> re(); </script>
</body>
</html>