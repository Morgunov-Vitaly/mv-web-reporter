<?php
	/*
		
		Конструктор  102 отчета в виде аккардеона
		цвет был #f4faf8 #ccffe3
		
	*/
	function mv_102t_constructor(){
		
		ob_start();
		// start Выводим заголовок отчета: дата от до, название организации 
		echo '<p style="display: inline;">'.__('Организация: ', 'mv-web-reporter').'</p><p id="displayorgname" style="display: inline;"></p>';
		echo '<p style="display: inline;"><br>'.__('Дата от: ', 'mv-web-reporter').'</p><p id="displaydatefrom" style="display: inline;"></p>';
		echo '<p style="display: inline;"> / '.__('Дата по: ', 'mv-web-reporter').'</p><p id="displaydateto" style="display: inline;"></p>';
		echo '<p><br></p>';
		// end Выводим заголовок отчета: дата от до, название организации 
				
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
	//PC::debug( $html );
	} 
?>