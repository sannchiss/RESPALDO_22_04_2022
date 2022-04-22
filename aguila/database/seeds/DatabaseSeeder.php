<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
	DB::unprepared(file_get_contents(__DIR__."/sqls/system_areas_statuses.sql"));
    	DB::unprepared(file_get_contents(__DIR__."/sqls/communes_regions.sql"));
    	DB::select("INSERT INTO phone_operators VALUES (DEFAULT,'MOVISTAR','Movistar','#',now(), now());");
    	DB::select("INSERT INTO gps_types VALUES (DEFAULT,'GEOTAB','Geotab',null,now(), now());");
    	DB::select("
			INSERT INTO cellphone_platforms VALUES 
			(DEFAULT, 'ANDROID', 'Android', null, now(), now());
		");
		DB::select("
			INSERT INTO roles VALUES 
			(DEFAULT, 'ADMIN', 'Administrador', now(), now()),
			(DEFAULT, 'MONITORING', 'Monitoreo', now(), now());
		");
		DB::select("INSERT INTO vehicle_types VALUES
			(DEFAULT,'DEFAULT','Sin Informacion', null, now(),now());
		");
		DB::select("INSERT INTO vehicle_conditions VALUES
			(DEFAULT,'OWN','Propio', null, now(),now()),
			(DEFAULT,'RENTED','Rentado', null, now(),now());
		");
		DB::select("INSERT INTO carriers  VALUES 
			(DEFAULT, 'PRISA','PRISA','PRISA', now(), now());
		");

		DB::select("
			INSERT INTO permissions VALUES 
			(DEFAULT, 'MAINTAINER_SECURITY_USER_ADMIN', '(ADMIN) Mantenedor > Seguridad > Usuarios', false, 'maintainer.security.user', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_SECURITY_ROLE_ADMIN', '(ADMIN) Mantenedor > Seguridad > Roles', false, 'maintainer.security.role', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_SECURITY_PERMISSION_ADMIN', '(ADMIN) Mantenedor > Seguridad > Permisos', false, 'maintainer.security.permission', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_SECURITY_MENU_ADMIN', '(ADMIN) Mantenedor > Seguridad > Menu', false, 'maintainer.security.menu', 15, now(), now()),
			(DEFAULT, 'HOME', 'Home', true, 'home', 15,now(), now()),
			(DEFAULT, 'MAINTAINER_EMPLOYEE_TYPE_ADMIN', '(ADMIN) Mantenedor > Empleado > Tipo', false, 'maintainer.employee.type', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_EMPLOYEE_EMPLOYEE_ADMIN', '(ADMIN) Mantenedor > Empleado > Empleado', false, 'maintainer.employee.employee', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_OFFICE_OFFICE_ADMIN', '(ADMIN) Mantenedor > Oficina > Oficcina', false, 'maintainer.office.office', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_OFFICE_GROUP_ADMIN', '(ADMIN) Mantenedor > Oficina > Grupo', false, 'maintainer.office.group', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_VEHICLE_TYPE_ADMIN', '(ADMIN) Mantenedor > Vehiculo > Tipo', false, 'maintainer.vehicle.type', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_VEHICLE_VEHICLE_ADMIN', '(ADMIN) Mantenedor > Vehiculo > Vehiculo', false, 'maintainer.vehicle.vehicle', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_VEHICLE_CARRIER_ADMIN', '(ADMIN) Mantenedor > Vehiculo > Transportista', false, 'maintainer.vehicle.carrier', 15, now(), now()),
			(DEFAULT, 'MONITORING', 'Monitoreo', true, 'monitoring.[index,markers,currentstatus]', 0, now(), now()),
			(DEFAULT, 'PICK_DELIVERY', 'Retiros & Entregas', true, 'pick_delivery.[index,documents,vehicle,document_detail,document_images,vehicle_position,customer_positions]', 0, now(), now()),
			(DEFAULT, 'QUEST', 'Consultas', true, 'quest.[index,document_detail,document_images,customers,vehicle_position]', 0, now(), now()),
			(DEFAULT, 'MAINTAINER_DEVICE_GPS_TYPE_ADMIN', '(ADMIN) Mantenedor > Gps > Tipo', false, 'maintainer.device.gps.type', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_DEVICE_GPS_DEVICE_ADMIN', '(ADMIN) Mantenedor > Gps > Dispositivo', false, 'maintainer.device.gps.device', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_SYSTEM_AREA_ADMIN', '(ADMIN) Mantenedor > Sistema > Area', false, 'maintainer.system.area', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_SYSTEM_STATUS_ADMIN', '(ADMIN) Mantenedor > Sistema > Estado', false, 'maintainer.system.status', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_SYSTEM_STATUS_REASON_ADMIN', '(ADMIN) Mantenedor > Sistema > Razones', false, 'maintainer.system.status_reason', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_DEVICE_MOBILE_APP_ADMIN', '(ADMIN) Mantenedor > Movil > Aplicaciones', false, 'maintainer.device.mobile.app', 15, now(), now()),
			(DEFAULT, 'MAINTAINER_DEVICE_MOBILE_CELLPHONE_ADMIN', '(ADMIN) Mantenedor > Movil > Telefono celulares', false, 'maintainer.device.mobile.cellphone', 15, now(), now());

		");

		DB::select("
			INSERT INTO role_permissions VALUES
			(DEFAULT, 1, 1, now(), now()),
			(DEFAULT, 1, 2, now(), now()),
			(DEFAULT, 1, 3, now(), now()),
			(DEFAULT, 1, 4, now(), now()),
			(DEFAULT, 1, 5, now(), now()),
			(DEFAULT, 1, 6, now(), now()),
			(DEFAULT, 1, 7, now(), now()),
			(DEFAULT, 1, 8, now(), now()),
			(DEFAULT, 1, 9, now(), now()),
			(DEFAULT, 1, 10, now(), now()),
			(DEFAULT, 1, 11, now(), now()),
			(DEFAULT, 1, 12, now(), now()),
			(DEFAULT, 1, 13, now(), now()),
			(DEFAULT, 1, 14, now(), now()),
			(DEFAULT, 1, 15, now(), now()),
			(DEFAULT, 1, 16, now(), now()),
			(DEFAULT, 1, 17, now(), now()),
			(DEFAULT, 1, 18, now(), now()),
			(DEFAULT, 1, 19, now(), now()),
			(DEFAULT, 1, 20, now(), now()),
			(DEFAULT, 1, 21, now(), now()),
			(DEFAULT, 1, 22, now(), now());
		");

		DB::select("
			INSERT INTO public.menus (id, menu_id, icon, route, label, \"order\", created_at, updated_at) 
			VALUES 
			(9, 7, 'fas fa-user-tie', 'maintainer.employee.employee.index', 'Empleados', 0, now(), now()),
			(8, 7, 'fas fa-user-tag', 'maintainer.employee.type.index', 'Tipos', 1, now(), now()),
			(3, 2, 'fas fa-users', 'maintainer.security.user.index', 'Usuarios', 0, now(), now()),
			(4, 2, 'fas fa-id-badge', 'maintainer.security.role.index', 'Roles', 1, now(), now()),
			(5, 2, 'fas fa-key', 'maintainer.security.permission.index', 'Permisos', 2, now(), now()),
			(2, 1, 'fas fa-shield-alt', '#', 'Seguridad', 0, now(), now()),
			(7, 1, 'fas fa-user-tie', '#', 'Empleado', 1, now(), now()),
			(6, 2, 'fas fa-align-left', 'maintainer.security.menu.index', 'Menu', 3, now(), now()),
			(16, 13, 'fas fa-truck', 'maintainer.vehicle.vehicle.index', 'Vehiculo', 0, now(), now()),
			(14, 13, 'fas fa-swatchbook', 'maintainer.vehicle.type.index', 'Tipo', 1, now(), now()),
			(15, 13, 'fas fa-shapes', 'maintainer.vehicle.carrier.index', 'Transportista', 2, now(), now()),
			(1, NULL, 'fas fa-cog', '#', 'Mantenedores', 0, now(), now()),
			(21, NULL, 'fas fa-map-marked-alt', 'monitoring.index', 'Monitoreo', 1, now(), now()),
			(11, 1, 'fab fa-houzz', 'maintainer.office.office.index', 'Oficinas', 2, now(), now()),
			(13, 1, 'fas fa-truck', '#', 'Vehiculo', 4, now(), now()),
			(17, 1, 'fas fa-server', '#', 'Dispositivos', 5, now(), now()),
			(23, NULL, 'fas fa-search', 'quest.index', 'Consultas', 99, now(), now()),
			(22, NULL, 'fas fa-shipping-fast', 'pick_delivery.index', 'Retiros & Entregas', 2, now(), now()),
			(24, 17, 'fas fa-hdd', '#', 'GPS', 1, now(), now()),
			(25, 24, 'fas fa-tags', 'maintainer.device.gps.type.index', 'Tipos', 1, now(), now()),
			(26, 24, 'fas fa-hdd', 'maintainer.device.gps.device.index', 'Dispositivos', 2, now(), now()),
			(27, 1, 'fab fa-codepen', '#', 'Sistema', 6, now(), now()),
			(28, 27, 'fas fa-vector-square ', 'maintainer.system.area.index', 'Area', 1, now(), now()),
			(29, 27, 'fas fa-star-of-life', 'maintainer.system.status.index', 'Estados', 2, now(), now()),
			(30, 27, 'fas fa-share-alt', 'maintainer.system.status_reason.index', 'Razones', 3, now(), now()),
			(31, 17, 'fas fa-tablet-alt', '#', 'Mobiles', 1, now(), now()),
			(32, 31, 'fas fa-rocket', 'maintainer.device.mobile.app.index', 'Aplicaciones', 1, now(), now()),
			(33, 31, 'fas fa-tablet-alt', 'maintainer.device.mobile.cellphone.index', 'Telefonos celulares', 2, now(), now());

		");

		DB::select("SELECT pg_catalog.setval('public.menus_id_seq', 33, true);");

		DB::select("INSERT INTO public.offices
		 VALUES (DEFAULT,'1000','PRISA',now(),now());
		");

		DB::select("INSERT INTO public.role_offices
			VALUES (DEFAULT,1,1,now(),now());
		");

		DB::unprepared("
    		INSERT INTO employee_types VALUES 
    		(DEFAULT,'SYSTEM','Sistemas',now(),now()),
    		(DEFAULT,'DRIVER','Conductor',now(),now()),
    		(DEFAULT,'AUXILIARY','Peoneta',now(),now()),
    		(DEFAULT,'DELIVERY','Despacho',now(),now());
    	");
	

			DB::table('employees')->insert([
			    [
			    	'rut'			   => '25614503',
			    	'dv'			   => '0',
			    	'name'		 	   => 'Yostin',
			    	'lastname'	 	   => 'Vargas',
			    	'phone'			   => '946987836',
			    	'employee_type_id' => 1,
			    	'has_access' 	   => true,
			    	'code' 	   		   => 8436,
			    	'status_id'		   => 8,
			    	'office_id'		   => 1,
	                'created_at' 	   => date('Y-m-d H:i:s'),
			    	'updated_at' 	   => date('Y-m-d H:i:s')
			    ],
			    [
			    	'rut'			   => '25614503',
			    	'dv'			   => '1',
			    	'name'		 	   => 'Cesar',
			    	'lastname'	 	   => 'Diaz',
			    	'phone'			   => '946987836',
			    	'employee_type_id' => 1,
			    	'has_access' 	   => true,
			    	'code' 	   		   => 2,
			    	'status_id'		   => 8,
			    	'office_id'		   => 1,
	                'created_at' 	   => date('Y-m-d H:i:s'),
			    	'updated_at' 	   => date('Y-m-d H:i:s')
			    ],
			    [
			    	'rut'			   => '26109955',
			    	'dv'			   => '1',
			    	'name'		 	   => 'Albert',
			    	'lastname'	 	   => 'Zerpa',
			    	'phone'			   => '999923304',
			    	'employee_type_id' => 4,
			    	'has_access' 	   => true,
			    	'code' 	   		   => 5521,
			    	'status_id'		   => 8,
			    	'office_id'		   => 1,
	                'created_at' 	   => date('Y-m-d H:i:s'),
			    	'updated_at' 	   => date('Y-m-d H:i:s')
			    ]
			]);

	    	DB::table('users')->insert([
			    [
			    	'email' 	  => 'yvargaso@prisa.cl',
			    	'role_id' 	  => 1,
			    	'employee_id' => 1,
			    	'password' 	  => bcrypt('secret'),
	                'created_at'  => date('Y-m-d H:i:s'),
			    	'updated_at'  => date('Y-m-d H:i:s')
			    ],
			    [
			    	'email' 	  => 'umillap@prisa.cl',
			    	'role_id' 	  => 2,
			    	'employee_id' => 3,
			    	'password' 	  => bcrypt('2018zerpa'),
	                'created_at'  => date('Y-m-d H:i:s'),
			    	'updated_at'  => date('Y-m-d H:i:s')
			    ]
			]);

			DB::table('apps')->insert([
			    [
			    	'code' 	  => 'ROUTES_ANDROID',
			    	'label' 	  => 'Rutas',
			    	'cellphone_platform_id' => 1,
			    	'latest_version_name' 	  => '1',
			    	'previus_version_name' 	  => '1',
			    	'latest_version_code' 	  => '1',
			    	'previus_version_code' 	  => '1',
			    	'active_update'			  => '1',
	                'created_at'  => date('Y-m-d H:i:s'),
			    	'updated_at'  => date('Y-m-d H:i:s')
			    ],
			]);

    }
}
