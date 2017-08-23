<?php	
/*
	
	Менеджер отчета Revenue Выручка
	ID 130 
		
*/

/* !!!!!!!!!!   Подключаем стили  !!!!!!!!!! */	

add_action( 'wp_footer', 'enqueue_mv_stylecss_130' );
/* Подвешиваем к хуку функцию подключения стилей */	

function enqueue_mv_stylecss_130() {
	/* Проверяем наличие шорткода  в посте */
	global $mv_report_params;	
	//PC::debug($mv_report_params['id']);
	if ($mv_report_params['id'] == '130') {
		wp_register_style( 'mv_stylecss_130', plugins_url('css/report-130.css', __FILE__));
		wp_enqueue_style( 'mv_stylecss_130' );
	}
}
/* / Подключаем стили !!!!!!!!!! */	

/* !!!!!!!!!!  Подключаем скрипты !!!!!!!!!! */	

function enqueue_mv_130_jquery() {
	/* Проверяем наличие шорткода в посте */
	global $mv_report_params;
	if ($mv_report_params['id'] == '130') {
		
		wp_register_script( 'mv-130-script-js', plugins_url('js/mv-130-script.js', __FILE__), array( 'jquery' ), '1.0', true);
		wp_enqueue_script( 'mv-130-script-js' ); /* регистрирую скрипт обработчика */
		
		$mv_dataToBePassed = array(
		'mv_admin_url' => admin_url( "admin-ajax.php" ), /* Путь до admin-ajax.php */
		'mv_nonce' => wp_create_nonce( "mv_take_report_data_130" ), /* проверочное значение nonce */
		'mv_translate_status' => __( 'Статус', 'mv-web-reporter' ),
		'mv_translate_status_token' => __( 'Статус запроса списка по токену', 'mv-web-reporter' ),
		'mv_translate_status_constr' => __( 'Статус запроса конструктора отчета', 'mv-web-reporter' ),			
		'mv_translate_error' => __( 'Ошибка', 'mv-web-reporter' ), /* <?php _e( 'Ошибка', 'mv-web-reporter' ); ?> */
		'mv_translate_message' => __( 'Сообщение', 'mv-web-reporter' ), /* <?php _e( 'Сообщение', 'mv-web-reporter' ); ?> */
		'mv_translate_time_inspired' => __( 'Время вашей сессии истекло. Пожалуйста, авторизуйтесь повторно', 'mv-web-reporter' ), /* <?php _e( 'Время вашей сессии истекло. Пожалуйста, авторизуйтесь повторно', 'mv-web-reporter' ); ?> */
		'mv_translate_not_found' => __( 'По заданным параметрам данных не найдено', 'mv-web-reporter' ), /* <?php _e( 'По указанной карте данных не найдено', 'mv-web-reporter' ); ?> */
		'mv_translate_constructor_error' => __( 'Ошибка конструктора отчета', 'mv-web-reporter' ), /* <?php _e( 'Ошибка конструктора отчета', 'mv-web-reporter' ); ?> */
		'mv_translate_empty_str' => __( 'Удаленный сервер вернул пустую строку', 'mv-web-reporter' ), /* <?php _e( 'Удаленный сервер вернул пустую строку', 'mv-web-reporter' ); ?> */
		'mv_translate_status_jqXHR' => __( 'jqXHR статус', 'mv-web-reporter' ) /* <?php _e( 'jqXHR статус', 'mv-web-reporter' ); ?> */
		
		/* в JS доступны в виде: mv_php_vars.mv_nonce и т.д. */
		);
		wp_localize_script( 'mv-130-script-js', 'mv_php_vars', $mv_dataToBePassed );
		
	}	
}		


/* Подвешиваем к хуку функцию подключения скриптов */	
add_action( 'wp_footer', 'enqueue_mv_130_jquery' );
/* / Подключаем скрипты */



/* !!!!!!! Подключаем AJAX обработчик отчета 130 JS !!!!!!!! */
require_once( plugin_dir_path( __FILE__ ) . 'handlers/report-constructor-130.php' );
require_once( plugin_dir_path( __FILE__ ) . 'handlers/report-handler-130.php' );


function mv_130_report(){
	ob_start();
	?>
	/* jQuery(".mv_reports_container").slideDown('normal'); */ /* показать .mv_reports_container - контейнер для вывода отчетов */
	/* jQuery("#form_param_container_inputs").slideDown('normal'); */ /* показать .mv_reports_container - контейнер для вывода отчетов */	

	/* Обработчик на SUBMIT  формы предварительных параметров */
	
	jQuery("#form_param").submit(function (event_pr) { /* отправка данных формы с параметрами для построения отчета */
	mv_130_report_do();
		event_pr.preventDefault();/* Отменяем стандартное действие кнопки Submit в форме */
	});
	<?php
	$html = ob_get_contents();
	ob_get_clean();
	
	return $html;
}
?>