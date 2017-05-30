<?php
	
	/*
		Plugin Name: MV-WEB-Reporter
		Plugin URI: http://cscl-reporter.com
		Description: Плагин для добавления отчетов с использованием библиотеки Select2 & AJAX-запросов в WordPress
		Author: Моргунов Виталий
		Author URI: https://vk.com/v.morgunov
		Version: 20170424
	*/
	
	
	
	/* !!!!!!! Подключаем стили и скрипты для всех конструкторов  !!!!!!!!!!!!!!!! */		
	require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv-style-and-js-switcher.php' );	
	
	
	/* !!!!!! Подключаем файл шорткода [mv_report_accordion_code]
	конструктора Login обработчика с кнопкой  и передаем системные переменные !!!!!!!!!!!!!!!! */		
	require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv-login-constructor.php' );
	
	/* !!!!!!! Подключаем шорткод конструктора формы ввода предварительных параметров отчетов  !!! */		
	require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv-form-param-constructor.php' );			
	
	/* !!!!!!! Подключаем файл шорткода [mv_report_accordion_code] конструктора вывода отчета в виде аккардеона !!!!!!!!!!!!!!!! */		
	require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv-accordion-constructor.php' );
	
	/* !!!!!!! Подключаем файл конструктора 102 отчета в виде аккардеона !!!!!!!!!!!!!!!! */		
	require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv_102_accordion.php' );	
	
	
	/* !!!!!! Подключаем файл Конструктора 102 отчета и его вспомогательные функции !!!!!!!!!!!!!!!! */		
	require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv_102_report_constructor.php' );
	
	/* поменяем настройки плагина WpDataTables изменим меню отображения количества строк таблицы */
	add_filter('wpdatatables_filter_table_description', 'wpdt_mv_hook', 10, 2 );
	
	function wpdt_mv_hook( $object, $table_id ) {
		
		$object->dataTableParams->aLengthMenu = array(
		array(
		2,
		3,
		10,
		25,
		-1
		),
		array(
		2,
		3,
		10,
		25,
		"All"
		)
		);
		return $object;
		
	}
/* / поменяем настройки плагина WpDataTables изменим меню отображения количества строк таблицы */