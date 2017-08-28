<?php
	/*
		
		PHP обработчик запроса на удаленный сервер данных 120 отчета Бонусы
		
	*/
	
	/* Функция вставки новых значений в таблицу БД WP - работает	*/
	function mv_tr_ins_ref_table_121($mv_rr, $mv_user, $mv_table_name ){
		
		global $wpdb;
		
		$wpdb->insert( 
		$wpdb->prefix . $mv_table_name, // указываем таблицу
		array(
		'user'=> $mv_user,
		'purchasebonus'=> $mv_rr->purchaseBonus,
		'cashback'=> $mv_rr->cashback,
		'withdrawbonustotal'=> $mv_rr->withdrawBonusTotal,
		'withdrawbonusourcards'=> $mv_rr->withdrawBonusOurCards,
		'withdrawbonusanothercards'=> $mv_rr->withdrawBonusAnotherCards,
		'purchasedcardscount'=> $mv_rr->purchasedCardsCount,
		'cardpurchaseamount'=> $mv_rr->cardPurchaseAmount,
		'scopedate'=> date("Y-m-d H:i:s", strtotime($mv_rr->scopeDate))
		),
		array( 
		'%s',
		'%f',
		'%f',
		'%f',
		'%f',
		'%f',
		'%d',
		'%f',
		'%s'
		)
		);
	}
	/* Функция  очистки таблицы БД WP */
	function mv_tr_truncate_table_121($mv_user, $mv_table_name){
		
		global $wpdb;
		$table  = $wpdb->prefix . $mv_table_name;
		//$delete = $wpdb->query("TRUNCATE TABLE $table"); /* надо дополнить условием отбора по токену */
		$wpdb->delete( $table, array( 'user' => $mv_user ), array( '%s' ) );
		//$wpdb->query( "DELETE FROM $table WHERE user='$mv_user'");
		
	}	
	
	
	add_action('wp_ajax_mv_take_report_data_120' , 'mv_take_report_data_120'); /* Вешаем обработчик mv_take_report_data на ajax  хук */
	add_action('wp_ajax_nopriv_mv_take_report_data_120', 'mv_take_report_data_120'); /* то же для незарегистрированных пользователей */
	
	function mv_take_report_data_120() {
		
		$nonce = $_GET['mv_nonce']; // Вытаскиваем из AJAX запроса переданное значение mv_nonce и заносим в переменную $nonce
		// проверяем nonce код, если проверка не пройдена прерываем обработку
		if( ! wp_verify_nonce( $nonce, 'mv_take_report_data_120' ) ) wp_die('Stop! Nonce code of mv_take_report_data_120 incorrect!');
		
		if (isset($_GET['cafe_ref']) && $_GET['cafe_ref']!=="0"){ 
			$refObject = $_GET['cafe_ref'];
			$objectType='coffeeshop';
			} else {
			$refObject = $_GET['ref_organization'];
			$objectType='company';
		}
		$dateFrom = $_GET['dateFrom'];
		$dateTo = $_GET['dateTo'];		
		$token = ( $_COOKIE['mv_cuc_token'] != '' ? $_COOKIE['mv_cuc_token'] : ''); //Забираем токен из кукиса
		
		/* Запрос для получения обощенных данных в шапку отчета - запрос 120 */
		/*		https://cscl.coffeeset.ru/ws-test/web/report?token=YTY0OTYxY2UtYTgwNS00N2M3LTg1YzctZjMyNTU3YTUyMTFj&id=121&dateFrom=2016-04-20T00:00:01&dateTo=2016-04-25T23:59:59&refObject=b0d6ce78-24ce-41d9-a997-f0b876895205&objectType=Company	
		*/
		$mv_url_120 = 'https://cscl.coffeeset.ru/ws-test/web/report?token=' . $token . '&id=120&dateFrom=' . $dateFrom . "&dateTo="  . $dateTo . "&refObject=" . $refObject . "&objectType=" .$objectType; // Формируем строку запроса 120
		
		PC::debug($mv_url_120 );	
		$mv_remote_get_120 = wp_remote_get( $mv_url_120, array(
		'timeout'     => 11)); //увеличиваем время ожидания ответа от удаленного сервера с 5? по умолчанию до 11 сек	
		
		
		/* / Запрос для получения обощенных данных в шапку отчета - запрос 120 */
		
		/* Подневной отчет - запрос 121 для таблицы */
		/* https://cscl.coffeeset.ru/ws-test/web/report?token=YTY0OTYxY2UtYTgwNS00N2M3LTg1YzctZjMyNTU3YTUyMTFj&id=121&dateFrom=2016-04-20T00:00:01&dateTo=2016-04-25T23:59:59&refObject=b0d6ce78-24ce-41d9-a997-f0b876895205&objectType=Company
		*/
		$mv_url_121 = 'https://cscl.coffeeset.ru/ws-test/web/report?token=' . $token . '&id=121&dateFrom=' . $dateFrom . "&dateTo="  . $dateTo . "&refObject=" . $refObject . "&objectType=" .$objectType; // Формируем строку запроса
		
		PC::debug($mv_url_121 );	
		$mv_remote_get_121 = wp_remote_get( $mv_url_121, array(
		'timeout'     => 11)); //увеличиваем время ожидания ответа от удаленного сервера с 5? по умолчанию до 11 сек
		/* Подневной отчет - запрос 121 для таблицы */
		
		/* Обрабатываем полученные данные  */		
		
		$mv_report_result_120 = json_decode( wp_remote_retrieve_body( $mv_remote_get_120 ) );
		$mv_report_result_121 = json_decode( wp_remote_retrieve_body( $mv_remote_get_121 ) ); /* PHP функция Принимает закодированную в JSON строку и преобразует ее в объект PHP */
		
		// Ну и если ответ сервера 200 OK, то можно вывести что-нибудь
		if ( (! is_wp_error( $mv_remote_get_121 )) && (! is_wp_error( $mv_remote_get_120 )) && ( wp_remote_retrieve_response_code( $mv_remote_get_121 ) == 200 ) && (wp_remote_retrieve_response_code( $mv_remote_get_120 ) == 200) )  {
			
			PC::debug( $mv_report_result_120 );			
			PC::debug( $mv_report_result_121 );
			
			$mv_user = ( $_COOKIE['mv_cuc_user'] != '' ? $_COOKIE['mv_cuc_user'] : '');
			
			/*
				!!!!!!!!!!!! 
				вызваем конструкторы отчета
				!!!!!!!!!!!! 
			*/
			/* конструктор таблицы 121 */
			mv_tr_truncate_table_121($mv_user, 'mv_report_120'); // Очищаем таблицу
			foreach ($mv_report_result_121->reportList as $mv_rr):
			mv_tr_ins_ref_table_121( $mv_rr, $mv_user, 'mv_report_120' ); // добавляем данные в таблицу базы данных WP связанную с wpdatarables
			endforeach;
			
			/* конструктор шапки 120 */			
			if (! empty( $mv_report_result_120 )) {
				$mv_html = mv_120_report_constructor($mv_report_result_120); 
				} else {
				$mv_html ='<p style="text-align: center;">' . __( 'Данные отсутствуют', 'mv-web-reporter' ) . '</p>';
			};
			/* / вызваем конструктор отчета */
			
			//PC::debug( $mv_html );
			$mv_data = array('mv_error_code' => '200', 'message' => 'Well done!'); 
			$mv_response = array('mv_data'=>$mv_data, 'mv_html'=>$mv_html);
			
			echo json_encode($mv_response); // Это передается во фронтэнед
			
			}else {
			
			/* 
				произошел сбой:
				- 401 отказано в доступе 401 Unauthorized («не авторизован»)
				- 404 "message": "User not found"
				- 403 - какая-то таинственная ошибка которая переодически выскакивает
				- 500 "message": "Произошла ошибка.",  "Exceptionmessage": "Timeout expired.  The timeout period elapsed prior to completion of the operation or the server is not responding." 
				
			*/			
			//PC::debug(wp_remote_retrieve_response_code( $mv_remote_get_121 ) );	
			//PC::debug($mv_report_result );
			if ( is_wp_error( $mv_remote_get_121 )) { //timeout? отказ в доступе и пр.
				PC::debug( $mv_remote_get_121 );
			}
			//$mv_error_code_result = ((null !== $mv_remote_get_121->get_error_code())  ? $mv_remote_get_121->get_error_code() : "" );
			$mv_html = '"mv_url_120:--' . $mv_url_120 . '   mv_url_121:--' . $mv_url_121 . '"'; //запишем в пустующий раздел адресс ссылки-запроса к удаленному серверу
			$mv_data = array('mv_error_code' => wp_remote_retrieve_response_code( $mv_remote_get_121 ), 'message' => '"120: '. ((isset($mv_report_result_120->message)) ? $mv_report_result_121->message :"" ) . '   121: ' . ((isset($mv_report_result_121->message)) ? $mv_report_result_121->message :"" ) . '"');
			$mv_response = array('mv_data'=>$mv_data, 'mv_html'=>$mv_html);			
			//echo '{"mv_error_code" : "' . wp_remote_retrieve_response_code( $mv_remote_get_121 ) . '", ' . '"message" :  "' . $mv_report_result_121->message . '"}';
			echo json_encode($mv_response); // Это передается во фронтэнед
		};
		
		// Не забываем завершать PHP
		wp_die();
		
	};		
	/* !!!!!!!!!!! / PHP обработчик AJAX запроса данных 120 отчета !!!!!!!!!!! */
?>