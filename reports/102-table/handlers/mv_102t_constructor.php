<?php
	/*
		
		Конструктор  102 отчета в виде аккардеона
		цвет был #f4faf8 #ccffe3
		
	*/
	function mv_102t_constructor($sum_bonusAddTotal, $sum_bonusPayTotal, $sum_bonusSalesAmount, $sum_cardSalesAmount, $sum_cardSalesCount, $sum_ordersCount, $sum_ordersWithCardCount, $sum_revenueFactSum, $sum_revenuePlanSum, $sum_salesRevenue, $sum_salesTotal){
		ob_start();
		// start Выводим заголовок отчета: дата от до, название организации 
		echo '<p style="display: inline;">'.__('Организация: ', 'mv-web-reporter').'</p><p id="displayorgname" style="display: inline;"></p>';
		echo '<p style="display: inline;"><br>'.__('Дата от: ', 'mv-web-reporter').'</p><p id="displaydatefrom" style="display: inline;"></p>';
		echo '<p style="display: inline;"> / '.__('Дата по: ', 'mv-web-reporter').'</p><p id="displaydateto" style="display: inline;"></p>';
		echo '<p> </p>';
		
		/* Выводим итоговые значения */
		/* адаптивные блоки №1 и №2 */
		echo'<div class="g-cols wpb_row type_default valign_top">';
		/* адаптивный блок №1 */
		echo'<div class="vc_col-md-6 wpb_column vc_column_container">';		
		echo'<div class="vc_column-inner">';
		echo'<div class="wpb_wrapper">';
		/* data here */		
		echo '<p>';
		echo __('Сумма заказов', 'mv-web-reporter'). ': ' . number_format($sum_salesTotal,  1, ',', ' ') . '<br>';
		echo __('Выручка (только кэш)', 'mv-web-reporter'). ': ' . number_format($sum_salesRevenue,  1, ',', ' ') . '<br>';
		echo __('Бонусов продано + кэшбэк', 'mv-web-reporter'). ': ' . number_format($sum_bonusAddTotal,  1, ',', ' ') . '<br>';
		echo __('Бонусов списано', 'mv-web-reporter'). ': ' . number_format($sum_bonusPayTotal,  1, ',', ' ') . '<br>';
		echo __('Бонусов продано', 'mv-web-reporter'). ': ' . number_format($sum_bonusSalesAmount,  1, ',', ' ') . '<br>';
		echo __('Карт продано на сумму', 'mv-web-reporter'). ': ' . number_format($sum_cardSalesAmount,  1, ',', ' ');
		echo '</p>
			</div>
		</div>
	</div>';
		/* адаптивный блок №2 */		
		echo'<div class="vc_col-md-6 wpb_column vc_column_container">';		
		echo'<div class="vc_column-inner">';
		echo'<div class="wpb_wrapper">';	
		
		echo '<p>';
		/* data here */
		echo __('Кол-во проданных карт', 'mv-web-reporter'). ': ' . number_format($sum_cardSalesCount,  1, ',', ' ') . '<br>';
		echo __('Кол-во заказов', 'mv-web-reporter'). ': ' . number_format($sum_ordersCount,  1, ',', ' ') . '<br>';
		echo __('Заказов с картами', 'mv-web-reporter'). ': ' . number_format($sum_ordersWithCardCount,  1, ',', ' ') . '<br>';
		echo __('План товарных продаж', 'mv-web-reporter'). ': ' . number_format($sum_revenuePlanSum,  1, ',', ' ') . '<br>';
		echo __('Факт товарных продаж', 'mv-web-reporter'). ': ' . number_format($sum_revenueFactSum,  1, ',', ' ');		
		echo '</p>
			</div>
		</div>
	</div>';		
		echo '</div>'; 
		/* /адаптивные блоки №1 и №2 */
		
		// end Выводим заголовок отчета: дата от до, название организации 
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
	//PC::debug( $html );
	} 
?>