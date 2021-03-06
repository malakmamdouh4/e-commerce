<?php


    ob_start(); // Output Buffering Start ( store every thing in memory except header )

    session_start();

    if (isset($_SESSION['UserName']))
    {
        $pageTitle = 'Dashboard' ;
        include 'init.php' ;

        $numUsers = 6; // Number Of Latest Users

        $latestUsers = getLatest("*", "users", "ID", $numUsers); // Latest Users Array

        $numItems = 6; // Number Of Latest Items

//        $latestItems = getLatest("*", 'items', 'Item_ID', $numItems); // Latest Items Array
//
//        $numComments = 4;
        ?>

        <div class="home-stats">
			<div class="container text-center">
				<h1>Dashboard</h1>
				<div class="row">
					<div class="col-md-3">
						<div class="stat st-members">
							<i class="fa fa-users"></i>
							<div class="info">
								Total Members
								<span>
									<a href="members.php"><?php echo countItems('ID', 'users') ?></a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat st-pending">
                            <i class="fa fa-user-plus"></i>
                            <div class="info">
                                Pending Members
                                <span>
                                                        <a href="members.php?route=Manage&page=Pending">
                                                            <?php echo checkItem("RegStatus", "users", 0) ?>
                                                        </a>
                                                    </span>
                            </div>
                        </div>
                    </div>

<!--                    <div class="col-md-3">-->
<!--                        <div class="stat st-items">-->
<!--                            <i class="fa fa-tag"></i>-->
<!--                            <div class="info">-->
<!--                                Total Items-->
<!--                                <span>-->
<!--                                                        <a href="items.php">--><?php //echo countItems('Item_ID', 'items') ?><!--</a>-->
<!--                                                    </span>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->

<!--                    <div class="col-md-3">-->
<!--                        <div class="stat st-comments">-->
<!--                            <i class="fa fa-comments"></i>-->
<!--                            <div class="info">-->
<!--                                Total Comments-->
<!--                                <span>-->
<!--                                                        <a href="comments.php">--><?php //echo countItems('c_id', 'comments') ?><!--</a>-->
<!--                                                    </span>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>
        </div>

        <div class="latest">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-users"></i>
                                Latest <?php echo $numUsers ?> Registerd Users
                                <span class="toggle-info pull-right">
                                            <i class="fa fa-plus fa-lg"></i>
                                        </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                    <?php
                                    if (! empty($latestUsers)) {
                                        foreach ($latestUsers as $user) {
                                            echo '<li>';
                                            echo $user['UserName'];
                                            echo '<a href="members.php?route=Edit&id=' . $user['ID'] . '">';
                                            echo '<span class="btn btn-success pull-right">';
                                            echo '<i class="fa fa-edit"></i> Edit';
                                            if ($user['RegStatus'] == 0) {
                                                echo "<a 
                                                                            href='members.php?route=Activate&id=" . $user['ID'] . "' 
                                                                            class='btn btn-info pull-right activate'>
                                                                            <i class='fa fa-check'></i> Activate</a>";
                                            }
                                            echo '</span>';
                                            echo '</a>';
                                            echo '</li>';
                                        }
                                    } else {
                                        echo 'There\'s No Members To Show';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-tag"></i> Latest <?php echo $numItems ?> Items
                                <span class="toggle-info pull-right">
                                            <i class="fa fa-plus fa-lg"></i>
                                        </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                    <?php
                                    if (! empty($latestItems)) {
                                        foreach ($latestItems as $item) {
                                            echo '<li>';
                                            echo $item['Name'];
                                            echo '<a href="items.php?route=Edit&itemid=' . $item['Item_ID'] . '">';
                                            echo '<span class="btn btn-success pull-right">';
                                            echo '<i class="fa fa-edit"></i> Edit';
                                            if ($item['Approve'] == 0) {
                                                echo "<a 
                                                                                href='items.php?route=Approve&itemid=" . $item['Item_ID'] . "' 
                                                                                class='btn btn-info pull-right activate'>
                                                                                <i class='fa fa-check'></i> Approve</a>";
                                            }
                                            echo '</span>';
                                            echo '</a>';
                                            echo '</li>';
                                        }
                                    } else {
                                        echo 'There\'s No Items To Show';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Start Latest Comments -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-comments-o"></i>
                                Latest <?php echo $numComments ?> Comments
                                <span class="toggle-info pull-right">
                                            <i class="fa fa-plus fa-lg"></i>
                                        </span>
                            </div>
                            <div class="panel-body">
                                <?php
                                $stmt = $con->prepare("SELECT 
                                                                        comments.*, users.Username AS Member  
                                                                    FROM 
                                                                        comments
                                                                    INNER JOIN 
                                                                        users 
                                                                    ON 
                                                                        users.UserID = comments.user_id
                                                                    ORDER BY 
                                                                        c_id DESC
                                                                    LIMIT $numComments");

                                $stmt->execute();
                                $comments = $stmt->fetchAll();

                                if (! empty($comments)) {
                                    foreach ($comments as $comment) {
                                        echo '<div class="comment-box">';
                                        echo '<span class="member-n">
                                                            <a href="members.php?route=Edit&id=' . $comment['user_id'] . '">
                                                                ' . $comment['Member'] . '</a></span>';
                                        echo '<p class="member-c">' . $comment['comment'] . '</p>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo 'There\'s No Comments To Show';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Latest Comments -->
            </div>
        </div>

        <?php

        include $tpls . 'footer.php' ;

    }
    else
    {
        header('Location: index.php');
        exit();
    }

    ob_end_flush(); // Release The Output
   ?>
