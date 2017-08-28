/*
	
	Функция - JS обработчик отчета выполняет AJAX запрос PHP обработчику, 
	в report-handler-120.php в котором вызывается конструктор отчета в report-constructor-120.php
	
*/

function mv_120_report_do(){
	mv_progress_circle_show(); // Отображаем колесо загрузчик ожидание slideUp('normal')
	if (mv_document_ready > 0) {	
		jQuery.ajax({
			type: 'GET',
			url: mv_php_vars.mv_admin_url, /* URL к которму подключаемся как альтернатива */
			data: {
				action: 'mv_take_report_data_120', /* Вызывам обработчик делающий запрос данных отчета*/
				mv_nonce: mv_php_vars.mv_nonce,
				ref_organization: document.getElementById('form_param_ref_organization').value, /* по ID поля $('#form_param_ref_organization').val() */
				cafe_ref: document.getElementById('form_param_cafe').value, /* по ID поля $('#form_param_cafe').val() */
				dateFrom: document.getElementById('dateFrom').value + 'T00:00:00', /* Дата От */
				dateTo: document.getElementById('dateTo').value + 'T23:59:59' /* Дата По и плюсуем конец суток по часам */				
			},
			success: function (result, status) {
				console.log( mv_php_vars.mv_translate_status_token + ": " + status); // Выводим сообщение об ошибках
				if (result != ""){
					mv_report_result = JSON.parse(result);
					//console.log('mv_report_result: ' + mv_report_result);
					
					if (mv_report_result.mv_data.mv_error_code == "200") { /* Все получилось! */
						
						jQuery("#mv_report_container").html(mv_report_result.mv_html); /* обновляем форму отчета */
						
						if ((typeof wpDataTables !== 'undefined') &&(wpDataTables !== null) ) { wpDataTables.table_1.fnDraw(); } // Обновляем wpDataTables но это нужно делать только если есть таблица!
						
						/* Блок вывода шапки отчета */
						if ( document.getElementById("displayorgname") != undefined) {
							document.getElementById("displayorgname").innerHTML = document.getElementById("form_param_ref_organization").options[document.getElementById("form_param_ref_organization").options.selectedIndex].text;
							
							var mv_dateFrom = new Date(document.getElementById("dateFrom").value);
							var mv_dateTo = new Date(document.getElementById("dateTo").value);
							document.getElementById("displaydatefrom").innerHTML = mv_dateFrom.format("dd.mm.yyyy");
							document.getElementById("displaydateto").innerHTML = mv_dateTo.format("dd.mm.yyyy");
						}
						console.log( mv_php_vars.mv_translate_status_constr + ": " + status); // Выводим сообщение об ошибках 
						jQuery(".mv_reports_container").slideDown('normal'); /* показать .mv_reports_container - контейнер для вывода отчетов */
						mv_progress_circle_hide(); /* скрываем колесо загрузчик ожидание slideUp('normal') */						
						} else {
						
						/* Выводим окно с сообщением об ошибке или сделать редирект на соответсвующую страницу 401, 403 и т.д. */
						/* alert("Ошибка конструктора отчета!: " + mv_report_result.mv_data.mv_error_code); */
						if (mv_report_result.mv_data.mv_error_code == 401){
							jQuery("#mv_report_container").html('<H3 style="text-align: center;">' + mv_php_vars.mv_translate_error + ': ' + mv_report_result.mv_data.mv_error_code + '</h3><p style="text-align: center;">' + mv_php_vars.mv_translate_message + ': ' + mv_report_result.mv_data.message + '</p><p style="text-align: center;">' + mv_php_vars.mv_translate_time_inspired  + '- <a class="mv_login_modal_init" href="#">LogIn</a></p>'); // Выводим сообщение об ошибке 401 Token expired 
							} else {
							if (mv_report_result.mv_data.mv_error_code == 404){
								jQuery("#mv_report_container").html('<H3 style="text-align: center;">' + mv_php_vars.mv_translate_error + ': ' + mv_report_result.mv_data.mv_error_code + '</h3><p style="text-align: center;">' + mv_php_vars.mv_translate_not_found + '</p>'); // Выводим сообщение об ошибке 404 информация не найдена 
								} else { 
								jQuery("#mv_report_container").html('<H3 style="text-align: center;">' + mv_php_vars.mv_translate_constructor_error + ': ' + mv_report_result.mv_data.mv_error_code + '</h3><p style="text-align: center;">' + mv_php_vars.mv_translate_message + ': ' + mv_report_result.mv_data.message + '</p>'); // Выводим сообщение о других типах ошибки
							}
						}
						/* /Выводим окно с сообщением об ошибке или сделать редирект на соответсвующую страницу 401, 403 и т.д. */						
						
						jQuery(".mv_reports_container").slideDown('normal');// показать .mv_reports_container - контейнер для вывода отчетов
						console.log('mv_error_code: ' + mv_report_result.mv_data.mv_error_code);
						console.log('message: ' + mv_report_result.mv_data.message);
						console.log('report URL: ' + mv_report_result.mv_html);
						mv_progress_circle_hide(); /* скрываем колесо загрузчик ожидание slideUp('normal') */
					}
					}else{
					console.log(mv_php_vars.mv_translate_empty_str + ": " + result);
					mv_progress_circle_hide(); /* скрываем колесо загрузчик ожидание slideUp('normal') */
				}
				
				
			},
			error: function (result, status, jqxhr) { // срабатывает только в случае если не сработает AJAX запрос на WP
				
				//jQuery("#mv_report_progress_circle").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
				//alert("Упс! Возникла ошибка при обращении №2 к серверу WP! Ответ сервера: " + result);
				console.log(mv_php_vars.mv_translate_status + ": " + status); // Выводим сообщение об ошибках
				console.log(mv_php_vars.mv_translate_status_jqXHR + ": " + jqxhr.status + " " + jqxhr.statusText);
				console.log(mv_php_vars.jqxhr.getAllResponseHeaders());
				jQuery("#mv_report_progress_circle").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
			}
		});
		
		
		mv_document_ready = mv_document_ready + 1; // счетчик для предотвращения повторного срабатывания функций
	}		
}