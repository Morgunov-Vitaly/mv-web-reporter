<?php
	/*
		
		Конструктор 119 отчета CSCL CardInfo Информация по картам лояльности  
		
	*/
	
	function mv_119_report_constructor($mv_report_result){
		
		/**
			функция находит требуемый элемент массива
		*/
		
		ob_start();
		/* заголовок */
		echo '<h4>' . __('Детализация по', 'mv-web-reporter') . '&nbsp;<strong>' . __('карте', 'mv-web-reporter') . ' CSCL:</strong></h4>';
		
		/* адаптивные блоки №1 и №2 */
		echo'<div class="g-cols vc_row wpb_row vc_row-fluid">';
		/* адаптивный блок №1 */
		echo'<div class="vc_col-sm-6 wpb_column vc_column_container">';		
		
		echo '<p class="mv_card_info"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/cscl-card.svg" title="' . __('Карта лояльности', 'mv-web-reporter') . '"> No: ' . $mv_report_result->number . '</p>'; /* number */
		echo '<p class="mv_card_info"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/pin-key.svg" title="' . __('Pin код', 'mv-web-reporter') . '"> PIN: ' . $mv_report_result->pin . '</p>'; /* pin */
		echo '<p class="mv_card_info"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/user.svg" title="' . __('Держатель', 'mv-web-reporter') . '"> ' . $mv_report_result->holder . '</p>'; /* holder */

 /* Состояние карты */
 		echo '<p class="mv_card_info">';
		if ($mv_report_result->isBlocked) { /* isBlocked */
				echo '<img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/block.svg" title="' . __('Заблокирована', 'mv-web-reporter') . '">'; 
			} 
		if ( $mv_report_result->isActive ) { /* isActive */
				echo '<img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/ok.svg" title="' . __('Активна', 'mv-web-reporter') . '">'; 
			}
 		echo ' '. __('Состояние карты', 'mv-web-reporter') . ': ';
		if ( $mv_report_result->isActive ) { /* isActive */
			echo ' ' .  __('Активна', 'mv-web-reporter') . ' '; 
			}
		if ($mv_report_result->isBlocked) { /* isBlocked */
			echo ' ' . __('Заблокирована', 'mv-web-reporter') . ' ';
			} 
		
		echo '</p>
		</div>';	
		/* адаптивный блок №2 */		
		echo'<div class="vc_col-sm-6 wpb_column vc_column_container">';		

		echo '<p class="mv_card_info"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/organization.svg" title="' . __('Организация', 'mv-web-reporter') . '"> '.__('Организация', 'mv-web-reporter').': ' . $mv_report_result->organization . '</p>'; /* organization */
		echo '<p class="mv_card_info"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/folder.svg" title="' . __('Категория', 'mv-web-reporter') . '"> '.__('Категория', 'mv-web-reporter').': ' . $mv_report_result->category . '</p>'; /* category */

		echo '<p class="mv_card_info"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/inbox.svg" title="' . __('Начальный баланс', 'mv-web-reporter') . '"> '.__('Начальный баланс', 'mv-web-reporter').': ' . $mv_report_result->initialBalance . '</p>'; /* initialBalance  */
		$mv_dateLastUsing = new DateTime($mv_report_result->dateLastUsing);
		echo '<p class="mv_card_info"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/time-1.svg"> '.__('Дата последней операции', 'mv-web-reporter').': ' . date_format( $mv_dateLastUsing, 'd.m.Y H:i:s') . '</p>'; /* dateLastUsing */		
		
		echo '</div>'; 
		echo '</div>'; 		
		/* / адаптивные блоки №1 и №2 */

/* БАЛАНС */
		echo '<p class="mv_balance"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/jewels.svg"> '.__('Баланс', 'mv-web-reporter').': ' . number_format($mv_report_result->balance,  2, ',', ' ') . '</p>'; /* balance */
		
		echo '<p> </p>'; 
		// end Выводим заголовок отчета
		
		echo'<ul id="mv_accordion" class="mv_accordion">';
		
		foreach ($mv_report_result->transactions as $tr):
		/* echo'<li>'; */
		
		echo'<li>';

		echo'<div class="mv_handle">'; /* "Держатель" акаордеона */
		
		echo'<div class="g-cols  vc_row wpb_row vc_row-fluid">';
		/* блок иконки чека, номера заказа и ФИО */
		echo'<div class="mv_header_1 vc_col-lg-7  vc_col-sm-6  vc_col-xs-12 wpb_column vc_column_container"><img class="mv_img_label" src="' . plugin_dir_url( __FILE__ ) . '../img/receipt-green.svg">  ' . $tr->name . '</div>'; /* Название и код операции убрал коды . ' | Code: <span>' . $tr->code . '</span> */
		
		/* блок иконок индикаторов */
		echo'<div class="mv_header_2 vc_col-lg-2  vc_col-sm-2  vc_col-xs-6 wpb_column vc_column_container">
		<p class="mv_header_2_content">';	
		
		if ( (($tr->bonusAdd != 0) &&($tr->bonusAdd != '')) ) {echo'  <img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/income.svg" title="' . __('Бонусы добавлены', 'mv-web-reporter') . '">'; }  /* luxury-2.svg */
		
		/* индикатор Бонусы списаны */
		if (($tr->bonusRemove != 0) &&($tr->bonusRemove != '') ) {echo'  <img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/outgo.svg" title="' . __('Бонусы списаны', 'mv-web-reporter') . '">'; }  /* luxury-2.svg */		
		
		echo'</p>';
		echo'</div>'; /* / mv_header_2*/		
		/* /блок иконок индикаторов */
		
		/* блок суммы и даты */
		$mv_date = new DateTime($tr->date);
		
		echo'<div class="mv_header_3 vc_col-lg-3  vc_col-sm-4  vc_col-xs-6 wpb_column vc_column_container">' . number_format($tr->orderValue,  2, ',', ' ') . '<br><span class="mv_date"><i class="fa fa-calendar" aria-hidden="true"></i>  '. date_format($mv_date, 'd.m.Y H:i:s') . '</span></div>'; 
		
		echo'</div>'; /* /g-cols */
		echo'</div>'; /* /mv_handle */
		
		/* 
			
			Блок с данными в скрытом содержимом аккардеона  
			
		*/
		echo'<div class="panel">';
		
		/* 
			
			Таблица - Общие сведения 
			
		*/
		
		echo'<table class="mv_119_report_table">';
		echo'<tbody>';
		/* Шапка таблицы позиций */
		
		/* Даты */
		echo'<tr>';
		echo'<td class="mv_icon_row"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/coffeeshop.svg" title="' . __('Заведение', 'mv-web-reporter') . '"></td>'; /* calendar-rate.svg */
		echo'<td style="border: none;">'.__('Заведение', 'mv-web-reporter').': ' . $tr->division. '</td>';
		echo'</tr>';
		
		/* Сумма операций с бонусами */
		echo'<tr>';
		echo'<td class="mv_icon_row"><img class="mv_img_indicators"';
		if ( (($tr->bonusAdd == 0) || ($tr->bonusAdd == '')) && (($tr->bonusRemove == 0) ||($tr->bonusRemove == '')) ) {     
			echo'style="opacity: 0.2;"';
		}		
		echo' src="' . plugin_dir_url( __FILE__ ) . '../img/jewels.svg"></td>';		
		echo'<td style="border: none;">'.__('Бонусы', 'mv-web-reporter'). ': ' . number_format(($tr->bonusAdd - $tr->bonusRemove),  2, ',', ' ') . '</td>'; /* luxury-2.svg */
		echo'</tr>';
		
		/* Операции с бонусами  добавление */
		if (($tr->bonusAdd != 0) || ($tr->bonusAdd != '')) { 		
			echo'<tr>';
			echo'<td class="mv_icon_row"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/income.svg"></td>';
			echo'<td style="border: none;">'.__('Бонусов добавлено', 'mv-web-reporter') . ': ' . number_format($tr->bonusAdd,  2, ',', ' ') . '</td>';
			echo'</tr>';
		}	
		/* Операции с бонусами  убавление */
		if (($tr->bonusRemove != 0) || ($tr->bonusRemove != '') ) { 		
			echo'<tr>';
			echo'<td class="mv_icon_row"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/outgo.svg"></td>';
			echo'<td style="border: none;">'.__('Бонусов списано', 'mv-web-reporter') . ': ' . number_format($tr->bonusRemove,  2, ',', ' ') . '</td>';
			echo'</tr>';
		}			
		echo'</tbody>';
		echo'</table>';
		
		
		/* Таблица позиций */
		echo'<table class="mv_119_report_table">';
		echo'<tbody>';
		/* Шапка таблицы позиций */
		echo'<tr class="mv_119_datatable_header" >';
		echo'<td class="mv_align_center" style="border: none;">' .__('Наименование', 'mv-web-reporter') . '</td>';
		echo'<td class="mv_align_center" style="border: none;">'.__('Цена', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_center" style="border: none;">'.__('Количество', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_center" style="border: none;">'.__('Сумма', 'mv-web-reporter').'</td>';		
		echo'</tr>';
		
		$mv_total = 0; /* переменная суммы */
		
		/* Цикл  вывода позиций */
		foreach ($tr->orderDetails as $od):
		echo'<tr class="mv_119_rt_border_top" >';
		echo'<td style="border: none;">' . $od->name . '</td>';
		echo'<td class="mv_align_right" style="border: none;">'. number_format($od->priceRelease,  2, ',', ' ') .'</td>';
		echo'<td class="mv_align_center" style="border: none;">'. number_format($od->qty,  1, ',', ' ') .'</td>';
		echo'<td class="mv_align_right" style="border: none;">'. number_format(($od->qty * $od->priceRelease),  2, ',', ' ') .'</td>';
		$mv_total = $mv_total + ($od->qty * $od->priceRelease);
		echo'</tr>';
		endforeach;
		/* /Цикл  вывода позиций  */
		/* строка итогов */
		echo'<tr class="mv_119_datatable_total" >';
		echo'<td colspan="2" style="border: none;">' .__('Итого', 'mv-web-reporter') . ':</td>';
/*		echo'<td style="border: none;"> </td>';
		echo'<td style="border: none;"> </td>'; */
		echo'<td colspan="2" class="mv_align_right" style="border: none;">' . number_format($mv_total,  2, ',', ' ') . '</td>';		
		echo'</tr>';		
		
		/* / строка итогов */
	
		echo'</tbody>';
		echo'</table>';
		/* / Таблица позиций */		
		
		echo'<p></p>'; /*добавим пустое пространство*/			
		echo'</div>'; /* / panel */
		echo'</li>';
		endforeach;

		echo'<script type="text/javascript">';
		echo'jQuery("#mv_accordion").mv_accordion({ "canToggle": true, "canOpenMultiple": true, handle: ".mv_handle" });';
		echo'</script>';
		echo'</ul>';
		
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
	
	function mv_119_extra_options_html($mv_url_param){
		ob_start();
		/* Блок вывода дополнительных параметров отчета */
		echo'<div class="g-cols vc_row wpb_row vc_row-fluid">';
		
		/* адаптивный блок №1 */
		echo'<div class="vc_col-sm-8 wpb_column vc_column_container">';				
		echo'<input class="mv_input_order_number" type="text" name="mv_cscl_csrd_number" id="mv_cscl_csrd_number" value="' . esc_attr( $mv_url_param )   . '" placeholder="CSCL-card No:" data-required="true" aria-required="true">'; /* Mv 242724781552 или 1080824*/
		echo'</div>'; 		
		/* адаптивный блок №2 */
		echo'<div class="vc_col-sm-2 wpb_column vc_column_container">';	
		echo'<a id="mv_find_number" class="w-btn style_solid color_primary icon_atleft" href="#"><i class="fa fa-search" aria-hidden="true"></i><span class="w-btn-label">' . __('Найти', 'mv-web-reporter') . '</span></a>';
		echo'</div>';		
		/* адаптивный блок №3 */
		echo'<div class="vc_col-sm-2 wpb_column vc_column_container">';			
		echo'<a id="mv_clear_number" class="w-btn style_solid color_secondary icon_atleft" href="#"><i class="fa fa-eraser" aria-hidden="true"></i><span class="w-btn-label">' . __('Очистить', 'mv-web-reporter') . '</span></a>';
		echo'</div>';
		
		echo'</div>'; 		
		
		echo '<script>

		
		/* обработка поля поиска по номеру карты лояльности  */		
		jQuery("#mv_find_number").click(function(){ /* клик по кнопке Найти */
			if (document.getElementById("mv_cscl_csrd_number").value != "" ) { /* поле парметра номера карты не должно быть пустым */
				mv_document_ready = mv_document_ready + 1;
				jQuery("#form_param").submit(); //Отправляем данные формы "Субмитим"
			} else {
				if ( document.getElementById("mv_cscl_csrd_number").value == "" ){ /* если поле парметра пустое, то просто очищаем все элементы от класса mv_no_cscl_card_marker - отображаем их */
					jQuery(".mv_no_cscl_card_marker").removeClass("mv_no_cscl_card_marker");
				}
			}
			; 
		});
		
		jQuery("#mv_clear_number").click(function(){ /* клик по кнопке Очистить */
			document.getElementById("mv_cscl_csrd_number").value = "";/* очищаем поле строки поиска */
			jQuery(".mv_no_cscl_card_marker").removeClass("mv_no_cscl_card_marker"); /* очищаем все элементы от класса mv_no_cscl_card_marker - отображаем их  */
		});';
		echo '</script>';
		
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
	}	
?>