<!DOCTYPE html>
<html lang="en">
<head>
<!-- Meta information -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<!-- Title-->
<title>SMART DOCTOR APPOINTMENTS |  @yield('title')</title>
<!-- Favicons -->
{{ HTML::style('assets/css/oxygen/icons.css') }}
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{url('assets/ico/apple-touch-icon-144-precomposed.png')}}">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{url('assets/ico/apple-touch-icon-114-precomposed.png')}}">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{url('assets/ico/apple-touch-icon-72-precomposed.png')}}">
<link rel="apple-touch-icon-precomposed" href="{{url('assets/ico/apple-touch-icon-57-precomposed.png')}}">
<link rel="shortcut icon" href="{{url('assets/ico/favicon.ico')}}">
<!-- CSS Stylesheet-->
<link type="text/css" rel="stylesheet" href="{{url('assets/css/bootstrap/bootstrap.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{url('assets/css/bootstrap/bootstrap-themes.css')}}" />
<link type="text/css" rel="stylesheet" href="{{url('assets/css/style.css')}}" />
<link rel="stylesheet" type="text/css" href="{{url('assets/css/pnotify.core.css')}}"/>
<link type="text/css" rel="stylesheet" href="http://fortawesome.github.io/Font-Awesome/assets/font-awesome/css/font-awesome.css" />
<link type="text/css" rel="stylesheet" href="{{url('assets/plugins/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css')}}" />

<!-- Styleswitch if  you don't chang theme , you can delete -->
<link type="text/css" rel="stylesheet"  href="{{url('assets/css/clinics.css')}}" />
<link rel='stylesheet' type='text/css'href="{{url('assets/css/timepicki.css')}}"/>
<link href="{{url('assets/plugins/fullcalendar/fullcalendar.css')}}" rel="stylesheet" />
<link href="{{url('assets/css/style_typehead.css')}}" rel="stylesheet" />

</head>
<body class="leftMenu nav-collapse">
<div id="wrapper">
		<!--
		/////////////////////////////////////////////////////////////////////////
		//////////////    HEADER  CONTENT     ///////////////
		//////////////////////////////////////////////////////////////////////
		-->
		@include('clinic.partials.header')
		<!-- //header-->
		
		
		<!--
		/////////////////////////////////////////////////////////////////////////
		//////////     SLIDE LEFT CONTENT include('clinic.partials.slide_left')     
		//////////////////////////////////////////////////////////////////////
		-->
		
		<!-- //nav-->
		
		
		<!--
		/////////////////////////////////////////////////////////////////////////
		//////////     TOP SEARCH CONTENT     ///////
		//////////////////////////////////////////////////////////////////////
		-->
		@include('clinic.partials.search')
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
		//////////     MODAL MESSAGES     //////////include('clinic.partials.message')
		///////////////////////////////////////////////////////////////
		-->
		
		<!-- //modal-->
		
		
		
		<!--
		//////////////////////////////////////////////////////////////////////////
		//////////     MODAL NOTIFICATION    include('clinic.partials.notification') 
		//////////////////////////////////////////////////////////////////////
		-->
		
		<!-- //modal-->
		
			
		<!--
		//////////////////////////////////////////////////////////////
		//////////     LEFT NAV MENU     //////////
		///////////////////////////////////////////////////////////
		-->
		@include('clinic.partials.menu_left')
		<!-- //nav left menu-->
		
		

		
</div>
<!-- //wrapper-->


<!--
////////////////////////////////////////////////////////////////////////
//////////     JAVASCRIPT  LIBRARY     //////////
/////////////////////////////////////////////////////////////////////
-->
		
<!-- Jquery Library -->
<script type="text/javascript" src="{{url('assets/js/jquery.min.js')}}"></script>

<script type="text/javascript" src="{{url('assets/plugins/moment/moment.js')}}"></script>
<script type="text/javascript" src="{{url('assets/plugins/moment/locale.js')}}"></script>
<script type="text/javascript" src="{{url('assets/plugins/jquery-timeago/jquery.timeago.js')}}"></script>

<script type="text/javascript" src="{{url('assets/js/jquery.ui.min.js')}}"></script>

<script type="text/javascript" src="{{url('assets/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>


<script type="text/javascript" src="{{url('assets/plugins/bootstrap/bootstrap.min.js')}}"></script>
<!-- Modernizr Library For HTML5 And CSS3 -->
<script type="text/javascript" src="{{url('assets/js/modernizr/modernizr.js')}}"></script>
<script type="text/javascript" src="{{url('assets/plugins/mmenu/jquery.mmenu.js')}}"></script>
<script type="text/javascript" src="{{url('assets/js/styleswitch.js')}}"></script>
<!-- Library 10+ Form plugins-->
<script type="text/javascript" src="{{url('assets/plugins/form/form.js')}}"></script>
<!-- Datetime plugins -->

<!-- Library Chart-->
<script type="text/javascript" src="{{url('assets/plugins/chart/chart.js')}}"></script>
<!-- Calendario plaugin-->
<script src="{{url('assets/plugins/fullcalendar/fullcalendar.js')}}"></script>
<script src="{{url('assets/js/lang-all.js')}}"></script>

<!-- Library  5+ plugins for bootstrap -->
<script type="text/javascript" src="{{url('assets/plugins/pluginsForBS/pluginsForBS.js')}}"></script>
<!-- Library 10+ miscellaneous plugins -->
<script type="text/javascript" src="{{url('assets/plugins/miscellaneous/miscellaneous.js')}}"></script>
<!-- Library datable -->
<script type="text/javascript" src="{{url('assets/plugins/datable/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{url('assets/plugins/datable/dataTables.bootstrap.js')}}"></script>
<script src="{{url('assets/js/pnotify.core.js')}}"></script>
<script type='text/javascript'src="{{url('assets/js/timepicki.js')}}"></script>
<!-- Option JS -->
@yield('js')
<!-- Library Themes Customize-->
<script type="text/javascript" src="{{url('assets/js/caplet.custom.js')}}"></script>
<script type="text/javascript">
 $(function() {	
 	$('nav#menu-ver').mmenu({
				searchfield   :  true,	
				slidingSubmenus	: false
			}).on( "closing.mm", function(){
				setTimeout(function () { closeSub() }, 200);
				function closeSub(){
					var nav=$('nav#menu-ver');
						nav.find("li").each(function(i) {
							$(this).removeClass("mm-opened");	
						});
				}
			  });

	@yield('js-script')
 });
</script>
</body>
</html>