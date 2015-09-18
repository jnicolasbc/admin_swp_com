		<div id="header">
		
				<div class="logo-area clearfix">
						<a href="#" class="logo"></a>
				</div>
				<!-- //logo-area-->
				
				<div class="tools-bar">
						
						<ul class="nav navbar-nav navbar-right tooltip-area">
								<li><a href="{{url('/clinic/admin-profile')}}" class="avatar-header">
										<img alt="" src="@if($profile = Profile::picture()!="") {{url(Profile::picture())}} @else {{url()}}/assets/doctor/images/profile_pic/default.png @endif"  class="circle">
									</a>
								</li>
								<li class="dropdown">
										@if($user = Sentry::getUser())
										<a href="{{url('/clinic/admin-profile')}}" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
											<em><strong>{{trans('main.hi')}}</strong>, {{$user->first_name}} </em>
										</a>
										@endif
										<!-- //dropdown-menu-->
								</li>
								<li class="visible-lg">
									<a href="#" class="h-seperate fullscreen" data-toggle="tooltip" title="{{trans('main.Full Screen')}}" data-container="body"  data-placement="left">
										<i class="fa fa-expand"></i>
									</a>
								</li>
						</ul>
				</div>
				<!-- //tools-bar-->
				
		</div>