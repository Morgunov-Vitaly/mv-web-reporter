	function mv_119_report_do(mv_url_param){
		if (mv_document_ready > 0) {	
			mv_progress_circle_show(); // Отображаем колесо загрузчик ожидание slideUp('normal')
			jQuery.ajax({
				type: 'GET',
				url: '<?php echo admin_url( "admin-ajax.php" ); ?>', /* URL к которму подключаемся как альтернатива */
				data: {
					action: 'mv_take_report_data_119', /* Вызывам обработчик делающий запрос данных отчета*/
					mv_nonce: '<?php echo wp_create_nonce( "mv_take_report_data_119" ); ?>',
					mv_cscl_card_num: mv_url_param // значение параметра поиска по номеру бонусной карты
				},
				success: function (result, status) {
					console.log("<?php _e( 'Статус запроса списка по токену', 'mv-web-reporter' ); ?>: " + status); // Выводим сообщение об ошибках
					if (result != ""){
						mv_report_result = JSON.parse(result);
						console.log('mv_report_result: ' + mv_report_result);
						if (mv_report_result.mv_data.mv_error_code == "200") { /* Все получилось! */
							$("#mv_report_container").html(mv_report_result.mv_html); /* обновляем форму отчета */
							mv_progress_circle_hide(); /* скрываем колесо загрузчик ожидание slideUp('normal') */
							/* Блок вывода шапки отчета */
							if ( document.getElementById("displayorgname") != undefined) {
								document.getElementById("displayorgname").innerHTML = document.getElementById("form_param_ref_organization").options[document.getElementById("form_param_ref_organization").options.selectedIndex].text;
								document.getElementById("displaydatefrom").innerHTML = document.getElementById("dateFrom").value;
								document.getElementById("displaydateto").innerHTML = document.getElementById("dateTo").value;
							}
							
							console.log("<?php _e( 'Статус запроса конструктора отчета', 'mv-web-reporter' ); ?>: " + status); // Выводим сообщение об ошибках
							
							} else {
							
							/* Здесь надо вывести окно с сообщением об ошибке или сделать редирект на соответсвующую страницу 401, 403 и т.д. */
							/* alert("<?php _e( 'Ошибка конструктора отчета!', 'mv-web-reporter' ); ?>: " + mv_report_result.mv_data.mv_error_code); */
							if (mv_report_result.mv_data.mv_error_code == 401){
								$("#mv_report_container").html('<H3 style="text-align: center;"><?php _e( 'Ошибка', 'mv-web-reporter' ); ?>: ' + mv_report_result.mv_data.mv_error_code + '</h3><p style="text-align: center;"><?php _e( 'Сообщение', 'mv-web-reporter' ); ?>: ' + mv_report_result.mv_data.message + '</p><p style="text-align: center;"><?php _e( 'Время вашей сессии истекло. Пожалуйста, авторизуйтесь повторно', 'mv-web-reporter' ); ?> - <a class="mv_login_modal_init" href="#">LogIn</a></p>'); // Выводим сообщение об ошибке 401 Token expired 
								} else {
								if (mv_report_result.mv_data.mv_error_code == 404){
									$("#mv_report_container").html('<H3 style="text-align: center;"><?php _e( 'Ошибка', 'mv-web-reporter' ); ?>: ' + mv_report_result.mv_data.mv_error_code + '</h3><p style="text-align: center;"><?php _e( 'По указанной карте данных не найдено', 'mv-web-reporter' ); ?></p>'); // Выводим сообщение об ошибке 404 информация не найдена 
									} else { 
									$("#mv_report_container").html('<H3 style="text-align: center;"><?php _e( 'Ошибка конструктора отчета', 'mv-web-reporter' ); ?>: ' + mv_report_result.mv_data.mv_error_code + '</h3><p style="text-align: center;"><?php _e( 'Сообщение', 'mv-web-reporter' ); ?>: ' + mv_report_result.mv_data.message + '</p>'); // Выводим сообщение о других типах ошибки
								}
							}
							$(".mv_reports_container").slideDown('normal');// показать .mv_reports_container - контейнер для вывода отчетов
							console.log('mv_error_code: ' + mv_report_result.mv_data.mv_error_code);
							console.log('message: ' + mv_report_result.mv_data.message);
							console.log('report URL: ' + mv_report_result.mv_html);
							mv_progress_circle_hide(); /* скрываем колесо загрузчик ожидание slideUp('normal') */
						}
						}else{
						console.log("<?php _e( 'Удаленный сервер вернул пустую строку', 'mv-web-reporter' ); ?>: " + result);
						mv_progress_circle_hide(); /* скрываем колесо загрузчик ожидание slideUp('normal') */
					}
					
					
				},
				error: function (result, status, jqxhr) { // срабатывает только в случае если не сработает AJAX запрос на WP
					
					//$("#mv_report_progress_circle").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
					//alert("<?php _e( 'Упс! Возникла ошибка при обращении №2 к серверу WP! Ответ сервера', 'mv-web-reporter' ); ?>: " + result);
					console.log("<?php _e( 'Статус', 'mv-web-reporter' ); ?>: " + status); // Выводим сообщение об ошибках
					console.log("<?php _e( 'jqXHR статус', 'mv-web-reporter' ); ?>: " + jqxhr.status + " " + jqxhr.statusText);
					console.log(jqxhr.getAllResponseHeaders());
					$("#mv_report_progress_circle").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
				}
			});
			
			
			mv_document_ready = mv_document_ready + 1; // счетчик для предотвращения повторного срабатывания функций
		}		
	}