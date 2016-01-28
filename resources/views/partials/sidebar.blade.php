<div class="sidebar-menu">

		<div class="sidebar-menu-inner">
			
			<header class="logo-env">

				<!-- logo -->
				<div class="logo">
					<a href="index.php">
						<img src="assets/images/logo@2x.png" width="120" alt="" />
					</a>
				</div>

				<!-- logo collapse icon -->
				<div class="sidebar-collapse">
					<a href="#" class="sidebar-collapse-icon"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
						<i class="entypo-menu"></i>
					</a>
				</div>

								
				<!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
				<div class="sidebar-mobile-menu visible-xs">
					<a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
						<i class="entypo-menu"></i>
					</a>
				</div>

			</header>
			
									
			<ul id="main-menu" class="main-menu">
				<!-- add class "multiple-expanded" to allow multiple submenus to open -->
				<!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
                <li>
					<a href="dashboard.php">
						<i class="entypo-gauge"></i>
						<span class="title">Dashboard</span>
					</a>
				</li>
                @if(session('user.role') == 'M' || session('user.role') == 'A')
                <li>
					<a href="users.php">
						<i class="entypo-users"></i>
						<span class="title">User List</span>
					</a>
				</li>
                @endif
                @if(session('user.role') == 'A')
                <li>
					<a href="managers.php">
						<i class="entypo-users"></i>
						<span class="title">Manager List</span>
					</a>
				</li>
                <li>
					<a href="assign_users.php">
						<i class="entypo-users"></i>
						<span class="title">Assign Users to Manager</span>
					</a>
				</li>
                <li>
					<a href="invite_user.php">
						<i class="entypo-plus-circled"></i>
						<span class="title">Invite User</span>
					</a>
				</li>
                @endif
                <li>
					<a href="add_report.php">
						<i class="entypo-plus-circled"></i>
						<span class="title">Add Report</span>
					</a>
				</li>
                <li>
					<a href="reports.php">
						<i class="entypo-newspaper"></i>
						<span class="title">Report List</span>
					</a>
				</li>
                 <li>
					<a href="items.php">
						<i class="entypo-map"></i>
						<span class="title">Item List</span>
					</a>
				</li>
                <li>
					<a href="quick_capture.php">
						<i class="entypo-picture"></i>
						<span class="title">Quick Capture</span>
					</a>
				</li>
                 <li>
					<a href="file_tree.php">
						<i class="entypo-flow-cascade"></i>
						<span class="title">File Tree</span>
					</a>
				</li>

				@if(session('user.role') == 'M' || session('user.role') == 'U')
                 <li>
					<a href="my_profile.php">
						<i class="entypo-user"></i>
						<span class="title">Settings</span>
					</a>
				</li>
                @endif
                 <li>
					<a href="archive_reports.php">
						<i class="entypo-newspaper"></i>
						<span class="title">Archived Report List</span>
					</a>
				</li>
                <li>
					<a href="archive_items.php">
						<i class="entypo-map"></i>
						<span class="title">Archived Item List</span>
					</a>
				</li>
                
            </ul>
			
		</div>

	</div>