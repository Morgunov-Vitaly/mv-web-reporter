<?php	
/*
	
	Менеджер отчета  102 By coffeeshops
	ID 102 
	
	
*/

/* !!!!!!!!!!   Подключаем стили  !!!!!!!!!! */	

add_action( 'wp_footer', 'enqueue_mv_stylecss_102' );
function enqueue_mv_stylecss_102() {
	/* Проверяем наличие шорткода  в посте */
	global $mv_report_params;	
	//PC::debug($mv_report_params['id']);
	if ($mv_report_params['id'] == '102') {
		wp_register_style( 'mvaccordioncss', plugins_url('css/accordion.core.css', __FILE__));
		//wp_register_style( 'mvaccordioncss', WP_PLUGIN_URL . '/mv-web-reporter/reports/102-by-coffeeshops/css/accordion.core.css');
		wp_enqueue_style( 'mvaccordioncss' );
	}
}
/* Подвешиваем к хуку функцию подключения стилей */	
//add_action( 'template_redirect', 'enqueue_mv_stylecss_102' );
/* / Подключаем стили !!!!!!!!!! */	


/* !!!!!!!!!!  Подключаем скрипты !!!!!!!!!! */	

function enqueue_mv_102_jquery() {
	/* Проверяем наличие шорткода в посте */
	global $mv_report_params;
	if ($mv_report_params['id'] == '102') {
		wp_register_script( 'mvaccordionjs', plugins_url('js/jquery.accordion.2.0.js', __FILE__), array( 'jquery' ), '1.0', true);
		wp_enqueue_script( 'mvaccordionjs' );
	}		
}	

/* Подвешиваем к хуку функцию подключения скриптов */	
add_action( 'wp_footer', 'enqueue_mv_102_jquery' );
/* / Подключаем скрипты */


/* !!!!!!! Подключаем файл конструктора 102 отчета в виде аккардеона !!!!!!!!!!!!!!!! */
require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv_102_constructor.php' );

/* !!!!!!! Подключаем файл шорткода [mv_report_accordion_code] конструктора вывода контейнера для отчета в виде аккардеона !!!!!!!!!!!!!!!! */
//require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv-accordion-constructor.php' );


/* !!!!!! Подключаем файл Конструктора 102 отчета и его вспомогательные функции !!!!!!!!!!!!!!!! */
require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv_102_handler.php' );

