<?php
	/*
		
		Конструктор 130 отчета Выручка
		
	*/
	
	function mv_130_report_constructor($mv_report_result_130){
		
		ob_start();
		/* адаптивные блоки №1 и №2 */
		echo'<div class="g-cols wpb_row type_default valign_top">';
		/* адаптивный блок №1 */
		echo'<div class="vc_col-md-6 wpb_column vc_column_container">';		
		echo'<div class="vc_column-inner">';
		echo'<div class="wpb_wrapper">';		
		// start Выводим заголовок отчета: дата от до, название организации 
		echo '<p style="display: inline;"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/organization.svg"> ' .__('Организация: ', 'mv-web-reporter').'</p><p id="displayorgname" style="display: inline;">'. $mv_report_result_130->objectName .'</p>';
		echo '<p style="display: inline;"><br><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/time-1.svg"> '.__('Дата от', 'mv-web-reporter').': </p><p id="displaydatefrom" style="display: inline;"></p>';
		echo '<p style="display: inline;"> / '.__('Дата по', 'mv-web-reporter').': </p><p id="displaydateto" style="display: inline;"></p>';
		echo '<p></p>';
		echo '<p><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/025-business-2.svg"> '.__('Средний чек', 'mv-web-reporter'). ': ' . number_format($mv_report_result_130->reportList[0]->avgCheckValue,  1, ',', ' ') .'<br><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/015-calculator-2.svg"> ';
		echo __('Количество заказов', 'mv-web-reporter'). ': ' . number_format($mv_report_result_130->reportList[0]->ordersCount,  1, ',', ' ');
		echo '</p>
			</div>
		</div>
	</div>';	
		/* адаптивный блок №2 */		
		echo'<div class="vc_col-md-6 wpb_column vc_column_container">';		
		echo'<div class="vc_column-inner">';
		echo'<div class="wpb_wrapper">';	
		
		echo '<p><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/income.svg">' . __('Выручка от продаж', 'mv-web-reporter'). ': ' . number_format($mv_report_result_130->reportList[0]->salesRevenue,  1, ',', ' ') . '<br><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/001-scissors-1black.svg"> ';
		echo __('Выручка от продаж по картам', 'mv-web-reporter'). ': ' . number_format($mv_report_result_130->reportList[0]->salesRevenueByCards,  1, ',', ' ') .'<br><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/021-coins-3.svg"> ';	
		echo __('Общий объем продаж', 'mv-web-reporter'). ': ' . number_format($mv_report_result_130->reportList[0]->salesTotal,  1, ',', ' ') .'<br><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/cscl-and-pot.svg"> ';	
		echo __('Общий объем продаж по картам', 'mv-web-reporter'). ': ' . number_format($mv_report_result_130->reportList[0]->salesTotalByCards,  1, ',', ' ') .'</p>';
		/* echo '<p>'.__('scopeDate', 'mv-web-reporter'). ': ' . date("Y-m-d H:i:s", strtotime($mv_report_result_130->reportList[0]->scopeDate)) .'</p>'; */
		echo '</div>'; 
		echo '</div>'; 		
		echo '</div>'; 
		echo '</div>'; 		

		echo '<p><br></p>';
		// end Выводим заголовок отчета: дата от до, название организации 		
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
		//PC::debug( $html );
	}
	
	
/*
	*
	*	функция - конструктор
	*	для передачи html кода 
	*	формы дополнительных параметров отчета
	*
	*
*/
	
	function mv_130_extra_options_html($mv_url_param){
		ob_start();
		/* Блок вывода дополнительных параметров отчета */
		echo'<p>Вывод дополнительных параметров</p>';
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
	}	
?>