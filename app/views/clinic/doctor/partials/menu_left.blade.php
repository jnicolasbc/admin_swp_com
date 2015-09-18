<nav id="menu"  data-search="close">
	<ul>
		<li><a href="{{url('/doctor')}}"><span><i class="icon fa fa-laptop"></i> Dashboard</span></a></li>
		@if(Doctor::VeryTypeDoctor()!=true)
		<li><span><i class="icon fa fa-th-list"></i> Gestión</span>
			<ul>
		        <li><a href="{{url('/doctor/agendas')}}"> Agendas</a></li>
		        <li><a href="{{url('/doctor/patients')}}"> Pacientes</a></li>
		        <li><a href="#"> Confirmacion de Citas</a></li>
		        <li><a href="#"> Citas Canceladas</a></li>
		    </ul>
		</li>
        <li><span><i class="icon fa fa-cogs"></i> Configuración</span>
			<ul>
		        <li><a href="{{url('/doctor/profile')}}"> Perfil</a></li>
		        <li><a href="{{url('/doctor/config-days')}}"> Agendas</a></li>
		        <li><a href="{{url('/doctor/config-days')}}"> Pagos</a></li>
			</ul>
		</li>
        @else
		<li><span><i class="icon fa fa-th-list"></i> Gestión</span>
			<ul>
				<li><a href="{{url('/doctor/patients')}}"> Historial y pacientes</a></li>
				<li><a href="{{url('/doctor/appointments')}}"> Calendario</a></li>
				<li><a href="{{url('/doctor/agenda-day')}}"> Agenda del Dia</a></li>
				<li><a href="{{url('/doctor/confirmation-appointments')}}"> Confirmacion de Citas</a></li>
				<li><a href="{{url('/doctor/cancels-appointments')}}"> Citas Canceladas</a></li>
            
            </ul>
		</li>
        <li><span><i class="icon fa fa-cogs"></i> Configuración</span>
			<ul>
		        <li><a href="{{url('/doctor/profile')}}"> Perfil</a></li>
		        <li><a href="{{url('/doctor/config-days')}}"> Horario estandar</a></li>
		        <li><a href="{{url('/doctor/custom-days')}}"> Días especiales</a></li>
			</ul>
		</li>
		@endif
		<li><a href="{{route('logout')}}"><span> <i class="icon  fa fa-sign-out"></i>  Desconectarse</span></a></li>

	</ul>
</nav>