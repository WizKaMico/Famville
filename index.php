<?php 
session_start();
include('connection/home_connection.php'); 

if(!empty($_GET['action']))
{
  switch($_GET['action'])
  {
      case "CONTACTS":
        if(isset($_POST['submit']))
        {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];
            if(!empty($name) && !empty($email) && !empty($subject) && !empty($message))
            {
                $result = $portCont->aAddInquiry($name, $email, $subject, $message);
                if (!empty($result)) {
                     header('Location:?view=HOME&message=success');
                }else{
                     header('Location:?view=HOME&message=failed');
                }
            }
        }
        break;
      case "REGISTER":
        if(isset($_POST['submit']))
        {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $contact = $_POST['contact'];
            $address = $_POST['address'];
            if(!empty($username) && !empty($email) && !empty($password) && !empty($fullname) && !empty($email) && !empty($contact) && !empty($address))
            {
                $result = $portCont->aAddAccount($username, $email, $password, $fullname, $address, $contact);
                if (!empty($result)) {
                     header('Location:?view=VERIFICATION&email='.$email.'&message=success');
                }else{
                     header('Location:?view=REGISTER&message=failed');
                }
            }

        }
        break;
       case "VERIFY":
         if(isset($_POST['submit']))
         {
            $code = $_POST['code'];
            $email = $_POST['email'];
            if(!empty($code) && !empty($email))
            {
                $result = $portCont->lSearchAccountVerification($code, $email);
                if(!empty($result))
                {
                    $portCont->AccountVerificationCompleted($code, $email);
                    header('Location:?view=HOME&message=success');
                }
                else
                {
                    header('Location:?view=VERIFICATION&email='.$email.'&message=failed');
                }
            }
         }
        break;
        case "FORGOT":
          if(isset($_POST['submit'])) 
          {
             $email = $_POST['email'];
             if(!empty($email))
             {
                $result = $portCont->lSearchAccount($email);
                if(!empty($result))
                {
                     header('Location:?view=NEWPASSWORD&email='.$email.'&message=success');
                }
                else
                {
                    header('Location:?view=FORGOT&message=failed');
                }
             }
          }
        break;
        case "NEWPASSWORD":
          if(isset($_POST['submit'])) 
          {
             $email = $_POST['email'];
             $password = $_POST['password'];
             if(!empty($email))
             {
                $result = $portCont->lSearchAccount($email);
                if(!empty($result))
                {
                    $portCont->AccountNewPasswordUpdate($password, $email);
                     header('Location:?view=HOME&message=success');
                }
                else
                {
                    header('Location:?view=NEWPASSWORD&email='.$email.'&message=failed');
                }
             }
          }
        break;
        case "LOGIN":
         if(isset($_POST['submit'])) 
          {
             $username = $_POST['username'];
             $password = md5($_POST['password']);
             if(!empty($username) && !empty($password))
             {
                $result = $portCont->lSearchAccountLogin($username, $password);
                if(!empty($result))
                {
                     $_SESSION['user_id'] = $result[0]['user_id'];   
                     header('Location:home.php?view=HOME'); 
                     exit;
                }
                else
                {
                     header('Location:?view=HOME&message=failed');
                }
             }
          }
        break;

  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Family Vill Clinic</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
    <?php 
    if(!empty($_GET['view']))
    {
        include('component/alert/alert.php'); 
    }
    ?>
    <!-- =======================================================
  * Template Name: Family Vill Clinic
  * Updated: Nov 13, 2024
  * Author: GMF
  ======================================================== -->
</head>

<body class="index-page">

    <header id="header" class="header sticky-top">
        <?php include('route/web/upperheader/upperheader.php'); ?>
        <?php include('route/web/navbar/navbar.php'); ?>
    </header>

    <main class="main">
        <?php if(!empty($_GET['view'])) { ?>
        <?php 
            switch($_GET['view']){
                case "HOME": 
                    include('route/web/content/home.php');
                    break;
                case "LOGIN":
                    include('route/web/content/login.php');
                    break;
                case "REGISTER":
                    include('route/web/content/register.php');
                    break;  
                case "VERIFICATION":
                    include('route/web/content/verification.php');
                    break; 
                case "FORGOT":
                    include('route/web/content/forgot.php');
                    break;  
                case "NEWPASSWORD":
                    include('route/web/content/newpassword.php');
                    break;  
                default:
                    include('route/web/content/home.php');
                    break;
            }
        ?>
        <?php } else { ?>
        <?php include('route/web/content/home.php'); ?>
        <?php } ?>
    </main>

    <footer id="footer" class="footer light-background">
        <?php include('route/web/footer/footer.php'); ?>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>