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

           $sort = 'asc';
           $sort_array = array('asc', 'desc');
           if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
               $sort = $_GET['sort'];
           }

           $stmt2 = $con->prepare("SELECT * FROM categories  WHERE parent = 0 ORDER BY Ordering $sort");
           $stmt2->execute();
           $cats = $stmt2->fetchAll();

           if (! empty($cats)) {
               ?>
               <h1 class="text-center">Manage Categories</h1>
               <div class="container categories">
                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <i class="fa fa-edit"></i> Manage Categories
                           <div class="option pull-right">
                               <i class="fa fa-sort"></i> Ordering: [
                               <a class="<?php if ($sort == 'asc') { echo 'active'; } ?>" href="?sort=asc">Asc</a> |
                               <a class="<?php if ($sort == 'desc') { echo 'active'; } ?>" href="?sort=desc">Desc</a> ]
                               <i class="fa fa-eye"></i> View: [
                               <span class="active" data-view="full">Full</span> |
                               <span data-view="classic">Classic</span> ]
                           </div>
                       </div>
                       <div class="panel-body">
                           <?php
                           foreach($cats as $cat) {
                               echo "<div class='cat'>";
                               echo "<div class='hidden-buttons'>";
                               echo "<a href='categories.php?route=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                               echo "<a href='categories.php?route=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
                               echo "</div>";
                               echo "<h3>" . $cat['name'] . '</h3>';
                               echo "<div class='full-view'>";
                               echo "<p>"; if($cat['description'] == '') { echo 'This category has no description'; } else { echo $cat['description']; } echo "</p>";
                               if($cat['visibility'] == 1) { echo '<span class="visibility cat-span"><i class="fa fa-eye"></i> Hidden</span>'; }
                               if($cat['allow_comment'] == 1) { echo '<span class="commenting cat-span"><i class="fa fa-close"></i> Comment Disabled</span>'; }
                               if($cat['allow_ads'] == 1) { echo '<span class="advertises cat-span"><i class="fa fa-close"></i> Ads Disabled</span>'; }
                               echo "</div>";

                               // Get Child Categories
                               $childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID", "ASC");
                               if (! empty($childCats)) {
                                   echo "<h4 class='child-head'>Child Categories</h4>";
                                   echo "<ul class='list-unstyled child-cats'>";
                                   foreach ($childCats as $c) {
                                       echo "<li class='child-link'>
												<a href='categories.php?route=Edit&catid=" . $c['ID'] . "'>" . $c['name'] . "</a>
												<a href='categories.php?route=Delete&catid=" . $c['ID'] . "' class='show-delete confirm'> Delete</a>
											</li>";
                                   }
                                   echo "</ul>";
                               }

                               echo "</div>";
                               echo "<hr>";
                           }
                           ?>
                       </div>
                   </div>
                   <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
               </div>

           <?php } else {

               echo '<div class="container">';
               echo '<div class="nice-message">There\'s No Categories To Show</div>';
               echo '<a href="categories.php?do=Add" class="btn btn-primary">
							<i class="fa fa-plus"></i> New Category
						</a>';
               echo '</div>';

           } ?>

           <?php


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

   elseif ($route == 'Edit')
   {

       $catid = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

       $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
       $stmt->execute(array($catid));
       $cat = $stmt->fetch();
       $count = $stmt->rowCount();

       if ($count > 0) { ?>

           <h1 class="text-center">Edit Category</h1>
           <div class="container">
               <form class="form-horizontal" action="?route=Update" method="POST">
                   <input type="hidden" name="id" value="<?php echo $catid ?>" />

                   <div class="form-group form-group-lg">
                       <label class="col-sm-2 control-label">Name</label>
                       <div class="col-sm-10 col-md-6">
                           <input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Category" value="<?php echo $cat['name'] ?>" />
                       </div>
                   </div>

                   <div class="form-group form-group-lg">
                       <label class="col-sm-2 control-label">Description</label>
                       <div class="col-sm-10 col-md-6">
                           <input type="text" name="description" class="form-control" placeholder="Describe The Category" value="<?php echo $cat['description'] ?>" />
                       </div>
                   </div>

                   <div class="form-group form-group-lg">
                       <label class="col-sm-2 control-label">Ordering</label>
                       <div class="col-sm-10 col-md-6">
                           <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" value="<?php echo $cat['ordering'] ?>" />
                       </div>
                   </div>

                   <div class="form-group form-group-lg">
                       <label class="col-sm-2 control-label">Parent?</label>
                       <div class="col-sm-10 col-md-6">
                           <select name="parent">
                               <option value="0">None</option>
                               <?php
                               $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "ASC");
                               foreach($allCats as $c) {
                                   echo "<option value='" . $c['ID'] . "'";
                                   if ($cat['parent'] == $c['ID']) { echo ' selected'; }
                                   echo ">" . $c['name'] . "</option>";
                               }
                               ?>
                           </select>
                       </div>
                   </div>

                   <div class="form-group form-group-lg">
                       <label class="col-sm-2 control-label">Visible</label>
                       <div class="col-sm-10 col-md-6">
                           <div>
                               <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['visibility'] == 0) { echo 'checked'; } ?> />
                               <label for="vis-yes">Yes</label>
                           </div>
                           <div>
                               <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['visibility'] == 1) { echo 'checked'; } ?> />
                               <label for="vis-no">No</label>
                           </div>
                       </div>
                   </div>

                   <div class="form-group form-group-lg">
                       <label class="col-sm-2 control-label">Allow Commenting</label>
                       <div class="col-sm-10 col-md-6">
                           <div>
                               <input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['allow_comment'] == 0) { echo 'checked'; } ?> />
                               <label for="com-yes">Yes</label>
                           </div>
                           <div>
                               <input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['allow_comment'] == 1) { echo 'checked'; } ?> />
                               <label for="com-no">No</label>
                           </div>
                       </div>
                   </div>

                   <div class="form-group form-group-lg">
                       <label class="col-sm-2 control-label">Allow Ads</label>
                       <div class="col-sm-10 col-md-6">
                           <div>
                               <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['allow_ads'] == 0) { echo 'checked'; } ?>/>
                               <label for="ads-yes">Yes</label>
                           </div>
                           <div>
                               <input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['allow_ads'] == 1) { echo 'checked'; } ?>/>
                               <label for="ads-no">No</label>
                           </div>
                       </div>
                   </div>

                   <div class="form-group form-group-lg">
                       <div class="col-sm-offset-2 col-sm-10">
                           <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                       </div>
                   </div>

               </form>
           </div>

           <?php

       }
       else
       {

           echo "<div class='container'>";

           $theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';

           redirectHome($theMsg);

           echo "</div>";

       }

   }
   elseif($route == 'Update')
   {
       echo 'welcome' ;
   }
   else
   {
       header('Location:index.php');
       exit();
   }

   ob_end_flush();

   ?>


