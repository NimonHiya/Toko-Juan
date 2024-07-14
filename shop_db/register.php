<?php

include 'config.php';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));

   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $message[] = 'user sudah ada!';
   }else{
      mysqli_query($conn, "INSERT INTO `user_form`(name, email, password) VALUES('$name', '$email', '$pass')") or die('query failed');
      $message[] = 'berhasil terdaftar!';
      header('location:login.php');
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>
   
<div class="form-container">

   <form action="" method="post">
      <h3>register</h3>
      <input type="text" name="name" required placeholder="masukan nama" class="box">
      <input type="email" name="email" required placeholder="masukan email" class="box">
      <input type="password" name="password" required placeholder="masukan password" class="box">
      <input type="password" name="cpassword" required placeholder="konfirmasi password" class="box">
      <input type="submit" name="submit" class="btn" value="register">
      <p>sudah punya akun? <a href="login.php">masuk disini</a></p>
   </form>

</div>

</body>
</html>