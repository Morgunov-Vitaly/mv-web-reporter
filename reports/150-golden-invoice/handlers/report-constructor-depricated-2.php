<?php
	/*
		
		Конструктор 150 отчета Золотой чек 
		
	*/
	
	function mv_150_report_constructor($mv_report_result){
		
		/**
			функция находит требуемый элемент массива
			* @param $mv_att - исходный объект $tr->purchaseCategoryInfo или $mv_report_result->employeeSummary[1]->purchaseCategoryInfo;
			* @param $mv_key - название ключа, например, 'category' 
			* @param $mv_vol - значение ключа, например, 'Coffee', 'Drink', 'Food', 'Others' 
			* @$mv_qty - передаем значение кол-ва
		*/		
		
		function mv_take_val_with_key ($mv_att, $mv_key, $mv_vol, &$mv_qty){
			$mv_rez_str="";
			foreach ($mv_att as $key => $value ) {
				if ($value->$mv_key == $mv_vol) { // например, если [0].category == Coffee
					$mv_rez_str = number_format(($value->qty), 0, ',', ' ');
					$mv_qty = $mv_qty + $value->qty;
					return $mv_rez_str;
					break;
				}
			}
			unset($value);
			unset($key);
		}
		ob_start();
		
		// start Выводим заголовок отчета: дата от до, название организации 
		echo '<p style="display: inline;">'.__('Организация', 'mv-web-reporter').': </p><p id="displayorgname" style="display: inline;"></p>';
		echo '<p style="display: inline;"><br>'.__('Дата от', 'mv-web-reporter').': </p><p id="displaydatefrom" style="display: inline;"></p>';
		echo '<p style="display: inline;"> / '.__('Дата по', 'mv-web-reporter').': </p><p id="displaydateto" style="display: inline;"></p>';
		// end Выводим заголовок отчета: дата от до, название организации 

		
		
		
		echo '<!-- Шапка таблицы -->';
		$mv_report150_head_mobile = '<div class="g-cols  vc_row wpb_row vc_row-fluid mv_mobile_view"><div class="report_header_orgname vc_col-sm-5  vc_col-xs-12 wpb_column vc_column_container" ><p class ="mv_orgname">' . $mv_report_result->employeeSummary[0]->divisionName . '</p></div>
		<div class="report_header  vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/coffee2.svg"><br>' .__('Кофе', 'mv-web-reporter') . '</p></div>
		<div class="report_header  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/drinks.svg"><br>' .__('Напитки', 'mv-web-reporter') . '</p></div>
		<div class="report_header  vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/food.svg"><br> ' .__('Еда', 'mv-web-reporter') . '</p></div>
		<div class="report_header  vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/receipt.svg"><br>' .__('Чеков', 'mv-web-reporter') . '</p></div>
		<div class="report_header  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/rate.svg"><br>' .__('Рейтинг', 'mv-web-reporter') . '</p></div></div>';
		
		echo '<div class="g-cols  vc_row wpb_row vc_row-fluid">
		<div class="report_header_orgname vc_col-sm-5  vc_col-xs-12 wpb_column vc_column_container" ><p class ="mv_orgname">' . $mv_report_result->employeeSummary[0]->divisionName . '</p>
		</div>
		<div class="report_header  vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/coffee2.svg"><br>' .__('Кофе', 'mv-web-reporter') . '</p>
		</div>
		<div class="report_header  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/drinks.svg"><br>' .__('Напитки', 'mv-web-reporter') . '</p>
		</div>
		<div class="report_header  vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/food.svg"><br> ' .__('Еда', 'mv-web-reporter') . '</p>
		</div>
		<div class="report_header  vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/receipt.svg"><br>' .__('Чеков', 'mv-web-reporter') . '</p>
		</div>
		<div class="report_header  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/rate.svg"><br>' .__('Рейтинг', 'mv-web-reporter') . '</p>
		</div>		
		</div>';
		echo '<!-- Данные по сотрудникам -->';
		$mv_index = 0;
		$mv_multiplicator = 5; // мультипликатор - через сколько записей повторять заголовок

		$mv_total_coffee_qty = 0;
		$mv_total_drink_qty = 0;
		$mv_total_food_qty = 0;
		$mv_total_invoices_qty = 0;
		
		
		foreach ($mv_report_result->employeeSummary as $tr){
			
			if (($mv_index == $mv_multiplicator)||($mv_index == ($mv_multiplicator * 2))||($mv_index == ($mv_multiplicator * 3))||($mv_index == ($mv_multiplicator * 4))||($mv_index == ($mv_multiplicator * 5))||($mv_index == ($mv_multiplicator * 6))||($mv_index == ($mv_multiplicator * 7))||($mv_index == ($mv_multiplicator * 8))||($mv_index == ($mv_multiplicator * 9))||($mv_index == ($mv_multiplicator * 10))){ $mv_index = $mv_index + 1; echo $mv_report150_head_mobile; } else {$mv_index = $mv_index + 1;}
			$mv_str="";
			
			
			echo '<div class="g-cols  vc_row wpb_row vc_row-fluid">';
			
			echo '<div class="report_data_emp_name  vc_col-sm-5  vc_col-xs-12 wpb_column vc_column_container" >';
			echo '<div class="mv_employee_name">' . $tr->author . '</div>';
			echo '</div>';
			
			$mv_str = mv_take_val_with_key($tr->productInfo, 'category', 'Coffee', $mv_total_coffee_qty);
			if ($mv_str != ""){
				echo '<div class="report_data vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" >';
				echo '<div class="mv_coffee">' . $mv_str  . '</div>';
				echo '</div>';
				} else {
				echo '<div class="report_data vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" >';
				echo '<div class="mv_coffee">0</div>';
				echo '</div>';
			}
			
			$mv_str =  mv_take_val_with_key($tr->productInfo, 'category', 'Drink', $mv_total_drink_qty);
			if ($mv_str != ""){
				echo '<div class="report_data vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >';
				echo '<div class="mv_drinks">' . $mv_str . '</div>';
				echo '</div>';
				} else {
				echo '<div class="report_data vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >';
				echo '<div class="mv_drinks">0</div>';
				echo '</div>';
			}
			
			$mv_str = mv_take_val_with_key($tr->productInfo, 'category', 'Food', $mv_total_food_qty);
			if ($mv_str != ""){
				echo '<div class="report_data vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" >';
				echo '<div class="mv_food">' . $mv_str . '</div>';
				echo '</div>';
				} else {
				echo '<div class="report_data vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" >';
				echo '<div class="mv_food">0</div>';
				echo '</div>';
			}
			
			if ($tr->rate != ""){
				echo '<div class="report_data vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" >';
				echo '<div class="mv_rate">' . number_format(($tr->sumInvoiceQty), 0, ',', ' ') . '</div>'; 
				echo '</div>';				
				echo '<div class="report_data vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >';
				echo '<div class="mv_rate">' . number_format(($tr->rate), 1, ',', ' ') . '</div>'; 
				echo '</div>';
				} else {
				echo '<div class="report_data vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" >';
				echo '<div class="mv_rate">' . number_format(($tr->sumInvoiceQty), 0, ',', ' ') . '</div>'; 
				echo '</div>';				
				echo '<div class="report_data vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >';
				echo '<div class="mv_rate">0</div>';
				echo '</div>';
			}
			$mv_total_invoices_qty = $mv_total_invoices_qty + $tr->sumInvoiceQty; /* Подсчитываем общее количество чеков */
			echo '</div>';
		}
		$mv_total_total = $mv_total_coffee_qty + $mv_total_drink_qty + $mv_total_food_qty;
		/* Итоговое значение */
echo'<div class="g-cols  vc_row wpb_row vc_row-fluid">
<div class="report_footer_total vc_col-sm-5  vc_col-xs-12 wpb_column vc_column_container" >' . __('Итого', 'mv-web-reporter') . ':</div>
<div class="report_footer  vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" >'. number_format(($mv_total_coffee_qty), 0, ',', ' '). '</div>
<div class="report_footer  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >'. number_format(($mv_total_drink_qty), 0, ',', ' ') . '</div>
<div class="report_footer  vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" >'. number_format(($mv_total_food_qty), 0, ',', ' ') . '</div>
<div class="report_footer  vc_col-sm-1 vc_col-xs-2 wpb_column vc_column_container" >'. number_format(($mv_total_invoices_qty), 0, ',', ' ') . '</div>
<div class="report_footer  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >-</div>
</div>';		
		
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
		//PC::debug( $html );
	} 
	
?>