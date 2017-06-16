<?php
	
	/* Конструктор вывода контейнера для отчета в виде аккардеона */
	
	function mv_report_accordion_constructor() {
		ob_start();
	?>
	<div id="mv_ac_tast">
	</div>
	<?php
		$html = ob_get_contents();
		ob_get_clean();
		
		return $html;
		
	}
	/* / Конструктор вывода отчета в виде аккардеона */		
	
	/*  !!!!!!!!!!!!!!!!!!!!!!!  Добавляем шорткод  [mv_report_accordion_code] */		
	add_shortcode('mv_report_accordion_code', 'mv_report_accordion_constructor');
?>