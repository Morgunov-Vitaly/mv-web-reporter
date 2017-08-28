<?php
	/*
		
		Конструктор 120 отчета Бонусы
		
	*/
	
	function mv_120_report_constructor($mv_report_result_120){
		
		ob_start();
		/* адаптивные блоки №1 и №2 */
		echo'<div class="g-cols wpb_row type_default valign_top">';
		/* адаптивный блок №1 */
		echo'<div class="vc_col-md-6 wpb_column vc_column_container">';		
		echo'<div class="vc_column-inner">';
		echo'<div class="wpb_wrapper">';		
		// start Выводим заголовок отчета: дата от до, название организации 
		echo '<p style="display: inline;"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/organization.svg"> ' .__('Организация: ', 'mv-web-reporter').'</p><p id="displayorgname" style="display: inline;">'. $mv_report_result_120->objectName .'</p>';
		echo '<p style="display: inline;"><br><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/time-1.svg"> '.__('Дата от', 'mv-web-reporter').': </p><p id="displaydatefrom" style="display: inline;"></p>';
		echo '<p style="display: inline;"> / '.__('Дата по', 'mv-web-reporter').': </p><p id="displaydateto" style="display: inline;"></p>';
		echo '<p></p>';
		/* Итоговые данные из 120 отчета */
		echo '<p><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/jewels.svg"> '.__('Бонусов продано', 'mv-web-reporter'). ': ' . number_format($mv_report_result_120->reportList[0]->purchaseBonus,  1, ',', ' ') .'<br><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/046-coins-1.svg"> ';
		echo __('Кэшбэк', 'mv-web-reporter'). ': ' . number_format($mv_report_result_120->reportList[0]->cashback,  1, ',', ' ');
		echo '</p>
			</div>
		</div>
	</div>';	
		/* адаптивный блок №2 */		
		echo'<div class="vc_col-md-6 wpb_column vc_column_container">';		
		echo'<div class="vc_column-inner">';
		echo'<div class="wpb_wrapper">';	
		/* вырезал 
		<img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/income.svg"> 
		<img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/cscl-income.svg"> 
		<img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/021-coins-3.svg"> 
		<img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/015-calculator-2.svg"> 
		<img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/cscl-and-pot.svg"> 
		*/
		echo '<p>' . __('Бонусов списано всего', 'mv-web-reporter'). ': ' . number_format($mv_report_result_120->reportList[0]->withdrawBonusTotal,  1, ',', ' ') . '<br>';
		echo __('Бонусов списано по нашим картам', 'mv-web-reporter'). ': ' . number_format($mv_report_result_120->reportList[0]->withdrawBonusOurCards,  1, ',', ' ') .'<br>';	
		echo __('Бонусов списано по картам партнёров', 'mv-web-reporter'). ': ' . number_format($mv_report_result_120->reportList[0]->withdrawBonusAnotherCards,  1, ',', ' ') .'<br>';	
		echo __('Кол-во проданных карт', 'mv-web-reporter'). ': ' . number_format($mv_report_result_120->reportList[0]->purchasedCardsCount,  0, ',', ' ') .'<br>';
		echo __('Карт продано на сумму', 'mv-web-reporter'). ': ' . number_format($mv_report_result_120->reportList[0]->cardPurchaseAmount,  1, ',', ' ') .'</p>'; 
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
	
	function mv_120_extra_options_html($mv_url_param){
		ob_start();
		/* Блок вывода дополнительных параметров отчета */
		echo'<p>Вывод дополнительных параметров</p>';
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
	}	
?>