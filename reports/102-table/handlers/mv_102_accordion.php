<?php
	/*
		
		Конструктор  102 отчета в виде аккардеона
		цвет был #f4faf8 #ccffe3
		
	*/
	function mv_102_accordion_constructor( $mv_report_result){
		
		ob_start();
		// start Выводим заголовок отчета: дата от до, название организации 
		echo '<p style="display: inline;">'.__('Организация: ', 'mv-web-reporter').'</p><p id="displayorgname" style="display: inline;"></p>';
		echo '<p style="display: inline;"><br>'.__('Дата от: ', 'mv-web-reporter').'</p><p id="displaydatefrom" style="display: inline;"></p>';
		echo '<p style="display: inline;"> / '.__('Дата по: ', 'mv-web-reporter').'</p><p id="displaydateto" style="display: inline;"></p>';
		echo '<p><br></p>';
		// end Выводим заголовок отчета: дата от до, название организации 
		echo'<ul id="mv_accordion" class="mv_accordion">';
		
		foreach ($mv_report_result->ReportList as $tr):
		echo'<li>';  $mv_revenueFactSum = $tr->SalesRevenue + $tr->BonusPayTotal - $tr->BonusSalesAmount - $tr->CardSalesAmount;
		$gradient = ($tr->RevenuePlanSum > 0) ? ( 100 * $tr->RevenueFactSum / $tr->RevenuePlanSum ) : 0; //$mv_revenueFactSum
		echo'<div class="mv_handle" title="' . number_format($gradient, 1, ',', ' ') . '%" style="background: linear-gradient(to right, #a2ddce 0%, #a2ddce '. $gradient .'%, rgba(255,255,255,1) '. $gradient .'%, rgba(255,255,255,1) 100%);">';
		if ($gradient >= 100 ) { 
		echo'<div class="org_name"><i class="fa fa-check-circle mv_aim mv_status_done" aria-hidden="true"></i>' . $tr->ObjectName . '</div>'; 
		} else {
		echo'<div class="org_name"><i class="fa fa-circle-o mv_aim" aria-hidden="true"></i>' . $tr->ObjectName . '</div>'; 
			}
		echo'<div class="sale_val">' . number_format($tr->SalesRevenue, 2, ',', ' ') . '</div>'; //number_format($tr->RevenueFactSum, 2, ',', ' ')
		echo'</div>';
		echo'<div class="panel g-cols vc_row wpb_row vc_row-fluid">';
		echo'<div class="vc_col-sm-6 wpb_column vc_column_container">';
		echo'<table class="mv_102_report_table">';
		echo'<tbody>';
		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-money" aria-hidden="true"></i></td>';
		echo'<td style="border: none;"><strong>'.__('Сумма заказов', 'mv-web-reporter').'</strong></td>';
		echo'<td class="mv_align_right" style="border: none;" title="SalesTotal">' . number_format($tr->SalesTotal, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Выручка (только кэш)', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="SalesRevenue">' . number_format($tr->SalesRevenue, 2, ',', ' ') . '</td>';
		echo'</tr>';
		//echo'<tr>';
		//echo'<td style="border: none;"></td>';
		//echo'<td style="border: none;">'.__('Факт товарных продаж расчет.', 'mv-web-reporter').'</td>';
		//echo'<td style="border: none;" title="RevenueFactSum ">' . number_format($mv_revenueFactSum, 2, ',', ' ') .'<span class="mv_small"> ' . number_format($gradient, 1, ',', ' ') .'%</span></td>';
		//echo'</tr>';
		
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Факт товарных продаж', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="RevenueFactSum ">' . number_format($tr->RevenueFactSum, 2, ',', ' ') .'<span class="mv_small"> ' . number_format($gradient, 1, ',', ' ') .'%</span></td>';
		echo'</tr>';
		
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('План товарных продаж', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="RevenuePlanSum">' . number_format($tr->RevenuePlanSum, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr class="mv_102_rt_border_top mv_102_rt_padding_top" >';
		echo'<td style="border: none;"><i class="fa fa-shopping-cart" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">'.__('Всего заказов', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="OrdersCount">' . number_format($tr->OrdersCount, 0, ',', ' ') . '</td>';
		echo'</tr>';

		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Заказов с картами', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="OrdersWithCardCount">' . number_format($tr->OrdersWithCardCount, 0, ',', ' ') . '</td>';
		echo'</tr>';

		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-ticket" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">'.__('Средний чек', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="AvgCheckValue">' . number_format($tr->AvgCheckValue, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Плановый средний чек', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="RevenuePlanAvgCheck">' . number_format($tr->RevenuePlanAvgCheck, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Фактический средний чек', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="RevenueFactAvgCheck">' . number_format($tr->RevenueFactAvgCheck, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'</tbody>';
		echo'</table>';
		echo'</div>';
		echo'<div class="vc_col-sm-6 wpb_column vc_column_container">';
		echo'<table class="mv_102_report_table">';
		echo'<tbody>';
		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-credit-card" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">'.__('Карт продано на сумму', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="CardSalesAmount">' . number_format($tr->CardSalesAmount, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Кол-во проданных карт', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="CardSalesCount">' . $tr->CardSalesCount . '</td>';
		echo'</tr>';

		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-diamond" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">'.__('Бонусов продано + кэшбэк', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="BonusAddTotal">' . number_format($tr->BonusAddTotal, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Бонусов продано', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="BonusSalesAmount">' . number_format($tr->BonusSalesAmount, 0, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Бонусов списано', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;" title="BonusPayTotal">' . number_format($tr->BonusPayTotal, 0, ',', ' ') . '</td>';
		echo'</tr>';

		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-angellist" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">'.__('Общий кэшбэк', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;">' . number_format((($tr->CardSalesAmount * 0.15) + ( $tr->BonusSalesAmount * 0.15) ), 2, ',', ' '). '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Кэшбэк (с бонусов)', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;">' . number_format(( $tr->BonusSalesAmount * 0.15), 2, ',', ' ')  . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">'.__('Кэшбек (с проданных карт)', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_right" style="border: none;">' . number_format(($tr->CardSalesAmount * 0.15), 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'</tbody>';
		echo'</table>';
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