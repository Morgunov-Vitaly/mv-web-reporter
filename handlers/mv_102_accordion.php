<?php
	/*
		
		Конструктор  102 отчета в виде аккардеона
		
	*/
	function mv_102_accordion_constructor( $mv_report_result){
		
		ob_start();
		echo'<ul id="mv_accordion" class="mv_accordion">';
	
		foreach ($mv_report_result->ReportList as $tr):
		echo'<li>';
		echo'<div class="mv_handle">';
		echo'<div class="org_name"><i class="fa fa-bullseye mv_aim ' . '" aria-hidden="true"></i>' . $tr->ObjectName . '</div>';
		echo'<div class="sale_val">' . $tr->SalesTotal . '</div>';
		echo'</div>';
		echo'<div class="panel g-cols vc_row wpb_row vc_row-fluid">';
		echo'<div class="vc_col-sm-6 wpb_column vc_column_container">';
		echo'<table style="width: 90%; border: none;">';
		echo'<tbody>';
		echo'<tr>';
		echo'<td style="border: none;"><i class="fa fa-money" aria-hidden="true"></i></td>';
		echo'<td style="border: none;"><strong>Сумма заказов </strong></td>';
		echo'<td style="border: none;">' . $tr->SalesTotal . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Выручка (только кэш)</td>';
		echo'<td style="border: none;">' . $tr->SalesRevenue . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Факт товарных продаж</td>';
		echo'<td style="border: none;">' . ($tr->SalesRevenue + $tr->BonusPayTotal - $tr->BonusSalesAmount - $tr->CardSalesAmount) . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"><i class="fa fa-shopping-cart" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">Всего заказов</td>';
		echo'<td style="border: none;">' . $tr->OrdersCount . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;"></td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Заказов с картами</td>';
		echo'<td style="border: none;">' . $tr->OrdersWithCardCount . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;"></td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"><i class="fa fa-ticket" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">Средний чек</td>';
		echo'<td style="border: none;">' . $tr->AvgCheckValue . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Плановый средний чек</td>';
		echo'<td style="border: none;">' . $tr->RevenuePlanAvgCheck . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Фактический средний чек</td>';
		echo'<td style="border: none;">' . $tr->RevenueFactAvgCheck . '</td>';
		echo'</tr>';
		echo'</tbody>';
		echo'</table>';
		echo'</div>';
		echo'<div class="vc_col-sm-6 wpb_column vc_column_container">';
		echo'<table style="width: 90%; border: none;">';
		echo'<tbody>';
		echo'<tr>';
		echo'<td style="border: none;"><i class="fa fa-credit-card" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">Карт продано на сумму</td>';
		echo'<td style="border: none;">' . $tr->CardSalesAmount . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Кол-во проданных карт</td>';
		echo'<td style="border: none;">' . $tr->CardSalesCount . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;"></td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Бонусов продано + кэшбэк</td>';
		echo'<td style="border: none;">' . $tr->BonusAddTotal . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"><i class="fa fa-diamond" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">Бонусов продано</td>';
		echo'<td style="border: none;">' . $tr->BonusSalesAmount . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Бонусов списано</td>';
		echo'<td style="border: none;">' . $tr->BonusPayTotal . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;"></td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"><i class="fa fa-angellist" aria-hidden="true"></i></td>';
		echo'<td style="border: none;">Общий кэшбэк</td>';
		echo'<td style="border: none;">' . (($tr->CardSalesAmount * 0.15) + ( $tr->BonusSalesAmount * 0.15) ). '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Кэшбэк (с бонусов)</td>';
		echo'<td style="border: none;">' . ( $tr->BonusSalesAmount * 0.15)  . '</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<td style="border: none;"></td>';
		echo'<td style="border: none;">Кэшбек (с проданных карт)</td>';
		echo'<td style="border: none;">' . ($tr->CardSalesAmount * 0.15) . '</td>';
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