function mv_102_report() {
	global $post;
	$content = $post->post_content; /* Считываем контент страницы поста для проверки наличия шорткодов */
	ob_start();
	?>
	jQuery("#form_param").submit(function (event_pr) { /* отправка данных формы с параметрами для построения отчета */
		if (mv_document_ready > 0) {	
			
			mv_progress_circle_show();// Отображаем колесо загрузчик ожидание slideUp('normal')
			
			$.ajax({
				type: 'GET',
				url: '<?php echo admin_url( "admin-ajax.php" ); ?>', /* URL к которму подключаемся как альтернатива */
				data: {
					action: 'mv_take_report_data_102', /* Вызывам обработчик делающий запрос данных отчета  mv_take_report_data_102*/
					mv_nonce: '<?php echo wp_create_nonce( "mv_take_report_data_102" ); ?>',
					ref_organization: document.getElementById('form_param_ref_organization').value, /* по ID поля $('#form_param_ref_organization').val() window.form_param_ref_organization.value */
					cafe_ref: document.getElementById('form_param_cafe').value, //по ID поля $('#form_param_cafe').val() window.form_param_cafe.value
					dateFrom: document.getElementById('dateFrom').value + 'T00:00:00', /* по ID поля window.dateFrom.value.toISOString().replace(/\..*$/, '') window.dateFrom.value + 'T00:00:00', */
					dateTo: document.getElementById('dateTo').value + 'T23:59:59' /* по ID поля document.getElementById('form_param_ref_organization').value   window.dateTo.value + 'T23:59:59' */
				},
				success: function (result, status) {
					console.log("<?php _e( 'Статус запроса списка по токену: ', 'mv-web-reporter' ); ?>" + status); // Выводим сообщение об ошибках
					if (result != ""){
						
						//mv_report_result = result;
						//console.log('mv_report_result: ');
						//console.log(mv_report_result);
						mv_report_result = JSON.parse(result);
						
						if (mv_report_result.mv_data.mv_error_code == "200") { //Все получилось!
							$(".mv_reports_container").slideDown('normal');// показать .mv_reports_container - контейнер для вывода отчетов
							
							$("#mv_report_container").html(mv_report_result.mv_html); // обновляем аккордеон
							
							//Добавить условие, если этот блок с выводом параметров отчета вообще есть
							if ( document.getElementById("displayorgname") != undefined) {
								document.getElementById("displayorgname").innerHTML = document.getElementById("form_param_ref_organization").options[document.getElementById("form_param_ref_organization").options.selectedIndex].text;
								document.getElementById("displaydatefrom").innerHTML = document.getElementById("dateFrom").value;
								document.getElementById("displaydateto").innerHTML = document.getElementById("dateTo").value;
							}
							<?php
							/* Проверка на наличие шорткода [wpdatatable] || [wpdatachart]  в контенте */
							if(( has_shortcode( $content, 'wpdatatable' )) || ( has_shortcode( $content, 'wpdatachart' ))){
								?>
								wpDataTables.table_1.fnDraw(); // Обновляем wpDataTables но это нужно делать только если есть таблица!
								<?php
							};
							?>
							console.log("<?php _e( 'Статус запроса конструктора отчета: ', 'mv-web-reporter' ); ?>" + status); // Выводим сообщение об ошибках
							//console.log("jqXHR статус: " + jqxhr.status + " " + jqxhr.statusText);
							//console.log(jqxhr.getAllResponseHeaders());
							//console.log('mv_report_result.mv_error_code:');
							//console.log(mv_report_result.mv_error_code);
							mv_progress_circle_hide(); // скрываем колесо загрузчик ожидание slideUp('normal')
							
							} else {
							//alert("<?php _e( 'Ошибка конструктора отчета!: ', 'mv-web-reporter' ); ?>" + mv_report_result.mv_data.mv_error_code);
							$("#mv_report_container").html('<H3 style="text-align: center;">Ошибка конструктора: ' + mv_report_result.mv_data.mv_error_code + '</h3><p style="text-align: center;">message: ' + mv_report_result.mv_data.message + '</p>'); // Выводим сообщение об ошибке
							$(".mv_reports_container").slideDown('normal');// показать .mv_reports_container - контейнер для вывода отчетов
							console.log('mv_error_code: ' + mv_report_result.mv_data.mv_error_code);
							console.log('message: ' + mv_report_result.mv_data.message);
							console.log('report URL: ' + mv_report_result.mv_html);
							/* Здесь надо вывести окно с сообщением об ошибке или сделать редирект на соответсвующую страницу 401, 403 и т.д. */
							mv_progress_circle_hide(); // скрываем колесо загрузчик ожидание slideUp('normal')
						}
						}else{
						console.log("<?php _e( 'Удаленный сервер вернул пустую строку: ', 'mv-web-reporter' ); ?>" + result);
						mv_progress_circle_hide(); // скрываем колесо загрузчик ожидание slideUp('normal')
					}
					
				},
				error: function (result, status, jqxhr) { // срабатывает только в случае если не сработает AJAX запрос на WP
					alert("<?php _e( 'Упс! Возникла ошибка при обращении №2 к серверу WP! Ответ сервера: ', 'mv-web-reporter' ); ?>" + result);
					console.log("<?php _e( 'Статус: ', 'mv-web-reporter' ); ?>" + status); // Выводим сообщение об ошибках
					console.log("<?php _e( 'jqXHR статус: ', 'mv-web-reporter' ); ?>" + jqxhr.status + " " + jqxhr.statusText);
					console.log(jqxhr.getAllResponseHeaders());
					mv_progress_circle_hide(); // скрываем колесо загрузчик ожидание slideUp('normal')
				}
			});	
			
			mv_document_ready = mv_document_ready + 1; // счетчик для предотвращения повторного срабатывания функций
			event_pr.preventDefault();/* Отменяем стандартное действие кнопки Submit в форме */
		}
	});
<?php
	$html = ob_get_contents();
	ob_get_clean();
	
	return $html;
}
?>