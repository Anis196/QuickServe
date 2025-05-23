<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        session_start(); 
        include("../conn_db.php"); 
        if($_SESSION["utype"]!="ADMIN"){
            header("location: ../restricted.php");
            exit(1);
        }
        if(isset($_POST["add_confirm"])){
            
            $s_name = $_POST["s_name"];
            $s_username = $_POST["s_username"];
            $s_location = $_POST["s_location"];
            $s_email = $_POST["s_email"];
            $s_phoneno = $_POST["s_phoneno"];
            $s_pwd = $_POST["s_pwd"];
            
            $insert_query = "INSERT INTO shop (s_username,s_name,s_pwd,s_location,s_email,s_phoneno) 
            VALUES ('{$s_username}','{$s_name}','{$s_pwd}','{$s_location}','{$s_email}','{$s_phoneno}');";
            $insert_result = $mysqli -> query($insert_query);
            if(!empty($_FILES["s_pic"]["name"]) && $insert_result){
                //Image upload
                $s_id = $mysqli -> insert_id;
                $target_dir = '/img/';
                $temp = explode(".",$_FILES["s_pic"]["name"]);
                $target_newfilename = "shop".$s_id.".".strtolower(end($temp));
                $target_file = $target_dir.$target_newfilename;
                if(move_uploaded_file($_FILES["s_pic"]["tmp_name"],SITE_ROOT.$target_file)){
                    $insert_query = "UPDATE shop SET s_pic = '{$target_newfilename}' WHERE s_id = {$s_id};";
                    $insert_result = $mysqli -> query($insert_query);
                }else{
                    $insert_result = false;
                }
            }
            if($insert_result){header("location: admin_shop_list.php?add_shp=1");}
            else{header("location: admin_shop_list.php?add_shp=0");}
            exit(1);
        }
        include('../head.php');
    ?>
    <meta charset="UTF-8">
     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/login.css" rel="stylesheet">
    <link href="../img/Color Icon with background.png" rel="icon">
    <title>Add new shop | QuickServe</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('nav_header_admin.php')?>

    <div class="container form-signin mt-auto w-50">
        <a class="nav nav-item text-decoration-none text-muted" href="#" onclick="history.back();">
            <i class="bi bi-arrow-left-square me-2"></i>Go back
        </a>
        <form method="POST" action="admin_shop_add.php" class="form-floating" enctype="multipart/form-data">
            <h2 class="mt-4 mb-3 fw-normal text-bold"><i class="bi bi-pencil-square me-2"></i>Add New Shop</h2>
            
            
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="shopusername" placeholder="Username" name="s_username"
                required>
                <label for="shopname">Username</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="shopname" placeholder="Shop Name" name="s_name" required>
                <label for="shopname">Shop Name</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="shoppwd" placeholder="Password" name="s_pwd" minlength="8"
                    maxlength="45" required>
                <label for="shoppwd">Password</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="shopcfpwd" placeholder="Confirm Password" minlength="8"
                    maxlength="45" name="cfpwd" required>
                <label for="shopcfpwd">Confirm Password</label>
                <div id="passwordHelpBlock" class="form-text smaller-font">
                    Your password must be at least 8 characters long.
                </div>
            </div>
            <div class="form-floating mb-2">
                <input type="email" class="form-control" id="email" placeholder="E-mail" name="s_email" required>
                <label for="email">E-mail</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="shoplocation" placeholder="Location" name="s_location" required>
                <label for="shoplocation">Shop Location</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="shopphoneno" placeholder="Phone Number" name="s_phoneno" required>
                <label for="shopphoneno">Phone Number</label>
            </div>
            
            <div class="mb-2">
                <label for="formFile" class="form-label">Upload shop image</label>
                <input class="form-control" type="file" id="s_pic" name="s_pic" accept="image/*">
            </div>
            <button class="w-100 btn btn-success mb-3" name="add_confirm" type="submit">Add new shop</button>
        </form>
    </div>

    <?php include('admin_footer.php')?>
</body>

</html>