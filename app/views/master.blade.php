<?php
use \Api\V1\Helpers as H;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Meta information -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<!-- Title-->
<title>SMART DOCTOR APPOINTMENTS |  @yield('title')</title>
<!-- Favicons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{url('assets/ico/apple-touch-icon-144-precomposed.png')}}">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{url('assets/ico/apple-touch-icon-114-precomposed.png')}}">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{url('assets/ico/apple-touch-icon-72-precomposed.png')}}">
<link rel="apple-touch-icon-precomposed" href="{{url('assets/ico/apple-touch-icon-57-precomposed.png')}}">
<link rel="shortcut icon" href="{{url('assets/ico/favicon.ico')}}">
<!-- CSS Stylesheet-->
<link type="text/css" rel="stylesheet" href="{{url('assets/css/bootstrap/bootstrap.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{url('assets/css/bootstrap/bootstrap-themes.css')}}" />
<link type="text/css" rel="stylesheet" href="{{url('assets/css/font-awesome/font-awesome.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{url('assets/plugins/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css')}}" />
<link type="text/css" rel="stylesheet" rel="stylesheet" href="{{url('assets/plugins/jquery-tags-Input/src/jquery.tagsinput.css')}}">

<link type="text/css" rel="stylesheet" rel="stylesheet" href="{{url('assets/plugins/bootstrap-tokenfield/dist/css/bootstrap-tokenfield.css')}}">

<link type="text/css" rel="stylesheet" href="{{url('assets/css/style.css')}}" />
<link type="text/css" rel="stylesheet" href="{{url('assets/css/style_typehead.css')}}" />

<!-- Styleswitch if  you don't chang theme , you can delete -->
<link type="text/css" rel="alternate stylesheet" media="screen" title="style1" href="{{url('assets/css/styleTheme1.css')}}" />
<link type="text/css" rel="alternate stylesheet" media="screen" title="style2" href="{{url('assets/css/styleTheme2.css')}}" />
<link type="text/css" rel="alternate stylesheet" media="screen" title="style3" href="{{url('assets/css/styleTheme3.css')}}" />
<link type="text/css" rel="alternate stylesheet" media="screen" title="style4" href="{{url('assets/css/styleTheme4.css')}}" />
<style>
#validate-wizard{
    width:530px;
    margin:auto;
    }
</style>

</head>
<body class="full-lg">
<div id="wrapper">

<div id="loading-top">
        <div id="canvas_loading"></div>
        <span>Checking...</span>
</div>

<div id="main">
        <div class="real-border">
                <div class="row">
                        <div class="col-xs-1"></div>
                        <div class="col-xs-1"></div>
                        <div class="col-xs-1"></div>
                        <div class="col-xs-1"></div>
                        <div class="col-xs-1"></div>
                        <div class="col-xs-1"></div>
                        <div class="col-xs-1"></div>
                        <div class="col-xs-1"></div>
                        <div class="col-xs-1"></div>
                        <div class="col-xs-1"></div>
                        <div class="col-xs-1"></div>
                        <div class="col-xs-1"></div>
                </div>
        </div>
        <div class="container">
            @yield('content')
        </div>
        <!-- //container-->
        
</div>
<!-- //main-->

        
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

<script type="text/javascript" src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{url('assets/plugins/bootstrap/bootstrap.min.js')}}"></script>
<!-- Modernizr Library For HTML5 And CSS3 -->
<script type="text/javascript" src="{{url('assets/js/modernizr/modernizr.js')}}"></script>
<script type="text/javascript" src="{{url('assets/plugins/mmenu/jquery.mmenu.js')}}"></script>
<script type="text/javascript" src="{{url('assets/js/styleswitch.js')}}"></script>
<script type="text/javascript" src="{{url('assets/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>

<!-- Library 10+ Form plugins-->
<script src="{{url('assets/plugins/form/form.js')}}" type="text/javascript" ></script>

<script src="{{url('assets/plugins/typeahead/typeahead.jquery.min.js')}}" type="text/javascript" ></script>
<script src="{{url('assets/plugins/typeahead/bloodhound.min.js')}}" type="text/javascript" ></script>

<script src="{{url('assets/plugins/parsley/src/parsley.'.H::lang().'.js')}}" type="text/javascript"></script>

<script src="{{url('assets/plugins/jquery-tags-Input/src/jquery.tagsinput.js')}}" type="text/javascript"></script>

<script src="{{url('assets/plugins/bootstrap-tokenfield/dist/bootstrap-tokenfield.min.js')}}" type="text/javascript"></script>
 
<!-- Library Chart-->
<script type="text/javascript" src="{{url('assets/plugins/chart/chart.js')}}"></script>
<!-- Library  5+ plugins for bootstrap -->
<script type="text/javascript" src="{{url('assets/plugins/pluginsForBS/pluginsForBS.js')}}"></script>
<!-- Library 10+ miscellaneous plugins -->
<script type="text/javascript" src="{{url('assets/plugins/miscellaneous/miscellaneous.js')}}"></script>
<script type="text/javascript" src="http://timschlechter.github.io/bootstrap-tagsinput/examples/lib/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>


