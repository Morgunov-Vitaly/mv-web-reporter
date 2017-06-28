<?php
	/* 	конструктор Login обработчика с кнопкой Необходимо загрузить системные переменные	и осуществить проверку: если нет токена - автоматически запустить окно авторизации */
	
	
	/* Добавляю шорткод [mv_closed] для указания на то, что запись закрыта для доступа без токена CSCL-reportera */
	add_shortcode( 'mv_closed', "mv_closed" );
	function mv_closed() {
		// Тут позже можно будет чего-нибудь вставить а пока нечего
	}
	
	/*  / Добавляю шорткод [mv_closed] для указания на то, что запись закрыта для доступа без токена CSCL-reportera */
	
	/* Добавляю шорткод [mv_dashboard] для указания на то, что это страница дашборда */
	add_shortcode( 'mv_dashboard', "mv_dashboard" );
	function mv_dashboard() {
		// Тут позже можно будет чего-нибудь вставить а пока нечего
	}
	
	/* /Добавляю шорткод [mv_dashboard] для указания на то, что это страница дашборда */
	
	
	/* Обработчик закрытия страницы от доступа без токена CSCL-reportera  */
	add_action( 'template_redirect', 'mv_close_content' );
	function mv_close_content() {
		global $post;
		$content = $post->post_content; /* Считываем контент страницы поста и смотрим есть ли шорткод [mv_closed] */
		if ( has_shortcode( $content, 'mv_closed' ) ) {
			// Редирект со статусом 401 Неавторизованный запрос, если нет токена или логина $_COOKIE['mv_cuc_user']
			if ( ! isset( $_COOKIE['mv_cuc_token'] ) ) {
				wp_redirect( home_url() ); // Пока без статуса ошибки
				exit;
			}
			// Далее вставить обработчик нажатий на кнопки формы LogIn/LogOut для закрытой формы он будет свой или нужен другой хук?
		}
	}
	
	/* / Обработчик закрытия страницы от доступа без токена CSCL-reportera  */
	
	
	/* !!!!!!!!!!!!!!! Блок Передачи нужных переменных на фронтэнед !!!!!!!!!!!!!!!! */
	function mv_js_variables_login() {
		// Вопрос нужно ли все это????
		$mvuser = get_userdata( get_current_user_id() ); // Логин текущего пользователя
		if ( isset( $mvuser ) && $mvuser ) {
			//Список переменных
			$mvvariables = [
			'mv_ajax_url'           => admin_url( 'admin-ajax.php' ),
			// передаем значение пути
			'mv_current_user_login' => $mvuser->user_login,
            // можно проверить window.mv_wp_data.mv_current_user_login   было $mvuser->data->user_login
            'mv_homepage' => home_url()// Передаем адрес главной страницы
			];
			//PC::debug($mvuser );
			} else {
			$mvvariables = [
			'mv_ajax_url'           => admin_url( 'admin-ajax.php' ),
			// передаем значение пути
			'mv_current_user_login' => __( 'Пользователь не определен', 'mv-web-reporter' ),
			// можно проверить window.mv_wp_data.mv_current_user_login   было $mvuser->data->user_login
			'mv_homepage' => home_url()// Передаем адрес главной страницы
			];
		}
		
		echo( '<script type="text/javascript"> window.mv_wp_data =' . json_encode( $mvvariables ) . ';</script>' );
		//	}
	}
	
	/* Привязываемся к хуку вывода шапки сайта и объявляем экшн: */
	add_action( 'wp_head', 'mv_js_variables_login' );
	
	/* !!!!!!!!!!!!! / Блок Передачи нужных переменных на фронтэнед !!!!!!!!!!!!!! */
	
	
	/* !!!!!!!!!!!!!!! Конструктор блока вывода Польщователя и Токен по шорткоду !!!!!!!!!!!!!!!! */
	
	function mv_var_constructor() {
		
		ob_start();
		if ( isset( $_COOKIE['mv_cuc_token'] ) ) { /* токен есть  */
		?>
        <p id="mv_user"><?php _e( 'Пользователь: ', 'mv-web-reporter' );
		echo $_COOKIE['mv_cuc_user']; ?></p> <!-- значение текущего пользователя -->
        <p id="mv_user_token"><?php _e( 'Токен: ', 'mv-web-reporter' );
		echo $_COOKIE['mv_cuc_token']; ?></p> <!-- значение токена -->
        <?php
			} else { // токена нет
		?>
        <p id="mv_user"><?php _e( 'Пользователь: ', 'mv-web-reporter' );
		_e( 'Пользователь не определен', 'mv-web-reporter' ); ?></p> <!-- значение пользователя -->
        <p id="mv_user_token"><?php _e( 'Токен не определен', 'mv-web-reporter' ); ?></p> <!-- значение токена -->
        <?php
		}
		$html = ob_get_contents();
		ob_get_clean();
		
		return $html;
	}
	
	/*  !!!!!!!!!!  Добавляем шорткод  [mv_variables] !!!!!!!!!! */
	
	add_shortcode( 'mv_variables', 'mv_var_constructor' );
	/* !!!!!!!!!!!!!!! / Конструктор блока вывода Польщователя и Токен по шорткоду !!!!!!!!!!!!!!!! */
	
	
	/* !!!!!!!!!!!!!!! Конструктор кнопки LogIn/LogOUT !!!!!!!!!!!!!!!! */
	
	function mv_login_constructor() {
		
		ob_start();
		// Забираем токен из кукиса и смотрим не равен ли он '', если его нет, то нужно вызвать форму регистрации
		if ( isset( $_COOKIE['mv_cuc_token'] ) ) { /* токен есть надо просто вывести кнопку, сделать запрос по токену и вызвать конструктор формы параметров (выбора отчетов) */
		?>
        <p class="align_center"><a id="mv_login_modal_init" class="w-btn style_solid color_primary icon_atcenter"
			href="#"><i class="fa fa-sign-out" aria-hidden="true"></i> <span
			class="w-btn-label"> <?php _e( 'Выход', 'mv-web-reporter' ); ?></span></a>
		</p> <!-- <button id="mv_login_modal_init">Вход / Выход</button> -->
		<?php
			} else { // кнопка сама нажимается и всплывает окно авторизации
		?>
        <p class="align_center"><a id="mv_login_modal_init" class="w-btn style_solid color_primary icon_atcenter"
			href="#"><i class="fa fa-sign-in" aria-hidden="true"></i><span
			class="w-btn-label"> <?php _e( 'Вход', 'mv-web-reporter' ); ?></span></a></p>
			<?php
			}
			$html = ob_get_contents();
			ob_get_clean();
			
			return $html;
	}
	
	/* !!!!!!!!!!!!!!! / Конструктор кнопки LogIn/LogOUT !!!!!!!!!!!!!!!! */
	
	
	/*  !!!!!!!!!!  Добавляем шорткод  [mv_login_code] !!!!!!!!!! */
	
	add_shortcode( 'mv_login_code', 'mv_login_constructor' );
	
	
	/* !!!!!!!!!!!!!!!  Обработчик формы LogIn-LogOut (пишем в футер) !!!!!!!!!!!!!!!!! */
	/*
		1) Обрабатывем нажатие на кнопку-ссылку LogOut
		2) Обрабатываем ввод неверных данных
		3) Корректный ввод - получаем от удаленного сервера значение токена, логина, списка организаций и кофеен
	*/
	function mv_login_handler() {
		global $post;
		$content = $post->post_content; /* Считываем контент страницы поста */
	?>
    <script type="text/javascript">
		<?php
			/* Проверка на наличие шорткода [mv_param_form]  в контенте */
			if( has_shortcode( $content, 'mv_param_form' )){ /* Для страниц отчетов */
			?>
			/* !!!!!!!!!!! Функция конструктор селектов списка ОРГАНИЗАЦИЙ   !!!!!!!!!!! */
			
			function mv_form_construct(result) { // передаем параметр - полученный объект
				var lastFirm = "";
				mv_default_coffee = 0; // Все кофейни по умолчанию
				mv_default_org = mv_result.ref_default_access_object; // простой случай accessType == "company"
				mv_results_data = []; // создаем массив значений первого списка (организаций)
				mv_results_data.length = 0;
				mv_results_data[0] = {"id": "0", "text": "--"};
				for (var organization in result) if (result.hasOwnProperty(organization)) {
					var t = result[organization]; //выбираем значения с ключем organization
					var mv_local_arr = {};
					mv_local_arr.length = 0; //дополнительно обнуляем
					if (lastFirm != t.ref) {
						/* условие для отсечки повторяющихся значений наверное он тут не нужен */
						mv_local_arr['id'] = t.ref;
						mv_local_arr['text'] = t.name;
						mv_results_data.push(mv_local_arr);
						//console.log("t.name ");
						lastFirm = t.ref;
						//Устанавливаем значение переменной mv_default_cofee - кофейня по умолчанию для данного пользователя
						if (mv_result.accessType == "coffeeshop") { // сложный случай accessType == "cofeeshop" надо перебирать
							//console.log(t.ref);
							for (var coffeeshop in t.divisions) if (t.divisions.hasOwnProperty(coffeeshop)) {
								var c = t.divisions[coffeeshop]; // t.divisions - массив кофеен
								if (c.Ref == mv_result.ref_default_access_object) {
									mv_default_org = t.ref; // значение организации по умолчанию
									mv_default_coffee = mv_result.ref_default_access_object; // Все кофейни по умолчанию
									break; // остановить перебор
								}
							}
						}
					}
				}
			}
			/* !!!!!!!!!!! / Функция конструктор селектов списка ОРГАНИЗАЦИЙ !!!!!!!!!!! */
			
			
			/* !!!!!!!!!!! Функция конструктор списка КОФЕЕН !!!!!!!!!!! */
			function onchangeFirmSelect(result) {
				var firmSelObj = document.getElementById('form_param_ref_organization');
				var mv_make_report_button = document.getElementById('saveForm');
				var form_param_cafe_place = document.getElementById('form_param_cafe_place'); //создаем указатель на элемент со вторым селектом по ID
				var currentFirm = firmSelObj.value; // запоминаем, что выбрали в первом селекте
				if (currentFirm == "0") {
					form_param_cafe_place.style.display = "none"; //бренд не выбран - прячем второй список и кнопку отправить отчет
					mv_make_report_button.disabled = 1;//id="saveForm" disabled
					} else {
					var caffeeSelObj = document.getElementById('form_param_cafe'); //создаем указатель на 2й селект по ID
					mv_results2_data = [];  // создаем массив значений второго списка (кофеен)
					//очистим список кофеен - всех, кроме первого элемента
					mv_results2_data[0] = {"id": "0", "text": "<?php _e( '00 Все кофейни', 'mv-web-reporter' ); ?>"};
					//console.log ( mv_results2_data);
					//jQuery("#form_param_cafe").html('<option value=""  selected>Все кофейни</option>'); //чистим старые
					//загрузим список кофеен соответствующей организации
					for (var organization in result) if (result.hasOwnProperty(organization)) { //перебираем массив организаций
						var t = result[organization];
						
						if (t.ref == currentFirm) { // находим выбранную компанию (может есть способ без перебора компаний?)
							//var selLen = caffeeSelObj.options.length; // определяем количество строк <option > во втором селекте
							for (var coffeeshop in t.divisions) if (t.divisions.hasOwnProperty(coffeeshop)) {
								var c = t.divisions[coffeeshop]; // t.divisions - массив подразделений (кофеен)
								if ( c['active']=="1"){ // учитываем только активные кофейни
								var mv_local_arr = {};
								//caffeeSelObj.options[selLen ++] = new Option(c.name, c.ref); // создания новых элементов списка мы //используем конструктор	Option(text, value), где text — это отображаемая метка элемента списка, а value — //её значение.
								mv_local_arr['id'] = c['Ref'];
								mv_local_arr['text'] = c['Name'];
								mv_results2_data.push(mv_local_arr); //добавляем значение
								}
							}
						}
					}
					// start сортируем полученный массив
					function mvcompareObjects(a, b) {
						if (a.text < b.text) return -1;
						if (a.text > b.text) return 1;
						return 0;
					};
					mv_results2_data.sort(mvcompareObjects);
					// end сортируем полученный массив
					jQuery(function ($) {
						mv_sel_coffee.empty(); //Обнуляем список
						mv_sel_coffee.select2({data: mv_results2_data}); // заполняем список новыми значениями
						
						/* Устанавливаем значение по умолчанию */
						if (mv_result.accessType == "coffeeshop") {
							$("#form_param_cafe").val(mv_result.ref_default_access_object).trigger('change');
						}
						/* / Устанавливаем значение по умолчанию */
						$("span.select2-container--default").css("width", "100%"); // костыль, чтобы изменить кривую вставку width = 1px
					});
					
					form_param_cafe_place.style.display = "block"; // Включить отображение списка
					mv_make_report_button.disabled = 0;//id="saveForm" enabled
				}
			}
			
			/* !!!!!!!!!! / Функция конструктор списка КОФЕЕН !!!!!!!!!!!!!!! */
			<?php
			} /* /Для страниц отчетов */
			
			
			/* Проверка на наличие шорткода [mv_dashboard]  в контенте */
			if( has_shortcode( $content, 'mv_dashboard' )){ /* Для страницы дашборда */
				if ( isset( $_COOKIE['mv_cuc_token'] ) ) {
				?>
				jQuery(document).ready(function ($) {
					
					$(".mv_token_show").slideDown('normal');// показать .mv_token_show - все объекты, которые должны отображаться только при наличии токена
					$(".mv_notoken_show").slideUp('normal');// скрыть .mv_notoken_show - все объекты, которые должны отображаться когда нет токена
					
				});
				<?php
				}
			}
		?>
		
        jQuery(function ($) {
			
            /* !!!!!! #1  Обрабатывем нажатие на кнопку-ссылку LogOut  !!!!!!!!!! */
            $("#mv_logout_btn").click(function(event_lo) {
                // alert ('LogOut Done!');
                function delete_cookie(cookie_name)// удаление куки с токеном
                {
                    var cookie_date = new Date();  // Текущая дата и время
                    cookie_date.setTime(cookie_date.getTime() - 1);
                    document.cookie = cookie_name + "=; path=/; expires=" + cookie_date.toGMTString();
				}
				
                delete_cookie("mv_cuc_token"); // удаление куки с токеном
                delete_cookie("mv_cuc_user"); // удаление куки с логином
                $("#mv_login_error").slideUp('normal'); // скрываем сообщение об ошибке
				<?php
					if( has_shortcode( $content, 'mv_param_form' )){ /* Для страниц отчетов */
					?>
					
					$("#form_param_user").attr("value", "");// очистить переменную пользователя и пароля
					$("#form_param_pass").attr("value", "");// очистить переменную пользователя и пароля
					$("#mv_login_modal_init").html('<i class="fa fa-sign-in" aria-hidden="true"></i><span class="w-btn-label"> <?php _e( 'Вход', 'mv-web-reporter' ); ?></span>'); // меняем надпись кнопки на Вход
					/* ?????????  может лучше эти действия выполнять через флаг - переменную ??????  */
					
					$("#mv_user").html("<?php _e( 'Пользователь: Пользователь не определен', 'mv-web-reporter' ); ?>");
					<!-- значение пользователя -->
					$("#mv_user_token").html("<?php _e( 'Токен не определен', 'mv-web-reporter' ); ?>");
					<!-- значение токена -->
					
					$("#form_param_container_inputs").slideUp('normal');// спрятать форму form_param_container_inputs
					$(".mv_reports_container").slideUp('normal');// скрыть .mv_reports_container - контейнер для вывода отчетов
					//$("#mv_report_place").slideUp('normal');// спрятать форму mv_report_place - контейнер для вывода отчетов
					//$("#mv_report_table").html('');// удаляем содержимое отчета, чтобы он не отображался
					<?php
					} /* для закрытых страниц */
				?>
                $(".mv-login").html('<i class=\'fa fa-lock\'></i> LogIn'); // меняем надпись Ссылки на LogIn
                $(".mv_token_show").slideUp('normal');// скрыть .mv_token_show - все объекты, которые должны отображаться только при наличии токена
                $(".mv_notoken_show").slideDown('normal');// показать .mv_notoken_show - все объекты, которые должны отображаться когда нет токена
                event_lo.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
				<?php
					if ( has_shortcode( $content, 'mv_closed' ) ) { /* Для закрытых страниц */
					?>
					
					document.location.href = 'http://cscl-reporter.com'; //Редирект на гл. страницу  ру енг? http://cscl-reporter.com/ru/otchety
					<?php
					}/* /Для закрытых страниц отчетов и закупок */
				?>
			});
            /* !!!!!! / #1  Обрабатывем нажатие на кнопку-ссылку LogOut  !!!!!!!!!! */
			
			
            
			
			/* !!!!!! #2 Обрабатывем нажатие на кнопку LogIn отправка и проверка логина и пароля   !!!!!!!!!! */
			
			
            /* Обрабатываем ввод неверных данных  */
            /* Если даные корректны - получаем от удаленного сервера значение токена, логина, списка организаций и кофеен */
			
            $("#form_login").submit(function(event_li) { /* Отправляем данные формы  */
                //$("body, #saveForm, #mv_login_btn, .pum-container").addClass("mv-cursor-whait"); // устанавливаем курсор ожидание
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
                        console.log("<?php _e( 'Статус выполнения AJAX запроса токена на локальный сервер: ', 'mv-web-reporter' ); ?>" + status); // Выводим сообщение об ошибках
                        mv_result = JSON.parse(result); // $.parseJSON(result); //функция JQuery
                        //$("body, #saveForm, #mv_login_btn, .pum-container").removeClass("mv-cursor-whait"); // убираем курсор ожидание
                        $("#mv_login_loader").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
						
                        if ((typeof mv_result.mv_error_code == "undefined") && (mv_result.token != '') && (mv_result.token != "undefined")) { // все получилось!
                            $("#pum-6132").popmake('close'); // закрываем чертово диалогово окно 6132!
                            $("#mv_login_error").slideUp('normal'); // скрываем сообщение об ошибке
							
                            /* Для страниц отчетов */
                            <?php
								if( has_shortcode( $content, 'mv_param_form' )){
								?>
								$("#mv_login_modal_init").html('<i class="fa fa-sign-out" aria-hidden="true"></i> <span class="w-btn-label"> <?php _e( 'Выход', 'mv-web-reporter' ); ?></span>'); // меняем надпись кнопки на Выход
								$("#mv_user").html("<?php _e( 'Пользователь: ', 'mv-web-reporter' ); ?>" + mv_result.login);
								<!-- значение пользователя -->
								$("#mv_user_token").html("<?php _e( 'Токен: ', 'mv-web-reporter' ); ?>" + mv_result.token);
								<!-- значение токена -->
								//console.log ( 'Результат успешен: ' + mv_result.mv_error_code );
								//console.log ( mv_result.Message );
								//console.log ( mv_result.token );
								//console.log ( mv_result.organizations );
								mv_form_construct(mv_result.organizations); //конструктор, который заносит список организаций в mv_results_data
								/* Динамически меняем селект */
								mv_sel_org.empty(); //Обнуляем список
								mv_sel_org.select2({data: mv_results_data}); // заполняем список новыми значениями
								
								//Устанавливаем значения выбранных элементов списка для данного пользователя по умолчанию
								$("#form_param_ref_organization").val(mv_default_org).trigger('change');
								onchangeFirmSelect(mv_result.organizations); //Здесь же нужно вызвать конструктор списка кофеен
								$("#form_param_cafe").val(mv_default_coffee).trigger('change');
								
								$("span.select2-container--default").css("width", "100%");  // костыль, чтобы изменить кривую вставку width = 1px
								$("#form_param_container_inputs").slideDown('normal');// показать форму form_param_container_inputs
								<?php
								} /* /Для страниц отчетов */
								/* Для страницы дашборда */
								if ( has_shortcode( $content, 'mv_dashboard' ) ) {
								?>
								$(".mv_token_show").slideDown('normal');// показать .mv_token_show - все объекты, которые должны отображаться только при наличии токена
								$(".mv_notoken_show").slideUp('normal');// скрыть .mv_notoken_show - все объекты, которые должны отображаться когда нет токена
								<?php
								} /* / Для страницы дашборда */
							?>
                            $(".mv-login").html('<i class=\'fa fa-unlock-alt\'></i> ' + mv_result.login); // меняем надпись Ссылки на логин пользователя
						}
                        else {
							
                            $("#mv_login_error").slideDown('normal'); //выводим сообщение об ошибке в форме
                            //alert ("<?php _e( 'Ошибка аутентификации!', 'mv-web-reporter' ); ?>");
                            console.log('<?php _e( 'Код ошибки запроса токена: ', 'mv-web-reporter' ); ?>' + mv_result.mv_error_code + '<?php _e( 'Сообщение: ', 'mv-web-reporter' ); ?>' + mv_result.Message);
						}
						
					},
                    error: function (result, status, jqxhr) { /* срабатывает только в случае если не сработает AJAX запрос на WP */
						//$("body, #saveForm, #mv_login_btn, .pum-container").removeClass("mv-cursor-whait"); // убираем курсор ожидание
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
					} /* / Для закрытых страниц */
				?>
			});
			
            /* !!!!!! / #2 Обрабатывем нажатие на кнопку LogIn  отправка и проверка логина и пароля   !!!!!!!!!! */
			
			
            /* !!!!!!!!!!! Включаем обработчик обновления второго селекта при изменении первого !!!!!!!!!!!!!! */
			
            $("#form_param_ref_organization").change(function () {
                onchangeFirmSelect(mv_result.organizations);
                /* конструктор второго списка кофеен для выбранной организации */
				
			});
			
		});
		
		<?php
			if( has_shortcode( $content, 'mv_param_form' )){
			?>
			
			/* Функция для чтения куки */
			function mv_getCookie(name) {
				var matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
				));
				return matches ? decodeURIComponent(matches[1]) : undefined;
			}
			
			
			/* !!!!!!!!!!!! Обработчик - конструктор формы ввода параметров отчетов ПО ТОКЕНУ !!!!!!!!!!!! */
			jQuery(document).ready(function ($) {
				// проверяем наличие токена
				mv_token = mv_getCookie('mv_cuc_token');
				if ((mv_token != "") && (typeof mv_token != "undefined") && (mv_flag_token_ask === 0)) { /* если токен не пустой и флаг усановлен на 0 */
					mv_flag_token_ask = mv_flag_token_ask + 1;
					/* изменяем флаг им можно будет пользоваться для подсчета кол-во срабатываний данного обработчика */
					//$("body, #saveForm, #mv_login_btn, .pum-container").addClass("mv-cursor-whait");
					/* устанавливаем курсор ожидание почему-то не видно */
					$("#mv_report_progress_circle").slideDown('normal'); // Отображаем колесо загрузчик ожидание slideUp('normal')
					$.ajax({
						type: 'GET',
						url: '<?php echo admin_url( "admin-ajax.php" ); ?>', /* URL к которму подключаемся */
						data: {
							action: 'mv_ask_params_list', /* Вызывам обработчик  mv_ask_params_list */
							mv_nonce: '<?php echo wp_create_nonce( "mv_ask_params_list" ); ?>',
							mv_token: mv_getCookie('mv_cuc_token') // передаем токен
						},
						success: function (result, status) {
							console.log("<?php _e( 'Статус запроса списка по токену: ', 'mv-web-reporter' ); ?>" + status); // Выводим сообщение об ошибках
							mv_result = JSON.parse(result); // $.parseJSON(result); //функция JQuery
							//$("body, #saveForm, #mv_login_btn, .pum-container").removeClass("mv-cursor-whait"); // убираем курсор ожидание
							$("#mv_report_progress_circle").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
							
							if ((typeof mv_result.mv_error_code == "undefined") && (mv_result.token != '') && (mv_result.token != "undefined")) { // все получилось или нам пришел 0!
								$("#pum-6132").popmake('close'); // закрываем диалогово окно 6132
								console.log("<?php _e( 'Результат запроса списка по токену успешен: ', 'mv-web-reporter' ); ?>");
								//console.log ( mv_result.Message );
								//console.log ( mv_result.token );
								//console.log ( mv_result.organizations );
								mv_form_construct(mv_result.organizations); //конструктор, который заносит список организаций в mv_results_data
								/* Динамически меняем селект */
								mv_sel_org.empty(); //Обнуляем список переменная mv_sel_org хранит в себе экземпляр объекта
								mv_sel_org.select2({data: mv_results_data}); // заполняем список новыми значениями
								
								//Устанавливаем значения выбранных элементов списка для данного пользователя по умолчанию
								$("#form_param_ref_organization").val(mv_default_org).trigger('change');
								onchangeFirmSelect(mv_result.organizations); //Здесь же нужно вызвать конструктор списка кофеен
								$("#form_param_cafe").val(mv_default_coffee).trigger('change');
								
								$("span.select2-container--default").css("width", "100%");  // костыль, чтобы изменить кривую вставку width = 1px
								$("#form_param_container_inputs").slideDown('normal');// показать форму form_param_container_inputs
								
							}
							else {
								
								$("#mv_login_error").slideDown('normal'); //выводим сообщение об ошибке в форме - но если форма закрыта, то ничего не выведет
								//alert ('Ошибка аутентификации!');
								console.log("<?php _e( 'Код ошибки запроса списка по токену: ', 'mv-web-reporter' ); ?>" + mv_result.mv_error_code + " <?php _e( 'Сообщение: ', 'mv-web-reporter' ); ?>" + mv_result.Message);
							}
							
						},
						error: function (result, status, jqxhr) { // срабатывает только в случае если не сработает AJAX запрос на WP
							$("body, #saveForm, #mv_login_btn, .pum-container").removeClass("mv-cursor-whait"); // убираем курсор ожидание
							alert("<?php _e( 'Упс! Возникла ошибка при запросе списка по токену к серверу WP! Ответ сервера: ', 'mv-web-reporter' ); ?>" + result);
							console.log("<?php _e( 'Статус: ', 'mv-web-reporter' ); ?>" + status);
							console.log("<?php _e( 'jqXHR статус: ', 'mv-web-reporter' ); ?>" + jqxhr.status + " " + jqxhr.statusText);
							console.log(jqxhr.getAllResponseHeaders());
						}
					});
				}
				
				/* !!!!!!!!! / Конструктор формы ввода предварительных параметров отчетов при наличии токена !!!!!!!! */
			});
			
			
			/* !!!!!!!!!!!!!!!!!!!! */
			/* Конструкторы отчетов */
			/* !!!!!!!!!!!!!!!!!!!! */
			
			
			
            /* !!!!! AJAX  Обработчик отправки данных формы параметров отчетов и вызова конструктора табличного отчета(102) !!!!!  */
			
			jQuery(function ($) {
				
				$("#form_param").submit(function (event_pr) { /* отправка данных формы с параметрами для построения отчета */
					//$("body, #saveForm, #mv_login_btn, .pum-container").addClass("mv-cursor-whait"); /* устанавливаем курсор ожидание */
					$("#mv_report_progress_circle").slideDown('normal'); // Отображаем колесо загрузчик ожидание slideUp('normal')
					$.ajax({
						type: 'GET',
						url: '<?php echo admin_url( "admin-ajax.php" ); ?>', /* URL к которму подключаемся как альтернатива */
						data: {
							action: 'mv_take_report_data', /* Вызывам обработчик делающий запрос данных отчета*/
							mv_nonce: '<?php echo wp_create_nonce( "mv_take_report_data" ); ?>',
							ref_organization: document.getElementById('form_param_ref_organization').value, //по ID поля $('#form_param_ref_organization').val() window.form_param_ref_organization.value
							cafe_ref: document.getElementById('form_param_cafe').value, //по ID поля $('#form_param_cafe').val() window.form_param_cafe.value
							dateFrom: document.getElementById('dateFrom').value + 'T00:00:00', //по ID поля window.dateFrom.value.toISOString().replace(/\..*$/, '') window.dateFrom.value + 'T00:00:00',
							dateTo: document.getElementById('dateTo').value + 'T23:59:59' //по ID поля document.getElementById('form_param_ref_organization').value   window.dateTo.value + 'T23:59:59'
						},
						success: function (result, status) {
							//$("body, #saveForm, #mv_login_btn, .pum-container").removeClass("mv-cursor-whait"); /* убираем курсор ожидание */
							$("#mv_report_progress_circle").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
							//mv_report_result = result;
							//console.log('mv_report_result: ');
							//console.log(mv_report_result);
							mv_report_result = JSON.parse(result);
							
							//$("body, #saveForm, #mv_login_btn, .pum-container").removeClass("mv-cursor-whait"); // убираем курсор ожидание
							$("#mv_report_progress_circle").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
							if (mv_report_result.mv_data.mv_error_code == "200") { //Все получилось!
								$(".mv_reports_container").slideDown('normal');// показать .mv_reports_container - контейнер для вывода отчетов
								//$("#mv_report_table").html(result);// раньше это был построитель таблицы
								$("#mv_ac_tast").html(mv_report_result.mv_html);// обновляем аккардеон
								document.getElementById("displayorgname").innerHTML = document.getElementById("form_param_ref_organization").options[document.getElementById("form_param_ref_organization").options.selectedIndex].text;		
								document.getElementById("displaydatefrom").innerHTML = document.getElementById("dateFrom").value; 
								document.getElementById("displaydateto").innerHTML = document.getElementById("dateTo").value;
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
								} else {
								alert("<?php _e( 'Ошибка конструктора отчета!: ', 'mv-web-reporter' ); ?>" + mv_report_result.mv_data.mv_error_code);
								console.log('mv_error_code: ' + mv_report_result.mv_data.mv_error_code);
								console.log('Message: ' + mv_report_result.mv_data.Message);
								console.log('report URL: ' + mv_report_result.mv_html);
							}
						},
						error: function (result, status, jqxhr) { // срабатывает только в случае если не сработает AJAX запрос на WP
							// $("body, #saveForm, #mv_login_btn, .pum-container").removeClass("mv-cursor-whait"); // убираем курсор ожидание
							$("#mv_report_progress_circle").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
							alert("<?php _e( 'Упс! Возникла ошибка при обращении №2 к серверу WP! Ответ сервера: ', 'mv-web-reporter' ); ?>" + result);
							console.log("<?php _e( 'Статус: ', 'mv-web-reporter' ); ?>" + status); // Выводим сообщение об ошибках
							console.log("<?php _e( 'jqXHR статус: ', 'mv-web-reporter' ); ?>" + jqxhr.status + " " + jqxhr.statusText);
							console.log(jqxhr.getAllResponseHeaders());
						}
					});
					
					event_pr.preventDefault();/* Отменяем стандартное действие кнопки Submit в форме */
				});
				/* !!!!!!!!! / AJAX  Обработчик отправки данных формы параметров отчетов  !!!!!!!!!!!!! */
			});
			<?php
			}
		?>
	</script>
	<?php
	}
	
	/* добавляем обработчик jQuery в футер страницы на фронтэнде */
	add_action( 'wp_footer', 'mv_login_handler' );
	
	
	/* *********************** PHP обработчики *******************  */
	
	/* !!!!!!!!!!! PHP обработчик AJAX запроса списков орг-ий и кофеен ПО ТОКЕНУ mv_ask_params_list !!!!!! */
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
				- 404 "Message": "User not found"
				- 500 "Message": "Произошла ошибка.",  "ExceptionMessage": "Timeout expired.  The timeout period elapsed prior to completion of the operation or the server is not responding."
				
			*/
			
			if ( is_wp_error( $mv_remote_get ) ) { //timeout ?
				PC::debug( $mv_remote_get );
				PC::debug( wp_remote_retrieve_response_code( $mv_remote_get ) );
			};
			echo '{"mv_error_code" : "' . wp_remote_retrieve_response_code( $mv_remote_get ) . '" , ' . '"Message" : "' . ( ( isset( $mv_result->Message ) ) ? $mv_result->Message : $mv_remote_get->get_error_code() ) . '"}';
		};
		
		// Не забываем завершать PHP
		wp_die();
		
	}
	
	;
	
	/* !!!!!!!!! / PHP обработчик AJAX запроса mv_ask_params_list !!!!!! */
	
	
	/* !!!!!!!!!!! PHP обработчик AJAX запроса токена и предварительных параметров mv_ask_token !!!!!!!!!!! */
	
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
				- 404 "Message": "User not found"
				- 500 "Message": "Произошла ошибка.",  "ExceptionMessage": "Timeout expired.  The timeout period elapsed prior to completion of the operation or the server is not responding."
				
			*/
			
			if ( is_wp_error( $mv_remote_get ) ) { //timeout ?
				PC::debug( $mv_remote_get );
				
			};
			PC::debug( wp_remote_retrieve_response_code( $mv_remote_get ) );
			echo '{ "mv_error_code" : "' . wp_remote_retrieve_response_code( $mv_remote_get ) . '", ' . '"Message" : "' . ( ( isset( $mv_result->Message ) ) ? $mv_result->Message : ( ( is_wp_error( $mv_remote_get ) ) ? $mv_remote_get->get_error_code() : "" ) ) . '"}';
		};
		
		// Не забываем завершать PHP
		wp_die();
		
	}
	
	;
	/* !!!!!!!!!!! / PHP обработчик AJAX запроса mv_ask_token !!!!!!!!!!! */
?>