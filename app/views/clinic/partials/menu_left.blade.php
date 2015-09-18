<nav id="menu"  data-search="close">
	<ul>
		<li><span><i class="icon  fa fa-th-list"></i> Gestión</span>
			<ul>
				<li><a href="{{url('clinic/agendas-day')}}"> Agenda Del Día</a></li>
				<li><a href="{{url('clinic/agendas')}}"> Agendas</a></li>
				<li><a href="{{url('clinic/confirmation-appointments')}}"> Confirmación de Citas</a></li>
				<li><a href="{{url('clinic/cancels-appointments')}}"> Citas Canceladas</a></li>
				<li><a href="{{url('clinic/patients')}}"> Pacientes</a></li>											
			</ul>
		</li>
		<li><span><i class="icon  fa fa-cogs"></i> Configuración</span>
			<ul>
				<li><a href="{{url('clinic/admin-profile')}}"> Perfil del Admistrador</a></li>
				<li><a href="{{url('clinic/config-data')}}"> Centro</a></li>
				<li><a href="{{route('clinic.doctors.index')}}"> Doctores</a></li>
				<li><a href="{{url('clinic/users')}}"> Usuarios</a></li>
				<li><a href="{{url('clinic/payment/history')}}"> Cuenta y Pagos</a></li>
			</ul>
		</li>
		<li><a href="{{route('logout')}}"><span> <i class="icon  fa fa-sign-out"></i>  Desconectarse</span></a></li>
	</ul>
</nav>