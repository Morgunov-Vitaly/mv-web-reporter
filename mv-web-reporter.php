<?php

/*
	Plugin Name: MV-WEB-Reporter
	Plugin URI: http://cscl-reporter.com
	Description: Плагин для добавления отчетов с использованием библиотеки Select2 & AJAX-запросов в WordPress
	Author: Моргунов Виталий
	Author URI: https://vk.com/v.morgunov
	Version: 20170424
*/

/* Локализация плагина */
add_action( 'plugins_loaded', 'mv_load_plugin_textdomain' );

function mv_load_plugin_textdomain() {
	load_plugin_textdomain( 'mv-web-reporter', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
/* /Локализация плагина */

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
add_filter( 'wpdatatables_filter_table_description', 'wpdt_mv_hook', 10, 2 );

function wpdt_mv_hook( $object, $table_id ) {

	$object->dataTableParams->aLengthMenu = array(
		array(
			2,
			3,
			10,
			25,
			- 1
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


/* Функция для автоматической подстановки значения текущего ТОКЕНА в Шорткод таблицы WpDataTables */
/*
	Данная версия работает только при обновлении страницы при имеющемся токене.
	Ничего не сработает если токена не было на момент загрузки страницы,
	или если токен поменяли сменив пользователя
 */

add_action( 'template_redirect', 'mv_receive_token_param');
function mv_receive_token_param(){
	global $post;
	if( has_shortcode( $post->post_content, 'wpdatatable' )) {
			// Если в контенте есть [ wpdatatable ... ]  главное, чтобы значение не поменялось в БД, иначе сработает только один раз
			$post->post_content = str_replace('[wpdatatable id=1 table_view=regular var1="YTY0OTYxY2UtYTgwNS00N2M3LTg1YzctZjMyNTU3YTUyMTFj"]', '[wpdatatable id=1 table_view=regular var1="'. (isset($_COOKIE['mv_cuc_token']) ? $_COOKIE['mv_cuc_token']: "") .'"]', $post->post_content);
	}
}
/* /Функция для автоматической подстановки значения текущего ТОКЕНА в Шорткод таблицы WpDataTables */