<!-- Library Themes Customize-->
 
<script type="text/javascript">
$(document).ready(function() {
            $('#insurances').tagsInput({
                defaultText:'add a insurances',
                width: 'auto',
                autocomplete_url:'{{url()}}/insurances' // jquery ui autocomplete requires a json endpoint
            });
           //Login animation to center 
            function toCenter(){
                    var mainH=$("#main").outerHeight();
                    var accountH=$(".account-wall").outerHeight();
                    var marginT=(mainH-accountH)/2;
                    if(marginT>30){
                        $(".account-wall").css("margin-top",marginT-15);
                     }else{
                         $(".account-wall").css("margin-top",30);
                     }
            }
                var toResize;
                $(window).resize(function(e) {
                    clearTimeout(toResize);
                    toResize = setTimeout(toCenter(), 500);
                });
                
            //Canvas Loading
              var throbber = new Throbber({  size: 144, padding: 50,  strokewidth: 2.8,  lines: 12, rotationspeed: 0, fps: 15,color:'black'});
              throbber.appendTo(document.getElementById('load'));
              throbber.start();
                
            $('#validate-wizard').bootstrapWizard({
                    tabClass:"nav-wizard",
                    onNext: function(tab, navigation, index) {
                                    var content=$('#step'+index);
                                    if(typeof  content.attr("parsley-validate") != 'undefined'){
                                                    var $valid = content.parsley( 'validate' );
                                                    if(!$valid){
                                                                    return false;
                                                    }
                                    };
                    // Set the name for the next tab
                    $('#step4 h3').find("span").html($('#fullname').val());
                    $('#step4 p').find("b").html($('#email').val());
                    },
                    onTabClick: function(tab, navigation, index) {
                                    $.notific8('Please click <strong>next button</strong> to wizard next step!! ',{ life:5000, theme:"danger" ,heading:" Wizard Tip :); "});
                                    return false;
                    },
                    onTabShow: function(tab, navigation, index) {
                                    tab.prevAll().addClass('completed');
                                    tab.nextAll().removeClass('completed');
                                    if(tab.hasClass("active")){
                                                    tab.removeClass('completed');
                                    }
                                    var $total = navigation.find('li').length;
                                    var $current = index+1;
                                    var $percent = ($current/$total) * 100;
                                    $('#validate-wizard').find('.progress-bar').css({width:$percent+'%'});
                                    $('#validate-wizard').find('.wizard-status span').html($current+" / "+$total);
                                    
                                    toCenter();
                                    
                                    var main=$("#load");
                                    var mgs=$("#block-mgs");
                                    //scroll to top
                                    main.animate({
                                        scrollTop: 0
                                    }, 500);
                                   if($percent==100){
                                        setTimeout(function () { main.show() }, 100);
                                        setTimeout(function () { main.hide() }, 2000);
                                        setTimeout(function () { $('#validate-wizard').submit(); }, 2100);
                                        
                                    }  
                    }
            });

});
</script>
  <script type="text/javascript">
            function split( val ) {return val.split( /,\s*/ ); } function extractLast( term ) {return split( term ).pop(); } function extractInsurance () {$ins =$('#insu'); }
            
            $(function () {
                $('.selectpicker').selectpicker();
                $('#datetimepicker2').datetimepicker({
                    defaultDate:false,
                    viewMode: 'years',
                    locale: '{{H::lang()}}',
                    format: 'D/M/YYYY',
                    maxDate: '12/31/1996'
                });
                  /*  $( "#country" ).autocomplete({
                        source: function( request, response ) {
                          $.getJSON('{{url()}}/countries', {
                            term: extractLast( request.term ),
                          }, response );
                        },                        
                        select: function( event, ui ) {
                            $( "#country_" ).val(ui.item.id);  
                            //console.log('code'+ui.item.id);  
                        }
                    });
                    $( "#country2" ).autocomplete({
                        source: function( request, response ) {
                          $.getJSON('{{url()}}/countries', {
                            term: extractLast( request.term ),
                          }, response );
                        },
                       
                         select: function( event, ui ) {
                            $( "#country2_" ).val(ui.item.id);  
                            //console.log('code'+ui.item.id);  

                        }
                    });
                    $( "#city" ).autocomplete({
                        source: function( request, response ) {
                            var vl = $('#country_').val();
                          $.getJSON('{{url()}}/cities/'+vl, {
                            term: extractLast( request.term ),
                          }, response );
                        },
                        select: function( event, ui ) {
                            $( "#city_" ).val(ui.item.id);  
                            //console.log('code'+ui.item.id);  

                        }
                        
                    });
                    $( "#city2" ).autocomplete({
                        source: function( request, response ) {
                            var as = $('#country2_').val();
                          $.getJSON('{{url()}}/cities/'+as, {
                            term: extractLast( request.term ),
                          }, response );
                        } ,
                        select: function( event, ui ) {
                            $( "#city2_" ).val(ui.item.id);  
                            //console.log('code'+ui.item.id);  

                        }                       
                    });*/
            });
        </script>
        <script type="text/javascript">
        $(function() {
          /*
            var Specialy = new Bloodhound({
                  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
                  queryTokenizer: Bloodhound.tokenizers.whitespace,
                  prefetch: 'http://104.236.201.9/proyectos/agendamedica/api/specialty2?lang={{H::lang()}}'
                });
                
                $('#specialy').tokenfield({
                    typeahead: [
                    {
                        highlight: true, 
                        minLength: 1, 
                        hint: false
                    },{ 
                        source: Specialy, 
                        name: 'value', 
                        display: 'label',
                        displayKey: 'value' 
                    }]
                });
              
              var countries = new Bloodhound({
              datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
              queryTokenizer: Bloodhound.tokenizers.whitespace,
              prefetch: 'http://104.236.201.9/proyectos/agendamedica/api/address/countries2',  
            });

            $('#remote .typeahead').typeahead(
              {
                highlight: true,
                minLength: 1,
                hint: false,
              }, 
              {
                  name: 'value',              
                  displayKey: 'value',
                  display: 'label',
                  source: countries,
                  templates: {
                            empty: ['<div class="noitems"> No Items Country </div>'].join('\n')
                  }
                }
            ).on('typeahead:selected', function (object, datum) { 
                //console.log(object); 
                var country_id = datum.value;
                //$('#city').prop('disabled',false);
                //$("#country2").val(datum.label);

                var cities = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,              
                    remote: {
                        url: "http://104.236.201.9/proyectos/agendamedica/api/address/cities2?country="+country_id+"&term=%QUERY",
                            wildcard: "%QUERY"
                    }
                });

                $('#remote2 .typeahead').typeahead(
                  {
                    highlight: true,
                    minLength: 1,
                    hint: false,
                  }, 
                  {
                       name: 'value', 
                       display: 'label', 
                      source: cities,
                      limit: 10,
                      templates: {
                                empty: ['<div class="noitems"> No Items Cities </div>'].join('\n')
                      }
                    }
                }); 
            });
            $('#remote3 .typeahead').typeahead(
              {
                highlight: true,
                minLength: 1,
                hint: false,
              }, 
              {
                 name: 'value', 
                 displayKey:'value',
                 display: 'label', 
                 source: countries,
                 templates: {
                        empty: ['<div class="noitems"> No Items Country </div>'].join('\n')
                  }
                }
            ).on('typeahead:selected', function (object, datum) { 
            });
                console.log('Country '+datum.value); 
                var country_id = datum.value;
                $("#country2").val(datum.value);

                var cities2 = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,              
                    remote: {
                        url: "http://104.236.201.9/proyectos/agendamedica/api/address/cities2?country="+country_id+"&term=%QUERY",
                            wildcard: "%QUERY"
                        }
                });
                 $('#remote4 .typeahead').typeahead(
                  {
                    highlight: true,
                    minLength: 1,
                    hint: false,
                  }, 
                  {
                      name: 'value', 
                       limit:10,
                       display: 'label', 
                      source: cities2,
                      templates: {
                                empty: ['<div class="noitems"> No Items Cities </div>'].join('\n')
                      }
                    }
                ).on('typeahead:selected', function (object, datum) { 
                    //console.log(object); 
                    $("#city2_").val(datum.value);
                    //console.log('city '+datum.value); 
                }); 

               
                var insure = new Bloodhound({
                  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
                  queryTokenizer: Bloodhound.tokenizers.whitespace,
                  prefetch: 'http://104.236.201.9/proyectos/agendamedica/api/insurance2?country='+country_id
                });
                
                $('#insu').tokenfield({
                    typeahead: [{
                        highlight: true, 
                        minLength: 1, 
                        hint: false
                    }, { 
                        source: insure, 
                        name: 'value', 
                        displayKey:'value',
                        display: 'label' 
                    }]
                });
          */
          $('#country').on('change',function(){            
            var country_id = $(this).val();
            $('#provinces').remove();
            $('#pprovince').load('http://104.236.201.9/proyectos/agendamedica/api/address/provincies2?id=provinces&load=city&country='+country_id);                                 
          });
                  
          $('#country2').on('change',function(){            
            var country_id = $(this).val();            
            $('#provinces2').remove();            
            $('#pprovince2').load('http://104.236.201.9/proyectos/agendamedica/api/address/provincies2?id=provinces2&load=city2&country='+country_id);                                 
            

            var my_insu = new Bloodhound({
              datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
              queryTokenizer: Bloodhound.tokenizers.whitespace,
              prefetch: 'http://104.236.201.9/proyectos/agendamedica/api/insurance3?country='+country_id
            });

              my_insu.initialize();
              my_insu.initialize();
              var elt = $('#insu');
              elt.tagsinput({
                itemValue: 'value',
                itemText: 'text',
                typeaheadjs: {
                  name: 'my_insu',
                  displayKey: 'text',
                  source: my_insu.ttAdapter()
                }
              });
          });
          
            
            
               
            
            
        });
    </script>
    <script>

</script>
</body>
</html>