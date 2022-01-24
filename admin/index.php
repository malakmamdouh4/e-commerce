

<?php

   session_start() ;
    if (isset($_SESSION['UserName']))
    {
        header('Location:dashboard.php');
    }


   $noNavbar = '' ;
   $pageTitle = 'Login' ;
   include 'init.php' ;


   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
       $username = $_POST['name'] ;
       $userpassword = $_POST['password'];
       $hashpassword = sha1($userpassword);


       $user = $con->prepare("SELECT ID, UserName, Password FROM users WHERE UserName = ? AND Password = ? AND GroupID = 1 LIMIT 1");
       $user->execute(array($username,$hashpassword));
       $array = $user->fetch();
       $count = $user->rowCount();

       if ($count > 0 )
       {
           $_SESSION['UserName'] = $username ;
           $_SESSION['ID'] = $array['ID'] ;
           header('Location:Dashboard.php');
           exit();
       }

   }

?>


<form class="login" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
     <h2 class="text-center"> Admin Login </h2>
     <input class="form-control" type="text" name="name" placeholder="username">
     <input class="form-control" type="password" name="password" placeholder="password">
     <input class="btn btn-primary btn-block" type="submit" value="login">
</form>


<?php include $tpls . 'footer.php' ;    ?>