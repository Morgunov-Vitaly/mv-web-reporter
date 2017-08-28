<?php
	
	/* !!!!!!!!!!  Подключаем скрипты и стили на страницу с шорткодом. 
	Разделил, чтобы стили подсоединились в шапке, а скрипты в футере !!!!!!!!!!!!!!!!!!*/
	
	
	/* !!!!!!!!!!   Подключаем стили в зависимости от обнаруженных шорткодов !!!!!!!!!! */	
	
	function enqueue_mv_style() {
		/* Проверяем наличие шорткода в посте */
		global $post;
		$content = $post->post_content; /* Считываем контент страницы поста  */
		
		if( has_shortcode( $content, 'mv_param_form' ) ) {
			// смотрим нет ли шорткода [mv_param_form]  в контенте. Раньше стояло [mv_login_code]
			// Подключаем измененный файл стилей 
			 wp_register_style( 'select2css', plugins_url('../css/select2.min.css', __FILE__));
			//wp_register_style( 'select2css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css');
			wp_enqueue_style( 'select2css' );
			
			wp_register_style( 'param_form_styles_css', plugins_url('../css/mv_report_param_form_styles.css', __FILE__));
			wp_enqueue_style( 'param_form_styles_css' );
		}	
		wp_register_style( 'mv_stylecss', WP_PLUGIN_URL . '/mv-web-reporter/css/mv_style.css'); // регистрируем стили используемые плагином
		wp_enqueue_style( 'mv_stylecss' );
		
		
	}
	/* Подвешиваем к хуку функцию подключения стилей */	
	add_action( 'template_redirect', 'enqueue_mv_style' );
	
	/* !!!!!!!!!!  / Подключаем стили !!!!!!!!!! */	
	
	
	
	/* !!!!!!!!!!  Подключаем скрипты в header  !!!!!!!!!! */	
	
	function enqueue_mv_scripts_header() {
		/* Проверяем наличие шорткода в посте */
		global $post;
		$content = $post->post_content; /* Просматриваем контент страницы поста */
		
		if( has_shortcode( $content, 'mv_param_form' ) ) {	
			/* Скрипты для работы с датами */
			wp_register_script( 'data_format', plugins_url('../js/date-format.js', __FILE__) );
			wp_enqueue_script( 'data_format' );			
		}
	}	
	/* !!!!!!!!!!  / Подключаем скрипты в header  !!!!!!!!!! */		
	/* Подвешиваем к хуку функцию подключения скриптов */	
	add_action( 'wp_enqueue_scripts', 'enqueue_mv_scripts_header' );	
	
	
	/* !!!!!!!!!!  Подключаем скрипты в футер  !!!!!!!!!! */	
	
	function enqueue_mv_scripts_footer() {
		/* Проверяем наличие шорткода в посте */
		global $post;
		$content = $post->post_content; /* Просматриваем контент страницы поста */
		
		if( has_shortcode( $content, 'mv_param_form' ) ) {
			// смотрим нет ли шорткода [mv_param_form]  в контенте. Раньше стояло [mv_login_code]
			// wp_register_script( 'select2', plugins_url('js/select2.full.min.js', __FILE__), array( 'jquery' ), '1.0', true );
			wp_register_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'select2' );
			
			/* !!!!!!!! Прописываем в футере код инициализации наших Select2 объектов (надо убрать это в шорткод?) !!!!!!!!!!!!! */
		?>
		<script type='text/javascript'>
			jQuery(document).ready(function ($) {
				
				mv_sel_org = $("#form_param_ref_organization").select2({ // инициализация селекта организаций
					language: "ru",
					placeholder: "Выберите объект"
					//data: mv_results_data
				});
				mv_sel_coffee = $("#form_param_cafe").select2({ // инициализация селекта кофеен
					language: "ru",
					placeholder: "Выберите объект"
					//data: mv_results2_data
				});					
				// инициализация фейкового селекта
				/* mv_sel_1 = $("#form_param_ref_organization2").select2({ 
					language: "ru",
					placeholder: "Выберите объект"
					
				}); */ 
				// инициализация фейкового множественного селекта
				/* mv_sel = $("#form_param_cafe2").select2();  */
				
			});
		</script>   
		<?php
			
		}
	}	
	
	/* Подвешиваем к хуку функцию подключения скриптов */	
	add_action( 'wp_footer', 'enqueue_mv_scripts_footer' );
	/* !!!!!!!!!!  / Подключаем скрипты в футер !!!!!!!!!! */	
	
	/* !!!!!!!!!!!!! / Подключаем скрипты и стили на страницу с шорткодом !!!!!!!!! */
?>