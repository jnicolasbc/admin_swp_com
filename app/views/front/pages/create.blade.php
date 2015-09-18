<?php
use \Api\V1\Helpers as H;

?>
@extends('master')

@section('title', 'Home ')

@section('content')
    <div class="row">
            <div class="col-lg-12">
                    <div class="account-wall">
                            <section class="align-lg-center">
                            <div class="site-logo" style="  height: 75px; background-size: contain;"></div>
                            <h1 class="login-title"><span> SMART DOCTOR </span><small> APPOINTMENTS</small></h1>
                            <br>
                            </section>
                            {{ Form::open(array('url' => 'action/saveplan','files'=>true,'class'=>'wizard-step shadow','id'=>'validate-wizard')) }} 
                           
                                    <input name="plan_id" value="{{$plan}}" type="hidden">
                                    <input name="is_clinic" value="{{$isclinic}}" type="hidden">
                                    <ul class="align-lg-center" style="">
                                            <li><a href="#step1" data-toggle="tab">1</a></li>
                                            <li><a href="#step2" data-toggle="tab">2</a></li>
                                            <li><a href="#step3" data-toggle="tab">3</a></li>
                                    </ul>
                                    <div class="progress progress-stripes progress-sm" style="margin:0">
                                            <div class="progress-bar" data-color="theme"></div>
                                    </div>
                                    <div class="tab-content">
                                           <div class="tab-pane fade" id="step1" parsley-validate parsley-bind> 
                                             <!--  <div class="tab-pane fade" id="step1"> -->
                                            		<div class="form-group">
                                            			<h3 class="login-title"><span>PERSONAL  </span>DATA</h3>
                                            		</div>

                                                    <div class="form-group row">
                                                            <div class="col-md-6">
                                                                <label class="control-label">Full Name</label>
                                                                <input name="first_name" type="text" class="form-control" id="fullname" parsley-required="true" placeholder="Your full name" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false">
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="control-label">Last Name</label>

                                                                <input name="last_name" type="text" class="form-control" parsley-required="true" placeholder="Your last name" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false">
                                                            </div>
                                                    </div>
                                                   
                                                    <div class="form-group">
                                                            <label class="control-label">DNI</label>
                                                            <input name="dni" type="text" class="form-control"  parsley-required="true" data-parsley-minlength="6" placeholder="12345678Z" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false">
                                                    </div>

                                                     
                                                     
                                                    <div class="form-group">
										                <label class="control-label">Birthday</label>
                                                        <div class='input-group date' >
										                    <input id='datetimepicker2' parsley-required="true" name="birthday" type='text' placeholder="Birthday" class="form-control" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false"/>
										                    <span class="input-group-addon">
										                        <i class="fa fa-calendar"></i>
										                    </span>
										                </div>
										            </div>                                                                                                    
                                                     <div class="form-group">
                                                            <label class="control-label">Country</label>
                                                              <select parsley-required="true"  id="country" class="selectpicker form-control" data-size="10" data-live-search="true">
                                                              <option value="">Live search Country </option>
                                                                <?php  
                                                                    Country::chunk(50, function($countries)
                                                                    {
                                                                        foreach($countries as $country)
                                                                        {
                                                                            echo "<option value=".$country->id.">".$country->name."</option>";
                                                                        }
                                                                    });
                                                                ?>                                                                
                                                              </select>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Province</label>
                                                            <div id="pprovince">
                                                              <select parsley-required="true" name="provinces"  id="provinces" class="form-control" >
                                                                    <option value="">Live search provinces </option>
                                                              </select>                                                                
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">City</label>
                                                            <div id="ccity">
                                                                <select parsley-required="true" name="city"  id="city" class="form-control" >
                                                                    <option value="">Live search Citys </option>
                                                                </select>
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Address</label>
                                                            <textarea name="address_person"  class="form-control" placeholder="Address" parsley-required="true" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Email Address</label>
                                                            <input name="email" id="email" type="text" class="form-control" parsley-type="email"  parsley-required="true" placeholder="john@email.com" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false">
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">User name</label>
                                                            <input name="nick" type="text" class="form-control"  parsley-rangelength="[4,15]"  parsley-required="true" parsley-trigger="keyup" placeholder="8-15 Characters" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false">
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Password</label>
                                                            <input name="password" type="password" class="form-control" id="pword"  parsley-trigger="keyup"  parsley-rangelength="[4,8]"  parsley-required="true" placeholder="4-8 Characters">
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Confirm Password</label>
                                                            <input name="password_confirmation" type="password" class="form-control"  parsley-trigger="keyup"  parsley-equalto="#pword" placeholder="Confirm Password" parsley-error-message="Password don't match" >
                                                    </div>
                                            </div>
                                            @if($isclinic=='c')
                                            <div class="tab-pane fade" id="step2" parsley-validate parsley-bind>
                                                    <h3>Clinics Info</h3><br>
                                                     <div class="form-group">
                                                            <label class="control-label">Name Clinic</label>
                                                            <input name="name_clinic" type="text" class="form-control" placeholder="Name Clinic" parsley-required="true" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false">
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Country</label>
                                                              <select parsley-required="true"  id="country2" class="selectpicker form-control" data-size="10" data-live-search="true">
                                                              <option value="">Live search Country </option>
                                                                <?php  
                                                                    Country::chunk(50, function($countries)
                                                                    {
                                                                        foreach($countries as $country)
                                                                        {
                                                                            echo "<option value=".$country->id.">".$country->name."</option>";
                                                                        }
                                                                    });
                                                                ?>                                                                
                                                              </select>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Province</label>
                                                            <div id="pprovince2">
                                                              <select parsley-required="true" name="provinces2"  id="provinces2" class="form-control" >
                                                                    <option value="">Live search provinces </option>
                                                              </select>                                                                
                                                            </div>
                                                    </div>
                                                      <div class="form-group">
                                                            <label class="control-label">City</label>
                                                            <div id="ccity2">
                                                                <select parsley-required="true" name="city2"  id="city2" class="form-control" >
                                                                    <option value="">Live search Cities </option>
                                                                </select>
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Address Clinic</label>
                                                            <textarea autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false" name="address_clinic" class="form-control" placeholder="Address Clinic" parsley-required="true"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Phone Clinic</label>
                                                            <input name="phone_clinic" type="text" class="form-control" placeholder="Phone" parsley-required="true" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false">
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Insurances</label>
                                                            <input id="insu" name="insurances" type="text" class="form-control"  parsley-required="true" data-parsley-minlength="6" placeholder="Insurances" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false">
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Language</label>
                                                        	<select name="lang"  class="selectpicker form-control">
																	<option value="es">Es</option>																	 
																	<option value="en">En</option>																	 
															</select>

                                                    </div>
													<div class="form-group">
														<label class="control-label">Image Logo</label>
														<div>
															<div class="fileinput fileinput-new" data-provides="fileinput" data-name="picture">
																	<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
																		
																	</div>
																	<div> 
																			<span class="btn btn-default btn-file">
																			<span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span>
																					<input type="file" name="picture">
																			</span> 
																			<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a> 
																	</div>
															</div><!-- //fileinput-->
														</div>
													</div><!-- //form-group-->
                                            </div>
                                            @else
                                            <div class="tab-pane fade" id="step2" parsley-validate parsley-bind>
                                                    <h3>Doctor Info</h3><br>
                                                    <div class="form-group">
                                                            <label class="control-label">Name Local</label>
                                                            <input name="name_doc" type="text" class="form-control" placeholder="Name Local" parsley-required="true" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false">
                                                    </div>
                        
                                                    <div class="form-group">
                                                            <label class="control-label">Country</label>
                                                              <select parsley-required="true"  id="country2" class="selectpicker form-control" data-size="10" data-live-search="true">
                                                              <option value="">Live search Country </option>
                                                                <?php  
                                                                    Country::chunk(50, function($countries)
                                                                    {
                                                                        foreach($countries as $country)
                                                                        {
                                                                            echo "<option value=".$country->id.">".$country->name."</option>";
                                                                        }
                                                                    });
                                                                ?>                                                                
                                                              </select>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Province</label>
                                                            <div id="pprovince2">
                                                              <select parsley-required="true" name="provinces2"  id="provinces2" class="form-control" >
                                                                    <option value="">Live search provinces </option>
                                                              </select>                                                                
                                                            </div>
                                                    </div>
                                                      <div class="form-group">
                                                            <label class="control-label">City</label>
                                                            <div id="ccity2">
                                                                <select parsley-required="true" name="city2"  id="city2" class="form-control" >
                                                                    <option value="">Live search Cities </option>
                                                                </select>
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Address 1</label>
                                                            <textarea name="address_doc"  class="form-control" placeholder="Address Clinic" parsley-required="true" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Phone local</label>
                                                            <input name="phone_doc" type="text" class="form-control" placeholder="Phone" parsley-required="true" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false">
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Insurances</label>
                                                            <input id="insu" name="insurances" type="text" class="form-control"  parsley-required="true" data-parsley-minlength="6" placeholder="Insurances" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false">
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label">Specialy</label>
                                                            <input id="specialy" name="specialy" type="text" class="form-control"  parsley-required="true" data-parsley-minlength="6" placeholder="Specialy" autocomplete="off"  oncopy="return false" ondrag="return false" ondrop="return false" onpaste="return false">
                                                    </div>                                                    
                                                    <div class="form-group">
                                                            <label class="control-label">Language</label>
                                                            <select name="lang"  class="selectpicker form-control">
                                                                    <option value="es">Es</option>                                                                   
                                                                    <option value="en">En</option>                                                                   
                                                            </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Image Logo</label>
                                                        <div>
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                                            <img src="http://somervilletoollibrary.com/images/image00.png" alt="">
                                                                        </div>
                                                                        <div> 
                                                                                <span class="btn btn-default btn-file">
                                                                                <span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span>
                                                                                        <input type="file" name="picture">
                                                                                </span> 
                                                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a> 
                                                                        </div>
                                                                </div><!-- //fileinput-->
                                                                
                                                        </div>
                                                    </div>
                                                    
                                            </div>
                                            @endif                                           
                                            <div class="tab-pane fade align-lg-center" id="step3">
                                                <div id="block-mgs" style="display: none;">
                                                    <br><h2>Gracias por su Registro <span></span> ...</h2><br>
                                                    <p id="mgs">Un correo ha sido enviado a <b></b> con detalles de como activar tu cuenta (en caso de no recibirla en su buzón de entrada, buscar en el buzón de Correo no Deseado o Spam).</p> 
                                                </div>
                                                <div  id="load" style="  display: block; width: 145px; margin: 0 auto;">
                                                <p>Procesando Informacion</p>
                                                </div>
                                            </div>
                                            <footer class="row">
                                                <div class="col-sm-12">
                                                        <section class="wizard">
                                                                <button type="button"  class="btn  btn-default previous pull-left"> <i class="fa fa-chevron-left"></i></button>
                                                                <button type="button"  class="btn btn-theme next pull-right">Next </button>
                                                        </section>
                                                </div>
                                            </footer>
                                    </div>
                                    <input id="city_" type="hidden" name="city">
                                @if($isclinic=='c')
                                    <input id="city2_" type="hidden" name="city_clinic_">
                                @else
                                    <input id="city2_" type="hidden" name="city_doctor_">
                                @endif
                            </form>
                            <section class="clearfix align-lg-center">
                                    <i class="fa fa-sign-in"></i> Return to <a href="{{url()}}">Login</a>
                            </section>  
                    </div>  
                    <!-- //account-wall-->
            </div>
            <!-- //col-sm-6 col-md-4 col-md-offset-4-->
    </div>
   
    <!-- //row

	<div class="jumbotron">
		<h1>Landing Page</h1>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia perferendis id odit laudantium non blanditiis debitis repellat nulla accusamus cupiditate unde.</p>

		@if (!Sentry::check())
		<p>
			<a href="/login" class="btn btn-success btn-lg" role="button">Login</a> or <a href="/register" class="btn btn-primary btn-lg" role="button">Register</a>
		</p>
		@endif
	</div>W

@stop