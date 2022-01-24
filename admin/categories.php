<?php

   ob_start();
   session_start();
   $pageTitle = 'Categories' ;

   if (isset($_SESSION['UserName']))
   {
       include 'init.php';

       $route = isset($_GET['route']) ? $_GET['route'] : 'Manage' ;

       if ($route == 'Manage')
       {
           echo 'welcome' ;
       }
       elseif ($route == 'Add')
       {
           ?>
           <h1 class="text-center"> Add New Category </h1>
           <div class="container">
               <form class="form-horizontal" action="?route=Insert" method="POST" enctype="multipart/form-data">

                   <div class="form-group form-group-lg">
                       <label class="col-sm-2 control-label"> Name </label>
                       <div class="col-sm-10 col-md-6">
                           <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="name of category" />
                       </div>
                   </div>

                   <div class="form-group form-group-lg">
                       <label class="col-sm-2 control-label"> Description </label>
                       <div class="col-sm-10 col-md-6">
                           <input type="text" name="description" class="form-control" placeholder="Descripe your category" />
                           <i class="show-pass fa fa-eye fa-2x"></i>
                       </div>
                   </div>

                   <div class="form-group form-group-lg">
                       <label class="col-sm-2 control-label"> Ordering </label>
                       <div class="col-sm-10 col-md-6">
                           <input type="text" name="ordering" class="form-control" placeholder="order category" />
                       </div>
                   </div>

                   <div class="form-group form-group-lg">
                       <label class="col-sm-2 control-label"> Visible </label>
                       <div class="col-sm-10 col-md-6">
                           <div>
                               <input id="vis-yes" type="radio" name="visibility" value="0" checked>
                               <label for="vis-yes"> Yes </label>
                           </div>
                           <div>
                               <input id="vis-no" type="radio" name="visibility" value="1">
                               <label for="vis-no"> No </label>
                           </div>
                       </div>
                   </div>

                   <div class="form-group form-group-lg">
                       <label class="col-sm-2 control-label"> Allow Commenting </label>
                       <div class="col-sm-10 col-md-6">
                           <div>
                               <input id="com-yes" type="radio" name="commenting" value="0" checked>
                               <label for="com-yes"> Yes </label>
                           </div>
                           <div>
                               <input id="com-no" type="radio" name="commenting" value="1">
                               <label for="com-no"> No </label>
                           </div>
                       </div>
                   </div>

                   <div class="form-group form-group-lg">
                       <label class="col-sm-2 control-label"> Ads </label>
                       <div class="col-sm-10 col-md-6">
                           <div>
                               <input id="ads-yes" type="radio" name="ads" value="0" checked>
                               <label for="ads-yes"> Yes </label>
                           </div>
                           <div>
                               <input id="ads-no" type="radio" name="ads" value="1">
                               <label for="ads-no"> No </label>
                           </div>
                       </div>
                   </div>

                   <div class="form-group form-group-lg">
                       <div class="col-sm-offset-2 col-sm-10">
                           <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
                       </div>
                   </div>
                   <!-- End Submit Field -->
               </form>
           </div>

           <?php
       }
       elseif($route=='Insert')
       {
           if ($_SERVER['REQUEST_METHOD'] == 'POST') {

               echo "<h1 class='text-center'>Insert Category </h1>";
               echo "<div class='container'>";

               $name 	       = $_POST['name'];
               $description    = $_POST['description'];
               $order 	       = $_POST['ordering'];
               $visible 	   = $_POST['visibility'];
               $comment 	   = $_POST['commenting'];
               $ads 	       = $_POST['ads'];
                   $check = checkItem("Name", "categories", $name);
                   if ($check == 1) {
                       $theMsg = '<div class="alert alert-danger">Sorry This Category Is Exist</div>';
                       redirectHome($theMsg, 'back');
                   } else {
                       $addcategory = $con->prepare("INSERT INTO 
												     	categories(name, description, ordering, visibility, allow_comment , allow_ads)
												       VALUES(:zname, :zdescription, :zorder, :zvisible, :zcomment , :zads) ");
                       $addcategory->execute(array(
                           'zname' 	=> $name,
                           'zdescription' 	=> $description,
                           'zorder' 	=> $order,
                           'zvisible' 	=> $visible,
                           'zcomment'	=> $comment ,
                           'zads'	=> $ads
                       ));

                       $theMsg = "<div class='alert alert-success'>" . $addcategory->rowCount() . ' Record Inserted</div>';
                       redirectHome($theMsg, 'back');

                   }

               }


           } else {

               echo "<div class='container'>";

               $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

               redirectHome($theMsg);

               echo "</div>";

           }

           echo "</div>";

   }
   else
   {
       header('Location:index.php');
       exit();
   }

   ob_end_flush();

   ?>


