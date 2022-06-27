<?php
  session_start();
  require 'php/db_config.php'; 
  // check login, update the user profile
  if (!isset($_SESSION['logged']) || $_SESSION['logged']!=true) {
    echo "
    <script>
      alert('Please loggin');
      window.location.href = 'index.php';
    </script>
    ";
    exit();
  }
  else{
    $account = $_SESSION['account'];
    $name = $_SESSION['name'];
    $phone = $_SESSION['phone'];
    $latitude = $_SESSION['latitude'];
    $longitude = $_SESSION['longitude'];
    $balance = $_SESSION['balance'];

    // set onload event to show user profile
    echo "
    <script>
      window.onload = show_profile;
      function show_profile(){
        document.getElementById('user_profile').innerHTML = 'Account: $account, Name: $name, Phone: $phone, Location: (LONGITUDE: $longitude, LATITUDE: $latitude)';
        document.getElementById('username').innerHTML = '$account';
        document.getElementById('wallet_balance').innerHTML = 'Wallet Balance: <font id=\'user_balance\'>$balance</font>';
      }
    </script>
    ";
  }
  $query = "SELECT shopname, ST_AsText(location) AS location, category, account FROM shop WHERE account = '$account'";
  $have_shop = $conn->query($query);
  //initialse $_SESSION['s_xxx']
  $_SESSION['s_shopname'] = isset($_SESSION['s_shopname'])?$_SESSION['s_shopname'] :false;
  $_SESSION['s_shopname_d'] = isset($_SESSION['s_shopname_d'])?$_SESSION['s_shopname_d'] :false;
  $_SESSION['s_category'] = isset($_SESSION['s_category'])?$_SESSION['s_category'] :false;
  $_SESSION['s_category_d'] = isset($_SESSION['s_category_d'])?$_SESSION['s_category_d'] :false;
  $_SESSION['s_distance'] = isset($_SESSION['s_distance'])?$_SESSION['s_distance'] :false;
  $_SESSION['s_distance_d'] = isset($_SESSION['s_distance_d'])?$_SESSION['s_distance_d'] :false;

  $_SESSION['shop'] = isset($_SESSION['shop'])?$_SESSION['shop']:"";
  $_SESSION['distance'] = isset($_SESSION['distance'])?$_SESSION['distance']:"";
  $_SESSION['left_price'] = isset($_SESSION['left_price'])?$_SESSION['left_price']:"";
  $_SESSION['right_price'] = isset($_SESSION['right_price'])?$_SESSION['right_price']:"";
  $_SESSION['meal'] = isset($_SESSION['meal'])?$_SESSION['meal']:"";
  $_SESSION['category'] = isset($_SESSION['category'])?$_SESSION['category']:"";

  //get input by post
  $isempty = true;
  if(!empty($_POST['shop'])){
    $isempty = false;
    $_SESSION['shop'] = htmlspecialchars($_POST['shop']);
  }
  else{
    $t_shop = $_SESSION['shop'];
    $_SESSION['shop'] =null;
  }

  if(!empty($_POST['distance'])&& $_POST['distance']!='all'){
	  $isempty = false;
    $_SESSION['distance'] = htmlspecialchars($_POST['distance']);
  }
  else if(!empty($_POST['distance']))
  {
	  $t_distance = $_SESSION['distance'];
      $_SESSION['distance'] ='all';
  }else if(empty($_POST['distance'])){
  	$t_distance = $_SESSION['distance'];
  }

  if(!empty($_POST['left_price'])){
    $isempty = false;
    $_SESSION['left_price'] = htmlspecialchars($_POST['left_price']);
  }else{
    $t_left_price= $_SESSION['left_price'];
    $_SESSION['left_price'] =null;
  }

  if(!empty($_POST['right_price'])){
    $isempty = false;
    $_SESSION['right_price'] = htmlspecialchars($_POST['right_price']);  
  }else{
    $t_right_price = $_SESSION['right_price'];
    $_SESSION['right_price'] =null;
  }


  if(!empty($_POST['meal'])){
    $isempty = false;
    $_SESSION['meal'] = htmlspecialchars($_POST['meal']);
  }else{
    $t_meal = $_SESSION['meal'];
    $_SESSION['meal'] =null;
  }

  if(!empty($_POST['category'])){
    $isempty = false;
    $_SESSION['category'] = htmlspecialchars($_POST['category']);
  }else{
    $t_category = $_SESSION['category'];
    $_SESSION['category'] =null;
  }

  if($isempty && !isset ($_POST['search_b'])){
	  //don't reset
	  $_SESSION['shop'] = $t_shop;
    $_SESSION['left_price'] = $t_left_price;
    $_SESSION['right_price'] = $t_right_price;
    $_SESSION['meal'] = $t_meal;
    $_SESSION['category'] = $t_category;
  }
  else if($isempty){
	  //reset
	  $_SESSION['shop'] = null;
	  $_SESSION['distance']=null;
    $_SESSION['left_price'] = null;
    $_SESSION['right_price'] = null;
    $_SESSION['meal'] = null;
    $_SESSION['category'] = null;
    $_SESSION['s_shopname_d'] = false;
    $_SESSION['s_shopname'] = false;
    $_SESSION['s_category_d'] = false;
    $_SESSION['s_category'] = false;
    $_SESSION['s_distance'] = false;
    $_SESSION['s_distance_d'] = false;
  }  

