<div class="row">
		
			<!-- Profile Info and Notifications -->
			<div class="col-md-6 col-sm-8 clearfix">
		
				<ul class="user-info pull-left pull-none-xsm">
		
					<!-- Profile Info -->
					<li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->
		
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        	<?php if($_SESSION['user']['type']!='A'){ ?>
                            
								<?php $usr_img = 'user'.$_SESSION['user']['id'].'.jpg';
                                if(getimagesize(MAINLOCATION.'assets/images/users/'.$usr_img)){ ?>
                                    <img src="<?php echo '../assets/images/users/'.$usr_img; ?>" class="img-circle" width="44" height="44" />
                                <?php }else{ ?>
                                    <img src="../assets/images/user.png" class="img-circle" width="44" />
                                <?php } ?>
                                
                                <?php echo $_SESSION['user']['disp_name']; ?>
                            
                            <?php }else{ ?>
                            		<img src="../assets/images/user.png" class="img-circle" width="44" />
                                    <?php echo $_SESSION['user']['disp_name']; ?>
                            <?php } ?>
						</a>
		
						<ul class="dropdown-menu">
		
							<!-- Reverse Caret -->
							<li class="caret"></li>
		
							<!-- Profile sub-links -->
							<li>
								<a href="change_password.php">
									<i class="entypo-user"></i>
									Change Password
								</a>
							</li>
                            <?php if($_SESSION['user']['type']!='A'){ ?>
                            <li>
								<a href="my_profile.php">
									<i class="entypo-user"></i>
									My Profile
								</a>
							</li>
                            <?php } ?>
						</ul>
					</li>
		
				</ul>
				
				
		
			</div>
		
		
			<!-- Raw Links -->
			<div class="col-md-6 col-sm-4 clearfix hidden-xs">
		
				<ul class="list-inline links-list pull-right">
		
					<!-- Language Selector -->
                    <li class="sep"></li>
                    
					<li>
						<a href="lockscreen.php">
							Lock Screen <i class="entypo-key right"></i>
						</a>
					</li>
		
					<li class="sep"></li>
		
		
					<li>
						<a href="logout.php">
							Log Out <i class="entypo-logout right"></i>
						</a>
					</li>
				</ul>
		
			</div>
		
		</div>