<?php
	/* 	
		
		Загрузчик переменных из php во front-end
		Менеджер процессов плагина 
		Обработчик процесса авторизации LogIn/Out, состояния токена, и др.
		
	*/
	
	
	
	/* 
		Добавляю шорткод [mv_closed] 
		для указания на то, что запись 
		закрыта для доступа без токена CSCL-reportera 
	*/
	
	add_shortcode( 'mv_closed', "mv_closed" );
	function mv_closed() {
		// Тут позже можно будет чего-нибудь вставить а пока нечего 
	}
	/*  / Добавляю шорткод [mv_closed] для указания на то, что запись закрыта для доступа без токена CSCL-reportera */
	
	
	
	/* 
		Добавляю шорткод [mv_dashboard] 
		для указания на то, что это страница дашборда 
	*/
	
	add_shortcode( 'mv_dashboard', "mv_dashboard" );
	function mv_dashboard() {
		// Тут позже можно будет чего-нибудь вставить а пока нечего
	}
	/* /Добавляю шорткод [mv_dashboard] для указания на то, что это страница дашборда */
	
	
	
	/* 
		Обработчик закрытия страницы 
		от доступа для неаторизованых 
		и бесправных - без токена CSCL-reportera  
	*/
	
	add_action( 'template_redirect', 'mv_close_content' );
	function mv_close_content() {
		global $post;
		$content = $post->post_content; /* Считываем контент страницы поста и смотрим есть ли шорткод [mv_closed] или [mv_reports] */
		if ( has_shortcode( $content, 'mv_closed' ) || has_shortcode( $content, 'mv_reports' ) ) {
			// Редирект со статусом 401 Неавторизованный запрос, если нет токена или логина $_COOKIE['mv_cuc_user']
			if (( ! isset( $_COOKIE['mv_cuc_token'] ) ) || (! isset($_COOKIE['mv_cuc_user']))) {
				// wp_redirect( home_url() ); // Пока без статуса ошибки
				if (pll_current_language() == 'ru') {
					wp_redirect( home_url() . '/ru/401-unauthorized-ru' ); // Редирект на русскую версию
				}
				if (pll_current_language() == 'en') {
					wp_redirect( home_url() . '/en/401-unauthorized' ); // Редирект на english версию
				}
				exit;
			}
			// Далее добавить код для проверки уровня доступа к информации
		}
	}
	/* / Обработчик закрытия страницы от доступа без токена CSCL-reportera  */
	
	
	
	/* 
		!!!!!!!!!!!!!!! 
		Блок Передачи нужных 
		переменных на фронтэнед 
		!!!!!!!!!!!!!!!! 
	*/
	
	function mv_js_variables_login() {
		// Вопрос нужно ли все это????
		$mvuser = get_userdata( get_current_user_id() ); // Логин текущего пользователя
		if ( isset( $mvuser ) && $mvuser ) {
			//Список переменных
			$mvvariables = [
			'mv_ajax_url' => admin_url( 'admin-ajax.php' ),
			// передаем значение пути
			'mv_current_user_login' => $mvuser->user_login,
            // можно проверить window.mv_wp_data.mv_current_user_login
            'mv_homepage' => home_url(),// Передаем адрес главной страницы
			'mv_login_page' => wp_login_url() //Ссылка на страницу авторизации
			];
			//PC::debug($mvuser );
			} else {
			$mvvariables = [
			'mv_ajax_url' => admin_url( 'admin-ajax.php' ),
			// передаем значение пути
			'mv_current_user_login' => __( 'Пользователь не определен', 'mv-web-reporter' ),
			// можно проверить window.mv_wp_data.mv_current_user_login   было $mvuser->data->user_login
			'mv_homepage' => home_url(),// Передаем адрес главной страницы
			'mv_login_page' => wp_login_url() //Ссылка на страницу авторизации			
			];
		}
		
		echo '<script type="text/javascript"> window.mv_wp_data =' . json_encode( $mvvariables ) . ';';
		echo ' mv_document_ready = 0; </script>';
		//	}
	}
	
	/* Привязываемся к хуку вывода шапки сайта  */
	add_action( 'wp_head', 'mv_js_variables_login' );
	
	/* / Блок Передачи нужных переменных на фронтэнед  */
	
	
	
	/* 
		!!!!!!!!!!!!!!!  
		Обработчик формы LogIn-LogOut 
		(пишем в футер)
		!!!!!!!!!!!!!!!!! 
	*/
	
	function mv_login_handler() {
		global $post;
		$content = $post->post_content; /* Считываем контент страницы поста */
		global $mv_login_popup;
	?>
    <script type="text/javascript">
		
		
		/* 
			Функция для чтения куки 
		*/
		
		function mv_getCookie(name) {
			var matches = document.cookie.match(new RegExp(
			"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
			));
			return matches ? decodeURIComponent(matches[1]) : undefined;
		}
		/* /Функция для чтения куки */
		
		
		
		/* 
			Функция для удаления куки
		*/
		
		function delete_cookie(cookie_name)
		{
			var cookie_date = new Date();  // Текущая дата и время
			cookie_date.setTime(cookie_date.getTime() - 1);
			document.cookie = cookie_name + "=; path=/; expires=" + cookie_date.toGMTString();
		}
		/* /Функция для удаления куки */
		
		
		
		/* 
			Функция включает 
			отображение всего с классом .mv_token_show, 
			скрывает все с классом .mv_notoken_show
			если ТОКЕН ЕСТЬ
		*/
		function mv_show_hide_token_on() {
			jQuery(document).ready(function ($) {
				
				$(".mv_token_show").slideDown('normal'); /* показать .mv_token_show - все объекты, которые должны отображаться только при наличии токена */
				$(".mv_notoken_show").slideUp('normal'); /* скрыть .mv_notoken_show - все объекты, которые должны отображаться когда нет токена */
				
			})
		}
		/*  / Функция  отображения /скрытия при  наличии токена  */
		
		
		
		/* 
			Функция включает 
			отображение всего с классом .mv_notoken_show, 
			скрывает все с классом .mv_token_show
			если ТОКЕНА НЕТ
		*/
		
		function mv_show_hide_token_off() {
			jQuery(document).ready(function($) {
				
				$(".mv_token_show").slideUp('normal'); /* показать .mv_token_show - все объекты, которые должны отображаться только при наличии токена */
				$(".mv_notoken_show").slideDown('normal'); /* скрыть .mv_notoken_show - все объекты, которые должны отображаться когда нет токена */
				
			})
		}
		/*  / Функция  отображения /скрытия при ОТСУТСТВИИ ТОКЕНА  */
		
		/* Функция для ПОКАЗА индикатора загрузки */
		function mv_progress_circle_show(){
			jQuery("#mv_report_progress_circle").css("display", "block"); /* показываем колесо загрузчик ожидание slideUp('normal') */
			}
		
		/* /Функция для ПОКАЗА отображения индикатора загрузки */

		/* Функция для СКРЫТИЯ индикатора загрузки */
		function mv_progress_circle_hide(){
			jQuery("#mv_report_progress_circle").css("display", "none"); /* скрываем колесо загрузчик ожидание slideUp('normal') */
			}
		
		/* /Функция для СКРЫТИЯ индикатора загрузки */

		
		<?php
			
			/* Проверка на наличие токена и отключаем то что должно быть скрыто при наличии токена */
			if ( isset( $_COOKIE['mv_cuc_token'] ) ) {
			?>
			mv_show_hide_token_on();
			<?php
			}
		?>
		
        jQuery(function ($) {
			
            /* 
				!!!!!!!!!! 
				Обрабатывем нажатие на кнопку LogOut
				формы авторизации
				!!!!!!!!!! 
			*/
            $("#mv_logout_btn").click(function(event_lo) {
                delete_cookie("mv_cuc_token"); // удаление куки с токеном
                delete_cookie("mv_cuc_user"); // удаление куки с логином
				$(".mv_login_code").html('<i class="fa fa-lock"></i> LogIn'); // меняем надпись Ссылки LogIn на логин пользователя
                $("#mv_login_error").slideUp('normal'); // скрываем сообщение об ошибке   !его желательно скрыть и при изменении одного из полей формы
				<?php
					if( has_shortcode( $content, 'mv_param_form' )){ /* Для страниц отчетов */
					?>
					
					$("#form_param_user").attr("value", "");// очистить переменную пользователя и пароля
					$("#form_param_pass").attr("value", "");// очистить переменную пользователя и пароля
					
					$("#form_param_container_inputs").slideUp('normal');// спрятать форму form_param_container_inputs
					$(".mv_reports_container").slideUp('normal');// скрыть .mv_reports_container - контейнер для вывода отчетов
					
					<?php
					} /* для закрытых страниц */
				?>
			    mv_show_hide_token_off (); //скрыть .mv_token_show  показать .mv_notoken_show
                event_lo.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
				<?php
					
					if ( has_shortcode( $content, 'mv_closed' ) ) { /* Для закрытых страниц */
					?>
					document.location.href = 'http://cscl-reporter.com'; //Редирект на гл. страницу  ру ен? http://cscl-reporter.com/ru/otchety
					<?php
					}/* /Для закрытых страниц отчетов и закупок */
				?>
			});
            /* /  Обрабатывем нажатие на кнопку LogOut  */
			
			
			
			/* 
				!!!!!!!!!! 
				Обработчик нажатия на кнопку LogIn 
				формы авторизации 
				Запрос Токена и списков организаций и кофеен 
				по логину и паролю
				!!!!!!!!!! 
			*/
			
            $("#form_login").submit(function(event_li) { /* Отправляем данные формы  */
                $("#mv_login_loader").slideDown('normal'); // отображаем колесо загрузчик ожидание slideUp('normal')
                $.ajax({
                    type: 'GET',
                    url: '<?php echo admin_url( "admin-ajax.php" ); ?>', /* URL к которму подключаемся */
                    data: {
                        action: 'mv_ask_token', /* Вызывам обработчик  mv_ask_token */
                        mv_nonce: '<?php echo wp_create_nonce( "mv_ask_token" ); ?>',
                        mv_login: $('#form_param_user').val(),
                        mv_password: $('#form_param_pass').val()
					},
                    success: function(result, status) {
                        console.log("<?php _e( 'Статус AJAX запроса токена и списка оганизаций и кофеен по логину и паролю: ', 'mv-web-reporter' ); ?>" + status); // Выводим сообщение об ошибках
                        if (result != ""){
						mv_result = JSON.parse(result); // $.parseJSON(result); //функция JQuery
						if ((typeof mv_result.mv_error_code == "undefined") && (mv_result.token != '') && (mv_result.token != "undefined")) { // все получилось!
                            $("#pum-<?php echo $mv_login_popup ?>").popmake('close'); // закрываем диалоговое окно LogIn!
                            $("#mv_login_error").slideUp('normal'); // скрываем сообщение об ошибке
							
                            /* Для страниц отчетов */
                            <?php
								if( has_shortcode( $content, 'mv_param_form' )){
								?>
								
								mv_form_construct(mv_result.organizations); //конструктор, который заносит список организаций в mv_results_data

								<?php
								} 
								/* /Для страниц отчетов */
							?>
							mv_show_hide_token_on();// показать .mv_token_show скрыть .mv_notoken_show
							
                            $(".mv_login_code").html('<i class=\'fa fa-unlock-alt\'></i> ' + mv_result.login); // /.mv-login меняем надпись Ссылки LogIn на логин пользователя
						}
						
                        else {
							
                            $("#mv_login_error").slideDown('normal'); //выводим сообщение об ошибке в форме
                            console.log('<?php _e( 'Код ошибки запроса токена: ', 'mv-web-reporter' ); ?>' + mv_result.mv_error_code + '<?php _e( 'Сообщение: ', 'mv-web-reporter' ); ?>' + mv_result.message);
						}
						
					} else {
					console.log("<?php _e( 'Удаленный сервер вернул пустую строку: ', 'mv-web-reporter' ); ?>" + result);
					}
					$("#mv_login_loader").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
					},
                    error: function (result, status, jqxhr) { /* срабатывает только в случае если не сработает AJAX запрос на WP */
                        $("#mv_login_loader").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
						alert("<?php _e( 'Возникла ошибка при запросе токена к серверу WP! Ответ сервера: ', 'mv-web-reporter' ); ?>" + result);
						console.log("<?php _e( 'Статус: ', 'mv-web-reporter' ); ?>" + status);
						console.log("<?php _e( 'jqXHR статус: ', 'mv-web-reporter' ); ?>" + jqxhr.status + " " + jqxhr.statusText);
						console.log(jqxhr.getAllResponseHeaders());
					}
				});
                event_li.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
	            <?php
					if ( has_shortcode( $content, 'mv_closed' ) ) { /* Для закрытых страниц [mv_param_form] */
					?>
					document.location.href = 'http://cscl-reporter.com'; //Редирект на главную страницу ру енг? http://cscl-reporter.com/ru/otchety
					<?php
					} 
					/* / Для закрытых страниц */
				?>
			});
            /* / Обрабатывем нажатие на кнопку LogIn  */
			
			
            /* 
				Включаем обработчик обновления 
				селекта списка кофеен 
				при изменении селекта со списком организаций 
			*/
			
            $("#form_param_ref_organization").change(function () {
                onchangeCoffeeSelect(mv_result.organizations); /* конструктор второго списка кофеен для выбранной организации */
			});

            /* 
				Включаем обработчик построения
				отчета при изменении выбранной кофейни
				при изменении селекта со списком организаций 
			*/
			
             $("#form_param_cafe").change(function () {
				 if ((document.getElementById('dateFrom').value != "") && (document.getElementById('form_param_ref_organization').value != "0") && (document.getElementById('form_param_cafe').value != "" )){ // должна быть выбрана организация, кофейня и дата
				 $("#form_param").submit(); //Отправляем данные формы "Субмитим"
				 mv_document_ready = mv_document_ready + 1;
				 }; 
				
			 }); 
			
			
		});
	</script>
	<?php
	}
	
	/* добавляем обработчик jQuery в футер страницы на фронтэнде */
	add_action( 'wp_footer', 'mv_login_handler' );
	
	
	/* 
		***************
		PHP обработчики 
		*************** 
	*/
	
	/* 
		!!!!!!!!!!! 
		PHP обработчик AJAX запроса 
		списков орг-ий и кофеен ПО ТОКЕНУ 
		mv_ask_params_list 
		!!!!!! 
	*/
	add_action( 'wp_ajax_mv_ask_params_list', 'mv_ask_params_list' ); /* Вешаем обработчик mv_ask_params_list на ajax хук */
	add_action( 'wp_ajax_nopriv_mv_ask_params_list', 'mv_ask_params_list' ); /* то же для незарегистрированных пользователей */
	
	function mv_ask_params_list() {
		$nonce = $_GET['mv_nonce']; // Вытаскиваем из AJAX запроса переданное значение mv_nonce и заносим в переменную $nonce
		// проверяем nonce код, если проверка не пройдена прерываем обработку
		if ( ! wp_verify_nonce( $nonce, 'mv_ask_params_list' ) ) {
			wp_die( 'Stop! Nonce code of mv_ask_params_list incorrect!' );
		}
		
		// обрабатываем данные и возвращаем
		$mv_token = $_GET['mv_token'];
		// $mv_token = ( $_COOKIE['mv_cuc_token'] != '' ? $_COOKIE['mv_cuc_token'] : ''); /* Забираем токен из кукиса и дальше нужна проверка - а может уже есть токен, а может уже есть список организаций */
		// формируем строку HTML запроса к удаленному серверу
		$mv_url = "https://cscl.coffeeset.ru/ws/web/security/authorize/" . $mv_token;
		
		// Делаем запрос
		$mv_remote_get = wp_remote_get( $mv_url, array(
		'timeout' => 11
		) ); //увеличиваем время ожидания ответа от удаленного сервера с 5? по умолчанию до 11 сек);
		
		//PC::debug($mv_remote_get );
		//PC::debug($mv_url);
		$mv_result = json_decode( wp_remote_retrieve_body( $mv_remote_get ) ); //json_decode PHP функция Принимает закодированную в JSON строку и преобразует ее в переменную PHP
		// Если ответ сервера 200 OK, то удачная передача
		// PC::debug($mv_result);
		if ( ! is_wp_error( $mv_remote_get ) && wp_remote_retrieve_response_code( $mv_remote_get ) == 200 ) {
			print_r( json_encode( $mv_result ) ); // Передаем результат во фронтэнед в формате JSON
			} else {
			
			/*
				произошел сбой:
				- 404 "message": "User not found"
				- 500 "message": "Произошла ошибка.",  "Exceptionmessage": "Timeout expired.  The timeout period elapsed prior to completion of the operation or the server is not responding."
				
			*/
			
			if ( is_wp_error( $mv_remote_get ) ) { //timeout ?
				//PC::debug( $mv_remote_get );
				//PC::debug( wp_remote_retrieve_response_code( $mv_remote_get ) );
			};
			echo '{"mv_error_code" : "' . wp_remote_retrieve_response_code( $mv_remote_get ) . '" , ' . '"message" : "' . ( ( isset( $mv_result->message ) ) ? $mv_result->message : $mv_remote_get->get_error_code() ) . '"}';
		};
		
		// Не забываем завершать PHP
		wp_die();
	};
	/* / PHP обработчик AJAX запроса mv_ask_params_list  */
	
	
	
	/* 
		!!!!!!!!!!! 
		PHP обработчик AJAX запроса ТОКЕНА 
		и предварительных параметров по логину и паролю
		mv_ask_token 
		!!!!!!!!!!! 
	*/
	
	add_action( 'wp_ajax_mv_ask_token', 'mv_ask_token' ); /* Вешаем обработчик mv_ask_token на ajax  хук */
	add_action( 'wp_ajax_nopriv_mv_ask_token', 'mv_ask_token' ); /* то же для незарегистрированных пользователей */
	
	function mv_ask_token() {
		
		$nonce = $_GET['mv_nonce']; // Вытаскиваем из AJAX запроса переданное значение mv_nonce и заносим в переменную $nonce
		// проверяем nonce код, если проверка не пройдена прерываем обработку
		if ( ! wp_verify_nonce( $nonce, 'mv_ask_token' ) ) {
			wp_die( 'Stop! Nonce code of mv_ask_token incorrect!' );
		}
		
		// обрабатываем данные и возвращаем
		$mv_login    = $_GET['mv_login'];
		$mv_password = $_GET['mv_password'];
		// формируем строку HTML запроса к удаленному серверу https://cscl.coffeeset.ru/ws/web/security/authorize/a.shpakov/12345qwerty
		$mv_url = "https://cscl.coffeeset.ru/ws/web/security/authorize/" . $mv_login . "/" . $mv_password;
		
		// Делаем запрос
		$mv_remote_get = wp_remote_get( $mv_url, array(
		'timeout' => 11
		) ); //увеличиваем время ожидания ответа от удаленного сервера с 5? по умолчанию до 11 сек);
		
		//PC::debug($mv_remote_get );
		//PC::debug($mv_url);
		$mv_result = json_decode( wp_remote_retrieve_body( $mv_remote_get ) ); //json_decode PHP функция Принимает закодированную в JSON строку и преобразует ее в переменную PHP
		// Если ответ сервера 200 OK, то удачная передача
		//PC::debug($mv_result);
		if ( ! is_wp_error( $mv_remote_get ) && wp_remote_retrieve_response_code( $mv_remote_get ) == 200 ) {
			$mv_token = $mv_result->token;
			setcookie( "mv_cuc_user", $mv_login, time() + 86400, "/" ); // Записываем имя пользователя в кукис
			setcookie( "mv_cuc_token", $mv_token, time() + 86400, "/" ); // Записываем токен в кукис если вы установите время истечения куки равным 0, то она будет удалена по истечении сессии в браузера (после закрытия)
			print_r( json_encode( $mv_result ) ); // Передаем результат во фронтэнед в формате JSON
			} else {
			
			/*
				произошел сбой:
				- 404 "message": "User not found"
				- 500 "message": "Произошла ошибка.",  "Exceptionmessage": "Timeout expired.  The timeout period elapsed prior to completion of the operation or the server is not responding."
				
			*/
			
			if ( is_wp_error( $mv_remote_get ) ) { //timeout ?
				//PC::debug( $mv_remote_get );
				
			};
			//PC::debug( wp_remote_retrieve_response_code( $mv_remote_get ) );
			echo '{ "mv_error_code" : "' . wp_remote_retrieve_response_code( $mv_remote_get ) . '", ' . '"message" : "' . ( ( isset( $mv_result->message ) ) ? $mv_result->message : ( ( is_wp_error( $mv_remote_get ) ) ? $mv_remote_get->get_error_code() : "" ) ) . '"}';
		};
		
		// Не забываем завершать PHP
		wp_die();
		
	};
	/* !!!!!!!!!!! / PHP обработчик AJAX запроса mv_ask_token !!!!!!!!!!! */
?>