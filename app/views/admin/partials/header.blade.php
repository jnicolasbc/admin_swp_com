		<div id="header">
				<div class="logo-area clearfix">
						<a href="#" class="logo"></a>
				</div>
				<!-- //logo-area-->
				<div class="tools-bar">
						<ul class="nav navbar-nav nav-main-xs">
								<li><a href="#" class="icon-toolsbar nav-mini"><i class="fa fa-bars"></i></a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right tooltip-area">
								<li><a href="#menu-right" data-toggle="tooltip" title="Right Menu" data-container="body" data-placement="left"><i class="fa fa-align-right"></i></a></li>
								<li class="hidden-xs hidden-sm"><a href="#" class="h-seperate">{{trans('main.help')}}</a></li>
								<li><button class="btn btn-circle btn-header-search" ><i class="fa fa-search"></i></button></li>
								<li><a href="#" class="nav-collapse avatar-header">
										<img alt="" src="{{url('assets/img/avatar.png')}}"  class="circle">
										<span class="badge">3</span>
									</a>
								</li>
								<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
											<em><strong>{{trans('main.hi')}}</strong>, Admin </em> <i class="dropdown-icon fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-right icon-right arrow">
												<li><a href="#"><i class="fa fa-user"></i> {{trans('main.profile')}}</a></li>
												<li><a href="#"><i class="fa fa-cog"></i> {{trans('main.setting')}} </a></li>
												<li><a href="#"><i class="fa fa-bookmark"></i> {{trans('main.bookmarks')}}</a></li>
												<li><a href="#"><i class="fa fa-money"></i> {{trans('main.make a deposit')}}</a></li>
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