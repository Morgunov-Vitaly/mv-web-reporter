<?php
	/*
		
		Конструктор  102 отчета в виде аккардеона
		цвет был #f4faf8 #ccffe3
		
	*/
	function mv_102_accordion_constructor( $mv_report_result, $sum_bonusAddTotal, $sum_bonusPayTotal, $sum_bonusSalesAmount, $sum_cardSalesAmount, $sum_cardSalesCount, $sum_ordersCount, $sum_ordersWithCardCount, $sum_revenueFactSum, $sum_revenuePlanSum, $sum_salesRevenue, $sum_salesTotal){
		
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
		
		echo '<p><br></p>';		
		
		echo'<ul id="mv_accordion" class="mv_accordion">';
		
		foreach ($mv_report_result->reportList as $tr):
		echo'<li>';  $mv_revenueFactSum = $tr->salesRevenue + $tr->bonusPayTotal - $tr->bonusSalesAmount - $tr->cardSalesAmount;
		$gradient = ($tr->revenuePlanSum > 0) ? ( 100 * $tr->revenueFactSum / $tr->revenuePlanSum ) : 0; //$mv_revenueFactSum
		echo'<div class="mv_handle" title="' . number_format($gradient, 1, ',', ' ') . '%" style="background: linear-gradient(to right, #a2ddce 0%, #a2ddce '. $gradient .'%, rgba(255,255,255,1) '. $gradient .'%, rgba(255,255,255,1) 100%);">';
		if ($gradient >= 100 ) { 
		echo'<div class="org_name"><i class="fa fa-check-circle mv_aim mv_status_done" aria-hidden="true"></i>' . $tr->objectName . '</div>'; 
		} else {
		echo'<div class="org_name"><i class="fa fa-circle-o mv_aim" aria-hidden="true"></i>' . $tr->objectName . '</div>'; 
			}
		echo'<div class="sale_val">' . number_format($tr->salesRevenue, 2, ',', ' ') . '</div>'; //number_format($tr->revenueFactSum, 2, ',', ' ')
		echo'</div>';
		echo'<div class="panel g-cols vc_row wpb_row type_boxes">';
		
		echo'<div class="vc_col-sm-6 wpb_column vc_column_container">';
		echo'<div class="mv_table_margin">';
		
		echo'<table class="mv_102_report_table">';
		echo'<tbody>';
		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-money" aria-hidden="true"></i></td>';
		echo'<td style="border: none;"><strong>'.__('Сумма заказов', 'mv-web-reporter').'</strong></td>';
		echo'<td class="mv_align_right" style="border: none;" title="SalesTotal">' . number_format($tr->salesTotal, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Выручка (только кэш)', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="SalesRevenue">' . number_format($tr->salesRevenue, 2, ',', ' ') . '</td>';
		echo'</tr>';
		//echo'<tr>';
		//echo'<td style="border: none;"></td>';
		//echo'<td style="border: none;">'.__('Факт товарных продаж расчет.', 'mv-web-reporter').'</td>';
		//echo'<td style="border: none;" title="revenueFactSum ">' . number_format($mv_revenueFactSum, 2, ',', ' ') .'<span class="mv_small"> ' . number_format($gradient, 1, ',', ' ') .'%</span></td>';
		//echo'</tr>';
		
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Факт товарных продаж', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="revenueFactSum ">' . number_format($tr->revenueFactSum, 2, ',', ' ') .'<span class="mv_small"> ' . number_format($gradient, 1, ',', ' ') .'%</span></td>';
		echo'</tr>';
		
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('План товарных продаж', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="revenuePlanSum">' . number_format($tr->revenuePlanSum, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr class="mv_102_rt_border_top mv_102_rt_padding_top" >';
		echo'<td style="border: none;"><i class="fa fa-shopping-cart" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">'.__('Всего заказов', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="ordersCount">' . number_format($tr->ordersCount, 0, ',', ' ') . '</td>';
		echo'</tr>';

		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Заказов с картами', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="ordersWithCardCount">' . number_format($tr->ordersWithCardCount, 0, ',', ' ') . '</td>';
		echo'</tr>';

		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-ticket" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">'.__('Средний чек', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="avgCheckValue">' . number_format($tr->avgCheckValue, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Плановый средний чек', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="revenuePlanAvgCheck">' . number_format($tr->revenuePlanAvgCheck, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Фактический средний чек', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="revenueFactAvgCheck">' . number_format($tr->revenueFactAvgCheck, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'</tbody>';
		echo'</table>';
		echo'</div>';		
		echo'</div>';
		
		echo'<div class="vc_col-sm-6 wpb_column vc_column_container">';
		echo'<div class="mv_table_margin">';
		
		echo'<table class="mv_102_report_table">';
		echo'<tbody>';
		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-credit-card" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">'.__('Карт продано на сумму', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="cardSalesAmount">' . number_format($tr->cardSalesAmount, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Кол-во проданных карт', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="cardSalesCount">' . $tr->cardSalesCount . '</td>';
		echo'</tr>';

		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-diamond" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">'.__('Бонусов продано + кэшбэк', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="bonusAddTotal">' . number_format($tr->bonusAddTotal, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Бонусов продано', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="bonusSalesAmount">' . number_format($tr->bonusSalesAmount, 0, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Бонусов списано', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="BonusPayTotal">' . number_format($tr->bonusPayTotal, 0, ',', ' ') . '</td>';
		echo'</tr>';

		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-angellist" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">'.__('Общий кэшбэк', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;">' . number_format((($tr->cardSalesAmount * 0.15) + ( $tr->bonusSalesAmount * 0.15) ), 2, ',', ' '). '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Кэшбэк (с бонусов)', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;">' . number_format(( $tr->bonusSalesAmount * 0.15), 2, ',', ' ')  . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Кэшбек (с проданных карт)', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;">' . number_format(($tr->cardSalesAmount * 0.15), 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'</tbody>';
		echo'</table>';
		echo'</div>';		
		echo'</div>';
		echo'</div>';
		echo'</li>';
		endforeach;
		
	echo'<script type="text/javascript">';
	echo'jQuery( function( $ ){ $("#mv_accordion").mv_accordion({ "canToggle": true, "canOpenMultiple": true, handle: ".mv_handle" }); })';
	echo'</script>';
	echo'</ul>';

		
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
	//PC::debug( $html );
	} 
?>