?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <!-- Renew location -->
  <script src="scripts/renew_location.js"></script>
  <!-- register shop with ajax -->
  <script type="text/javascript" src="scripts/ajax_shop.js"></script>
  <script type="text/javascript" src="scripts/register_shop.js"></script>
  <!-- Add meal into database -->
  <script type="text/javascript" src="scripts/addmeal.js"></script>

  <!-- HW3 -->
  <!-- Get order information and check -->
  <script type="text/javascript" src="scripts/home_order.js"></script>
  <script type="text/javascript" src="scripts/add_recharge.js"></script>
  <script type="text/javascript" src="scripts/transaction_record.js"></script>

  <title>Hello, world!</title>
</head>

<body>
 
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand " href="#" id="username">NULL</a>
        <a class="navbar-brand" href="index.php" style="font-size: 10px">Logout</a>
      </div>

    </div>
  </nav>
  <div class="container">

    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" onclick="hidemenu()">Home</a></li>
      <li><a href="#menu1" onclick="hidehome()">shop</a></li>
      <li><a href="#menu2" onclick="hidehome()">My Order</a></li>
      <li><a href="#menu3" onclick="hidehome()">Shop Order</a></li>
      <li><a href="#menu4" onclick="transaction_record()">Transaction Record</a></li>


    </ul>


    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
        <div class="row" id="hide1">
        <h3>Profile</h3>
          <div class="col-xs-12">
            <div id="user_profile">Account: , Name: , Phone: , Location: (null, null)</div>
            
            <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
            data-target="#location">edit location</button>
            <!--  -->
            <div class="modal fade" id="location"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog  modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">edit location</h4>
                  </div>
                  <div class="modal-body">
                    <label class="control-label " for="latitude">latitude</label>
                    <input type="text" class="form-control" id="latitude" placeholder="enter latitude">
                      <br>
                      <label class="control-label " for="longitude">longitude</label>
                    <input type="text" class="form-control" id="longitude" placeholder="enter longitude">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="renew_location()">Edit</button>
                  </div>
                </div>
              </div>
            </div>



            <!--  -->
            <div id="wallet_balance">
            walletbalance: 0
            </div>
            <!-- Modal -->
            <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
              data-target="#myModal">Add value</button>
            <div class="modal fade" id="myModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog  modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add value</h4>
                  </div>
                  <div class="modal-body">
                    <input type="text" class="form-control" id="recharge-value" placeholder="enter add value">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="recharge()">Add</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- 
                
             -->
             <h3>Search</h3>
        <div class=" row  col-xs-8" id="hide2">
          <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="search">
            <div class="form-group">
              <label class="control-label col-sm-1" for="Shop">Shop</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" placeholder="Enter Shop name" name="shop">
              </div>
              <label class="control-label col-sm-1" for="distance" >distance</label>
              <div class="col-sm-5">


                <select class="form-control" id="sel1" name="distance">
                  <option>all</option>
                  <option>near</option>
                  <option>medium </option>
                  <option>far</option>
                </select>
              </div>

            </div>

            <div class="form-group">

              <label class="control-label col-sm-1" for="Price">Price</label>
              <div class="col-sm-2">

                <input type="number" class="form-control" name="left_price">

              </div>
              <label class="control-label col-sm-1" for="~">~</label>
              <div class="col-sm-2">

                <input type="number" class="form-control" name="right_price">

              </div>
              <label class="control-label col-sm-1" for="Meal">Meal</label>
              <div class="col-sm-5">
                <input type="text" list="Meals" class="form-control" id="Meal" placeholder="Enter Meal" name="meal">
                <datalist id="Meals">
                  <option value="Hamburger">
                  <option value="coffee">
                </datalist>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-1" for="category"> category</label>
            
              
                <div class="col-sm-5">
                  <input type="text" list="categorys" class="form-control" id="category" placeholder="Enter shop category" name="category">
                  <datalist id="categorys">
                    <option value="fast food">
               
                  </datalist>
                </div>
                <button type="submit" style="margin-left: 18px;"class="btn btn-primary" name="search_b" >Search(If inputs are empty, reset the filter.)</button>
            </div>
            <div class="form-group">
                <br></br>
                <button type="input" style="margin-left: 18px;"class="btn btn-primary" name="s_shopname">sort by Shop name</button>                
                <button type="input" style="margin-left: 18px;"class="btn btn-primary" name="s_category">sort by Shop category</button>
                <button type="input" style="margin-left: 18px;"class="btn btn-primary" name="s_distance">sort by Distance</button>
                <br></br>
                <button type="input" style="margin-left: 18px;"class="btn btn-primary" name="s_shopname_d">sort by Shop name(descend)</button>                
                <button type="input" style="margin-left: 18px;"class="btn btn-primary" name="s_category_d">sort by Shop category(descend)</button>
                <button type="input" style="margin-left: 18px;"class="btn btn-primary" name="s_distance_d">sort by Distance(descend)</button>

            </div> 
          </form>
        </div>
        <?php
              // echo "Search:"."<br>";
              // echo !empty($_SESSION['shop'])?"Shop name= ".$_SESSION['shop']:"Haven't input Shop name.";
              // echo "<br>";
              // echo !empty($_SESSION['distance'])?"Distance=".$_SESSION['distance']:"Haven't input distance.";
              // echo "<br>";
              // echo !empty($_SESSION['left_price'])?"left_price =".$_SESSION['left_price']:"Haven't input left_price.";
              // echo "<br>";
              // echo !empty($_SESSION['right_price'])?"right_price=".$_SESSION['right_price']:"Haven't input right_price.";
              // echo "<br>";
              // echo !empty($_SESSION['meal'])?"meal = ".$_SESSION['meal']:"Haven't input meal." ;
              // echo "<br>";
              // echo !empty($_SESSION['category'])?"category=".$_SESSION['category']:"Haven't input category.";
              // echo "<br>";


              //filter function.
              $conn = require_once "db_account/config.php";
              
              // query
              $stores = [];
              $myset = [];
              $nosearch=true;
              if(!empty($_SESSION['shop'])){
                $nosearch = false;
                
                $t_shop = $_SESSION['shop'];
                $t_shop = "%".strtoupper($t_shop)."%";
                $stmt1 = $conn->prepare("select * from shop where upper(shopname) LIKE :shop;");
                $stmt1->execute(array('shop' => $t_shop));
                foreach($stmt1 as $row){
                  $flag = false;
                  foreach($myset as $index){
                    if($index==$row['shopname']){
                      $flag = true;
                      break;
                    }
                  }
                  if (!$flag){
                    array_push($stores,$row);
                    array_push($myset,$row['shopname']);
                  }
                }
              }
              
              
              if(!empty($_SESSION['distance'])&&$_SESSION['distance']!='all'){
                $nosearch = false;
                $latitude = $_SESSION['latitude'];
                $longitude = $_SESSION['longitude'];
                $tag = "";
                $stmt1 =$conn->prepare("select * from shop;");
                
                if($_SESSION['distance']=="near"){
                  $stmt1 = $conn->prepare("select * from shop where ST_Distance_Sphere(POINT(:longitude,:latitude),location)  <=50000 ;");
                  $stmt1->execute(array('longitude'=>$longitude,'latitude'=>$latitude));
                  $tag = "near";
                  
                }
                else if($_SESSION['distance']=="medium"){
                  $stmt1 = $conn->prepare("select *from shop where ST_Distance_Sphere(POINT(:longitude,:latitude),location)  > 50000 and ST_Distance_Sphere(POINT(:longitude,:latitude),location)  <= 300000;");
                  $stmt1->execute(array('longitude'=>$longitude,'latitude'=>$latitude));
                  $tag="medium";
                }else if($_SESSION['distance']=="far"){
                  $stmt1 = $conn->prepare("select * from shop where ST_Distance_Sphere(POINT(:longitude,:latitude),location)  > 300000;");
                  $stmt1->execute(array('longitude'=>$longitude,'latitude'=>$latitude));
                  $tag="far";
                }
                
                foreach($stmt1 as $row){
                  $flag = false;
                  foreach($myset as $index){
                    if($index==$row['shopname']){
                      $flag = true;
                      break;
                    }
                  }
                  if (!$flag){
                    $row['distance'] =$tag;
                    array_push($stores,$row);
                    array_push($myset,$row['shopname']);
                  }
                }
              }
              
              if(!empty($_SESSION['left_price']) && !empty($_SESSION['right_price'])){
                $nosearch = false;
                $stmt1 = $conn->prepare("select * from meal,shop where meal.shopname = shop.shopname and meal.price >= :left_price and meal.price<=:right_price;");
                $stmt1->execute(array('left_price' => $_SESSION['left_price'], 'right_price'=>$_SESSION['right_price']));
                foreach($stmt1 as $row){
                  $flag = false;
                  foreach($myset as $index){
                    if($index==$row['shopname']){
                      $flag = true;
                      break;
                    }
                  }
                  if (!$flag){
                    array_push($stores,$row);
                    array_push($myset,$row['shopname']);
                  }
                }
              }

              else if(!empty($_SESSION['left_price'])&& empty($_SESSION['right_price'])){
                $nosearch = false;
                $stmt1 = $conn->prepare("select * from meal,shop where meal.shopname = shop.shopname and meal.price >= :left_price ;");
                $stmt1->execute(array('left_price' => $_SESSION['left_price']));
                foreach($stmt1 as $row){
                  $flag = false;
                  foreach($myset as $index){
                    if($index==$row['shopname']){
                      $flag = true;
                      break;
                    }
                  }
                  if (!$flag){
                    array_push($stores,$row);
                    array_push($myset,$row['shopname']);
                  }
                }
              }
              else if(empty($_SESSION['left_price'])&&!empty($_SESSION['right_price'])){
                $nosearch = false;
                $stmt1 = $conn->prepare("select * from meal,shop where meal.shopname = shop.shopname and meal.price <= :right_price ;");
                $stmt1->execute(array('right_price' => $_SESSION['right_price']));
                foreach($stmt1 as $row){
                  $flag = false;
                  foreach($myset as $index){
                    if($index==$row['shopname']){
                      $flag = true;
                      break;
                    }
                  }
                  if (!$flag){
                    array_push($stores,$row);
                    array_push($myset,$row['shopname']);
                  }
                }
              }

              if(!empty($_SESSION['meal'])){
                $nosearch = false;
                $t_meal = $_SESSION['meal'];
                $t_meal = "%".strtoupper($t_meal)."%";
                $stmt1 = $conn->prepare("select * from meal,shop where meal.shopname = shop.shopname and upper(meal.mealname) LIKE :meal;");
                $stmt1->execute(array('meal' => $t_meal));
                foreach($stmt1 as $row){
                  $flag = false;
                  foreach($myset as $index){
                    if($index==$row['shopname']){
                      $flag = true;
                      break;
                    }
                  }
                  if (!$flag){
                    array_push($stores,$row);
                    array_push($myset,$row['shopname']);
                  }
                }
              }

              if(!empty($_SESSION['category'])){
                $nosearch = false;
                $t_category = $_SESSION['category'];
                $t_category = "%".strtoupper($t_category)."%";
                $stmt1 = $conn->prepare("select * from shop where upper(category) LIKE :category;");
                $stmt1->execute(array('category' =>$t_category));
                foreach($stmt1 as $row){
                  $flag = false;
                  foreach($myset as $index){
                    if($index==$row['shopname']){
                      $flag = true;
                      break;
                    }
                  }
                  if (!$flag){
                    array_push($stores,$row);
                    array_push($myset,$row['shopname']);
                  }
                }
              }
              
              if ($nosearch){
                $stmt1 = $conn->query("select * from shop;");
                foreach($stmt1 as $row){
                  $flag = false;
                  foreach($myset as $index){
                    if($index==$row['shopname']){
                      $flag = true;
                      break;
                    }
                  }
                  if (!$flag){
                    array_push($stores,$row);
                    array_push($myset,$row['shopname']);
                  }
                }
              }
              $stores1=[];
	      foreach($stores as $s){
              $stmt1 = $conn->prepare("select *, ST_Distance_Sphere(POINT(:longitude,:latitude),location) as distance from shop;");
              $stmt1->execute(array('longitude'=>$_SESSION['longitude'],'latitude'=>$_SESSION['latitude']));
                foreach($stmt1 as $row){
                  if($row['shopname']==$s['shopname']){
                    if($row['distance']<=50000){
                      $s['distance'] = "near";
                    }
                    else if($row['distance']<=300000){
                      $s['distance'] = "medium";
                    }else{
                      $s['distance'] = "far";
                    }
                    break;
                  }
                }
                array_push($stores1,$s);
              }
              $stores = $stores1;

              if(isset($_POST['s_shopname'])){
                usort($stores,fn($a,$b)=>strtolower($a['shopname'])>strtolower($b['shopname']));
                $_SESSION['s_shopname']=true;
                $_SESSION['s_shopname_d']=false;
                $_SESSION['s_category']=false;
                $_SESSION['s_category_d']=false;
                $_SESSION['s_distance'] = false;
                $_SESSION['s_distance_d']=false;
              }
              else if(isset($_POST['s_shopname_d'])){
                usort($stores,fn($a,$b)=>strtolower($a['shopname'])<strtolower($b['shopname']));
                $_SESSION['s_shopname']=false;
                $_SESSION['s_shopname_d']=true;
                $_SESSION['s_category']=false;
                $_SESSION['s_category_d']=false;
                $_SESSION['s_distance'] = false;
                $_SESSION['s_distance_d']=false;
              }
              else if($_SESSION['s_shopname']){
                usort($stores,fn($a,$b)=>strtolower($a['shopname'])>strtolower($b['shopname']));
              }else if($_SESSION['s_shopname_d']){
                usort($stores,fn($a,$b)=>strtolower($a['shopname'])<strtolower($b['shopname']));
              }
              
              if(isset($_POST['s_category'])){
                usort($stores,fn($a,$b)=>strtolower($a['category'])>strtolower($b['category']));
                $_SESSION['s_shopname']=false;
                $_SESSION['s_shopname_d']=false;
                $_SESSION['s_category']=true;
                $_SESSION['s_category_d']=false;
                $_SESSION['s_distance'] = false;
                $_SESSION['s_distance_d']=false;
              }else if(isset($_POST['s_category_d'])){
                usort($stores,fn($a,$b)=>strtolower($a['category'])<strtolower($b['category']));
                $_SESSION['s_shopname']=false;
                $_SESSION['s_shopname_d']=false;
                $_SESSION['s_category']=false;
                $_SESSION['s_category_d']=true;
                $_SESSION['s_distance'] = false;
                $_SESSION['s_distance_d']=false;
              }else if($_SESSION['s_category']){
                usort($stores,fn($a,$b)=>strtolower($a['category'])>strtolower($b['category']));
              }else if($_SESSION['s_category_d']){
                usort($stores,fn($a,$b)=>strtolower($a['category'])<strtolower($b['category']));
              }
              
              if(isset($_POST['s_distance'])){
                usort($stores,fn($a,$b)=>$a['distance']<$b['distance']);
                $_SESSION['s_shopname']=false;
                $_SESSION['s_shopname_d']=false;
                $_SESSION['s_category']=false;
                $_SESSION['s_category_d']=false;
                $_SESSION['s_distance'] = true;
                $_SESSION['s_distance_d']=false;
              }else if(isset($_POST['s_distance_d'])){
                usort($stores,fn($a,$b)=>$a['distance']>$b['distance']);
                $_SESSION['s_shopname']=false;
                $_SESSION['s_shopname_d']=false;
                $_SESSION['s_category']=false;
                $_SESSION['s_category_d']=false;
                $_SESSION['s_distance'] = false;
                $_SESSION['s_distance_d']=true;
              }else if($_SESSION['s_distance']){
                usort($stores,fn($a,$b)=>$a['distance']<$b['distance']);
              }else if($_SESSION['s_distance_d']){
                usort($stores,fn($a,$b)=>$a['distance']>$b['distance']);
              }
              
          ?>
        <!-- </div> -->
        

        <div class="row" id="hide3">
          <div class="  col-xs-8">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">shop name</th>
                  <th scope="col">shop category</th>
                  <th scope="col">Distance</th>
               
                </tr>
              </thead>
              <tbody>
                
                  <!-- <th scope="row">1</th> -->
                  <?php
                    //pages (bonus functions)
                    $data_nums = sizeof($stores);
                    $per = 5;
                    $pages = ceil($data_nums/$per);
                    if(!isset($_GET['page'])){
                      $page = 1;
                    }else{
                      $page = intval($_GET["page"]);
                    }
                    $start = ($page-1)*$per;
                    $temp = 1;
                    for($x=$start;$x<$start+$per and $x < $data_nums;$x++){
                      $s = $stores[intval($x)];
                      echo "<tr>";
                      echo '<th scope="row">' .$temp."</th>";
                      echo "<td>". $s['shopname']."</td><td>".$s['category']."</td>";
		      echo "<td>".$s['distance']."</td>";
		      
      			$s['shopname']=str_replace(" ","",$s['shopname']);
                      echo "<td><button type='button' class='btn btn-info ' data-toggle='modal' data-target=#".$s['shopname']." onclick='menu_store_items(\"".$s['shopname']."\")' >Open menu</button></td>";
                      echo "</tr>";
                      $temp ++;
                    }
                  ?>
                  
                
                  <!-- <td>near </td> -->
              </tbody>
            </table>


                <!-- Modal -->
                <?php

  for($x=$start;$x<$start+$per and $x<$data_nums;$x++){
      $s = $stores[intval($x)];
      $foods = [];
      $stmt=$conn->prepare("select * from meal where shopname=:shopname");
      $stmt->execute(array('shopname' => $s['shopname']));
      foreach($stmt as $food){
        array_push($foods,$food);
      }
      $s['shopname']=str_replace(" ","",$s['shopname']);
    echo "<div class='modal fade' id=".$s['shopname']."  data-backdrop='static' tabindex='-1' role='dialog' aria-labelledby='staticBackdropLabel' aria-hidden='true'>";
      echo '<div class="modal-dialog">';
        echo "<!-- Modal content-->";
        echo '<div class="modal-content">';
          echo '<div class="modal-header">';
            echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
            echo '<h4 class="modal-title">menu</h4>';
          echo '</div>';
          echo '<div class="modal-body">';
          echo '<!--  -->';

          echo '<div class="row">';
            echo '<div class="  col-xs-12" id="div-'.$s['shopname'].'">';
              echo '<table class="table" style=" margin-top: 15px;">';
                echo '<thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Picture</th>
                  
                    <th scope="col">meal name</th>
                
                    <th scope="col">price</th>
                    <th scope="col">Quantity</th>
                  
                    <th scope="col">Order check</th>
                  </tr>
                </thead>';
                $temp =0;
                foreach($foods as $food){
                  $temp++;
                echo '<tbody>';
                echo '<tr id="row-'.$s['shopname'].'-'.$temp.'">';
                    echo '<th scope="row">'.$temp.'</th>';
                    echo '<td><img id="img-'.$s['shopname'].'-'.$food['mealname'].'" src="data:image/jpg;charset=utf8;base64,'.base64_encode($food['picture']).'" style="width: 40%;" with="50" heigh="10" alt='.$food['mealname'].'></td>';
                  
                    echo '<td id="meal-'.$s['shopname'].'-'.$temp.'" >'.$food['mealname'].'</td>';
                  
                    echo '<td id="shop-price-'.$s['shopname'].'-'.$food['mealname'].'">'.$food['price'].'</td>';
                    echo '<td id="shop-quantity-'.$s['shopname'].'-'.$food['mealname'].'">'.$food['quantity'].'</td>';
                
                    echo '<td> <input type="number" id="quantity-'.$s['shopname'].'-'.$food['mealname'].'" min="0" max="'.$food['quantity'].'" step="1" value="0"></td>';
                echo '</tr>';
                }

                echo'</tbody>
              </table>
              
              <label>
              Type: 
              <select id="delivery-type-'.$s['shopname'].'">
              <option value="delivery">Delivery</option>
              <option value="pick-up">Pick-Up</option>
              </select>
              </label>

            </div>
          </div>
          
          <!--  -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="static" onclick="get_order(\''.$s['shopname'].'\')">Order</button>
          </div>
        </div>
        
      </div>
    </div>';
    }
