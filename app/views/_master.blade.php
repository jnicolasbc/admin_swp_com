<!DOCTYPE html>
<html lang="en">
<head>
<!-- Meta information -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<!-- Title-->
<title>AgendaMedica |  @yield('title')</title>
<!-- Favicons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{url('assets/ico/apple-touch-icon-144-precomposed.png')}}">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{url('assets/ico/apple-touch-icon-114-precomposed.png')}}">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{url('assets/ico/apple-touch-icon-72-precomposed.png')}}">
<link rel="apple-touch-icon-precomposed" href="{{url('assets/ico/apple-touch-icon-57-precomposed.png')}}">
<link rel="shortcut icon" href="{{url('assets/ico/favicon.ico')}}">
<!-- CSS Stylesheet-->
<link type="text/css" rel="stylesheet" href="{{url('assets/css/bootstrap/bootstrap.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{url('assets/css/bootstrap/bootstrap-themes.css')}}" />
<link type="text/css" rel="stylesheet" href="{{url('assets/css/style.css')}}" />

<!-- Styleswitch if  you don't chang theme , you can delete -->
<link type="text/css" rel="alternate stylesheet" media="screen" title="style1" href="{{url('assets/css/styleTheme1.css')}}" />
<link type="text/css" rel="alternate stylesheet" media="screen" title="style2" href="{{url('assets/css/styleTheme2.css')}}" />
<link type="text/css" rel="alternate stylesheet" media="screen" title="style3" href="{{url('assets/css/styleTheme3.css')}}" />
<link type="text/css" rel="alternate stylesheet" media="screen" title="style4" href="{{url('assets/css/styleTheme4.css')}}" />

</head>
<body class="leftMenu nav-collapse">
<div id="wrapper">
		<!--
		/////////////////////////////////////////////////////////////////////////
		//////////     HEADER  CONTENT     ///////////////
		//////////////////////////////////////////////////////////////////////
		-->
		@include('partials.header')
		<!-- //header-->
		
		
		<!--
		/////////////////////////////////////////////////////////////////////////
		//////////     SLIDE LEFT CONTENT     //////////
		//////////////////////////////////////////////////////////////////////
		-->
		@include('partials.slide_left')
		<!-- //nav-->
		
		
		<!--
		/////////////////////////////////////////////////////////////////////////
		//////////     TOP SEARCH CONTENT     ///////
		//////////////////////////////////////////////////////////////////////
		-->
		@include('partials.search')
		<!-- //widget-top-search-->
		
		
				
		<!--
		/////////////////////////////////////////////////////////////////////////
		//////////     MAIN SHOW CONTENT     //////////
		//////////////////////////////////////////////////////////////////////
		-->
		<div id="main">
			@yield('content')						
		</div>
		<!-- //main-->
		
		
		
		<!--
		///////////////////////////////////////////////////////////////////
		//////////     MODAL MESSAGES     //////////
		///////////////////////////////////////////////////////////////
		-->
		@include('partials.message')
		<!-- //modal-->
		
		
		
		<!--
		//////////////////////////////////////////////////////////////////////////
		//////////     MODAL NOTIFICATION     //////////
		//////////////////////////////////////////////////////////////////////
		-->
		@include('partials.notification')
		<!-- //modal-->
		
			
		<!--
		//////////////////////////////////////////////////////////////
		//////////     LEFT NAV MENU     //////////
		///////////////////////////////////////////////////////////
		-->
		@include('partials.menu_left')
		<!-- //nav left menu-->
		
		
		
		<!--
		/////////////////////////////////////////////////////////////////
		//////////     RIGHT NAV MENU     //////////
		/////////////////////////////////////////////////////////////
		-->
		@include('partials.menu_right')		
		<!-- //nav right menu-->
		
		
</div>
<!-- //wrapper-->


<!--
////////////////////////////////////////////////////////////////////////
//////////     JAVASCRIPT  LIBRARY     //////////
/////////////////////////////////////////////////////////////////////
-->
		
<!-- Jquery Library -->
<script type="text/javascript" src="{{url('assets/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{url('assets/js/jquery.ui.min.js')}}"></script>
<script type="text/javascript" src="{{url('assets/plugins/bootstrap/bootstrap.min.js')}}"></script>
<!-- Modernizr Library For HTML5 And CSS3 -->
<script type="text/javascript" src="{{url('assets/js/modernizr/modernizr.js')}}"></script>
<script type="text/javascript" src="{{url('assets/plugins/mmenu/jquery.mmenu.js')}}"></script>
<script type="text/javascript" src="{{url('assets/js/styleswitch.js')}}"></script>
<!-- Library 10+ Form plugins-->
<script type="text/javascript" src="{{url('assets/plugins/form/form.js')}}"></script>
<!-- Datetime plugins -->
<script type="text/javascript" src="{{url('assets/plugins/datetime/datetime.js')}}"></script>
<!-- Library Chart-->
<script type="text/javascript" src="{{url('assets/plugins/chart/chart.js')}}"></script>
<!-- Library  5+ plugins for bootstrap -->
<script type="text/javascript" src="{{url('assets/plugins/pluginsForBS/pluginsForBS.js')}}"></script>
<!-- Library 10+ miscellaneous plugins -->
<script type="text/javascript" src="{{url('assets/plugins/miscellaneous/miscellaneous.js')}}"></script>
<!-- Library Themes Customize-->
<script type="text/javascript" src="{{url('assets/js/caplet.custom.js')}}"></script>
</body>
</html>