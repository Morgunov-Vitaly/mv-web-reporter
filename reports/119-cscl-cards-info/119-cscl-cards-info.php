<?php	
/*
	
	Менеджер отчета CSCL CardInfo
	ID 119 
	
	
*/

/* !!!!!!!!!!   Подключаем стили  !!!!!!!!!! */	

add_action( 'wp_footer', 'enqueue_mv_stylecss_119' );
/* Подвешиваем к хуку функцию подключения стилей */	

function enqueue_mv_stylecss_119() {
	/* Проверяем наличие шорткода  в посте */
	global $mv_report_params;	
	//PC::debug($mv_report_params['id']);
	if ($mv_report_params['id'] == '119') {
		wp_register_style( 'mv_stylecss_119', plugins_url('css/report-119.css', __FILE__));
		wp_enqueue_style( 'mv_stylecss_119' );
		
		wp_register_style( 'mvaccordioncss', plugins_url('css/accordion.core.css', __FILE__));
		wp_enqueue_style( 'mvaccordioncss' );
	}
}
/* / Подключаем стили !!!!!!!!!! */	

/* !!!!!!!!!!  Подключаем скрипты !!!!!!!!!! */	

function enqueue_mv_119_jquery() {
	/* Проверяем наличие шорткода в посте */
	global $mv_report_params;
	global $mv_url_param;
	if ($mv_report_params['id'] == '119') {
		wp_register_script( 'mvaccordionjs', plugins_url('js/jquery.accordion.2.0.js', __FILE__), array( 'jquery' ), '1.0', true);
		wp_enqueue_script( 'mvaccordionjs' );
		
		
		wp_register_script( 'mv-119-script-js', plugins_url('js/mv-119-script.js', __FILE__), array( 'mvaccordionjs' ));
		wp_enqueue_script( 'mv-119-script-js' ); /* регистрирую скрипт обработчика */
		
		$mv_dataToBePassed = array(
		'mv_admin_url' => admin_url( "admin-ajax.php" ), /* Путь до admin-ajax.php */
		'mv_nonce' => wp_create_nonce( "mv_take_report_data_119" ), /* проверочное значение nonce */
		'mv_url_param' => $mv_url_param, /* передаю ему параметры - переменные из URL */
		'mv_translate_status' => __( 'Статус', 'mv-web-reporter' ),
		'mv_translate_status_token' => __( 'Статус запроса списка по токену', 'mv-web-reporter' ),
		'mv_translate_status_constr' => __( 'Статус запроса конструктора отчета', 'mv-web-reporter' ),			
		'mv_translate_error' => __( 'Ошибка', 'mv-web-reporter' ), /* <?php _e( 'Ошибка', 'mv-web-reporter' ); ?> */
		'mv_translate_message' => __( 'Сообщение', 'mv-web-reporter' ), /* <?php _e( 'Сообщение', 'mv-web-reporter' ); ?> */
		'mv_translate_time_inspired' => __( 'Время вашей сессии истекло. Пожалуйста, авторизуйтесь повторно', 'mv-web-reporter' ), /* <?php _e( 'Время вашей сессии истекло. Пожалуйста, авторизуйтесь повторно', 'mv-web-reporter' ); ?> */
		'mv_translate_not_found' => __( 'По указанной карте данных не найдено', 'mv-web-reporter' ), /* <?php _e( 'По указанной карте данных не найдено', 'mv-web-reporter' ); ?> */
		'mv_translate_constructor_error' => __( 'Ошибка конструктора отчета', 'mv-web-reporter' ), /* <?php _e( 'Ошибка конструктора отчета', 'mv-web-reporter' ); ?> */
		'mv_translate_empty_str' => __( 'Удаленный сервер вернул пустую строку', 'mv-web-reporter' ), /* <?php _e( 'Удаленный сервер вернул пустую строку', 'mv-web-reporter' ); ?> */
		'mv_translate_status_jqXHR' => __( 'jqXHR статус', 'mv-web-reporter' ) /* <?php _e( 'jqXHR статус', 'mv-web-reporter' ); ?> */
		
		/* в JS доступны в виде: mv_php_vars.mv_nonce и т.д. */
		);
		wp_localize_script( 'mv-119-script-js', 'mv_php_vars', $mv_dataToBePassed );
		
	}	
}		


/* Подвешиваем к хуку функцию подключения скриптов */	
add_action( 'wp_footer', 'enqueue_mv_119_jquery' );
/* / Подключаем скрипты */



/* !!!!!!! Подключаем AJAX обработчик отчета 119 JS !!!!!!!! */
require_once( plugin_dir_path( __FILE__ ) . 'handlers/report-constructor-119.php' );
require_once( plugin_dir_path( __FILE__ ) . 'handlers/report-handler-119.php' );


function mv_119_report(){
	global $post;
	$content = $post->post_content; /* Считываем контент страницы поста и смотрим есть ли шорткод [mv_closed] или [mv_reports] */
	ob_start();
	?>
	jQuery(".mv_reports_container").slideDown('normal'); /* показать .mv_reports_container - контейнер для вывода отчетов */
	jQuery("#form_param_container_inputs").slideDown('normal'); /* показать .mv_reports_container - контейнер для вывода отчетов */	
	jQuery("#form_param").submit(function (event_pr) { /* отправка данных формы с параметрами для построения отчета */
		mv_url_param = document.getElementById('mv_cscl_csrd_number').value; // значение параметра поиска по номеру бонусной карты
		mv_119_report_do(mv_url_param);
		event_pr.preventDefault();/* Отменяем стандартное действие кнопки Submit в форме */
	});
	
	<?php
	$html = ob_get_contents();
	ob_get_clean();
	
	return $html;
}
?>