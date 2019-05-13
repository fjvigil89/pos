-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-05-2019 a las 19:34:22
-- Versión del servidor: 10.1.28-MariaDB
-- Versión de PHP: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inpos_licencia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_access_employees`
--

CREATE TABLE `phppos_access_employees` (
  `location` int(11) NOT NULL,
  `id_day_access` int(11) NOT NULL,
  `id_hour_access` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_additional_item_numbers`
--

CREATE TABLE `phppos_additional_item_numbers` (
  `item_id` int(11) NOT NULL,
  `item_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `update_additional_item_number` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_additional_item_seriales`
--

CREATE TABLE `phppos_additional_item_seriales` (
  `item_id` int(11) NOT NULL,
  `item_serial` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_app_config`
--

CREATE TABLE `phppos_app_config` (
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `phppos_app_config`
--

INSERT INTO `phppos_app_config` (`key`, `value`) VALUES
('activar_casa_cambio', '0'),
('activar_pago_segunda_moneda', '0'),
('activate_sale_by_serial', '0'),
('active_keyboard', '0'),
('add_cart_by_id_item', '0'),
('additional_payment_types', ''),
('address', 'll 21 45 N 31 52'),
('always_show_item_grid', '0'),
('auto_focus_on_item_after_sale_and_receiving', '1'),
('automatically_email_receipt', '0'),
('automatically_show_comments_on_receipt', '0'),
('averaging_method', 'moving_average'),
('barcode_price_include_tax', '0'),
('cache_control_max-age_offline', '28800'),
('calculate_average_cost_price_from_receivings', '1'),
('categoria_gastos', ''),
('change_sale_date_when_completing_suspended_sale', '0'),
('change_sale_date_when_suspending', '0'),
('commission_default_rate', '0'),
('company', 'Facil Pos'),
('company_dni', '89-9545885'),
('company_giros', ''),
('company_regimen', 'SIMPLIFICADO'),
('config_remember_saved', '0'),
('currency_symbol', '$'),
('custom_subcategory1_name', ''),
('custom_subcategory2_name', ''),
('custom1_name', ''),
('custom1_support_name', ''),
('custom2_name', ''),
('custom2_support_name', ''),
('custom3_name', ''),
('customers_store_accounts', '0'),
('database_version', '0'),
('date_format', 'middle_endian'),
('decimal_separator', ','),
('default_payment_type', 'Efectivo'),
('default_sales_person', 'Sesión iniciada empleado'),
('default_sales_type', '0'),
('default_tax_1_name', 'IVA'),
('default_tax_1_rate', '19'),
('default_tax_2_cumulative', '0'),
('default_tax_2_name', 'Sales Tax 2'),
('default_tax_2_rate', ''),
('default_tax_3_name', ''),
('default_tax_3_rate', ''),
('default_tax_4_name', ''),
('default_tax_4_rate', ''),
('default_tax_5_name', ''),
('default_tax_5_rate', ''),
('default_tax_rate', '8'),
('disable_confirmation_sale', '1'),
('disable_giftcard_detection', '0'),
('disable_sale_notifications', '0'),
('disable_subtraction_of_giftcard_amount_from_sales', '0'),
('divisa', 'VEF'),
('due_date_alarm', '0'),
('enabled_for_Restaurant', '0'),
('equivalencia', '1'),
('equivalencia1', '1'),
('equivalencia2', '1'),
('equivalencia3', '1'),
('es_franquicia', '0'),
('expire_date', '2020-02-20 23:26:33'),
('expire_date_franquicia', '0000-00-00 00:00:00'),
('ftp_hostname', '0'),
('ftp_password', '0'),
('ftp_route', '0'),
('ftp_username', '0'),
('ganancia_distribuidor', '10'),
('Generate_simplified_order', '0'),
('group_all_taxes_on_receipt', '0'),
('hide_balance_receipt_payment', '0'),
('hide_barcode_on_sales_and_recv_receipt', '0'),
('hide_customer_recent_sales', '0'),
('hide_dashboard_statistics', '0'),
('hide_description', '1'),
('hide_invoice_taxes_details', '0'),
('hide_layaways_sales_in_reports', '0'),
('hide_modal', '0'),
('hide_signature', '0'),
('hide_store_account_payments_from_report_totals', '0'),
('hide_store_account_payments_in_reports', '0'),
('hide_support_chat', '0'),
('hide_ticket', '0'),
('hide_ticket_taxes', '0'),
('hide_video_stack0', '0'),
('hide_video_stack1', '0'),
('hide_video_stack2', '0'),
('hide_video_stack3', '0'),
('hide_video_stack4', '0'),
('hide_video_stack5', '0'),
('hide_video_stack6', '0'),
('hide_video_stack7', '0'),
('hide_video_stack8', '0'),
('id_to_show_on_sale_interface', 'number'),
('in_suppliers', '0'),
('inhabilitar_subcategory1', '0'),
('initial_config', '0'),
('language', 'spanish'),
('last_login', '2019-04-26 23:29:28'),
('legacy_detailed_report_export', '0'),
('license', '13e54b2467f91de15325e00fd35abb35'),
('license_type', 'lifetime'),
('limit_cash_flow', '0'),
('max_registers', '20'),
('moneda', 'USD'),
('moneda1', 'USD'),
('moneda2', 'EUR'),
('moneda3', 'MXN'),
('monitor_product_rank', '0'),
('name_new_tax', 'IVA02'),
('number_of_items_per_page', '20'),
('ocultar_forma_pago', '0'),
('offline_sales', '1'),
('order_star', '0'),
('padding_ticket', '0'),
('percent_point', '2'),
('phone', '5555-555-555'),
('prices_include_tax', '0'),
('print_after_receiving', '0'),
('print_after_sale', '1'),
('quantity_subcategory_of_items', '5'),
('receipt_copies', '1'),
('receipt_text_size', 'small'),
('remove_decimals', '0'),
('reproducrir_sonido_orden', '0'),
('require_customer_for_sale', '0'),
('resellers_id', '1'),
('resolution', ''),
('return_policy', ''),
('return_policy_credit', ''),
('return_policy_support', 'Garantía exepto por golpes o daños elétrios.'),
('round_cash_on_sales', '0'),
('round_tier_prices_to_2_decimals', '0'),
('round_value', '0'),
('sale_prefix', 'FACTURA'),
('sales_stock_inventory', '0'),
('select_sales_person_during_sale', '0'),
('send_txt_invoice', '0'),
('show_fullname_item', '0'),
('show_image', '1'),
('show_inventory_brand', '0'),
('show_inventory_colour', '0'),
('show_inventory_image', '0'),
('show_inventory_isbn', '0'),
('show_inventory_model', '0'),
('show_inventory_size', '0'),
('show_number_item', '0'),
('show_payments_ticket', '1'),
('show_point', '1'),
('show_receipt_after_suspending_sale', '0'),
('show_receivings_cost_iva', '0'),
('show_receivings_cost_transport', '0'),
('show_receivings_cost_without_tax', '0'),
('show_receivings_description', '0'),
('show_receivings_discount', '0'),
('show_receivings_inventory', '0'),
('show_receivings_num_item', '0'),
('show_receivings_price_sales', '0'),
('show_report_register_close', '1'),
('show_return_policy_credit', '0'),
('show_sales_description', '0'),
('show_sales_discount', '0'),
('show_sales_inventory', '0'),
('show_sales_num_item', '0'),
('show_sales_price_iva', '0'),
('show_sales_price_without_tax', '0'),
('sip_account', ''),
('sip_password', '0'),
('spreadsheet_format', 'XLSX'),
('st_correo_of_items', '0'),
('subcategory_of_items', '0'),
('suspended', '0'),
('system_point', '1'),
('table_acount', '0'),
('tasa_compra', '1'),
('tasa_venta', '1'),
('thousand_separator', '.'),
('time_format', '24_hour'),
('track_cash', '1'),
('value_max_cash_flow', ''),
('value_point', '1000'),
('value_tax', '2'),
('version', '4.0.0'),
('website', 'http://facilpos.co');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_app_files`
--

CREATE TABLE `phppos_app_files` (
  `file_id` int(10) NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_data` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_cajas_empleados`
--

CREATE TABLE `phppos_cajas_empleados` (
  `register_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `phppos_cajas_empleados`
--

INSERT INTO `phppos_cajas_empleados` (`register_id`, `person_id`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_customers`
--

CREATE TABLE `phppos_customers` (
  `id` int(10) NOT NULL,
  `person_id` int(10) NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `balance` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `credit_limit` decimal(23,10) DEFAULT NULL,
  `taxable` int(1) NOT NULL DEFAULT '1',
  `cc_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_preview` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_issuer` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `tier_id` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `update_customer` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_day_access`
--

CREATE TABLE `phppos_day_access` (
  `id` int(11) NOT NULL,
  `key_language` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `phppos_day_access`
--

INSERT INTO `phppos_day_access` (`id`, `key_language`) VALUES
(1, 'day_2'),
(2, 'day_3'),
(3, 'day_4'),
(4, 'day_5'),
(5, 'day_6'),
(6, 'day_7'),
(7, 'day_1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_defective_audit`
--

CREATE TABLE `phppos_defective_audit` (
  `defective_audit_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_denomination_currency`
--

CREATE TABLE `phppos_denomination_currency` (
  `id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type_currency` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_employees`
--

CREATE TABLE `phppos_employees` (
  `id` int(10) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(10) NOT NULL,
  `language` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commission_percent` decimal(23,10) DEFAULT '0.0000000000',
  `type` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_balance` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `deleted` int(1) NOT NULL DEFAULT '0',
  `password_offline` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_employee` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_rate` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `phppos_employees`
--

INSERT INTO `phppos_employees` (`id`, `username`, `password`, `person_id`, `language`, `commission_percent`, `type`, `account_balance`, `deleted`, `password_offline`, `update_employee`, `id_rate`) VALUES
(1, 'admin', '1c9f0d7603b88771977ca30e271dc907', 1, 'spanish', '0.0000000000', NULL, '0.0000000000', 0, NULL, '2019-01-20 04:38:19', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_employees_locations`
--

CREATE TABLE `phppos_employees_locations` (
  `employee_id` int(10) NOT NULL,
  `location_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `phppos_employees_locations`
--

INSERT INTO `phppos_employees_locations` (`employee_id`, `location_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_giftcards`
--

CREATE TABLE `phppos_giftcards` (
  `giftcard_id` int(11) NOT NULL,
  `giftcard_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` decimal(23,10) NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `update_giftcard` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_hour_access`
--

CREATE TABLE `phppos_hour_access` (
  `id` int(11) NOT NULL,
  `hour_access` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `phppos_hour_access`
--

INSERT INTO `phppos_hour_access` (`id`, `hour_access`) VALUES
(1, '01:00:00'),
(2, '02:00:00'),
(3, '03:00:00'),
(4, '04:00:00'),
(5, '05:00:00'),
(6, '06:00:00'),
(7, '07:00:00'),
(8, '08:00:00'),
(9, '09:00:00'),
(10, '10:00:00'),
(11, '11:00:00'),
(12, '12:00:00'),
(13, '13:00:00'),
(14, '14:00:00'),
(15, '15:00:00'),
(16, '16:00:00'),
(17, '17:00:00'),
(18, '18:00:00'),
(19, '19:00:00'),
(20, '20:00:00'),
(21, '21:00:00'),
(22, '22:00:00'),
(23, '23:00:00'),
(24, '00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_inventory`
--

CREATE TABLE `phppos_inventory` (
  `trans_id` int(11) NOT NULL,
  `trans_items` int(11) NOT NULL DEFAULT '0',
  `trans_user` int(11) NOT NULL DEFAULT '0',
  `trans_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trans_comment` text COLLATE utf8_unicode_ci NOT NULL,
  `trans_inventory` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `location_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_items`
--

CREATE TABLE `phppos_items` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `item_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `colour` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `marca` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `unit` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tax_included` int(1) NOT NULL DEFAULT '0',
  `cost_price` decimal(23,10) NOT NULL,
  `unit_price` decimal(23,10) NOT NULL,
  `items_discount` decimal(23,10) NOT NULL,
  `costo_tax` decimal(23,10) NOT NULL,
  `promo_price` decimal(23,10) DEFAULT NULL,
  `promo_quantity` int(11) NOT NULL DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `expiration_day` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `reorder_level` decimal(23,10) DEFAULT NULL,
  `item_id` int(10) NOT NULL,
  `allow_alt_description` tinyint(1) NOT NULL,
  `is_serialized` tinyint(1) NOT NULL,
  `image_id` int(10) DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  `is_service` int(1) NOT NULL DEFAULT '0',
  `commission_percent` decimal(23,10) DEFAULT '0.0000000000',
  `commission_fixed` decimal(23,10) DEFAULT '0.0000000000',
  `deleted` int(1) NOT NULL DEFAULT '0',
  `update_item` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `custom1` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `custom2` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `custom3` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `subcategory` tinyint(4) NOT NULL,
  `activate_range` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `phppos_items`
--

INSERT INTO `phppos_items` (`name`, `category`, `supplier_id`, `item_number`, `product_id`, `description`, `size`, `colour`, `model`, `marca`, `unit`, `tax_included`, `cost_price`, `unit_price`, `items_discount`, `costo_tax`, `promo_price`, `promo_quantity`, `start_date`, `end_date`, `expiration_date`, `expiration_day`, `reorder_level`, `item_id`, `allow_alt_description`, `is_serialized`, `image_id`, `override_default_tax`, `is_service`, `commission_percent`, `commission_fixed`, `deleted`, `update_item`, `custom1`, `custom2`, `custom3`, `subcategory`, `activate_range`) VALUES
('Abono a línea de crédito', 'Abono a línea de crédito', NULL, NULL, NULL, '', '', '', '', '', '', 0, '0.0000000000', '0.0000000000', '0.0000000000', '0.0000000000', NULL, 0, NULL, NULL, NULL, '', NULL, 1, 0, 0, NULL, 1, 1, '0.0000000000', '0.0000000000', 0, '2019-03-01 17:23:39', '', '', '', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_items_more`
--

CREATE TABLE `phppos_items_more` (
  `id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type_field` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_items_more_field`
--

CREATE TABLE `phppos_items_more_field` (
  `field_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_items_subcategory`
--

CREATE TABLE `phppos_items_subcategory` (
  `item_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `custom1` varchar(80) NOT NULL,
  `custom2` varchar(80) NOT NULL,
  `quantity` decimal(23,10) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `update_subcategory_item` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_items_suppliers`
--

CREATE TABLE `phppos_items_suppliers` (
  `item_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `price_suppliers` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_items_taxes`
--

CREATE TABLE `phppos_items_taxes` (
  `id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(4,1) DEFAULT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  `update_item_taxes` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_items_tier_prices`
--

CREATE TABLE `phppos_items_tier_prices` (
  `tier_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT '0.0000000000',
  `percent_off` int(11) DEFAULT NULL,
  `update_items_tier_price` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_item_kits`
--

CREATE TABLE `phppos_item_kits` (
  `item_kit_id` int(11) NOT NULL,
  `item_kit_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tax_included` int(1) NOT NULL DEFAULT '0',
  `unit_price` decimal(23,10) DEFAULT NULL,
  `cost_price` decimal(23,10) DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  `commission_percent` decimal(23,10) DEFAULT '0.0000000000',
  `commission_fixed` decimal(23,10) DEFAULT '0.0000000000',
  `deleted` int(1) NOT NULL DEFAULT '0',
  `update_kit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_item_kits_taxes`
--

CREATE TABLE `phppos_item_kits_taxes` (
  `id` int(10) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(4,1) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_item_kits_tier_prices`
--

CREATE TABLE `phppos_item_kits_tier_prices` (
  `tier_id` int(10) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT '0.0000000000',
  `percent_off` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_item_kit_items`
--

CREATE TABLE `phppos_item_kit_items` (
  `item_kit_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(23,10) NOT NULL,
  `update_item_kit_item` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_item_range`
--

CREATE TABLE `phppos_item_range` (
  `range_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `register_log_id` int(11) NOT NULL,
  `start_range` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `final_range` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `extra_charge` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `employee_id_recharge` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_locations`
--

CREATE TABLE `phppos_locations` (
  `location_id` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci,
  `address` text COLLATE utf8_unicode_ci,
  `phone` text COLLATE utf8_unicode_ci,
  `fax` text COLLATE utf8_unicode_ci,
  `email` text COLLATE utf8_unicode_ci,
  `receive_stock_alert` text COLLATE utf8_unicode_ci,
  `stock_alert_email` text COLLATE utf8_unicode_ci,
  `timezone` text COLLATE utf8_unicode_ci,
  `mailchimp_api_key` text COLLATE utf8_unicode_ci,
  `enable_credit_card_processing` text COLLATE utf8_unicode_ci,
  `merchant_id` text COLLATE utf8_unicode_ci,
  `merchant_password` text COLLATE utf8_unicode_ci,
  `default_tax_1_rate` text COLLATE utf8_unicode_ci,
  `default_tax_1_name` text COLLATE utf8_unicode_ci,
  `default_tax_2_rate` text COLLATE utf8_unicode_ci,
  `default_tax_2_name` text COLLATE utf8_unicode_ci,
  `default_tax_2_cumulative` text COLLATE utf8_unicode_ci,
  `default_tax_3_rate` text COLLATE utf8_unicode_ci,
  `default_tax_3_name` text COLLATE utf8_unicode_ci,
  `default_tax_4_rate` text COLLATE utf8_unicode_ci,
  `default_tax_4_name` text COLLATE utf8_unicode_ci,
  `default_tax_5_rate` text COLLATE utf8_unicode_ci,
  `default_tax_5_name` text COLLATE utf8_unicode_ci,
  `overwrite_data` tinyint(4) NOT NULL DEFAULT '0',
  `company_dni` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_giros` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_regimen` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` text COLLATE utf8_unicode_ci,
  `image_id` int(11) DEFAULT '0',
  `deleted` int(1) DEFAULT '0',
  `serie_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `start_range` int(11) NOT NULL DEFAULT '1',
  `final_range` int(11) DEFAULT NULL,
  `limit_date` date NOT NULL DEFAULT '0000-00-00',
  `increment` int(11) NOT NULL DEFAULT '1',
  `show_serie` tinyint(4) NOT NULL DEFAULT '0',
  `show_rango` tinyint(4) NOT NULL DEFAULT '0',
  `codigo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `phppos_locations`
--

INSERT INTO `phppos_locations` (`location_id`, `name`, `address`, `phone`, `fax`, `email`, `receive_stock_alert`, `stock_alert_email`, `timezone`, `mailchimp_api_key`, `enable_credit_card_processing`, `merchant_id`, `merchant_password`, `default_tax_1_rate`, `default_tax_1_name`, `default_tax_2_rate`, `default_tax_2_name`, `default_tax_2_cumulative`, `default_tax_3_rate`, `default_tax_3_name`, `default_tax_4_rate`, `default_tax_4_name`, `default_tax_5_rate`, `default_tax_5_name`, `overwrite_data`, `company_dni`, `company_giros`, `company_regimen`, `website`, `image_id`, `deleted`, `serie_number`, `start_range`, `final_range`, `limit_date`, `increment`, `show_serie`, `show_rango`, `codigo`) VALUES
(1, 'Tienda Principal', 'Cll 21 45 No 31 52', '555-555-5555', '', 'no-reply@facilpos.com', '0', '', 'America/Bogota', '0', '0', 'admin', '123', NULL, 'Sales Tax', NULL, 'Sales Tax 2', '0', NULL, '', NULL, '', NULL, '0', 0, '89-9545885', '', 'SIMPLIFICADO', 'http://facilpos.co', NULL, 0, '00-00-00-', 1, 1, '2020-12-31', 1, 0, 0, '0000-0000-0000-0000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_location_items`
--

CREATE TABLE `phppos_location_items` (
  `location_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `items_discount` decimal(23,10) DEFAULT NULL,
  `cost_price` decimal(23,10) DEFAULT NULL,
  `unit_price` decimal(23,10) DEFAULT NULL,
  `promo_price` decimal(23,10) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `quantity` decimal(23,10) DEFAULT '0.0000000000',
  `quantity_warehouse` decimal(23,10) DEFAULT NULL,
  `quantity_defect` decimal(23,10) DEFAULT NULL,
  `reorder_level` decimal(23,10) DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  `override_defect` int(1) NOT NULL DEFAULT '0',
  `update_item_location` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_location_items_taxes`
--

CREATE TABLE `phppos_location_items_taxes` (
  `id` int(10) NOT NULL,
  `location_id` int(11) NOT NULL,
  `item_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(16,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  `update_item_taxes_location` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_location_items_tier_prices`
--

CREATE TABLE `phppos_location_items_tier_prices` (
  `tier_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `location_id` int(10) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT '0.0000000000',
  `percent_off` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_location_item_kits`
--

CREATE TABLE `phppos_location_item_kits` (
  `location_id` int(11) NOT NULL,
  `item_kit_id` int(11) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT NULL,
  `cost_price` decimal(23,10) DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  `update_location_item_kits` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_location_item_kits_taxes`
--

CREATE TABLE `phppos_location_item_kits_taxes` (
  `id` int(10) NOT NULL,
  `location_id` int(11) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(16,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_location_item_kits_tier_prices`
--

CREATE TABLE `phppos_location_item_kits_tier_prices` (
  `tier_id` int(10) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `location_id` int(10) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT '0.0000000000',
  `percent_off` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_modules`
--

CREATE TABLE `phppos_modules` (
  `name_lang_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `desc_lang_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(10) NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `phppos_modules`
--

INSERT INTO `phppos_modules` (`name_lang_key`, `desc_lang_key`, `sort`, `icon`, `module_id`, `color`) VALUES
('module_campaigns', 'module_campaigns_desc', 20, 'bullhorn', 'campaigns', 'blue-ebonyclay'),
('module_changes_house', 'module_changes_house_desc', 143, 'exchange', 'changes_house', 'green-meadow'),
('module_config', 'module_config_desc', 100, 'cogs', 'config', 'blue'),
('module_customers', 'module_customers_desc', 10, 'group', 'customers', 'green-jungle'),
('module_employees', 'module_employees_desc', 80, 'user', 'employees', 'green'),
('module_giftcards', 'module_giftcards_desc', 90, 'credit-card', 'giftcards', 'red'),
('module_item_kits', 'module_item_kits_desc', 30, 'inbox', 'item_kits', 'red-flamingo'),
('module_items', 'module_items_desc', 20, 'table', 'items', 'blue-madison'),
('module_licenses', 'module_licenses_desc', 140, 'calendar', 'licenses', 'red-thunderbird'),
('module_locations', 'module_locations_desc', 110, 'home', 'locations', 'purple'),
('module_offline', 'module_module_offline_desc', 145, 'plug', 'offline', 'blue-hoki'),
('module_receivings', 'module_receivings_desc', 60, 'cloud-download', 'receivings', 'purple-plum'),
('module_recharges', 'module_recharges_desc', 120, 'phone-square', 'recharges', 'grey-cascade'),
('module_registers_movement', 'module_registers_movement_desc', 123, 'money', 'registers_movement', 'yellow-casablanca'),
('module_reports', 'module_reports_desc', 50, 'bar-chart-o', 'reports', 'yellow'),
('module_sales', 'module_sales_desc', 70, 'shopping-cart', 'sales', 'grey-gallery'),
('module_suppliers', 'module_suppliers_desc', 40, 'download', 'suppliers', 'yellow-gold'),
('module_technical_supports', 'module_technical_supports_desc', 142, 'wrench', 'technical_supports', 'green-haze'),
('module_warehouse', 'module_warehouse_desc', 146, 'university', 'warehouse', 'grey');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_modules_actions`
--

CREATE TABLE `phppos_modules_actions` (
  `action_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `action_name_key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `phppos_modules_actions`
--

INSERT INTO `phppos_modules_actions` (`action_id`, `module_id`, `action_name_key`, `sort`) VALUES
('add_update', 'customers', 'module_action_add_update', 1),
('add_update', 'employees', 'module_action_add_update', 130),
('add_update', 'giftcards', 'module_action_add_update', 200),
('add_update', 'item_kits', 'module_action_add_update', 70),
('add_update', 'items', 'module_action_add_update', 40),
('add_update', 'locations', 'module_action_add_update', 240),
('add_update', 'registers_movement', 'module_action_add_update', 213),
('add_update', 'suppliers', 'module_action_add_update', 100),
('add_update', 'technical_supports', 'module_action_add_update', 308),
('agregar_o_sustraer', 'items', 'module_action_agregar_o_sustraer', 502),
('allow_graphics_arnings_monsth', 'reports', 'module_allow_graphics_arnings_monsth', 332),
('allow_graphics_by_store', 'reports', 'module_allow_graphics_by_store', 311),
('assign_all_locations', 'employees', 'module_action_assign_all_locations', 151),
('cancel_sale_suspend', 'sales', 'module_cancel_sale_suspend', 313),
('delete', 'customers', 'module_action_delete', 20),
('delete', 'employees', 'module_action_delete', 140),
('delete', 'giftcards', 'module_action_delete', 210),
('delete', 'item_kits', 'module_action_delete', 80),
('delete', 'items', 'module_action_delete', 50),
('delete', 'locations', 'module_action_delete', 250),
('delete', 'suppliers', 'module_action_delete', 110),
('delete', 'technical_supports', 'module_action_delete_technical_supports', 310),
('delete_payments_to_credit', 'receivings', 'module_delete_payments_to_credit', 336),
('delete_payments_to_credit', 'sales', 'module_delete_payments_to_credit', 331),
('delete_sale', 'sales', 'module_action_delete_sale', 230),
('delete_sale_offline', 'offline', 'module_action_delete_sale_offline', 230),
('delete_serial', 'items', 'module_action_delete_serial', 501),
('delete_suspended_sale', 'sales', 'module_action_delete_suspended_sale', 181),
('delete_taxes', 'sales', 'module_action_delete_taxes', 182),
('edit_balance_employee', 'changes_house', 'module_edit_balance_employee', 315),
('edit_quantity', 'items', 'module_action_edit_quantity', 332),
('edit_rate', 'changes_house', 'module_edit_rate', 316),
('edit_rate_transition', 'changes_house', 'module_rate_transition', 328),
('edit_sale', 'sales', 'module_edit_sale', 190),
('edit_sale_price', 'sales', 'module_edit_sale_price', 170),
('edit_store_account_balance', 'customers', 'customers_edit_store_account_balance', 31),
('edit_tier', 'sales', 'module_edit_tier', 311),
('edit_tier_all', 'sales', 'module_edit_tier_all', 312),
('give_discount', 'sales', 'module_give_discount', 180),
('include_other_employees_report', 'changes_house', 'module_include_other_employees_report', 329),
('module_allow_open_money_box', 'sales', 'module_allow_open_money_box', 230),
('mostrar_valor_cierre_caja', 'registers_movement', 'module_action_mostrar_valor_cierre_caja', 233),
('overwrite_tax', 'sales', 'module_overwrite_tax', 315),
('refuse_approve', 'changes_house', 'module_refuse_approve', 314),
('return_item', 'sales', 'module_return_item', 334),
('return_item_with_invoice', 'sales', 'module_action_return_item_with_invoice', 503),
('search', 'customers', 'module_action_search_customers', 30),
('search', 'employees', 'module_action_search_employees', 150),
('search', 'giftcards', 'module_action_search_giftcards', 220),
('search', 'item_kits', 'module_action_search_item_kits', 90),
('search', 'items', 'module_action_search_items', 60),
('search', 'locations', 'module_action_search_locations', 260),
('search', 'suppliers', 'module_action_search_suppliers', 120),
('search', 'technical_supports', 'module_action_search_technical_supports', 309),
('see_cash_flows_uniqued', 'registers_movement', 'module_see_cash_flows_uniqued', 233),
('see_cost_price', 'item_kits', 'module_see_cost_price', 111),
('see_cost_price', 'items', 'module_see_cost_price', 61),
('see_quantity_defect', 'items', 'module_see_quantity_defect', 23),
('see_sales_uniqued', 'sales', 'module_sales_see_sales_uniqued', 91),
('select_seller_during_sale', 'sales', 'module_select_seller_during_sale', 335),
('show_column_cash_flow', 'registers_movement', 'module_action_show_column_cash_flow', 233),
('show_cost_price', 'reports', 'reports_show_cost_price', 290),
('show_profit', 'reports', 'reports_show_profit', 280),
('view_categories', 'reports', 'reports_categories', 100),
('view_change_house', 'reports', 'reports_change_house', 330),
('view_commissions', 'reports', 'reports_commission', 111),
('view_consolidated', 'reports', 'reports_consolidated', 280),
('view_customers', 'reports', 'reports_customers', 120),
('view_deleted_sales', 'reports', 'reports_deleted_sales', 130),
('view_discounts', 'reports', 'reports_discounts', 140),
('view_employees', 'reports', 'reports_employees', 150),
('view_giftcards', 'reports', 'reports_giftcards', 160),
('view_graph_amount_module', 'reports', 'reports_graph_amount_module', 301),
('view_graph_sale_daily', 'reports', 'reports_graph_sale_daily', 303),
('view_graph_sale_employee', 'reports', 'reports_graph_sale_employee', 302),
('view_graph_sale_month', 'reports', 'reports_graph_sale_month', 300),
('view_inventory_reports', 'reports', 'reports_inventory_reports', 170),
('view_item_kits', 'reports', 'module_item_kits', 180),
('view_items', 'reports', 'reports_items', 190),
('view_movement_cash', 'reports', 'reports_movement_cash', 317),
('view_payments', 'reports', 'reports_payments', 200),
('view_profit_and_loss', 'reports', 'reports_profit_and_loss', 210),
('view_receivings', 'reports', 'reports_receivings', 220),
('view_register_log', 'reports', 'reports_register_log_title', 230),
('view_sales', 'reports', 'reports_sales', 240),
('view_sales_generator', 'reports', 'reports_sales_generator', 110),
('view_store_account', 'reports', 'reports_store_account', 250),
('view_suppliers', 'reports', 'reports_suppliers', 260),
('view_suspended_sales', 'reports', 'reports_suspended_sales', 261),
('view_tables', 'reports', 'report_tables', 304),
('view_taxes', 'reports', 'reports_taxes', 270),
('view_transfer_location', 'reports', 'reports_transfer_location', 327);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_movement_balance_employees`
--

CREATE TABLE `phppos_movement_balance_employees` (
  `id_movement` int(11) NOT NULL,
  `id_person` int(11) NOT NULL COMMENT 'id empleado, que se le modifica el saldo',
  `amount` decimal(23,10) NOT NULL,
  `type_movement` varchar(20) NOT NULL COMMENT 'add 0 y restar 1',
  `registered_by` int(11) NOT NULL,
  `register_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `new_balance` decimal(20,10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_orders_sales`
--

CREATE TABLE `phppos_orders_sales` (
  `order_sale_id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `number` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `state` varchar(30) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_pay_cash`
--

CREATE TABLE `phppos_pay_cash` (
  `pay_cash_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `sold_by_employee_id` int(10) DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `show_comment_on_receipt` int(1) NOT NULL DEFAULT '0',
  `pay_cash_id` int(10) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_ref_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `deleted_by` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `suspended` int(1) NOT NULL DEFAULT '0',
  `store_account_payment` int(1) NOT NULL DEFAULT '0',
  `location_id` int(11) NOT NULL,
  `register_id` int(11) DEFAULT NULL,
  `receiving_id` int(11) DEFAULT NULL,
  `monton_total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_pay_cash_payments`
--

CREATE TABLE `phppos_pay_cash_payments` (
  `payment_id` int(11) NOT NULL,
  `pay_cash_id` int(11) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_amount` decimal(26,0) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_people`
--

CREATE TABLE `phppos_people` (
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `image_id` int(10) DEFAULT NULL,
  `person_id` int(10) NOT NULL,
  `update_people` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `phppos_people`
--

INSERT INTO `phppos_people` (`first_name`, `last_name`, `phone_number`, `email`, `address_1`, `address_2`, `city`, `state`, `zip`, `country`, `comments`, `image_id`, `person_id`, `update_people`) VALUES
('SuperAdministrador ', '', '555-555-5555', 'no-reply@facilpos.com', 'Address 1', '', '', '', '', '', '', NULL, 1, '2019-03-04 04:09:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_permissions`
--

CREATE TABLE `phppos_permissions` (
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `phppos_permissions`
--

INSERT INTO `phppos_permissions` (`module_id`, `person_id`) VALUES
('campaigns', 1),
('changes_house', 1),
('config', 1),
('customers', 1),
('employees', 1),
('giftcards', 1),
('item_kits', 1),
('items', 1),
('licenses', 1),
('locations', 1),
('offline', 1),
('receivings', 1),
('recharges', 1),
('registers_movement', 1),
('reports', 1),
('sales', 1),
('suppliers', 1),
('technical_supports', 1),
('warehouse', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_permissions_actions`
--

CREATE TABLE `phppos_permissions_actions` (
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(11) NOT NULL,
  `action_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `phppos_permissions_actions`
--

INSERT INTO `phppos_permissions_actions` (`module_id`, `person_id`, `action_id`) VALUES
('changes_house', 1, 'edit_balance_employee'),
('changes_house', 1, 'edit_rate'),
('changes_house', 1, 'edit_rate_transition'),
('changes_house', 1, 'include_other_employees_report'),
('changes_house', 1, 'refuse_approve'),
('customers', 1, 'add_update'),
('customers', 1, 'delete'),
('customers', 1, 'edit_store_account_balance'),
('customers', 1, 'search'),
('employees', 1, 'add_update'),
('employees', 1, 'assign_all_locations'),
('employees', 1, 'delete'),
('employees', 1, 'search'),
('giftcards', 1, 'add_update'),
('giftcards', 1, 'delete'),
('giftcards', 1, 'search'),
('item_kits', 1, 'add_update'),
('item_kits', 1, 'delete'),
('item_kits', 1, 'search'),
('item_kits', 1, 'see_cost_price'),
('items', 1, 'add_update'),
('items', 1, 'agregar_o_sustraer'),
('items', 1, 'delete'),
('items', 1, 'delete_serial'),
('items', 1, 'edit_quantity'),
('items', 1, 'search'),
('items', 1, 'see_cost_price'),
('items', 1, 'see_quantity_defect'),
('locations', 1, 'add_update'),
('locations', 1, 'delete'),
('locations', 1, 'search'),
('offline', 1, 'delete_sale_offline'),
('receivings', 1, 'delete_payments_to_credit'),
('registers_movement', 1, 'add_update'),
('registers_movement', 1, 'mostrar_valor_cierre_caja'),
('registers_movement', 1, 'see_cash_flows_uniqued'),
('registers_movement', 1, 'show_column_cash_flow'),
('reports', 1, 'allow_graphics_arnings_monsth'),
('reports', 1, 'allow_graphics_by_store'),
('reports', 1, 'show_cost_price'),
('reports', 1, 'show_profit'),
('reports', 1, 'view_categories'),
('reports', 1, 'view_change_house'),
('reports', 1, 'view_commissions'),
('reports', 1, 'view_consolidated'),
('reports', 1, 'view_customers'),
('reports', 1, 'view_deleted_sales'),
('reports', 1, 'view_discounts'),
('reports', 1, 'view_employees'),
('reports', 1, 'view_giftcards'),
('reports', 1, 'view_graph_amount_module'),
('reports', 1, 'view_graph_sale_daily'),
('reports', 1, 'view_graph_sale_employee'),
('reports', 1, 'view_graph_sale_month'),
('reports', 1, 'view_inventory_reports'),
('reports', 1, 'view_item_kits'),
('reports', 1, 'view_items'),
('reports', 1, 'view_movement_cash'),
('reports', 1, 'view_payments'),
('reports', 1, 'view_profit_and_loss'),
('reports', 1, 'view_receivings'),
('reports', 1, 'view_register_log'),
('reports', 1, 'view_sales'),
('reports', 1, 'view_sales_generator'),
('reports', 1, 'view_store_account'),
('reports', 1, 'view_suppliers'),
('reports', 1, 'view_suspended_sales'),
('reports', 1, 'view_tables'),
('reports', 1, 'view_taxes'),
('reports', 1, 'view_transfer_location'),
('sales', 1, 'delete_payments_to_credit'),
('sales', 1, 'delete_sale'),
('sales', 1, 'delete_suspended_sale'),
('sales', 1, 'delete_taxes'),
('sales', 1, 'edit_sale'),
('sales', 1, 'edit_sale_price'),
('sales', 1, 'edit_tier'),
('sales', 1, 'edit_tier_all'),
('sales', 1, 'give_discount'),
('sales', 1, 'module_allow_open_money_box'),
('sales', 1, 'overwrite_tax'),
('sales', 1, 'return_item'),
('sales', 1, 'return_item_with_invoice'),
('sales', 1, 'see_sales_uniqued'),
('sales', 1, 'select_seller_during_sale'),
('suppliers', 1, 'add_update'),
('suppliers', 1, 'delete'),
('suppliers', 1, 'search'),
('technical_supports', 1, 'add_update'),
('technical_supports', 1, 'delete'),
('technical_supports', 1, 'search');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_petty_cash`
--

CREATE TABLE `phppos_petty_cash` (
  `petty_cash_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `sold_by_employee_id` int(10) DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `show_comment_on_receipt` int(1) NOT NULL DEFAULT '0',
  `petty_cash_id` int(10) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_ref_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `deleted_by` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `suspended` int(1) NOT NULL DEFAULT '0',
  `store_account_payment` int(1) NOT NULL DEFAULT '0',
  `location_id` int(11) NOT NULL,
  `register_id` int(11) DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `monton_total` double NOT NULL COMMENT 'Indica la cantidad abonada o pagada de una línea de crédito'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_petty_cash_payments`
--

CREATE TABLE `phppos_petty_cash_payments` (
  `payment_id` int(11) NOT NULL,
  `petty_cash_id` int(11) NOT NULL,
  `payment_type` varchar(255) NOT NULL,
  `payment_amount` int(11) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_points`
--

CREATE TABLE `phppos_points` (
  `id_point` int(11) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `points` int(11) NOT NULL,
  `update_points_customer` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_points_movement`
--

CREATE TABLE `phppos_points_movement` (
  `id_point` int(11) NOT NULL,
  `point_quantity` int(11) NOT NULL,
  `detail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_price_tiers`
--

CREATE TABLE `phppos_price_tiers` (
  `id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_quotes`
--

CREATE TABLE `phppos_quotes` (
  `sale_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `sold_by_employee_id` int(10) DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `show_comment_on_receipt` int(1) NOT NULL DEFAULT '0',
  `quote_id` int(10) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_ref_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `deleted_by` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `suspended` int(1) NOT NULL DEFAULT '0',
  `store_account_payment` int(1) NOT NULL DEFAULT '0',
  `location_id` int(11) NOT NULL,
  `register_id` int(11) DEFAULT NULL,
  `overwrite_tax` tinyint(4) DEFAULT '0',
  `tier_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_quotes_items`
--

CREATE TABLE `phppos_quotes_items` (
  `quote_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `item_cost_price` decimal(23,10) NOT NULL,
  `item_unit_price` decimal(23,10) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  `commission` decimal(23,10) NOT NULL DEFAULT '0.0000000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_quotes_items_taxes`
--

CREATE TABLE `phppos_quotes_items_taxes` (
  `quote_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_quotes_payments`
--

CREATE TABLE `phppos_quotes_payments` (
  `payment_id` int(10) NOT NULL,
  `quote_id` int(10) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_amount` decimal(23,10) NOT NULL,
  `truncated_card` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `card_issuer` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_rates`
--

CREATE TABLE `phppos_rates` (
  `id_rate` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `sale_rate` decimal(23,10) NOT NULL,
  `rate_buy` decimal(23,10) NOT NULL,
  `deleted` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `phppos_rates`
--

INSERT INTO `phppos_rates` (`id_rate`, `name`, `sale_rate`, `rate_buy`, `deleted`) VALUES
(1, 'Casa de cambio', '1.0000000000', '1.0000000000', 0),
(2, 'Distribuidor', '1.0000000000', '1.0000000000', 0),
(3, 'Bolívares', '1.0000000000', '1.0000000000', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_receivings`
--

CREATE TABLE `phppos_receivings` (
  `receiving_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `receiving_id` int(10) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_by` int(10) DEFAULT NULL,
  `suspended` int(1) NOT NULL DEFAULT '0',
  `location_id` int(11) NOT NULL,
  `transfer_to_location_id` int(11) DEFAULT NULL,
  `mount` decimal(26,2) DEFAULT NULL,
  `credit` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_receivings_items`
--

CREATE TABLE `phppos_receivings_items` (
  `receiving_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL,
  `quantity_purchased` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `item_cost_price` decimal(23,10) NOT NULL,
  `item_unit_price` decimal(23,10) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  `item_cost_transport` decimal(23,10) NOT NULL,
  `custom1_subcategory` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom2_subcategory` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity_subcategory` decimal(23,10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_receivings_items_taxes`
--

CREATE TABLE `phppos_receivings_items_taxes` (
  `receiving_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_receivings_payments`
--

CREATE TABLE `phppos_receivings_payments` (
  `payment_id` int(10) NOT NULL,
  `receiving_id` int(10) DEFAULT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_amount` decimal(23,10) DEFAULT NULL,
  `truncated_card` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `card_issuer` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_registers`
--

CREATE TABLE `phppos_registers` (
  `register_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `phppos_registers`
--

INSERT INTO `phppos_registers` (`register_id`, `location_id`, `name`, `deleted`) VALUES
(1, 1, 'Caja 1', 0),
(2, 1, 'Caja 2', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_registers_movement`
--

CREATE TABLE `phppos_registers_movement` (
  `register_movement_id` int(10) NOT NULL,
  `register_log_id` int(10) NOT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mount` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `detailed_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type_movement` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `mount_cash` decimal(23,0) NOT NULL,
  `categorias_gastos` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_employee` int(11) DEFAULT NULL,
  `delivered_to` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_register_log`
--

CREATE TABLE `phppos_register_log` (
  `register_log_id` int(10) NOT NULL,
  `employee_id_open` int(10) NOT NULL,
  `employee_id_close` int(11) DEFAULT NULL,
  `register_id` int(11) DEFAULT NULL,
  `shift_start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shift_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `open_amount` decimal(23,10) NOT NULL,
  `close_amount` decimal(23,10) NOT NULL,
  `cash_sales_amount` decimal(23,10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `synchronizations_offline_id` int(11) DEFAULT NULL,
  `register_log_id_offline` int(11) DEFAULT NULL,
  `created_online` tinyint(4) NOT NULL DEFAULT '1',
  `closed_manual` tinyint(4) DEFAULT NULL,
  `descripcion` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_sales`
--

CREATE TABLE `phppos_sales` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `sold_by_employee_id` int(10) DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `show_comment_on_receipt` int(1) NOT NULL DEFAULT '0',
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_ref_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `deleted_by` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `support_id` int(11) DEFAULT NULL,
  `ticket_number` int(11) DEFAULT NULL,
  `invoice_number` int(11) DEFAULT NULL,
  `is_invoice` int(1) NOT NULL DEFAULT '1',
  `suspended` int(1) NOT NULL DEFAULT '0',
  `store_account_payment` int(1) NOT NULL DEFAULT '0',
  `location_id` int(11) NOT NULL,
  `register_id` int(11) DEFAULT NULL,
  `serie_number_invoice` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `tier_id` int(10) DEFAULT NULL,
  `ntable` int(11) DEFAULT NULL COMMENT 'cantidad de mesas para restaurante',
  `overwrite_tax` tinyint(4) NOT NULL DEFAULT '0',
  `synchronizations_offline_id` int(11) DEFAULT NULL,
  `sale_id_offline` int(11) DEFAULT NULL,
  `another_currency` tinyint(4) NOT NULL DEFAULT '0',
  `currency` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_other_currency` decimal(23,10) DEFAULT NULL,
  `value_other_currency` decimal(23,10) DEFAULT NULL,
  `transaction_rate` decimal(23,10) DEFAULT NULL COMMENT 'tasa de  transacción puesto pos la casa ',
  `opcion_sale` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `divisa` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_sales_items`
--

CREATE TABLE `phppos_sales_items` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `item_cost_price` decimal(23,10) NOT NULL,
  `item_unit_price` decimal(23,10) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  `commission` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `custom1_subcategory` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom2_subcategory` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_tier` int(11) DEFAULT NULL,
  `numero_cuenta` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero_documento` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `titular_cuenta` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tasa` decimal(23,10) DEFAULT NULL,
  `tipo_documento` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_estado` timestamp NULL DEFAULT NULL,
  `tipo_cuenta` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8_unicode_ci,
  `celular` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_id` bigint(20) DEFAULT NULL,
  `transaction_status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comentarios` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_sales_items_taxes`
--

CREATE TABLE `phppos_sales_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_sales_item_kits`
--

CREATE TABLE `phppos_sales_item_kits` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_kit_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `item_kit_cost_price` decimal(23,10) NOT NULL,
  `item_kit_unit_price` decimal(23,10) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  `commission` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `id_tier` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_sales_item_kits_taxes`
--

CREATE TABLE `phppos_sales_item_kits_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_sales_payments`
--

CREATE TABLE `phppos_sales_payments` (
  `payment_id` int(10) NOT NULL,
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_amount` decimal(23,10) NOT NULL,
  `truncated_card` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `card_issuer` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_shop_config`
--

CREATE TABLE `phppos_shop_config` (
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `phppos_shop_config`
--

INSERT INTO `phppos_shop_config` (`key`, `value`) VALUES
('active_shop', '0'),
('shop', '0'),
('shop_description', ''),
('shop_redes_facebook', ''),
('shop_redes_google', ''),
('shop_redes_twitter', ''),
('shop_theme', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_spare_parts`
--

CREATE TABLE `phppos_spare_parts` (
  `id` int(11) NOT NULL,
  `serie` varchar(25) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `quantity` decimal(10,0) NOT NULL,
  `id_support` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Esta tabla en la nueva version se dejó de usar';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_store_accounts`
--

CREATE TABLE `phppos_store_accounts` (
  `sno` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `petty_cash_id` int(11) DEFAULT NULL,
  `transaction_amount` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `balance` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `movement_type` tinyint(4) DEFAULT NULL COMMENT '1 resta saldo, 0 agregar saldo',
  `category` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_store_payments`
--

CREATE TABLE `phppos_store_payments` (
  `sno` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `pay_cash_id` int(11) DEFAULT NULL,
  `transaction_amount` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `balance` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `comment` text COLLATE utf8_unicode_ci,
  `movement_type` tinyint(4) DEFAULT NULL COMMENT '1 resta saldo, 0 agregar saldo',
  `category` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `abono` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_suppliers`
--

CREATE TABLE `phppos_suppliers` (
  `id` int(10) NOT NULL,
  `person_id` int(10) NOT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `balance` decimal(23,10) NOT NULL DEFAULT '0.0000000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_support_cart`
--

CREATE TABLE `phppos_support_cart` (
  `id` bigint(20) NOT NULL,
  `id_support` int(11) NOT NULL,
  `line` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `tax_included` tinyint(4) NOT NULL,
  `description` text,
  `serialnumber` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `discount` decimal(20,10) DEFAULT NULL,
  `custom1_subcategory` varchar(30) DEFAULT NULL,
  `custom2_subcategory` varchar(30) DEFAULT NULL,
  `price` decimal(20,10) NOT NULL,
  `id_tier` int(11) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_support_cart_taxes`
--

CREATE TABLE `phppos_support_cart_taxes` (
  `support_cart_id` bigint(20) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `percent` decimal(20,10) NOT NULL,
  `cumulative` tinyint(4) NOT NULL,
  `line` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_support_payments`
--

CREATE TABLE `phppos_support_payments` (
  `payment_id` int(11) NOT NULL,
  `id_support` int(11) NOT NULL,
  `payment` float NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL,
  `type` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_synchronizations_offline`
--

CREATE TABLE `phppos_synchronizations_offline` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `person_id` int(11) NOT NULL,
  `token` varchar(150) NOT NULL,
  `ip` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_technical_supports`
--

CREATE TABLE `phppos_technical_supports` (
  `Id_support` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_employee_receive` int(11) NOT NULL,
  `id_employee_register` int(11) NOT NULL,
  `model` varchar(80) NOT NULL,
  `custom1_support_name` varchar(90) NOT NULL,
  `custom2_support_name` varchar(90) NOT NULL,
  `do_have_accessory` tinyint(4) NOT NULL,
  `accessory` varchar(150) DEFAULT NULL,
  `damage_failure` varchar(60) NOT NULL,
  `damage_failure_peronalizada` varchar(150) DEFAULT NULL,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` varchar(50) NOT NULL,
  `order_support` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `type_team` varchar(80) NOT NULL,
  `id_location` int(11) NOT NULL,
  `technical_failure` varchar(200) DEFAULT NULL,
  `deliver_date` timestamp NULL DEFAULT NULL,
  `id_technical` int(11) DEFAULT NULL COMMENT 'indica id del tecnico  que se le asignado',
  `repair_cost` float DEFAULT '0',
  `marca` varchar(50) NOT NULL,
  `observaciones_entrega` text,
  `date_garantia` date NOT NULL DEFAULT '0000-00-00',
  `date_entregado` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `do_have_guarantee` tinyint(4) NOT NULL DEFAULT '0',
  `ubi_equipo` varchar(150) NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `retirado_por` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_technical_supports_diag_tec`
--

CREATE TABLE `phppos_technical_supports_diag_tec` (
  `id` int(11) NOT NULL,
  `id_support` int(11) NOT NULL,
  `diagnostico` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_technical_supports_tfallas`
--

CREATE TABLE `phppos_technical_supports_tfallas` (
  `id` int(11) NOT NULL,
  `tfallas` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_technical_supports_tservicios`
--

CREATE TABLE `phppos_technical_supports_tservicios` (
  `id` int(11) NOT NULL,
  `tservicios` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_technical_supports_ubi_equipos`
--

CREATE TABLE `phppos_technical_supports_ubi_equipos` (
  `id` int(11) NOT NULL,
  `ubicacion` varchar(150) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_time_zones`
--

CREATE TABLE `phppos_time_zones` (
  `time_zone` varchar(50) NOT NULL,
  `language` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='guarda los time zones de amÃ©rica ';

--
-- Volcado de datos para la tabla `phppos_time_zones`
--

INSERT INTO `phppos_time_zones` (`time_zone`, `language`) VALUES
('America/New_York', 'english'),
('America/Sao_Paulo', 'portugues_brasil'),
('America/Bogota', 'spanish'),
('America/Argentina/Buenos_Aires', 'spanish_argentina'),
('America/La_Paz', 'spanish_bolivia'),
('America/Santiago', 'spanish_chile'),
('America/Costa_Rica', 'spanish_costarica'),
('America/Guayaquil', 'spanish_ecuador'),
('America/El_Salvador', 'spanish_elsalvador'),
('America/Guatemala', 'spanish_guatemala'),
('America/Tegucigalpa', 'spanish_honduras'),
('America/Mexico_City', 'spanish_mexico'),
('America/Managua', 'spanish_nicaragua'),
('America/Panama', 'spanish_panama'),
('America/Asuncion', 'spanish_paraguay'),
('America/Lima', 'spanish_peru'),
('America/Puerto_Rico', 'spanish_puertorico'),
('America/Santo_Domingo', 'spanish_republicadominicana'),
('Europe/Madrid', 'spanish_spain'),
('America/Montevideo', 'spanish_uruguay'),
('America/Caracas', 'spanish_venezuela');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_transfer_files`
--

CREATE TABLE `phppos_transfer_files` (
  `file_id` bigint(20) NOT NULL,
  `name_file` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data_file` longblob NOT NULL,
  `date_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `phppos_access_employees`
--
ALTER TABLE `phppos_access_employees`
  ADD PRIMARY KEY (`employee_id`,`id_hour_access`,`id_day_access`,`location`) USING BTREE,
  ADD KEY `phppos_access_employees_ibfk_2` (`id_day_access`),
  ADD KEY `id_hour_access` (`id_hour_access`),
  ADD KEY `location` (`location`);

--
-- Indices de la tabla `phppos_additional_item_numbers`
--
ALTER TABLE `phppos_additional_item_numbers`
  ADD PRIMARY KEY (`item_id`,`item_number`),
  ADD UNIQUE KEY `item_number` (`item_number`),
  ADD KEY `update_additional_item_number` (`update_additional_item_number`);

--
-- Indices de la tabla `phppos_additional_item_seriales`
--
ALTER TABLE `phppos_additional_item_seriales`
  ADD PRIMARY KEY (`item_id`,`item_serial`),
  ADD KEY `item_serial` (`item_serial`);

--
-- Indices de la tabla `phppos_app_config`
--
ALTER TABLE `phppos_app_config`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `phppos_app_files`
--
ALTER TABLE `phppos_app_files`
  ADD PRIMARY KEY (`file_id`);

--
-- Indices de la tabla `phppos_cajas_empleados`
--
ALTER TABLE `phppos_cajas_empleados`
  ADD PRIMARY KEY (`register_id`,`person_id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indices de la tabla `phppos_customers`
--
ALTER TABLE `phppos_customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_number` (`account_number`),
  ADD KEY `person_id` (`person_id`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `cc_token` (`cc_token`),
  ADD KEY `phppos_customers_ibfk_2` (`tier_id`),
  ADD KEY `update_customer` (`update_customer`);

--
-- Indices de la tabla `phppos_day_access`
--
ALTER TABLE `phppos_day_access`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `phppos_defective_audit`
--
ALTER TABLE `phppos_defective_audit`
  ADD PRIMARY KEY (`defective_audit_id`),
  ADD KEY `location_id` (`location_id`,`item_id`);

--
-- Indices de la tabla `phppos_denomination_currency`
--
ALTER TABLE `phppos_denomination_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `phppos_employees`
--
ALTER TABLE `phppos_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `person_id` (`person_id`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `id_rate` (`id_rate`),
  ADD KEY `update_employee` (`update_employee`);

--
-- Indices de la tabla `phppos_employees_locations`
--
ALTER TABLE `phppos_employees_locations`
  ADD PRIMARY KEY (`employee_id`,`location_id`),
  ADD KEY `phppos_employees_locations_ibfk_2` (`location_id`);

--
-- Indices de la tabla `phppos_giftcards`
--
ALTER TABLE `phppos_giftcards`
  ADD PRIMARY KEY (`giftcard_id`),
  ADD UNIQUE KEY `giftcard_number` (`giftcard_number`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `phppos_giftcards_ibfk_1` (`customer_id`);

--
-- Indices de la tabla `phppos_hour_access`
--
ALTER TABLE `phppos_hour_access`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `phppos_inventory`
--
ALTER TABLE `phppos_inventory`
  ADD PRIMARY KEY (`trans_id`),
  ADD KEY `phppos_inventory_ibfk_1` (`trans_items`),
  ADD KEY `phppos_inventory_ibfk_2` (`trans_user`),
  ADD KEY `location_id` (`location_id`);

--
-- Indices de la tabla `phppos_items`
--
ALTER TABLE `phppos_items`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `item_number` (`item_number`),
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD KEY `name` (`name`),
  ADD KEY `category` (`category`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `phppos_items_ibfk_2` (`image_id`),
  ADD KEY `update_item` (`update_item`);

--
-- Indices de la tabla `phppos_items_more`
--
ALTER TABLE `phppos_items_more`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `phppos_items_more_field`
--
ALTER TABLE `phppos_items_more_field`
  ADD PRIMARY KEY (`field_id`,`item_id`),
  ADD KEY `phppos_items_more_field_ibfk_2` (`item_id`);

--
-- Indices de la tabla `phppos_items_subcategory`
--
ALTER TABLE `phppos_items_subcategory`
  ADD PRIMARY KEY (`item_id`,`location_id`,`custom1`,`custom2`),
  ADD KEY `update_subcategory_item` (`update_subcategory_item`);

--
-- Indices de la tabla `phppos_items_suppliers`
--
ALTER TABLE `phppos_items_suppliers`
  ADD PRIMARY KEY (`item_id`,`supplier_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indices de la tabla `phppos_items_taxes`
--
ALTER TABLE `phppos_items_taxes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tax` (`item_id`,`name`,`percent`),
  ADD KEY `update_item_taxes` (`update_item_taxes`);

--
-- Indices de la tabla `phppos_items_tier_prices`
--
ALTER TABLE `phppos_items_tier_prices`
  ADD PRIMARY KEY (`tier_id`,`item_id`),
  ADD KEY `phppos_items_tier_prices_ibfk_2` (`item_id`),
  ADD KEY `update_items_tier_price` (`update_items_tier_price`);

--
-- Indices de la tabla `phppos_item_kits`
--
ALTER TABLE `phppos_item_kits`
  ADD PRIMARY KEY (`item_kit_id`),
  ADD UNIQUE KEY `item_kit_number` (`item_kit_number`),
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD KEY `name` (`name`),
  ADD KEY `deleted` (`deleted`);

--
-- Indices de la tabla `phppos_item_kits_taxes`
--
ALTER TABLE `phppos_item_kits_taxes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tax` (`item_kit_id`,`name`,`percent`);

--
-- Indices de la tabla `phppos_item_kits_tier_prices`
--
ALTER TABLE `phppos_item_kits_tier_prices`
  ADD PRIMARY KEY (`tier_id`,`item_kit_id`),
  ADD KEY `phppos_item_kits_tier_prices_ibfk_2` (`item_kit_id`);

--
-- Indices de la tabla `phppos_item_kit_items`
--
ALTER TABLE `phppos_item_kit_items`
  ADD PRIMARY KEY (`item_kit_id`,`item_id`,`quantity`),
  ADD KEY `phppos_item_kit_items_ibfk_2` (`item_id`),
  ADD KEY `update_item_kit_item` (`update_item_kit_item`);

--
-- Indices de la tabla `phppos_item_range`
--
ALTER TABLE `phppos_item_range`
  ADD PRIMARY KEY (`range_id`),
  ADD UNIQUE KEY `item_id` (`item_id`,`register_log_id`),
  ADD KEY `employee_id_recharge` (`employee_id_recharge`),
  ADD KEY `register_log_id` (`register_log_id`);

--
-- Indices de la tabla `phppos_locations`
--
ALTER TABLE `phppos_locations`
  ADD PRIMARY KEY (`location_id`),
  ADD KEY `deleted` (`deleted`);

--
-- Indices de la tabla `phppos_location_items`
--
ALTER TABLE `phppos_location_items`
  ADD PRIMARY KEY (`location_id`,`item_id`),
  ADD KEY `phppos_location_items_ibfk_2` (`item_id`),
  ADD KEY `update_item_location` (`update_item_location`);

--
-- Indices de la tabla `phppos_location_items_taxes`
--
ALTER TABLE `phppos_location_items_taxes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tax` (`location_id`,`item_id`,`name`,`percent`),
  ADD KEY `phppos_location_items_taxes_ibfk_2` (`item_id`),
  ADD KEY `update_item_taxes_location` (`update_item_taxes_location`);

--
-- Indices de la tabla `phppos_location_items_tier_prices`
--
ALTER TABLE `phppos_location_items_tier_prices`
  ADD PRIMARY KEY (`tier_id`,`item_id`,`location_id`),
  ADD KEY `phppos_location_items_tier_prices_ibfk_2` (`location_id`),
  ADD KEY `phppos_location_items_tier_prices_ibfk_3` (`item_id`);

--
-- Indices de la tabla `phppos_location_item_kits`
--
ALTER TABLE `phppos_location_item_kits`
  ADD PRIMARY KEY (`location_id`,`item_kit_id`),
  ADD KEY `phppos_location_item_kits_ibfk_2` (`item_kit_id`),
  ADD KEY `update_location_item_kits` (`update_location_item_kits`);

--
-- Indices de la tabla `phppos_location_item_kits_taxes`
--
ALTER TABLE `phppos_location_item_kits_taxes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tax` (`location_id`,`item_kit_id`,`name`,`percent`),
  ADD KEY `phppos_location_item_kits_taxes_ibfk_2` (`item_kit_id`);

--
-- Indices de la tabla `phppos_location_item_kits_tier_prices`
--
ALTER TABLE `phppos_location_item_kits_tier_prices`
  ADD PRIMARY KEY (`tier_id`,`item_kit_id`,`location_id`),
  ADD KEY `phppos_location_item_kits_tier_prices_ibfk_2` (`location_id`),
  ADD KEY `phppos_location_item_kits_tier_prices_ibfk_3` (`item_kit_id`);

--
-- Indices de la tabla `phppos_modules`
--
ALTER TABLE `phppos_modules`
  ADD PRIMARY KEY (`module_id`),
  ADD UNIQUE KEY `desc_lang_key` (`desc_lang_key`),
  ADD UNIQUE KEY `name_lang_key` (`name_lang_key`);

--
-- Indices de la tabla `phppos_modules_actions`
--
ALTER TABLE `phppos_modules_actions`
  ADD PRIMARY KEY (`action_id`,`module_id`),
  ADD KEY `phppos_modules_actions_ibfk_1` (`module_id`);

--
-- Indices de la tabla `phppos_movement_balance_employees`
--
ALTER TABLE `phppos_movement_balance_employees`
  ADD PRIMARY KEY (`id_movement`),
  ADD KEY `id_person` (`id_person`),
  ADD KEY `registered_by` (`registered_by`),
  ADD KEY `location_id` (`location_id`);

--
-- Indices de la tabla `phppos_orders_sales`
--
ALTER TABLE `phppos_orders_sales`
  ADD PRIMARY KEY (`order_sale_id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `number` (`number`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `state` (`state`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indices de la tabla `phppos_pay_cash`
--
ALTER TABLE `phppos_pay_cash`
  ADD PRIMARY KEY (`pay_cash_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `phppos_receivings_ibfk_4` (`deleted_by`),
  ADD KEY `pay_cash_search` (`location_id`,`store_account_payment`,`pay_cash_time`,`pay_cash_id`),
  ADD KEY `phppos_pay_cash_ibfk_7` (`register_id`),
  ADD KEY `phppos_pay_cash_ibfk_6` (`sold_by_employee_id`),
  ADD KEY `receiving_id` (`receiving_id`);

--
-- Indices de la tabla `phppos_pay_cash_payments`
--
ALTER TABLE `phppos_pay_cash_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `pay_cash_id` (`pay_cash_id`);

--
-- Indices de la tabla `phppos_people`
--
ALTER TABLE `phppos_people`
  ADD PRIMARY KEY (`person_id`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `email` (`email`),
  ADD KEY `phppos_people_ibfk_1` (`image_id`),
  ADD KEY `update_people` (`update_people`);

--
-- Indices de la tabla `phppos_permissions`
--
ALTER TABLE `phppos_permissions`
  ADD PRIMARY KEY (`module_id`,`person_id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indices de la tabla `phppos_permissions_actions`
--
ALTER TABLE `phppos_permissions_actions`
  ADD PRIMARY KEY (`module_id`,`person_id`,`action_id`),
  ADD KEY `phppos_permissions_actions_ibfk_2` (`person_id`),
  ADD KEY `phppos_permissions_actions_ibfk_3` (`action_id`);

--
-- Indices de la tabla `phppos_petty_cash`
--
ALTER TABLE `phppos_petty_cash`
  ADD PRIMARY KEY (`petty_cash_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `phppos_sales_ibfk_4` (`deleted_by`),
  ADD KEY `petty_cash_search` (`location_id`,`store_account_payment`,`petty_cash_time`,`petty_cash_id`),
  ADD KEY `phppos_petty_cash_ibfk_7` (`register_id`),
  ADD KEY `phppos_petty_cash_ibfk_6` (`sold_by_employee_id`);

--
-- Indices de la tabla `phppos_petty_cash_payments`
--
ALTER TABLE `phppos_petty_cash_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `petty_cash_id` (`petty_cash_id`);

--
-- Indices de la tabla `phppos_points`
--
ALTER TABLE `phppos_points`
  ADD PRIMARY KEY (`id_point`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indices de la tabla `phppos_points_movement`
--
ALTER TABLE `phppos_points_movement`
  ADD KEY `id_point` (`id_point`);

--
-- Indices de la tabla `phppos_price_tiers`
--
ALTER TABLE `phppos_price_tiers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `phppos_quotes`
--
ALTER TABLE `phppos_quotes`
  ADD PRIMARY KEY (`quote_id`),
  ADD KEY `customer_id` (`customer_id`,`employee_id`,`sold_by_employee_id`,`location_id`,`register_id`,`tier_id`),
  ADD KEY `register_id` (`register_id`),
  ADD KEY `tier_id` (`tier_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `sold_by_employee_id` (`sold_by_employee_id`),
  ADD KEY `deleted_by` (`deleted_by`);

--
-- Indices de la tabla `phppos_quotes_items`
--
ALTER TABLE `phppos_quotes_items`
  ADD PRIMARY KEY (`quote_id`,`item_id`,`line`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `phppos_quotes_items_taxes`
--
ALTER TABLE `phppos_quotes_items_taxes`
  ADD PRIMARY KEY (`quote_id`,`item_id`,`line`,`name`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `phppos_quotes_payments`
--
ALTER TABLE `phppos_quotes_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `sale_id` (`quote_id`),
  ADD KEY `sale_id_2` (`quote_id`);

--
-- Indices de la tabla `phppos_rates`
--
ALTER TABLE `phppos_rates`
  ADD PRIMARY KEY (`id_rate`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `phppos_receivings`
--
ALTER TABLE `phppos_receivings`
  ADD PRIMARY KEY (`receiving_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `transfer_to_location_id` (`transfer_to_location_id`);

--
-- Indices de la tabla `phppos_receivings_items`
--
ALTER TABLE `phppos_receivings_items`
  ADD PRIMARY KEY (`receiving_id`,`item_id`,`line`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `phppos_receivings_items_taxes`
--
ALTER TABLE `phppos_receivings_items_taxes`
  ADD PRIMARY KEY (`receiving_id`,`item_id`,`line`,`name`,`percent`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `phppos_receivings_payments`
--
ALTER TABLE `phppos_receivings_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `receiving_id` (`receiving_id`);

--
-- Indices de la tabla `phppos_registers`
--
ALTER TABLE `phppos_registers`
  ADD PRIMARY KEY (`register_id`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `phppos_registers_ibfk_1` (`location_id`);

--
-- Indices de la tabla `phppos_registers_movement`
--
ALTER TABLE `phppos_registers_movement`
  ADD PRIMARY KEY (`register_movement_id`),
  ADD KEY `register_log_id` (`register_log_id`),
  ADD KEY `register_log_id_2` (`register_log_id`),
  ADD KEY `id_employee` (`id_employee`);

--
-- Indices de la tabla `phppos_register_log`
--
ALTER TABLE `phppos_register_log`
  ADD PRIMARY KEY (`register_log_id`),
  ADD UNIQUE KEY `synchronizations_offline_id` (`synchronizations_offline_id`,`register_log_id_offline`),
  ADD KEY `phppos_register_log_ibfk_1` (`employee_id_open`),
  ADD KEY `phppos_register_log_ibfk_2` (`register_id`),
  ADD KEY `phppos_register_log_ibfk_3` (`employee_id_close`);

--
-- Indices de la tabla `phppos_sales`
--
ALTER TABLE `phppos_sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD UNIQUE KEY `offline_sales` (`synchronizations_offline_id`,`sale_id_offline`),
  ADD UNIQUE KEY `support_id` (`support_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `phppos_sales_ibfk_4` (`deleted_by`),
  ADD KEY `sales_search` (`location_id`,`store_account_payment`,`sale_time`,`sale_id`),
  ADD KEY `phppos_sales_ibfk_5` (`tier_id`),
  ADD KEY `phppos_sales_ibfk_7` (`register_id`),
  ADD KEY `phppos_sales_ibfk_6` (`sold_by_employee_id`);

--
-- Indices de la tabla `phppos_sales_items`
--
ALTER TABLE `phppos_sales_items`
  ADD PRIMARY KEY (`sale_id`,`item_id`,`line`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Indices de la tabla `phppos_sales_items_taxes`
--
ALTER TABLE `phppos_sales_items_taxes`
  ADD PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `phppos_sales_item_kits`
--
ALTER TABLE `phppos_sales_item_kits`
  ADD PRIMARY KEY (`sale_id`,`item_kit_id`,`line`),
  ADD KEY `item_kit_id` (`item_kit_id`);

--
-- Indices de la tabla `phppos_sales_item_kits_taxes`
--
ALTER TABLE `phppos_sales_item_kits_taxes`
  ADD PRIMARY KEY (`sale_id`,`item_kit_id`,`line`,`name`,`percent`),
  ADD KEY `item_id` (`item_kit_id`);

--
-- Indices de la tabla `phppos_sales_payments`
--
ALTER TABLE `phppos_sales_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `sale_id` (`sale_id`);

--
-- Indices de la tabla `phppos_shop_config`
--
ALTER TABLE `phppos_shop_config`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `phppos_spare_parts`
--
ALTER TABLE `phppos_spare_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_support` (`id_support`);

--
-- Indices de la tabla `phppos_store_accounts`
--
ALTER TABLE `phppos_store_accounts`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `phppos_store_accounts_ibfk_1` (`petty_cash_id`),
  ADD KEY `phppos_store_accounts_ibfk_2` (`customer_id`);

--
-- Indices de la tabla `phppos_store_payments`
--
ALTER TABLE `phppos_store_payments`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `phppos_store_accounts_ibfk_1` (`pay_cash_id`),
  ADD KEY `phppos_store_accounts_ibfk_2` (`supplier_id`);

--
-- Indices de la tabla `phppos_suppliers`
--
ALTER TABLE `phppos_suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_number` (`account_number`),
  ADD KEY `person_id` (`person_id`),
  ADD KEY `deleted` (`deleted`);

--
-- Indices de la tabla `phppos_support_cart`
--
ALTER TABLE `phppos_support_cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `line` (`line`,`id_support`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `phppos_support_cart_taxes`
--
ALTER TABLE `phppos_support_cart_taxes`
  ADD KEY `phppos_support_cart_taxes_ibfk_1` (`support_cart_id`),
  ADD KEY `line` (`line`);

--
-- Indices de la tabla `phppos_support_payments`
--
ALTER TABLE `phppos_support_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `id_support` (`id_support`);

--
-- Indices de la tabla `phppos_synchronizations_offline`
--
ALTER TABLE `phppos_synchronizations_offline`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indices de la tabla `phppos_technical_supports`
--
ALTER TABLE `phppos_technical_supports`
  ADD PRIMARY KEY (`Id_support`),
  ADD KEY `id_location` (`id_location`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_employee_receive` (`id_employee_receive`),
  ADD KEY `id_employee_register` (`id_employee_register`),
  ADD KEY `id_technical` (`id_technical`);

--
-- Indices de la tabla `phppos_technical_supports_diag_tec`
--
ALTER TABLE `phppos_technical_supports_diag_tec`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_support` (`id_support`);

--
-- Indices de la tabla `phppos_technical_supports_tfallas`
--
ALTER TABLE `phppos_technical_supports_tfallas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tfallas` (`tfallas`);

--
-- Indices de la tabla `phppos_technical_supports_tservicios`
--
ALTER TABLE `phppos_technical_supports_tservicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tservicios` (`tservicios`);

--
-- Indices de la tabla `phppos_technical_supports_ubi_equipos`
--
ALTER TABLE `phppos_technical_supports_ubi_equipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `phppos_time_zones`
--
ALTER TABLE `phppos_time_zones`
  ADD PRIMARY KEY (`language`);

--
-- Indices de la tabla `phppos_transfer_files`
--
ALTER TABLE `phppos_transfer_files`
  ADD PRIMARY KEY (`file_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `phppos_additional_item_numbers`
--
ALTER TABLE `phppos_additional_item_numbers`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_app_files`
--
ALTER TABLE `phppos_app_files`
  MODIFY `file_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_customers`
--
ALTER TABLE `phppos_customers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_day_access`
--
ALTER TABLE `phppos_day_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `phppos_defective_audit`
--
ALTER TABLE `phppos_defective_audit`
  MODIFY `defective_audit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_denomination_currency`
--
ALTER TABLE `phppos_denomination_currency`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_employees`
--
ALTER TABLE `phppos_employees`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `phppos_giftcards`
--
ALTER TABLE `phppos_giftcards`
  MODIFY `giftcard_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_hour_access`
--
ALTER TABLE `phppos_hour_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `phppos_inventory`
--
ALTER TABLE `phppos_inventory`
  MODIFY `trans_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_items`
--
ALTER TABLE `phppos_items`
  MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `phppos_items_more`
--
ALTER TABLE `phppos_items_more`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_items_taxes`
--
ALTER TABLE `phppos_items_taxes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_item_kits`
--
ALTER TABLE `phppos_item_kits`
  MODIFY `item_kit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_item_kits_taxes`
--
ALTER TABLE `phppos_item_kits_taxes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_item_range`
--
ALTER TABLE `phppos_item_range`
  MODIFY `range_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_locations`
--
ALTER TABLE `phppos_locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `phppos_location_items_taxes`
--
ALTER TABLE `phppos_location_items_taxes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_location_item_kits_taxes`
--
ALTER TABLE `phppos_location_item_kits_taxes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_movement_balance_employees`
--
ALTER TABLE `phppos_movement_balance_employees`
  MODIFY `id_movement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_orders_sales`
--
ALTER TABLE `phppos_orders_sales`
  MODIFY `order_sale_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_pay_cash`
--
ALTER TABLE `phppos_pay_cash`
  MODIFY `pay_cash_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_pay_cash_payments`
--
ALTER TABLE `phppos_pay_cash_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_people`
--
ALTER TABLE `phppos_people`
  MODIFY `person_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `phppos_petty_cash`
--
ALTER TABLE `phppos_petty_cash`
  MODIFY `petty_cash_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_petty_cash_payments`
--
ALTER TABLE `phppos_petty_cash_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_points`
--
ALTER TABLE `phppos_points`
  MODIFY `id_point` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_price_tiers`
--
ALTER TABLE `phppos_price_tiers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_quotes`
--
ALTER TABLE `phppos_quotes`
  MODIFY `quote_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_quotes_payments`
--
ALTER TABLE `phppos_quotes_payments`
  MODIFY `payment_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_rates`
--
ALTER TABLE `phppos_rates`
  MODIFY `id_rate` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `phppos_receivings`
--
ALTER TABLE `phppos_receivings`
  MODIFY `receiving_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_receivings_payments`
--
ALTER TABLE `phppos_receivings_payments`
  MODIFY `payment_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_registers`
--
ALTER TABLE `phppos_registers`
  MODIFY `register_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `phppos_registers_movement`
--
ALTER TABLE `phppos_registers_movement`
  MODIFY `register_movement_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_register_log`
--
ALTER TABLE `phppos_register_log`
  MODIFY `register_log_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_sales`
--
ALTER TABLE `phppos_sales`
  MODIFY `sale_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_sales_payments`
--
ALTER TABLE `phppos_sales_payments`
  MODIFY `payment_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_spare_parts`
--
ALTER TABLE `phppos_spare_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_store_accounts`
--
ALTER TABLE `phppos_store_accounts`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_store_payments`
--
ALTER TABLE `phppos_store_payments`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_suppliers`
--
ALTER TABLE `phppos_suppliers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_support_cart`
--
ALTER TABLE `phppos_support_cart`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_support_payments`
--
ALTER TABLE `phppos_support_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_synchronizations_offline`
--
ALTER TABLE `phppos_synchronizations_offline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_technical_supports`
--
ALTER TABLE `phppos_technical_supports`
  MODIFY `Id_support` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_technical_supports_diag_tec`
--
ALTER TABLE `phppos_technical_supports_diag_tec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_technical_supports_tfallas`
--
ALTER TABLE `phppos_technical_supports_tfallas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_technical_supports_tservicios`
--
ALTER TABLE `phppos_technical_supports_tservicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_technical_supports_ubi_equipos`
--
ALTER TABLE `phppos_technical_supports_ubi_equipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `phppos_transfer_files`
--
ALTER TABLE `phppos_transfer_files`
  MODIFY `file_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `phppos_access_employees`
--
ALTER TABLE `phppos_access_employees`
  ADD CONSTRAINT `phppos_access_employees_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `phppos_access_employees_ibfk_2` FOREIGN KEY (`id_day_access`) REFERENCES `phppos_day_access` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `phppos_access_employees_ibfk_3` FOREIGN KEY (`id_hour_access`) REFERENCES `phppos_hour_access` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `phppos_access_employees_ibfk_4` FOREIGN KEY (`location`) REFERENCES `phppos_locations` (`location_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `phppos_additional_item_numbers`
--
ALTER TABLE `phppos_additional_item_numbers`
  ADD CONSTRAINT `phppos_additional_item_numbers_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`);

--
-- Filtros para la tabla `phppos_additional_item_seriales`
--
ALTER TABLE `phppos_additional_item_seriales`
  ADD CONSTRAINT `phppos_additional_item_seriales_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`);

--
-- Filtros para la tabla `phppos_cajas_empleados`
--
ALTER TABLE `phppos_cajas_empleados`
  ADD CONSTRAINT `phppos_cajas_empleados_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_cajas_empleados_ibfk_2` FOREIGN KEY (`register_id`) REFERENCES `phppos_registers` (`register_id`);

--
-- Filtros para la tabla `phppos_customers`
--
ALTER TABLE `phppos_customers`
  ADD CONSTRAINT `phppos_customers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `phppos_people` (`person_id`),
  ADD CONSTRAINT `phppos_customers_ibfk_2` FOREIGN KEY (`tier_id`) REFERENCES `phppos_price_tiers` (`id`);

--
-- Filtros para la tabla `phppos_employees`
--
ALTER TABLE `phppos_employees`
  ADD CONSTRAINT `phppos_employees_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `phppos_people` (`person_id`),
  ADD CONSTRAINT `phppos_employees_ibfk_2` FOREIGN KEY (`id_rate`) REFERENCES `phppos_rates` (`id_rate`);

--
-- Filtros para la tabla `phppos_employees_locations`
--
ALTER TABLE `phppos_employees_locations`
  ADD CONSTRAINT `phppos_employees_locations_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_employees_locations_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`);

--
-- Filtros para la tabla `phppos_giftcards`
--
ALTER TABLE `phppos_giftcards`
  ADD CONSTRAINT `phppos_giftcards_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `phppos_customers` (`person_id`);

--
-- Filtros para la tabla `phppos_inventory`
--
ALTER TABLE `phppos_inventory`
  ADD CONSTRAINT `phppos_inventory_ibfk_1` FOREIGN KEY (`trans_items`) REFERENCES `phppos_items` (`item_id`),
  ADD CONSTRAINT `phppos_inventory_ibfk_2` FOREIGN KEY (`trans_user`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_inventory_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`);

--
-- Filtros para la tabla `phppos_items`
--
ALTER TABLE `phppos_items`
  ADD CONSTRAINT `phppos_items_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `phppos_app_files` (`file_id`);

--
-- Filtros para la tabla `phppos_items_more_field`
--
ALTER TABLE `phppos_items_more_field`
  ADD CONSTRAINT `phppos_items_more_field_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `phppos_items_more` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phppos_items_more_field_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`);

--
-- Filtros para la tabla `phppos_items_subcategory`
--
ALTER TABLE `phppos_items_subcategory`
  ADD CONSTRAINT `phppos_items_subcategory_ibfk_1` FOREIGN KEY (`item_id`,`location_id`) REFERENCES `phppos_location_items` (`item_id`, `location_id`);

--
-- Filtros para la tabla `phppos_items_suppliers`
--
ALTER TABLE `phppos_items_suppliers`
  ADD CONSTRAINT `phppos_items_suppliers_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `phppos_suppliers` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `phppos_items_suppliers_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `phppos_items_taxes`
--
ALTER TABLE `phppos_items_taxes`
  ADD CONSTRAINT `phppos_items_taxes_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `phppos_items_tier_prices`
--
ALTER TABLE `phppos_items_tier_prices`
  ADD CONSTRAINT `phppos_items_tier_prices_ibfk_1` FOREIGN KEY (`tier_id`) REFERENCES `phppos_price_tiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phppos_items_tier_prices_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`);

--
-- Filtros para la tabla `phppos_item_kits_taxes`
--
ALTER TABLE `phppos_item_kits_taxes`
  ADD CONSTRAINT `phppos_item_kits_taxes_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `phppos_item_kits_tier_prices`
--
ALTER TABLE `phppos_item_kits_tier_prices`
  ADD CONSTRAINT `phppos_item_kits_tier_prices_ibfk_1` FOREIGN KEY (`tier_id`) REFERENCES `phppos_price_tiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phppos_item_kits_tier_prices_ibfk_2` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`);

--
-- Filtros para la tabla `phppos_item_kit_items`
--
ALTER TABLE `phppos_item_kit_items`
  ADD CONSTRAINT `phppos_item_kit_items_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phppos_item_kit_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `phppos_item_range`
--
ALTER TABLE `phppos_item_range`
  ADD CONSTRAINT `phppos_item_range_ibfk_1` FOREIGN KEY (`employee_id_recharge`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_item_range_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`),
  ADD CONSTRAINT `phppos_item_range_ibfk_3` FOREIGN KEY (`register_log_id`) REFERENCES `phppos_register_log` (`register_log_id`);

--
-- Filtros para la tabla `phppos_location_items`
--
ALTER TABLE `phppos_location_items`
  ADD CONSTRAINT `phppos_location_items_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  ADD CONSTRAINT `phppos_location_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`);

--
-- Filtros para la tabla `phppos_location_items_taxes`
--
ALTER TABLE `phppos_location_items_taxes`
  ADD CONSTRAINT `phppos_location_items_taxes_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phppos_location_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `phppos_location_items_tier_prices`
--
ALTER TABLE `phppos_location_items_tier_prices`
  ADD CONSTRAINT `phppos_location_items_tier_prices_ibfk_1` FOREIGN KEY (`tier_id`) REFERENCES `phppos_price_tiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phppos_location_items_tier_prices_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  ADD CONSTRAINT `phppos_location_items_tier_prices_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`);

--
-- Filtros para la tabla `phppos_location_item_kits`
--
ALTER TABLE `phppos_location_item_kits`
  ADD CONSTRAINT `phppos_location_item_kits_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  ADD CONSTRAINT `phppos_location_item_kits_ibfk_2` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`);

--
-- Filtros para la tabla `phppos_location_item_kits_taxes`
--
ALTER TABLE `phppos_location_item_kits_taxes`
  ADD CONSTRAINT `phppos_location_item_kits_taxes_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phppos_location_item_kits_taxes_ibfk_2` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `phppos_location_item_kits_tier_prices`
--
ALTER TABLE `phppos_location_item_kits_tier_prices`
  ADD CONSTRAINT `phppos_location_item_kits_tier_prices_ibfk_1` FOREIGN KEY (`tier_id`) REFERENCES `phppos_price_tiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phppos_location_item_kits_tier_prices_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  ADD CONSTRAINT `phppos_location_item_kits_tier_prices_ibfk_3` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`);

--
-- Filtros para la tabla `phppos_modules_actions`
--
ALTER TABLE `phppos_modules_actions`
  ADD CONSTRAINT `phppos_modules_actions_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `phppos_modules` (`module_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `phppos_movement_balance_employees`
--
ALTER TABLE `phppos_movement_balance_employees`
  ADD CONSTRAINT `phppos_movement_balance_employees_ibfk_1` FOREIGN KEY (`id_person`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_movement_balance_employees_ibfk_2` FOREIGN KEY (`registered_by`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_movement_balance_employees_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`);

--
-- Filtros para la tabla `phppos_orders_sales`
--
ALTER TABLE `phppos_orders_sales`
  ADD CONSTRAINT `phppos_orders_sales_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales` (`sale_id`),
  ADD CONSTRAINT `phppos_orders_sales_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`);

--
-- Filtros para la tabla `phppos_pay_cash_payments`
--
ALTER TABLE `phppos_pay_cash_payments`
  ADD CONSTRAINT `phppos_pay_cash_payments_ibfk_1` FOREIGN KEY (`pay_cash_id`) REFERENCES `phppos_pay_cash` (`pay_cash_id`);

--
-- Filtros para la tabla `phppos_people`
--
ALTER TABLE `phppos_people`
  ADD CONSTRAINT `phppos_people_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `phppos_app_files` (`file_id`);

--
-- Filtros para la tabla `phppos_permissions`
--
ALTER TABLE `phppos_permissions`
  ADD CONSTRAINT `phppos_permissions_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_permissions_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `phppos_modules` (`module_id`);

--
-- Filtros para la tabla `phppos_permissions_actions`
--
ALTER TABLE `phppos_permissions_actions`
  ADD CONSTRAINT `phppos_permissions_actions_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `phppos_modules` (`module_id`),
  ADD CONSTRAINT `phppos_permissions_actions_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_permissions_actions_ibfk_3` FOREIGN KEY (`action_id`) REFERENCES `phppos_modules_actions` (`action_id`);

--
-- Filtros para la tabla `phppos_petty_cash`
--
ALTER TABLE `phppos_petty_cash`
  ADD CONSTRAINT `phppos_petty_cash_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_petty_cash_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `phppos_customers` (`person_id`),
  ADD CONSTRAINT `phppos_petty_cash_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  ADD CONSTRAINT `phppos_petty_cash_ibfk_4` FOREIGN KEY (`deleted_by`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_petty_cash_ibfk_6` FOREIGN KEY (`sold_by_employee_id`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_petty_cash_ibfk_7` FOREIGN KEY (`register_id`) REFERENCES `phppos_registers` (`register_id`);

--
-- Filtros para la tabla `phppos_petty_cash_payments`
--
ALTER TABLE `phppos_petty_cash_payments`
  ADD CONSTRAINT `phppos_petty_cash_payments_ibfk_1` FOREIGN KEY (`petty_cash_id`) REFERENCES `phppos_petty_cash` (`petty_cash_id`);

--
-- Filtros para la tabla `phppos_points`
--
ALTER TABLE `phppos_points`
  ADD CONSTRAINT `phppos_point_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `phppos_people` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `phppos_points_movement`
--
ALTER TABLE `phppos_points_movement`
  ADD CONSTRAINT `phppos_points_movement_ibfk_1` FOREIGN KEY (`id_point`) REFERENCES `phppos_points` (`id_point`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `phppos_quotes`
--
ALTER TABLE `phppos_quotes`
  ADD CONSTRAINT `phppos_quotes_ibfk_1` FOREIGN KEY (`register_id`) REFERENCES `phppos_registers` (`register_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `phppos_quotes_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `phppos_customers` (`person_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `phppos_quotes_ibfk_3` FOREIGN KEY (`tier_id`) REFERENCES `phppos_price_tiers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `phppos_quotes_ibfk_4` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `phppos_quotes_ibfk_5` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `phppos_quotes_ibfk_6` FOREIGN KEY (`sold_by_employee_id`) REFERENCES `phppos_employees` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `phppos_quotes_ibfk_7` FOREIGN KEY (`deleted_by`) REFERENCES `phppos_employees` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `phppos_quotes_items`
--
ALTER TABLE `phppos_quotes_items`
  ADD CONSTRAINT `phppos_quotes_items_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `phppos_quotes` (`quote_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `phppos_quotes_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `phppos_quotes_items_taxes`
--
ALTER TABLE `phppos_quotes_items_taxes`
  ADD CONSTRAINT `phppos_quotes_items_taxes_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `phppos_quotes_items_taxes_ibfk_2` FOREIGN KEY (`quote_id`) REFERENCES `phppos_quotes` (`quote_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `phppos_quotes_payments`
--
ALTER TABLE `phppos_quotes_payments`
  ADD CONSTRAINT `phppos_quotes_payments_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `phppos_quotes` (`quote_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `phppos_receivings`
--
ALTER TABLE `phppos_receivings`
  ADD CONSTRAINT `phppos_receivings_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_receivings_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `phppos_suppliers` (`person_id`),
  ADD CONSTRAINT `phppos_receivings_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  ADD CONSTRAINT `phppos_receivings_ibfk_4` FOREIGN KEY (`transfer_to_location_id`) REFERENCES `phppos_locations` (`location_id`);

--
-- Filtros para la tabla `phppos_receivings_items`
--
ALTER TABLE `phppos_receivings_items`
  ADD CONSTRAINT `phppos_receivings_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`),
  ADD CONSTRAINT `phppos_receivings_items_ibfk_2` FOREIGN KEY (`receiving_id`) REFERENCES `phppos_receivings` (`receiving_id`);

--
-- Filtros para la tabla `phppos_receivings_items_taxes`
--
ALTER TABLE `phppos_receivings_items_taxes`
  ADD CONSTRAINT `phppos_receivings_items_taxes_ibfk_1` FOREIGN KEY (`receiving_id`) REFERENCES `phppos_receivings_items` (`receiving_id`),
  ADD CONSTRAINT `phppos_receivings_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`);

--
-- Filtros para la tabla `phppos_receivings_payments`
--
ALTER TABLE `phppos_receivings_payments`
  ADD CONSTRAINT `phppos_receivings_payments_ibfk_1` FOREIGN KEY (`receiving_id`) REFERENCES `phppos_receivings` (`receiving_id`);

--
-- Filtros para la tabla `phppos_registers`
--
ALTER TABLE `phppos_registers`
  ADD CONSTRAINT `phppos_registers_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`);

--
-- Filtros para la tabla `phppos_registers_movement`
--
ALTER TABLE `phppos_registers_movement`
  ADD CONSTRAINT `phppos_registers_movement_ibfk_1` FOREIGN KEY (`register_log_id`) REFERENCES `phppos_register_log` (`register_log_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `phppos_registers_movement_ibfk_2` FOREIGN KEY (`id_employee`) REFERENCES `phppos_employees` (`person_id`);

--
-- Filtros para la tabla `phppos_register_log`
--
ALTER TABLE `phppos_register_log`
  ADD CONSTRAINT `phppos_register_log_ibfk_1` FOREIGN KEY (`employee_id_open`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_register_log_ibfk_2` FOREIGN KEY (`register_id`) REFERENCES `phppos_registers` (`register_id`),
  ADD CONSTRAINT `phppos_register_log_ibfk_3` FOREIGN KEY (`employee_id_close`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_register_log_ibfk_4` FOREIGN KEY (`synchronizations_offline_id`) REFERENCES `phppos_synchronizations_offline` (`id`);

--
-- Filtros para la tabla `phppos_sales`
--
ALTER TABLE `phppos_sales`
  ADD CONSTRAINT `phppos_sales_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `phppos_customers` (`person_id`),
  ADD CONSTRAINT `phppos_sales_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  ADD CONSTRAINT `phppos_sales_ibfk_4` FOREIGN KEY (`deleted_by`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_sales_ibfk_5` FOREIGN KEY (`tier_id`) REFERENCES `phppos_price_tiers` (`id`),
  ADD CONSTRAINT `phppos_sales_ibfk_6` FOREIGN KEY (`sold_by_employee_id`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_sales_ibfk_7` FOREIGN KEY (`register_id`) REFERENCES `phppos_registers` (`register_id`),
  ADD CONSTRAINT `phppos_sales_ibfk_8` FOREIGN KEY (`synchronizations_offline_id`) REFERENCES `phppos_synchronizations_offline` (`id`),
  ADD CONSTRAINT `phppos_sales_ibfk_9` FOREIGN KEY (`support_id`) REFERENCES `phppos_technical_supports` (`Id_support`);

--
-- Filtros para la tabla `phppos_sales_items`
--
ALTER TABLE `phppos_sales_items`
  ADD CONSTRAINT `phppos_sales_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`),
  ADD CONSTRAINT `phppos_sales_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales` (`sale_id`),
  ADD CONSTRAINT `phppos_sales_items_ibfk_3` FOREIGN KEY (`file_id`) REFERENCES `phppos_transfer_files` (`file_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `phppos_sales_items_taxes`
--
ALTER TABLE `phppos_sales_items_taxes`
  ADD CONSTRAINT `phppos_sales_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales_items` (`sale_id`),
  ADD CONSTRAINT `phppos_sales_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`);

--
-- Filtros para la tabla `phppos_sales_item_kits`
--
ALTER TABLE `phppos_sales_item_kits`
  ADD CONSTRAINT `phppos_sales_item_kits_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`),
  ADD CONSTRAINT `phppos_sales_item_kits_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales` (`sale_id`);

--
-- Filtros para la tabla `phppos_sales_item_kits_taxes`
--
ALTER TABLE `phppos_sales_item_kits_taxes`
  ADD CONSTRAINT `phppos_sales_item_kits_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales_item_kits` (`sale_id`),
  ADD CONSTRAINT `phppos_sales_item_kits_taxes_ibfk_2` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`);

--
-- Filtros para la tabla `phppos_sales_payments`
--
ALTER TABLE `phppos_sales_payments`
  ADD CONSTRAINT `phppos_sales_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales` (`sale_id`);

--
-- Filtros para la tabla `phppos_spare_parts`
--
ALTER TABLE `phppos_spare_parts`
  ADD CONSTRAINT `phppos_spare_parts_ibfk_1` FOREIGN KEY (`id_support`) REFERENCES `phppos_technical_supports` (`Id_support`);

--
-- Filtros para la tabla `phppos_store_accounts`
--
ALTER TABLE `phppos_store_accounts`
  ADD CONSTRAINT `phppos_store_accounts_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `phppos_customers` (`person_id`),
  ADD CONSTRAINT `phppos_store_accounts_ibfk_3` FOREIGN KEY (`petty_cash_id`) REFERENCES `phppos_petty_cash` (`petty_cash_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `phppos_store_payments`
--
ALTER TABLE `phppos_store_payments`
  ADD CONSTRAINT `phppos_store_payments_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `phppos_suppliers` (`person_id`),
  ADD CONSTRAINT `phppos_store_payments_ibfk_2` FOREIGN KEY (`pay_cash_id`) REFERENCES `phppos_pay_cash` (`pay_cash_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `phppos_suppliers`
--
ALTER TABLE `phppos_suppliers`
  ADD CONSTRAINT `phppos_suppliers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `phppos_people` (`person_id`);

--
-- Filtros para la tabla `phppos_support_cart`
--
ALTER TABLE `phppos_support_cart`
  ADD CONSTRAINT `phppos_support_cart_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`);

--
-- Filtros para la tabla `phppos_support_cart_taxes`
--
ALTER TABLE `phppos_support_cart_taxes`
  ADD CONSTRAINT `phppos_support_cart_taxes_ibfk_1` FOREIGN KEY (`support_cart_id`) REFERENCES `phppos_support_cart` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `phppos_support_payments`
--
ALTER TABLE `phppos_support_payments`
  ADD CONSTRAINT `phppos_support_payments_ibfk_1` FOREIGN KEY (`id_support`) REFERENCES `phppos_technical_supports` (`Id_support`);

--
-- Filtros para la tabla `phppos_technical_supports`
--
ALTER TABLE `phppos_technical_supports`
  ADD CONSTRAINT `phppos_technical_supports_ibfk_3` FOREIGN KEY (`id_location`) REFERENCES `phppos_locations` (`location_id`),
  ADD CONSTRAINT `phppos_technical_supports_ibfk_4` FOREIGN KEY (`id_customer`) REFERENCES `phppos_customers` (`person_id`),
  ADD CONSTRAINT `phppos_technical_supports_ibfk_5` FOREIGN KEY (`id_employee_receive`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_technical_supports_ibfk_6` FOREIGN KEY (`id_employee_register`) REFERENCES `phppos_employees` (`person_id`),
  ADD CONSTRAINT `phppos_technical_supports_ibfk_7` FOREIGN KEY (`id_technical`) REFERENCES `phppos_employees` (`person_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
