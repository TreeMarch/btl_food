<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   // Lọc chuỗi $name đã được mã hóa để loại bỏ các ký tự không hợp lệ.
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   // Lọc chuỗi $email đã được mã hóa để loại bỏ các ký tự không hợp lệ.
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   // Lọc chuỗi $number đã được mã hóa để loại bỏ các ký tự không hợp lệ.
   $pass = sha1($_POST['pass']); // mã hoá = sha1
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   // Lọc chuỗi $pass đã được mã hóa để loại bỏ các ký tự không hợp lệ.
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
   // Lọc chuỗi $cpass đã được mã hóa để loại bỏ các ký tự không hợp lệ.


   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
   $select_user->execute([$email, $number]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'Email này đã tồn tại!';
   }else{
      if($pass != $cpass){
         $message[] = 'Mật khẩu không giống nhau!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
         $insert_user->execute([$name, $email, $number, $cpass]);
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if($select_user->rowCount() > 0){
            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');
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
   <title>Đăng ký</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container">

   <form action="" method="post">
      <h3>Đăng ký ngay</h3>
      <input type="text" name="name" required placeholder="Tên người dùng" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="abc@gmail.com" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="Số điện thoại" class="box" min="0" max="9999999999" maxlength="10">
      <input type="password" name="pass" required placeholder="Mật khẩu" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Xác nhận mật khẩu" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" name="submit" class="btn">
      <p>Bạn đã có tài khoản ?<a href="login.php">Đăng nhập ngay</a></p>
   </form>

</section>











<?php include 'components/footer.php'; ?>







<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>