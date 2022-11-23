<?php 

?>
<div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="images/<?=$_SESSION['user']['profile_image']?>" alt=""><?=$_SESSION['user']['name']?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <!--li><a href="javascript:;"> Profile</a></li-->
                    <?php if($_SESSION['user']['category'] == 'staff') {?>
                    
                    <li><a href="change_password.php">Change Password</a></li>
                    <?php } ?>
                    <?php if($_SESSION['user']['category'] == 'admin') {?>
                    <li>
                      <a href="admin_settings">
                        <span>Settings</span>
                      </a>
                    </li>
                    <li><a href="change_password.php">Change Password</a></li>
                    <li><a href="package.php">Subscription Package</a></li>
                    <li><a href="permission">Permission</a></li>
                    <?php } ?>
                    <li><a href="dashboard">Dashboard</a></li>
                    <li><a href="logout"> Log Out</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>