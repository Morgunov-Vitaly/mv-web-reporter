<?php
	
	/*
		Plugin Name: MV-WEB-Reporter
		Plugin URI: http://cscl-reporter.com
		Description: Плагин для добавления отчетов с использованием библиотеки Select2 & AJAX-запросов в WordPress
		Author: Моргунов Виталий
		Author URI: https://vk.com/v.morgunov
		Version: 20170424
	*/
// if (!is_admin()){  почему-то  с этой проверкой начинает глючить отчеты
	
	// установим глобальную переменную с ID модального окна LogIn для удобства укажем ее здесь	
	$mv_login_popup = 6132; 
	
	// установим глобальную переменную с html кодом дополнительных параметров для формы дополнительных параметров
	$mv_extra_options_html =''; 
	
	// установим глобальную переменную с html кодом отчета
	$mv_report_html =''; 
	
	
	/* Локализация плагина */
	add_action( 'plugins_loaded', 'mv_load_plugin_textdomain' );
	
	function mv_load_plugin_textdomain() {
		load_plugin_textdomain( 'mv-web-reporter', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	/* /Локализация плагина */
	
	
	/* !!!!!!! Подключаем стили и скрипты для ВСЕХ ОТЧЕТОВ конструкторов  !!!!!!!!!! */
	require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv-style-and-js-switcher.php' );
	
	/* !!!!!! Подключаем Login обработчик с кнопкой  и передаем системные переменные !!!!!!!!!!!!!!!! */
	require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv-login-constructor.php' );
	
	/* !!!!!!! Подключаем шорткод конструктора формы ввода предварительных параметров отчетов  !!! */
	require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv-form-param-constructor.php' );
	
	/* !!!!!!! Подключаем менеджер отчета 102 - By Coffeeshops !!!!!!!!! */
	require_once( plugin_dir_path( __FILE__ ) . 'reports/102-by-coffeeshops/102-by-coffeeshops.php' );
	
	/* !!!!!!! Подключаем менеджер отчета 102t - table report By Coffeeshops !!!!!!!!! */
	require_once( plugin_dir_path( __FILE__ ) . 'reports/102-table/102-table.php' );
	
	/* !!!!!!! Подключаем менеджер отчета 150 - Sales Mix !!!!!!!!! */
	require_once( plugin_dir_path( __FILE__ ) . 'reports/150-golden-invoice/150-golden-invoice.php' );
	
	/* !!!!!!! Подключаем менеджер отчета 160 - Sales Mix !!!!!!!!! */
	require_once( plugin_dir_path( __FILE__ ) . 'reports/160-sales-mix/160-sales-mix.php' );
	
	/* !!!!!!! Подключаем менеджер отчета id=132 ReportOrderList !!!!!!!!! */
	require_once( plugin_dir_path( __FILE__ ) . 'reports/132-orders/132-orders.php' );	
	/* !!!!!!! Подключаем менеджер отчета id=119 Информация по карте лояльности  !!!!!!!!! */
	require_once( plugin_dir_path( __FILE__ ) . 'reports/119-cscl-cards-info/119-cscl-cards-info.php' );		
	/* !!!!!!! Подключаем менеджер отчета id=130 Информация по выручке  !!!!!!!!! */
	require_once( plugin_dir_path( __FILE__ ) . 'reports/130-revenue/130-revenue.php' );	

	/*
		!!!!!!!! 
		Подключение отчетов 
		находим шорткод отчетов в контенте [mv_reports id="номер отчета"] определяем указанные параметры  
		и установим ряд глобальных переменных для дальнейшего построения отчетов
		!!!!!!!!!!
	*/	
	
	add_action( 'template_redirect', 'mv_set_global_variables' );
	
	function mv_set_global_variables (){
		global $post;
		global $mv_extra_options_html;
		global $mv_report_html;
		global $mv_url_param;
		
		/* считываем возможные переданные параметры через url */
		$mv_url_param = "";
		if (isset($_GET['mv_var'])){
		$mv_url_param = $_GET['mv_var'];
		}
		//PC::debug($mv_url_param); 
		
		/* /считываем возможные переданные параметры через url */ 
		
		if( has_shortcode( $post->post_content, 'mv_reports' )) { // если в контенте есть шорткод отчета
			/* считываем параметр отчета - его ID */
			$mv_report_params;
			$mv_report_params = '';
			/* preg_match_all('#\[mv_reports id=\'(.+?)\']#is', $post->post_content, $arr); */ // Найденное значение будет в $arr[1]
			preg_match('#\[mv_reports\s*id\s*=\s*[\'\"](.+?)[\'\"]]#is', $post->post_content, $arr); // Найденное значение будет в $arr[1]
			$mv_report_params = $arr[1];
			//PC::debug($mv_report_params);
			
			if ($mv_report_params == "102") { /* Отчет по кофейням 102 */
				$mv_report_html = mv_102_report(); 
			}
			if ($mv_report_params == "102t") { /* Отчет по кофейням 102t */
				$mv_report_html = mv_102t_report(); 
			}
			if ($mv_report_params == "150") { /* Отчет Золотой чек 150 */
				$mv_report_html = mv_150_report(); 
			}							
			if ($mv_report_params == "160") { /* Отчет SalesMix 160 */
				$mv_extra_options_html = mv_160_extra_options_html(); // формируем строку для дополнительных параметров
				$mv_report_html = mv_160_sales_mix_report(); 
			}
			if ($mv_report_params == "132") { /* Отчет ReportOrderList 132 */
				$mv_extra_options_html = mv_132_extra_options_html(); // формируем строку для дополнительных параметров
				$mv_report_html = mv_132_report();								
			}
			if ($mv_report_params == "119") { /* Отчет CSCL CardInfo 119 */
				$mv_extra_options_html = mv_119_extra_options_html($mv_url_param); // формируем строку для дополнительных параметров
				$mv_report_html = mv_119_report();								
			}	
			if ($mv_report_params == "130") { /* Отчет CSCL CardInfo 119 */
				//$mv_extra_options_html = mv_130_extra_options_html(); // формируем строку для дополнительных параметров
				$mv_report_html = mv_130_report();								
			}				
			/* устанавливаем глобальную переменную - html строку для вывода дополнительных параметров */
			
			/* устанавливаем глобальную переменную mv_report_id  с ID отчета */
			
			/* определим глобальную строковую переменную с  html кодом самого отчета */
		}
	}
	
	
	
	/*
		!!!!!!!! 
		Обработчик шорткода отчетов при submit
		[mv_reports id="номер отчета"]
		!!!!!!!!!!
	*/
	function mv_reports($atts){
		// задаем значения параметров по умолчанию
		// ID по умолчанию, если его не указывать
		global $mv_report_params;
		global $mv_report_html;	
		$html ='';
		$mv_report_params = shortcode_atts( array('id' => '102'), $atts);
		//global $mv_extra_options_html;
		//global $mv_login_popup; 	
		
		
		ob_start(); // передадим значение mv_report_id в фронт-энд ! возможно это - лишнее
		?>
		<div id="mv_report_container"> <!-- контейнер отчета --></div>
		<script type="text/javascript">
			mv_report_id = '<?php echo $mv_report_params['id'] ?>';
			/* mv_old_input_val = '';  старое значение введенного поля фильтра дополнительных параметров */
			/* !!!!!!!!!!!!!!!!!!!! */
			/* Конструкторы отчетов */
			/* !!!!!!!!!!!!!!!!!!!! */
			jQuery(function ($) {
				$(document).ready(function(){ 
				<?php echo $mv_report_html; ?> /* ядро отчета */
					/* !!!!!!!!! / AJAX  Обработчик отправки данных формы параметров отчетов  !!!!!!!!!!!!! */
				});
			});
		</script><?php
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
	}
	// Также подключаем обработчики других отчетов
	
	add_shortcode('mv_reports', 'mv_reports');   
	/*/ Обработчик шорткода отчетов [mv_reports]*/
	
	
	/* добавляем шорткод для вставки колеса загрузки progress circle */
	
	add_shortcode('mv_progress_circle', 'mv_progress_circle_constructor'); 
	function mv_progress_circle_constructor($atts){
		$params = shortcode_atts( array( // Значенияпо умолчанию
		'id' => 'mv_login_loader' // ID блока по умолчанию mv_login_loader, если его не указывать
		), $atts );
		ob_start();
		?>
		<div id="<?php echo $params['id'] ?>" class="mv_loader" title="0">
			<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><span class="sr-only"><?php _e( 'Загрузка', 'mv-web-reporter' ); ?>...</span>
		</div>
		<?php
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
	}	
	/* / добавляем шорткод для вставки колеса загрузки progress circle */
	
	
	/* 
		Добавление шорткода [mv-current-username] 
		отображающего LogIn/LogOut пользователя 
		в систему reporter  
	*/
	add_shortcode( 'mv-login' , 'mv_LogIn' );
	
	function mv_LogIn(){
		$UsName =  (isset($_COOKIE['mv_cuc_user'])) ? $_COOKIE['mv_cuc_user'] : "LogIn" ;
		If ($UsName == "LogIn"){
			$LogInLink = "<a class='w-text-value mv_login_code mv_login_modal_init' href='#'><i class='fa fa-lock'></i> ". $UsName ."</a>";
			} else {
			$LogInLink = "<a class='w-text-value mv_login_code mv_login_modal_init' href='#'><i class='fa fa-unlock-alt'></i> " . $UsName . "</a>"; /* #mv_login_modal_init - это триггер для модального окна LogIn */
		}
		echo $LogInLink;
	}
/* / Добавление шорткода [mv-current-username] отображающего LogIn/LogOut пользователя в систему reporter  */

	/* поменяем настройки плагина WpDataTables изменим меню отображения количества строк таблицы */
	
	add_filter( 'wpdatatables_filter_table_description', 'mv_wpdt_hook', 10, 2 );
	
	function mv_wpdt_hook( $object, $table_id ) {
		
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
//} // !is_admin() -  почему-то  с этой проверкой начинает глючить отчеты