?>
<!-- Check order information -->
<div>
  <div id="check-order-info" class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-sg" >
      <div class="modal-content">
        <div class="modal-header">
          <div class="modal-title">
            Check Your Order
          </div>
        </div>
        <div class="modal-body" id="check-order-info-body">
          <table class="table" style="margin-top: 15px;">
            <thead>
              <tr>
                <th scope='col'>Picture</th>
                <th scope="col">Meal Name</th>
                <th scope="col">Price</th>
                <th scope="col">Quantity</th>
              </tr>
            </thead>
            <tbody id="check-order-info-tbody">

            </tbody>
          </table>
          <label>Total: 
          <font id="check-order-info-total"></font>
          </label>
        </div>
        <div class="modal-footer">
          <button class="btn" data-dismiss="modal" onclick="start_transaction()">Order</button>
          <button class="btn" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</div>


<?php
echo 'Total '.$data_nums.' stores. At page '.$page.'. Total '.$pages.' pages. ';
echo "<a href=?page=1>First page</a> ";
if($page>1){
  echo "<br /><a href=?page=".($page>1?$page-1:1).">Former page </a>";
}
else{
  echo "<br />";
}

for( $i=1 ; $i<=$pages ; $i++ ) {
  if ($i==$page){
    continue;
  }
  if ( $page-3 < $i && $i < $page+3 ) {
      echo "<a href=?page=".$i.">".$i."</a> ";
  }
}



