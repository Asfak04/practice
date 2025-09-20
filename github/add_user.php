<?php
session_start();
include 'connect.php';
include 'logout.php';

if(!isset($_SESSION['admin'])){
    header('location:admin_login.php');
  }

  if(isset($_GET['name']) || isset($_GET['email']) || isset($_GET['phone'])){
    $name=$_GET['name'];
    $name=$_GET['email'];
    $name=$_GET['phone'];
  }else{
    $name="";
    $email="";
    $phone="";

  }
$error = [];
  if(isset($_POST['add_user'])){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];

        if(empty($name)){
            $error['name'] = "Name is required";
        }else{
            if(!preg_match("/^[a-zA-Z ]*$/", $name))
            {
                $error['name'] = "Only Alphabets And Spaces Are Allowed";
            }
            elseif(strlen($name)>50){
                $error['name'] = "Name Can't be greater than 50";
            } 
        }
        if(empty($email)){
          $error['email'] = "Email is required";
        }else{
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $error['email'] = "Invalid email formate";
                }
        }
        if(empty($phone)){
          $error['phone'] = "Phone number is required";
       }else{
            if(!preg_match("/^[0-9]*$/", $phone))
              {
                  $error['phone'] = "Only Numbers are allowed";
              }  elseif(strlen($phone)<10 or strlen($phone)>10 ){
                $error['phone'] = "Phone Number Can't be less or greater than 10";
            } 
       }if(count($error) == 0){
        $unique = "SELECT * FROM users WHERE email='$email'";
        $unique_result = mysqli_query($conn, $unique);
        $present = mysqli_num_rows($unique_result);
             if($present){
               $error['email']="Email should be unique";
             }else{
                $insert = "INSERT INTO users(name, email, phone) VALUES ('$name','$email','$phone')";
                $insert_result = mysqli_query($conn, $insert);
                   if($insert_result){
                    $_SESSION['message'] = "User Created Successfully";
                    header('location:user.php');
                    exit(0);
                   }else{
                       die(mysqli_error($conn));
                   }
             }
    }


  }


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="header">
    <!-- navbar -->
    <?php include 'navbar.php'?>

</div>
<div>
    <form method="POST" class="col-4 m-auto border p-2 mt-5 shadow">
                   <h1 class="text-center text-success">Add Users</h1>
                   <div class="row g-2">
                        <div class="name">
                            <label for="user_name" class="form-label">Name</label>
                            <input type="text" class="form-control <?php if(isset($error['name'])){ echo 'is-invalid';}?>" name="name" value="<?php echo $name;?>">
                            <?php if(isset($error['name'])){ echo "<div class='invalid-feedback'>".$error['name']."</div>";}?>
                        </div>
                        <div class="email">
                            <label for="user_email" class="form-label">Email</label>
                            <input type="text" class="form-control  <?php if(isset($error['email'])){ echo 'is-invalid';}?>" name="email" value="<?php echo $email;?>">
                            <?php if(isset($error['email'])){ echo "<div class='invalid-feedback'>".$error['email']."</div>";}?>
                        </div>
                        <div class="phone">
                            <label for="user_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control <?php if(isset($error['phone'])){ echo 'is-invalid';}?>" name="phone" value="<?php echo $phone;?>">
                            <?php if(isset($error['phone'])){ echo "<div class='invalid-feedback'>".$error['phone']."</div>";}?>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-success col-6" type="submit" name="add_user">Add User</button>
                        </div>
                   </div>
      
    </form>
</div>
   

</body>
</html>
