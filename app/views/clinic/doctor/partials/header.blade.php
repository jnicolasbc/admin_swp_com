		<div id="header">
		
				<div class="logo-area clearfix">
						<a href="#" class="logo"></a>
				</div>
				<!-- //logo-area-->
				
				<div class="tools-bar">
						
						<ul class="nav navbar-nav tooltip-area">
								<li><a href="{{url('/doctor/profile')}}" class="avatar-header">
										<img alt="" src="@if($profile = Profile::picture()!="") {{url(Profile::picture())}} @else {{url()}}/assets/doctor/images/profile_pic/default.png @endif"  class="circle">
									</a>
								</li>
								<li class="dropdown">
										@if($user = Sentry::getUser())
										<a href="{{url('/doctor/profile')}}" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
											<em><strong>Hi</strong>, {{$user->first_name}} </em> <i class="dropdown-icon fa fa-angle-down"></i>
										</a>
										@endif
										<ul class="dropdown-menu pull-right icon-right arrow">
												<li><a href="{{url('/doctor/profile')}}"><i class="fa fa-user"></i> Profile</a></li>
												<li class="divider"></li>
												<li><a href="{{url('/logout')}}"><i class="fa fa-sign-out"></i> {{trans('main.sign_out')}} </a></li>
										</ul>
										<!-- //dropdown-menu-->
								</li>
								<li class="visible-lg">
									<a href="#" class="h-seperate fullscreen" data-toggle="tooltip" title="Full Screen" data-container="body"  data-placement="left">
										<i class="fa fa-expand"></i>
									</a>
								</li>
						</ul>
				</div>
				<!-- //tools-bar-->
				
		</div>