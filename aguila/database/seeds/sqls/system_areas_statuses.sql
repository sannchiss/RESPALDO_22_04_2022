--
-- PostgreSQL database dump
--

-- Dumped from database version 10.5
-- Dumped by pg_dump version 10.5
--
-- Data for Name: system_areas; Type: TABLE DATA; Schema: public; Owner: yvargas
--

INSERT INTO public.system_areas (id, code, label, created_at, updated_at) VALUES (2, 'ROUTE', 'Ruta', '2018-09-07 12:57:14', '2018-09-07 12:57:14');
INSERT INTO public.system_areas (id, code, label, created_at, updated_at) VALUES (3, 'DOCUMENT', 'Documentos', '2018-09-07 12:57:14', '2018-09-07 12:57:14');
INSERT INTO public.system_areas (id, code, label, created_at, updated_at) VALUES (1, 'VEHICLE', 'Vehiculos', '2018-09-07 12:57:14', '2018-09-07 12:57:14');
INSERT INTO public.system_areas (id, code, label, created_at, updated_at) VALUES (4, 'DOCUMENT_DETAIL', 'Detalle de documento', '2018-09-07 15:55:01', '2018-09-07 15:55:01');
INSERT INTO public.system_areas (id, code, label, created_at, updated_at) VALUES (5, 'EMPLOYEE', 'Empleados', '2018-09-07 15:55:01', '2018-09-07 15:55:01');
INSERT INTO public.system_areas (id, code, label, created_at, updated_at) VALUES (6, 'CELLPHONE', 'Telefonos Celulares', '2018-09-07 15:55:01', '2018-09-07 15:55:01');
INSERT INTO public.system_areas (id, code, label, created_at, updated_at) VALUES (7, 'GPS_DEVICE', 'Dispositivos GPS', '2018-09-07 15:55:01', '2018-09-07 15:55:01');


--
-- Data for Name: statuses; Type: TABLE DATA; Schema: public; Owner: yvargas
--

INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (1, 1, 'ACTIVE', 'Activo', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (2, 2, 'PENDING_DEPARTURE', 'En zona de anclaje', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (3, 2, 'IN_DELIVERY', 'En reparto', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (4, 3, 'IN_DELIVERY', 'En reparto', NULL, 'warning', '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (5, 3, 'PENDING_DEPARTURE', 'En zona de anclaje', NULL, 'secondary', '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (6, 4, 'IN_DELIVERY', 'En reparto', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (7, 4, 'PENDING_DEPARTURE', 'En zona de anclaje', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (8, 5, 'ACTIVE', 'Activo', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (9, 2, 'REDESPACHING', 'Redespacho', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (10, 3, 'REDESPACHING', 'Redespacho', NULL, 'info', '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (11, 4, 'REDESPACHING', 'Redespacho', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (12, 2, 'REJECTED', 'Rechazado', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (13, 3, 'REJECTED', 'Rechazado', NULL, 'danger', '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (14, 4, 'REJECTED', 'Rechazado', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (15, 2, 'PARTIAL_REJECTION', 'Rechazado parcial', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (16, 3, 'PARTIAL_REJECTION', 'Rechazado parcial', NULL, 'orange', '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (17, 4, 'PARTIAL_REJECTION', 'Rechazado parcial', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (18, 5, 'INACTIVE', 'Inactivo', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (19, 3, 'ACCEPTED', 'Aceptado', NULL, 'success', '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (20, 4, 'ACCEPTED', 'Aceptado', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (21, 2, 'COMPLETED', 'Completada', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (22, 6, 'ACTIVE', 'Activo', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (23, 6, 'INACTIVE', 'inactivo', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (24, 7, 'ACTIVE', 'Activo', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');
INSERT INTO public.statuses (id, system_area_id, code, label, icon, color, created_at, updated_at) VALUES (25, 7, 'INACTIVE', 'inactivo', NULL, NULL, '2018-09-07 16:00:14', '2018-09-07 16:00:14');

--
-- Data for Name: status_reasons; Type: TABLE DATA; Schema: public; Owner: yvargas
--

INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (1, 13, 'Local cerrado', NULL, NULL, '2018-09-25 18:47:26', '2018-09-25 18:47:26');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (2, 13, 'Cliente sin dinero', NULL, NULL, '2018-09-25 18:47:53', '2018-09-25 18:47:53');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (3, 13, 'Pedido imcompleto', NULL, NULL, '2018-09-25 18:48:10', '2018-09-25 18:48:10');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (4, 13, 'No solicitado', NULL, NULL, '2018-09-25 18:48:52', '2018-09-25 18:48:52');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (5, 10, 'Local cerrado', NULL, NULL, '2018-09-25 18:55:58', '2018-09-25 18:55:58');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (6, 10, 'Averia', NULL, NULL, '2018-09-25 18:56:33', '2018-09-25 18:56:33');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (7, 10, 'Tiempo insuficiente', NULL, NULL, '2018-09-25 18:57:04', '2018-09-25 18:57:04');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (8, 10, 'Dirección no corresponde', NULL, NULL, '2018-09-25 18:58:08', '2018-09-25 18:58:08');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (9, 14, 'Producto dañado', NULL, NULL, '2018-09-25 18:58:35', '2018-09-25 18:58:35');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (10, 14, 'Cantidad no pedida', NULL, NULL, '2018-09-25 18:59:03', '2018-09-25 18:59:03');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (11, 14, 'Producto vencido', NULL, NULL, '2018-09-25 19:00:07', '2018-09-25 19:00:07');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (12, 14, 'Producto repetido', NULL, NULL, '2018-09-25 19:00:38', '2018-09-25 19:00:38');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (13, 14, 'Diferencia de precio', NULL, NULL, '2018-09-25 19:00:59', '2018-09-25 19:00:59');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (14, 17, 'Producto dañado', NULL, NULL, '2018-09-25 19:01:40', '2018-09-25 19:01:40');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (15, 17, 'Cantidad no pedida', NULL, NULL, '2018-09-25 19:02:14', '2018-09-25 19:02:14');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (16, 17, 'Producto vencido', NULL, NULL, '2018-09-25 19:02:30', '2018-09-25 19:02:30');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (17, 17, 'Producto repetido', NULL, NULL, '2018-09-25 19:03:37', '2018-09-25 19:03:37');
INSERT INTO public.status_reasons (id, status_id, label, icon, color, created_at, updated_at) VALUES (18, 17, 'Diferencia de precio', NULL, NULL, '2018-09-25 19:04:02', '2018-09-25 19:04:02');

UPDATE statuses set color = DATA.color FROM (select code, color from statuses where system_area_id = 3) AS DATA WHERE DATA.code = statuses.code and statuses.system_area_id = 4;
--
-- Name: status_reasons_id_seq; Type: SEQUENCE SET; Schema: public; Owner: yvargas
--

SELECT pg_catalog.setval('public.status_reasons_id_seq', 18, true);


--
-- Name: statuses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: yvargas
--

SELECT pg_catalog.setval('public.statuses_id_seq', 25, true);


--
-- Name: system_areas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: yvargas
--

SELECT pg_catalog.setval('public.system_areas_id_seq', 7, true);


--
-- PostgreSQL database dump complete
--

