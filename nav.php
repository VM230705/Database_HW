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

    // set onload event to show user profile
    echo "
    <script>
      window.onload = show_profile;
      function show_profile(){
        document.getElementById('user_profile').innerHTML = 'Account: $account, Name: $name, Phone: $phone, Location: ($latitude, $longitude)';
        document.getElementById('username').innerHTML = '$account';
      }
    </script>
    ";
  }
  $query = "SELECT shopname, ST_AsText(location) AS location, category, account FROM shop WHERE account = '$account'";
  $have_shop = $conn->query($query);
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
      <li class="active"><a href="#home">Home</a></li>
      <li><a href="#menu1">shop</a></li>


    </ul>

    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
        <h3>Profile</h3>
        <div class="row">
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
            walletbalance: 100
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
                    <input type="text" class="form-control" id="value" placeholder="enter add value">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Add</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- 
                
             -->
        <h3>Search</h3>
        <div class=" row  col-xs-8">
          <form class="form-horizontal" action="/action_page.php">
            <div class="form-group">
              <label class="control-label col-sm-1" for="Shop">Shop</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" placeholder="Enter Shop name">
              </div>
              <label class="control-label col-sm-1" for="distance">distance</label>
              <div class="col-sm-5">


                <select class="form-control" id="sel1">
                  <option>near</option>
                  <option>medium </option>
                  <option>far</option>

                </select>
              </div>

            </div>

            <div class="form-group">

              <label class="control-label col-sm-1" for="Price">Price</label>
              <div class="col-sm-2">

                <input type="text" class="form-control">

              </div>
              <label class="control-label col-sm-1" for="~">~</label>
              <div class="col-sm-2">

                <input type="text" class="form-control">

              </div>
              <label class="control-label col-sm-1" for="Meal">Meal</label>
              <div class="col-sm-5">
                <input type="text" list="Meals" class="form-control" id="Meal" placeholder="Enter Meal">
                <datalist id="Meals">
                  <option value="Hamburger">
                  <option value="coffee">
                </datalist>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-1" for="category"> category</label>
            
              
                <div class="col-sm-5">
                  <input type="text" list="categorys" class="form-control" id="category" placeholder="Enter shop category">
                  <datalist id="categorys">
                    <option value="fast food">
               
                  </datalist>
                </div>
                <button type="submit" style="margin-left: 18px;"class="btn btn-primary">Search</button>
              
            </div>
          </form>
        </div>
        <div class="row">
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
                <tr>
                  <th scope="row">1</th>
               
                  <td>macdonald</td>
                  <td>fast food</td>
                
                  <td>near </td>
                  <td>  <button type="button" class="btn btn-info " data-toggle="modal" data-target="#macdonald">Open menu</button></td>
            
                </tr>
           

              </tbody>
            </table>

                <!-- Modal -->
  <div class="modal fade" id="macdonald"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">menu</h4>
        </div>
        <div class="modal-body">
         <!--  -->
  
         <div class="row">
          <div class="  col-xs-12">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Picture</th>
                 
                  <th scope="col">meal name</th>
               
                  <th scope="col">price</th>
                  <th scope="col">Quantity</th>
                
                  <th scope="col">Order check</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td><img src="Picture/1.jpg" with="50" heigh="10" alt="Hamburger"></td>
                
                  <td>Hamburger</td>
                
                  <td>80 </td>
                  <td>20 </td>
              
                  <td> <input type="checkbox" id="cbox1" value="Hamburger"></td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td><img src="Picture/2.jpg" with="10" heigh="10" alt="coffee"></td>
                 
                  <td>coffee</td>
             
                  <td>50 </td>
                  <td>20</td>
              
                  <td><input type="checkbox" id="cbox2" value="coffee"></td>
                </tr>

              </tbody>
            </table>
          </div>

        </div>
        

        <!--  -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Order</button>
        </div>
      </div>
      
    </div>
  </div>
          </div>

        </div>
      </div>

    <!--Shop Area-->
      <div id="menu1" class="tab-pane fade">
        <!--import script tag to check sql by php-->

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
                  <td><img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['picture']); ?>" with="10" heigh="10" alt="<?php echo $row['mealname']?>"></td>
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
          location.reload();
        }
        console.log(this.response);
      };
      xhr.send(data);
      // location.reload();
      
      return false;
      }
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

