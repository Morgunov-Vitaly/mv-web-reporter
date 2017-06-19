<?php
	/*
		
		Конструктор  102 отчета в виде аккардеона
		цвет был #f4faf8 #ccffe3
		
	*/
	function mv_102_accordion_constructor( $mv_report_result){
		
		ob_start();
		echo'<ul id="mv_accordion" class="mv_accordion">';
	
		foreach ($mv_report_result->ReportList as $tr):
		echo'<li>';  $mv_revenueFactSum = $tr->SalesRevenue + $tr->BonusPayTotal - $tr->BonusSalesAmount - $tr->CardSalesAmount;
		$gradient = ($tr->RevenuePlanSum > 0) ? ( 100 * $tr->RevenueFactSum / $tr->RevenuePlanSum ) : 0; //$mv_revenueFactSum
		echo'<div class="mv_handle" style="background: linear-gradient(to right, #a2ddce 0%, #a2ddce '. $gradient .'%, rgba(255,255,255,1) '. $gradient .'%, rgba(255,255,255,1) 100%);">';
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
		echo'<td style="border: none;"><strong>Сумма заказов </strong></td>';
		echo'<td style="border: none;" title="SalesTotal">' . number_format($tr->SalesTotal, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Выручка (только кэш)</td>';
		echo'<td style="border: none;" title="SalesRevenue">' . number_format($tr->SalesRevenue, 2, ',', ' ') . '</td>';
		echo'</tr>';
		//echo'<tr>';
		//echo'<td style="border: none;"></td>';
		//echo'<td style="border: none;">Факт товарных продаж расчет.</td>';
		//echo'<td style="border: none;" title="RevenueFactSum ">' . number_format($mv_revenueFactSum, 2, ',', ' ') .'<span class="mv_small"> ' . number_format($gradient, 1, ',', ' ') .'%</span></td>';
		//echo'</tr>';
		
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Факт товарных продаж</td>';
		echo'<td style="border: none;" title="RevenueFactSum ">' . number_format($tr->RevenueFactSum, 2, ',', ' ') .'<span class="mv_small"> ' . number_format($gradient, 1, ',', ' ') .'%</span></td>';
		echo'</tr>';
		
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">План товарных продаж</td>';
		echo'<td style="border: none;" title="RevenuePlanSum">' . number_format($tr->RevenuePlanSum, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr class="mv_102_rt_border_top mv_102_rt_padding_top" >';
		echo'<td style="border: none;"><i class="fa fa-shopping-cart" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">Всего заказов</td>';
		echo'<td style="border: none;" title="OrdersCount">' . $tr->OrdersCount . '</td>';
		echo'</tr>';

		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Заказов с картами</td>';
		echo'<td style="border: none;" title="OrdersWithCardCount">' . $tr->OrdersWithCardCount . '</td>';
		echo'</tr>';

		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-ticket" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">Средний чек</td>';
		echo'<td style="border: none;" title="AvgCheckValue">' . number_format($tr->AvgCheckValue, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Плановый средний чек</td>';
		echo'<td style="border: none;" title="RevenuePlanAvgCheck">' . number_format($tr->RevenuePlanAvgCheck, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Фактический средний чек</td>';
		echo'<td style="border: none;" title="RevenueFactAvgCheck">' . number_format($tr->RevenueFactAvgCheck, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'</tbody>';
		echo'</table>';
		echo'</div>';
		echo'<div class="vc_col-sm-6 wpb_column vc_column_container">';
		echo'<table class="mv_102_report_table">';
		echo'<tbody>';
		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-credit-card" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">Карт продано на сумму</td>';
		echo'<td style="border: none;" title="CardSalesAmount">' . number_format($tr->CardSalesAmount, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Кол-во проданных карт</td>';
		echo'<td style="border: none;" title="CardSalesCount">' . $tr->CardSalesCount . '</td>';
		echo'</tr>';

		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-diamond" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">Бонусов продано + кэшбэк</td>';
		echo'<td style="border: none;" title="BonusAddTotal">' . number_format($tr->BonusAddTotal, 2, ',', ' ') . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Бонусов продано</td>';
		echo'<td style="border: none;" title="BonusSalesAmount">' . $tr->BonusSalesAmount . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Бонусов списано</td>';
		echo'<td style="border: none;" title="BonusPayTotal">' . $tr->BonusPayTotal . '</td>';
		echo'</tr>';

		echo'<tr class="mv_102_rt_border_top" >';
		echo'<td style="border: none;"><i class="fa fa-angellist" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">Общий кэшбэк</td>';
		echo'<td style="border: none;">' . number_format((($tr->CardSalesAmount * 0.15) + ( $tr->BonusSalesAmount * 0.15) ), 2, ',', ' '). '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Кэшбэк (с бонусов)</td>';
		echo'<td style="border: none;">' . number_format(( $tr->BonusSalesAmount * 0.15), 2, ',', ' ')  . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Кэшбек (с проданных карт)</td>';
		echo'<td style="border: none;">' . number_format(($tr->CardSalesAmount * 0.15), 2, ',', ' ') . '</td>';
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