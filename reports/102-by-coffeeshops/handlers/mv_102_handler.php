<?php
	/*
		
		Конструктор 102 отчета и его вспомогательные функции 
		
	*/
	
	/* Функция вставки новых значений в таблицу БД WP - работает	*/
	function tr_ins_ref_table($mv_rr, $mv_user, $mv_table_name ){
		
		global $wpdb;
		
		$wpdb->insert( 
		$wpdb->prefix . $mv_table_name, // указываем таблицу
		array(
		'user'=> $mv_user,
		'avgcheckvalue'=> $mv_rr->avgCheckValue,
		'bonusaddtotal'=> $mv_rr->bonusAddTotal,
		'bonuspaytotal'=> $mv_rr->bonusPayTotal,
		'bonussalesamount'=> $mv_rr->bonusSalesAmount,
		'cardsalesamount'=> $mv_rr->cardSalesAmount,
		'cardsalescount'=> $mv_rr->cardSalesCount,
		'objectname'=> $mv_rr->objectName,
		'orderscount'=> $mv_rr->ordersCount,
		'orderswithcardcount'=> $mv_rr->ordersWithCardCount,
		'refobject'=> $mv_rr->refObject,
		'revenuefactavgcheck'=> $mv_rr->revenueFactAvgCheck,
		'revenuefactsum'=> $mv_rr->revenueFactSum,
		'revenueplanavgcheck'=> $mv_rr->revenuePlanAvgCheck,
		'revenueplansum'=> $mv_rr->revenuePlanSum,
		'salesrevenue'=> $mv_rr->salesRevenue,
		'salestotal'=> $mv_rr->salesTotal,
		'firstDateClosed'=> date("Y-m-d H:i:s", strtotime($mv_rr->firstDateClosed)),
		'lastDateClosed'=> date("Y-m-d H:i:s", strtotime($mv_rr->lastDateClosed))
		),
		array( 
		'%s',
		'%f',
		'%f',
		'%f',
		'%f',
		'%f',
		'%d',
		'%s',
		'%d',
		'%d',
		'%s',
		'%f',
		'%f',
		'%f',
		'%f',
		'%f',
		'%f',
		'%s',
		'%s'
		)
		);
		
	}
	/* Функция  очистки таблицы БД WP */
	function tr_truncate_table($mv_user, $mv_table_name){
		
		global $wpdb;
		$table  = $wpdb->prefix . $mv_table_name;
		//$delete = $wpdb->query("TRUNCATE TABLE $table"); /* надо дополнить условием отбора по токену */
		$wpdb->delete( $table, array( 'user' => $mv_user ), array( '%s' ) );
		//$wpdb->query( "DELETE FROM $table WHERE user='$mv_user'");
		
	}
	
	
	/* !!!!!!!!!!!   PHP обработчик AJAX запроса данных 102 отчета таблица !!!!!!!!!!! */
	
	function mv_take_report_data_102() {
		
		$nonce = $_GET['mv_nonce']; // Вытаскиваем из AJAX запроса переданное значение mv_nonce и заносим в переменную $nonce
		// проверяем nonce код, если проверка не пройдена прерываем обработку
		if( ! wp_verify_nonce( $nonce, 'mv_take_report_data_102' ) ) wp_die('Stop! Nonce code of mv_take_report_data_102 incorrect!');
		
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
		/* https://cscl.coffeeset.ru/ws/web/report/102/YTY0OTYxY2UtYTgwNS00N2M3LTg1YzctZjMyNTU3YTUyMTFj/?dateFrom=2016-04-20T00:00:01&dateTo=2016-04-20T23:59:59&refObject=b0d6ce78-24ce-41d9-a997-f0b876895205&objectType=Company  */
		$mv_url = "https://cscl.coffeeset.ru/ws/web/report/102/" . $token . "/?dateFrom=" . $dateFrom . "&dateTo="  . $dateTo . "&refObject=" . $refObject . "&objectType=" .$objectType; // Формируем строку запроса
		
		PC::debug($mv_url);
		
		$mv_remote_get = wp_remote_get( $mv_url, array(
		'timeout'     => 11)); //увеличиваем время ожидания ответа от удаленного сервера с 5? по умолчанию до 11 сек
		
		$mv_report_result = json_decode( wp_remote_retrieve_body( $mv_remote_get ) ); /* PHP функция Принимает закодированную в JSON строку и преобразует ее в переменную PHP */
		// Ну и если ответ сервера 200 OK, то можно вывести что-нибудь
		if ( ! is_wp_error( $mv_remote_get )  &&  wp_remote_retrieve_response_code( $mv_remote_get ) == 200 )  {
			
			// Запись в БД в таблицу csc_mv_report_102
			// Удаляем старые данные и записываем новые
			
			//PC::debug( $mv_report_result );
			//PC::debug( $token );
			$mv_user = ( $_COOKIE['mv_cuc_user'] != '' ? $_COOKIE['mv_cuc_user'] : '');
			//tr_truncate_table($mv_user, 'mv_report_102'); // Очищаем таблицу

			/* Обнуляем суммарные значения */
			$sum_bonusAddTotal = 0;
			$sum_bonusPayTotal = 0;
			$sum_bonusSalesAmount = 0;
			$sum_cardSalesAmount = 0;
			$sum_cardSalesCount = 0;
			$sum_ordersCount = 0;
			$sum_ordersWithCardCount = 0;
			$sum_revenueFactSum = 0;
			$sum_revenuePlanSum = 0;
			$sum_salesRevenue = 0;
			$sum_salesTotal = 0;			
			foreach ($mv_report_result->reportList as $mv_rr):
			//tr_ins_ref_table( $mv_rr, $mv_user, 'mv_report_102' ); // добавляем данные в таблицу базы данных WP связанную с wpdatarables
				$sum_bonusAddTotal = $sum_bonusAddTotal + $mv_rr->bonusAddTotal;
				$sum_bonusPayTotal = $sum_bonusPayTotal + $mv_rr->bonusPayTotal;
				$sum_bonusSalesAmount = $sum_bonusSalesAmount + $mv_rr->bonusSalesAmount;
				$sum_cardSalesAmount = $sum_cardSalesAmount + $mv_rr->cardSalesAmount;
				$sum_cardSalesCount = $sum_cardSalesCount + $mv_rr->cardSalesCount;
				$sum_ordersCount = $sum_ordersCount + $mv_rr->ordersCount;
				$sum_ordersWithCardCount = $sum_ordersWithCardCount + $mv_rr->ordersWithCardCount;
				$sum_revenueFactSum = $sum_revenueFactSum + $mv_rr->revenueFactSum;
				$sum_revenuePlanSum = $sum_revenuePlanSum + $mv_rr->revenuePlanSum;
				$sum_salesRevenue = $sum_salesRevenue + $mv_rr->salesRevenue;
				$sum_salesTotal = $sum_salesTotal + $mv_rr->salesTotal;			
			endforeach;
			
			$mv_html = mv_102_accordion_constructor($mv_report_result, $sum_bonusAddTotal, $sum_bonusPayTotal, $sum_bonusSalesAmount, $sum_cardSalesAmount, $sum_cardSalesCount, $sum_ordersCount, $sum_ordersWithCardCount, $sum_revenueFactSum, $sum_revenuePlanSum, $sum_salesRevenue, $sum_salesTotal); //вызваем конструктор аккордеона
			//PC::debug( $mv_html );
			$mv_data = array('mv_error_code' => '200', 'message' => 'Well done!'); 
			$mv_response = array('mv_data'=>$mv_data, 'mv_html'=>$mv_html);
			
			//print_r ($mv_report_result); // Это передается во фронтэнед 
			echo json_encode($mv_response); // Это передается во фронтэнед
			// echo '{"mv_error_code" : "200", "message" : " Well done!" , "mv_html" : "'. $mv_html .'" }'; // Это передается во фронтэнед
			
			}else {
			
			/* 
				произошел сбой:
				- 401 отказано в доступе 401 Unauthorized («не авторизован»)
				- 404 "message": "User not found"
				- 403 - какая-то таинственная ошибка которая переодически выскакивает
				- 500 "message": "Произошла ошибка.",  "Exceptionmessage": "Timeout expired.  The timeout period elapsed prior to completion of the operation or the server is not responding." 
				
			*/			
			//PC::debug(wp_remote_retrieve_response_code( $mv_remote_get ) );	
			//PC::debug($mv_report_result );
			if ( is_wp_error( $mv_remote_get )) { //timeout? отказ в доступе и пр.
				//PC::debug( $mv_remote_get );
			}
			$mv_html = $mv_url; //запишем в пустующий раздел адресс ссылки-запроса к удаленному серверу
			$mv_data = array('mv_error_code' => '"' . wp_remote_retrieve_response_code( $mv_remote_get ) .'"', 'message' => '"'. ((isset($mv_report_result->message)) ? $mv_report_result->message : $mv_remote_get->get_error_code()) . '"');
			$mv_response = array('mv_data'=>$mv_data, 'mv_html'=>$mv_html);			
			//echo '{"mv_error_code" : "' . wp_remote_retrieve_response_code( $mv_remote_get ) . '", ' . '"message" :  "' . $mv_report_result->message . '"}';
			echo json_encode($mv_response); // Это передается во фронтэнед
		};
		
		// Не забываем завершать PHP
		wp_die();
		
	};		
	
	add_action('wp_ajax_mv_take_report_data_102' , 'mv_take_report_data_102'); /* Вешаем обработчик mv_take_report_data на ajax  хук */
	add_action('wp_ajax_nopriv_mv_take_report_data_102', 'mv_take_report_data_102'); /* то же для незарегистрированных пользователей */
	
	/* !!!!!!!!!!! / PHP обработчик AJAX запроса данных 102 отчета !!!!!!!!!!! */

?>