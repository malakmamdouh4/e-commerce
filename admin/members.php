<?php

    session_start();

    $pageTitle = 'Members' ;

    if (isset($_SESSION['UserName']))
    {
        include 'init.php' ;

        $route = isset($_GET['route']) ? $_GET['route'] : 'Manage' ;

        if ($route == 'Manage')
        {
            $query = '';
            if (isset($_GET['page']) && $_GET['page'] == 'Pending')
            {
                $query = 'AND RegStatus = 0';
            }

            $users = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY ID DESC");
            $users->execute();
            $rows = $users->fetchAll();

            if (! empty($rows)) {   ?>

                <h1 class="text-center">Manage Members</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table manage-members text-center table table-bordered">
                            <tr>
                                <td>#ID</td>
<!--                                <td>Avatar</td>-->
                                <td>Username</td>
                                <td>Email</td>
                                <td>Full Name</td>
                                <td>Registered Date</td>
                                <td>Control</td>
                            </tr>
                            <?php
                            foreach($rows as $row) {
                                echo "<tr>";
                                echo "<td>" . $row['ID'] . "</td>";
//                                echo "<td>";
//                                if (empty($row['avatar'])) {
//                                    echo 'No Image';
//                                } else {
//                                    echo "<img src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
//                                }
//                                echo "</td>";

                                echo "<td>" . $row['UserName'] . "</td>";
                                echo "<td>" . $row['Email'] . "</td>";
                                echo "<td>" . $row['FullName'] . "</td>";
                                echo "<td>" . $row['Date'] ."</td>";
                                echo "<td>
										<a href='members.php?route=Edit&id=" . $row['ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='members.php?route=Delete&id=" . $row['ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                if ($row['RegStatus'] == 0) {
                                    echo "<a 
													href='members.php?route=Activate&id=" . $row['ID'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Activate</a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                            <tr>
                        </table>
                    </div>
                    <a href="members.php?route=Add" class="btn btn-primary">
                        <i class="fa fa-plus"></i> New Member
                    </a>
                </div>

            <?php
            }
            else
            {
                echo '<div class="container">';
                echo '<div class="nice-message">There\'s No Members To Show</div>';
                echo '<a href="members.php?route=Add" class="btn btn-primary">
							<i class="fa fa-plus"></i> New Member
						</a>';
                echo '</div>';

            } ?>

            <?php


        }
        elseif ($route == 'Add')
        { ?>
            <h1 class="text-center">Add New Member</h1>
			<div class="container">
				<form class="form-horizontal" action="?route=Insert" method="POST" enctype="multipart/form-data">

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Username</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login Into Shop" />
						</div>
					</div>

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10 col-md-6">
							<input type="password" name="password" class="password form-control" required="required" autocomplete="new-password" placeholder="Password Must Be Hard & Complex" />
							<i class="show-pass fa fa-eye fa-2x"></i>
						</div>
					</div>

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-6">
							<input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid" />
						</div>
					</div>

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Full Name</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="full" class="form-control" required="required" placeholder="Full Name Appear In Your Profile Page" />
						</div>
					</div>

<!--					<div class="form-group form-group-lg">-->
<!--						<label class="col-sm-2 control-label">User Avatar</label>-->
<!--						<div class="col-sm-10 col-md-6">-->
<!--							<input type="file" name="avatar" class="form-control" required="required" />-->
<!--						</div>-->
<!--					</div>-->

					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
						</div>
					</div>
					<!-- End Submit Field -->
				</form>
			</div>

		<?php
        }

        elseif ($route == 'Insert')
        {

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h1 class='text-center'>Insert Member</h1>";
                echo "<div class='container'>";

//                $avatarName = $_FILES['avatar']['name'];
//                $avatarSize = $_FILES['avatar']['size'];
//                $avatarTmp	= $_FILES['avatar']['tmp_name'];
//                $avatarType = $_FILES['avatar']['type'];
//
//                $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");
//                $avatarExtension = strtolower(end(explode('.', $avatarName)));

                $user 	= $_POST['username'];
                $pass 	= $_POST['password'];
                $email 	= $_POST['email'];
                $name 	= $_POST['full'];

                $hashPass = sha1($_POST['password']);

                $formErrors = array();

                if (strlen($user) < 4) {
                    $formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
                }

                if (strlen($user) > 20) {
                    $formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
                }

                if (empty($user)) {
                    $formErrors[] = 'Username Cant Be <strong>Empty</strong>';
                }

                if (empty($pass)) {
                    $formErrors[] = 'Password Cant Be <strong>Empty</strong>';
                }

                if (empty($name)) {
                    $formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
                }

                if (empty($email)) {
                    $formErrors[] = 'Email Cant Be <strong>Empty</strong>';
                }

//                if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
//                    $formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
//                }
//
//                if (empty($avatarName)) {
//                    $formErrors[] = 'Avatar Is <strong>Required</strong>';
//                }
//
//                if ($avatarSize > 4194304) {
//                    $formErrors[] = 'Avatar Cant Be Larger Than <strong>4MB</strong>';
//                }

                foreach($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }

                if (empty($formErrors)) {

//                    $avatar = rand(0, 10000000000) . '_' . $avatarName;
//
//                    move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);

                    $check = checkItem("UserName", "users", $user);


                    if ($check == 1) {

                        $theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';

                       redirectHome($theMsg, 'back');

                    } else {

                        $adduser = $con->prepare("INSERT INTO 
													users(UserName, Password, Email, FullName, RegStatus , Date)
												VALUES(:zuser, :zpass, :zmail, :zname, 1 , now()) ");
                        $adduser->execute(array(

                            'zuser' 	=> $user,
                            'zpass' 	=> $hashPass,
                            'zmail' 	=> $email,
                            'zname' 	=> $name,
//                            'zavatar'	=> $avatar

                        ));

                        $theMsg = "<div class='alert alert-success'>" . $adduser->rowCount() . ' Record Inserted</div>';
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

            $userid = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

            $user = $con->prepare("SELECT * FROM users WHERE ID = ? LIMIT 1");
            $user->execute(array($userid));
            $row = $user->fetch();
            $count = $user->rowCount();

            if ($count > 0) { ?>

                <h1 class="text-center">Edit Member</h1>
                <div class="container">
                    <form class="form-horizontal" action="?route=Update" method="POST">
                        <input type="hidden" name="id" value="<?php echo $userid ?>" />

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="username" class="form-control" value="<?php echo $row['UserName'] ?>" autocomplete="off" required="required" />
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
                                <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change" />
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required" />
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Full Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="required" />
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

                // If There's No Such ID Show Error Message

            } else {

                echo "<div class='container'>";

                $theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';

                redirectHome($theMsg);

                echo "</div>";

            }


        }
        elseif($route == 'Update')
        {

            echo "<h1 class='text-center'>  Update Member </h1>";
            echo "<div class='container'>";

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $id 	= $_POST['id'];
                $user 	= $_POST['username'];
                $email 	= $_POST['email'];
                $name 	= $_POST['full'];

                $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                $formErrors = array();

                if (strlen($user) < 4) {
                    $formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
                }

                if (strlen($user) > 20) {
                    $formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
                }

                if (empty($user)) {
                    $formErrors[] = 'Username Cant Be <strong>Empty</strong>';
                }

                if (empty($name)) {
                    $formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
                }

                if (empty($email)) {
                    $formErrors[] = 'Email Cant Be <strong>Empty</strong>';
                }

                foreach($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }

                if (empty($formErrors)) {

                    $stmt2 = $con->prepare("SELECT * FROM users WHERE UserName = ? AND ID != ?");
                    $stmt2->execute(array($user, $id));
                    $count = $stmt2->rowCount();

                    if ($count == 1) {

                        $theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';

                        redirectHome($theMsg, 'back');

                    } else {

                        $stmt = $con->prepare("UPDATE users SET UserName = ?, Email = ?, FullName = ?, Password = ? WHERE ID = ?");
                        $stmt->execute(array($user, $email, $name, $pass, $id));
                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

                        redirectHome($theMsg, 'back');

                    }

                }

            } else
            {
                $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
                redirectHome($theMsg);

            }
            echo "</div>";

        }

    elseif ($route == 'Delete')
    {

        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";

        $userid = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;


        $check = checkItem('id', 'users', $userid);

        if ($check > 0) {

            $stmt = $con->prepare("DELETE FROM users WHERE ID = :zuser");

            $stmt->bindParam(":zuser", $userid);

            $stmt->execute();

            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

            redirectHome($theMsg, 'back');

        }
        else {

            $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

            redirectHome($theMsg);

        }

        echo '</div>';
    }
        elseif ($route == 'Activate') {

            echo "<h1 class='text-center'>Activate Member</h1>";
            echo "<div class='container'>";

            // Check If Get Request userid Is Numeric & Get The Integer Value Of It

            $userid = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

            // Select All Data Depend On This ID

            $check = checkItem('id', 'users', $userid);

            // If There's Such ID Show The Form

            if ($check > 0) {

                $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE ID = ?");

                $stmt->execute(array($userid));

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

                redirectHome($theMsg);

            } else {

                $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

                redirectHome($theMsg);

            }

            echo '</div>';

        }

        include $tpls . 'footer.php' ;

    }
    else
    {
        header('Location: index.php');
        exit();
    }
