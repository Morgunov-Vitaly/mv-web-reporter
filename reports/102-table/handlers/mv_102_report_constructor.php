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
		'avgcheckvalue'=> $mv_rr->AvgCheckValue,
		'bonusaddtotal'=> $mv_rr->BonusAddTotal,
		'bonuspaytotal'=> $mv_rr->BonusPayTotal,
		'bonussalesamount'=> $mv_rr->BonusSalesAmount,
		'cardsalesamount'=> $mv_rr->CardSalesAmount,
		'cardsalescount'=> $mv_rr->CardSalesCount,
		'objectname'=> $mv_rr->ObjectName,
		'orderscount'=> $mv_rr->OrdersCount,
		'orderswithcardcount'=> $mv_rr->OrdersWithCardCount,
		'refobject'=> $mv_rr->RefObject,
		'revenuefactavgcheck'=> $mv_rr->RevenueFactAvgCheck,
		'revenuefactsum'=> $mv_rr->RevenueFactSum,
		'revenueplanavgcheck'=> $mv_rr->RevenuePlanAvgCheck,
		'revenueplansum'=> $mv_rr->RevenuePlanSum,
		'salesrevenue'=> $mv_rr->SalesRevenue,
		'salestotal'=> $mv_rr->SalesTotal,
		'firstDateClosed'=> date("Y-m-d H:i:s", strtotime($mv_rr->FirstDateClosed)),
		'lastDateClosed'=> date("Y-m-d H:i:s", strtotime($mv_rr->LastDateClosed))
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
			$objectType='Coffeeshop';
			} else {
			$refObject = $_GET['ref_organization'];
			$objectType='Company';
		}
		$dateFrom = $_GET['dateFrom'];
		$dateTo = $_GET['dateTo'];
		$token = ( $_COOKIE['mv_cuc_token'] != '' ? $_COOKIE['mv_cuc_token'] : ''); //Забираем токен из кукиса
		/* https://cscl.coffeeset.ru/ws/web/report/102/YTY0OTYxY2UtYTgwNS00N2M3LTg1YzctZjMyNTU3YTUyMTFj/?dateFrom=2016-04-20T00:00:01&dateTo=2016-04-20T23:59:59&refObject=b0d6ce78-24ce-41d9-a997-f0b876895205&objectType=Company  */
		$mv_url = "https://cscl.coffeeset.ru/ws/web/report/102/" . $token . "/?dateFrom=" . $dateFrom . "&dateTo="  . $dateTo . "&refObject=" . $refObject . "&objectType=" .$objectType; // Формируем строку запроса
		
		//PC::debug($mv_url);
		
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
			tr_truncate_table($mv_user, 'mv_report_102'); // Очищаем таблицу
			foreach ($mv_report_result->ReportList as $mv_rr):
			tr_ins_ref_table( $mv_rr, $mv_user, 'mv_report_102' ); // добавляем данные в таблицу базы данных WP связанную с wpdatarables
			endforeach;
			
			$mv_html = mv_102_accordion_constructor($mv_report_result); //вызваем конструктор аккордеона
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
		
	//	PC::debug($object->advancedFilterOptions['aoColumns'][3]->values);
		// а здесь попробуем изменить список организаций для фильтра - checkbox
	 /*  $object->advancedFilterOptions['aoColumns'][3]->values = array( //теперь надо указать правильный список кофеен, но это будет работать только один раз :( 
		'yes1', 
		'yes2', 
		'yes3'
		); */
		
		
		return $object;
		
	}
	/* / поменяем настройки плагина WpDataTables изменим меню отображения количества строк таблицы */
	
	
	
	/* 
		Функция для автоматической подстановки 
		значения текущего ТОКЕНА в Шорткод таблицы 
		WpDataTables 
	*/
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

		//	PC::debug($_COOKIE['mv_cuc_user'] );
			$post->post_content = str_replace('var1="123413543154"]', 'var1="' . (isset($_COOKIE['mv_cuc_user']) ? $_COOKIE['mv_cuc_user']: "") . '"]', $post->post_content);
		//	PC::debug($post->post_content );
		}
	}
	/* /Функция для автоматической подстановки значения текущего ТОКЕНА в Шорткод таблицы WpDataTables */	
?>