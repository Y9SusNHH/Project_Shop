<?php
if (empty($_POST['user_name_signin']
    || $_POST['password_signin'])) 
{
    header('location:login_register.php?empty_signin');
    die();
}
    // lấy dữ liệu từ form 
$user_name = addslashes($_POST['user_name_signin']);
$password = addslashes($_POST['password_signin']);

    // kiểm tra xem có tích vào remember không
    if (isset($_POST['remember'])) {
        $remember = true;
    }else{
        $remember = false;
    }

        // liên kết file đến database
require './admin/connect.php';

        // đếm xem đã xuất hiện user_name trong database hay chưa
$sql = "select * from customer
where user_name = '$user_name' and password='$password'";
$result = mysqli_query($connect, $sql);
        // đếm xem có bao nhiêu bản ghi
$number_rows = mysqli_num_rows($result);

        // nếu có = 1 như dưới, tức có bản ghi rồi thì chạy điều kiện
if ($number_rows == 1) {
    session_start();
    $each = mysqli_fetch_array($result);
    $id =$each['id'];
    $_SESSION['id'] = $each['id'];
    $_SESSION['user_name'] = $each['user_name'];
    if($remember) {
        $token = uniqid('user_', true);
        $sql = "update customer
        set
        token = '$token'
        where
        id = '$id'
        ";      
        mysqli_query($connect,$sql);
        setcookie('remember', $token, time() + 60*60*24*30);
        header('location:customer/index.php');
        mysqli_close($connect);
    }
    header('location:customer/index.php');
} else {

    header('location:login_register.php?error_sigin');
    exit;
}