if($page<$pages){
  echo " <a href=?page=".($page+1).">next page</a><br /><br />";
} 

?>

      </div>
    </div>
  </div>

  <!--Shop Area-->
  <div id="menu1" class="tab-pane fade">
        <!--import script tag to check sql by php-->
      <?php require 'php/db_config.php'; ?>
        <!--Register Shop-->
      <form onsubmit="return register_shop()">
        <h3> Start a business </h3>
        <div class="form-group ">
          <div class="row">
            <div class="col-xs-2">
              <label for="ex5">shop name</label>
              <input class="form-control" id="shopname" placeholder="macdonald" type="text" name="shopname">
            </div>
            <div class="col-xs-2">
              <label for="ex5">shop category</label>
              <input class="form-control" id="ex5" placeholder="fast food" type="text" name="category">
            </div>
            <div class="col-xs-2">
              <label for="ex6">latitude</label>
              <input class="form-control" id="ex6" placeholder="121.00028167648875" type="text" name="latitude" >
            </div>
            <div class="col-xs-2">
              <label for="ex8">longitude</label>
              <input class="form-control" id="ex8" placeholder="24.78472733371133" type="text" name="longitude">
            </div>
          </div>
        </div>
        
        <!--AJAX Response-->
        <div id="sname_response"></div>

        <div class=" row" style=" margin-top: 25px;">
          <div class=" col-xs-3">
            <button type="submit" class="btn btn-primary" id="register_btn">register</button>
          </div>
        </div>
      </form>

        <hr>
        <div id="shop_profile">
          <?php
            if($have_shop->num_rows>0){ 
              $shop_profile = $have_shop->fetch_assoc();
              $shopname = $shop_profile['shopname'];;
              ?>
          <h3>Shop Profile</h3>
          <p id="show_shop"><?php echo "Shop name: ".$shopname."<br/>Location: ".$shop_profile['location']."<br/>Category: ".$shop_profile['category'];?></p>          
          <?php } ?>
        </div>
        <!--Add Meal-->
        <div id="add_container">
        <h3>ADD</h3>
        <div class="form-group ">
          <form id="add_meal"  method="post" enctype="multipart/form-data">
          <div class="row">
            
            <div class="col-xs-6">
              <label for="ex3">meal name</label>
              <input class="form-control" id="ex3" type="text" name="mealname">
            </div>
          </div>
          <div class="row" style=" margin-top: 15px;">
            <div class="col-xs-3">
              <label for="ex7">price</label>
              <input class="form-control" id="ex7" type="number" name="price">
            </div>
            <div class="col-xs-3">
              <label for="ex4">quantity</label>
              <input class="form-control" id="ex4" type="number" name="quantity">
            </div>
          </div>


          <div class="row" style=" margin-top: 25px;">

            <div class=" col-xs-3">
              <label for="ex12">上傳圖片</label>
              <input id="myFile" type="file" name="myFile" multiple class="file-loading">
              <div id="preview"></div><br>
              <div id="err"></div>
              <hr>
            </div>
            <div class=" col-xs-3">
              <input type="hidden" name="shopname" value="<?php echo $shopname ?>">
              <input style=" margin-top: 15px;" type="submit" class="btn btn-primary" id="add_btn" name="add_btn" value="Add">
            </div>

          </div>
          </form>
        </div>


        <div class="row">
          <div class="  col-xs-8">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Picture</th>
                  <th scope="col">meal name</th>
              
                  <th scope="col">price</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Edit</th>
                  <th scope="col">Delete</th>
                </tr>
              </thead>
              <tbody>
              <?php 
              $result = $conn->query("SELECT * FROM meal where shopname = '$shopname'");
              $count = 0;
              ?>
              <?php if($result->num_rows > 0){ ?> 
                <tr>
                
                
                <?php while($row = $result->fetch_assoc()){ 
                          $count = $count + 1;
                ?> 
                  <th scope="row"><?php echo $count;?></th>
		  <td><img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['picture']); ?>" " style = "width:40%;" with="50" heigh="10" alt="<?php echo $row['mealname']?>"></td>
                  <td><?php echo $row['mealname']?></td>
                  <td><?php echo $row['price']?> </td>
                  <td><?php echo $row['quantity']?></td>
                  <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#<?php echo $row['mealname']?>-1">
                    Edit
                    </button></td>
                    <!-- Modal -->
                    <form class="editform" action="php/editmeal.php" method="post" name="<?php echo $row['mealname']?>">
                        <div class="modal fade" id="<?php echo $row['mealname']?>-1" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel-<?php echo $count?>"><?php echo $row['mealname']?> Edit</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>

                              
                              <div class="modal-body">
                                <input type="hidden" id="<?php echo $row['mealname']?>" value="<?php echo $row['mealname']?>" name="mealname">
                                <div class="row" >
                                  <div class="col-xs-6">
                                    <label for="price-<?php echo $row['mealname']?>"><?php echo $row['price']?></label>
                                    <input class="form-control" id="price-<?php echo $row['mealname']?>" type="number" name="price">
                                  </div>
                                  <div class="col-xs-6">
                                    <label for="quabtity-<?php echo $row['mealname']?>"><?php echo $row['quantity']?></label>
                                    <input class="form-control" id="quantity-<?php echo $row['mealname']?>" type="number" name="quantity">
                                  </div>
                                </div>
                      
                              </div>
                              <div class="modal-footer">
                                <button id="<?php echo $row['mealname']?>_edit" name="<?php echo $row['mealname']?>" type="button" class="btn btn-secondary" data-dismiss="modal" onclick="edit_form_submit(this)">Edit</button>

                              </div>
                            </div>
                          </div>
                        </div>
                    </form>
                  <td><button id="<?php echo $row['mealname']?>_del" name="<?php echo $row['mealname']?>" type="button" class="btn btn-danger" onclick="delete_meal(this)">Delete</button></td>
                </tr>
                <?php } ?> 
              <?php }else{ ?> 
                  <p class="status error">Image(s) not found...</p> 
              <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      </div>


       <!--Shop Area-->
  <div id="menu4" class="tab-pane fade">
    <!--Register Shop-->
    <h3> Transaction Record </h3>
    <div class="row" id="hide3">
      <div class="  col-xs-8">
      <label class="control-label col-sm-1" for="Action" style="font-size: 20px;" >Action</label>
      <select class="form-control" id="action_choose" name="Action" onchange="transaction_record()">
        <option>all</option>
        <option>Payment</option>
        <option>Collection</option>
        <option>Recharge</option>
        <option>Refund</option>
      </select>
      </div>
      <div class="  col-xs-8">
        <table class="table" style=" margin-top: 15px;">
          <thead>
            <tr>
              <th scope="col">Record ID</th>
              <th scope="col">Action</th>
              <th scope="col">Time</th>
              <th scope="col">Trader</th>
              <th scope="col">Ammount change</th>
            </tr>
          </thead>
          <tbody id="transaction-record-tbody">

          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
  <script>
    $(document).ready(function () {
      $(".nav-tabs a").click(function () {
        $(this).tab('show');
      });
    });
    // function for submit edit meal form 
    function edit_form_submit(edit_btn_id) {
      var mealname = edit_btn_id.getAttribute("name");
      var edit_form = document.getElementsByClassName("editform"); 
      console.log(mealname);
      console.log(edit_form);
      for (var i=0; i<edit_form.length; i++) {
        if(edit_form[i].getAttribute("name") == mealname){
          edit_form[i].submit();
          break;
        }
      } 
    }    
    function delete_meal(del_btn_id){
      var mealname = del_btn_id.getAttribute("name");
      var data = new FormData();
      data.append("mealname", mealname);
      var xhr = new XMLHttpRequest();
      if(confirm('Are you sure you want to delete it?') == false){
        return;
      }
      xhr.open("POST", "php/deletemeal.php");
      xhr.onload = function(){
        if(confirm(this.response) == true){
          window.location.href = 'nav.php';
        }
        console.log(this.response);
      };
      xhr.send(data);
      // location.reload();
      
      return false;
      }

      // function hidemenu(){
      //   alert("hidemenu")
      //   var shoparea = document.getElementById("menu1");
      //   shoparea.style.display = 'none';
      //   var hide1 = document.getElementById("hide1");
      //   hide1.style.display = 'block';
      //   var hide2 = document.getElementById("hide2");
      //   hide2.style.display = 'block';
      //   var hide3 = document.getElementById("hide3");
      //   hide3.style.display = 'block';
      // }
      // function hidehome(){
      //   alert("hidehome")
      //   var hide1 = document.getElementById("hide1");
      //   hide1.style.display = 'none';
      //   var hide2 = document.getElementById("hide2");
      //   hide2.style.display = 'none';
      //   var hide3 = document.getElementById("hide3");
      //   hide3.style.display = 'none';
      //   var shoparea = document.getElementById("menu1");
      //   shoparea.style.display = 'block';
      // }
  </script>

<?php   

if($have_shop->num_rows>0){
  echo "<script>
        document.getElementById('register_btn').disabled = true;
        </script>
        ";
}
else{
  echo "<script>
        document.getElementById('add_container').style.visibility = 'hidden';      // Hide
        </script>";
}
?>
  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>
