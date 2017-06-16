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

/* !!!!!!! Подключаем файл шорткода [mv_report_accordion_code] конструктора вывода контейнера для отчета в виде аккардеона !!!!!!!!!!!!!!!! */
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

/**/
add_shortcode('mv_progress_circle', 'mv_progress_circle_constructor'); // добавляем шорткод для вставки колеса загрузки progress circle
function mv_progress_circle_constructor($atts){
	$params = shortcode_atts( array( // в массиве укажите значения параметров по умолчанию
		'id' => 'mv_login_loader', // ID по умолчанию, если его н указывать
	), $atts );
	ob_start();
	?>
	<div id="<?php echo $params['id'] ?>" class="mv_loader" title="0">
		<svg version="1.1" class="mv_svg_loader" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 50 50" enable-background="new 0 0 50 50" xml:space="preserve">
<path class="mv_svg-path" opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169zM20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/><path class="mv_svg-path" fill="#000" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0C22.32,8.481,24.301,9.057,26.013,10.047z">
				<animateTransform attributeType="xml"  attributeName="transform" type="rotate" from="0 20 20" to="360 20 20" dur="0.5s" repeatCount="indefinite"/>
			</path>
</svg>
	</div><?php
	$html = ob_get_contents();
	ob_get_clean();
	return $html;
}