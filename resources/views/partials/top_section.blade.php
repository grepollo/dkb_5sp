<div class="row">
		
			<!-- Profile Info and Notifications -->
			<div class="col-md-6 col-sm-8 clearfix">
		
				<ul class="user-info pull-left pull-none-xsm">
		
					<!-- Profile Info -->
					<li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->
		
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        	@if(session('user.type') != 'A')
								@if(file_exists(public_path('assets/images/users/' .  'user' . session('user.id') . '.jpg')))
                                    <img src="{{ asset('assets/images/users/' . 'user' . session('user.id') . '.jpg' ) }}" class="img-circle" width="44" height="44" />
                                @else
                                    <img src="{{  asset('assets/images/user.png') }}" class="img-circle" width="44" />
                                @endif
                            @else
                            	<img src="{{  asset('/assets/images/user.png') }}" class="img-circle" width="44" />

                            @endif
							{{  session('user.disp_name') }}
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
                            @if(session('user.type') !='A' )
                            <li>
								<a href="my_profile.php">
									<i class="entypo-user"></i>
									My Profile
								</a>
							</li>
                            @endif
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
						<a href="{{ url('/lockscreen') }}">
							Lock Screen <i class="entypo-key right"></i>
						</a>
					</li>
		
					<li class="sep"></li>
		
		
					<li>
						<a href="{{ url('/logout') }}">
							Log Out <i class="entypo-logout right"></i>
						</a>
					</li>
				</ul>
		
			</div>
		
		</div>