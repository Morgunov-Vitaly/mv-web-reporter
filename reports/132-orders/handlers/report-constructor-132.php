<?php
	/*
		
		Конструктор 132 отчета Золотой чек 
		
	*/
	
	function mv_132_report_constructor($mv_report_result , $mv_cscl_card_num){
		
		/**
			функция находит требуемый элемент массива
			* @param $mv_att - исходный объект $tr->purchaseCategoryInfo или $mv_report_result->employeeSummary[1]->purchaseCategoryInfo;
			* @param $mv_key - название ключа, например, 'category' 
			* @param $mv_vol - значение ключа, например, 'Coffee', 'Drink', 'Food', 'Others' 
			* @$mv_qty - передаем значение кол-ва
			* @$mv_cscl_card_num - параметр поиска по номеру карты лояльности 
		*/
		/*
			orders [
			0 {
			orderNumber:066992, Заказ №
			orderValue:604, Сумма (общая сумма заказа)
			cashPay:0, Наличные (Наличные)
			bankCardPay:604, Банк (Банковская карта)
			cashChange:0, Сдача
			dateOpen:2017-04-22T22:59:00, Открыт (заказ открыт)
			dateClose:2017-04-23T02:03:56, Закрыт (заказ закрыт)
			isReturn:false, Вовзрат
			author:Смаглюк Екатерина Николаевна,
			cardName { } ?? (Карта лояльности №)
			bonusAdd:0, (Бонусов добавлено)
			bonusRemove:0 (Бонусов списано)
			},
			orderDetails {
			
			0 {
			goodsName:Кофе Эспрессо (двойной), (Наименование товара)
			categoryName:Coffee,  (Наименование категории)
			qty:1, (Количество)
			price:155,  (Цена)
			priceRelease:155, (Цена реализации (или сумма))
			isBonusBuy:false,  (Куплены бонусы)
			isDiscount:false (Продано со скидкой)
			}
			
			
			
		*/
		
		ob_start();
		
		// start Выводим заголовок отчета: дата от до, название организации 
		echo '<p style="display: inline;">'.__('Организация', 'mv-web-reporter').': </p><p id="displayorgname" style="display: inline;"></p>';
		echo '<p style="display: inline;"><br>'.__('Дата от', 'mv-web-reporter').': </p><p id="displaydatefrom" style="display: inline;"></p>';
		echo '<p style="display: inline;"> / '.__('Дата по', 'mv-web-reporter').': </p><p id="displaydateto" style="display: inline;"></p>';
		// end Выводим заголовок отчета: дата от до, название организации 
		echo '<p> </p>'; 
		
		echo'<ul id="mv_accordion" class="mv_accordion">';
		
		foreach ($mv_report_result->orders as $tr):
		/* echo'<li>'; */
		
		/* добавляем классы - маркеры */
		
		echo'<li class="'; /* Номер заказа  mv_orderNumber_marker_  $tr->orderNumber */
		if ($mv_cscl_card_num != "") { /* если фильтр задан */
			if (strripos( $tr->cardName, $mv_cscl_card_num ) === false) { /* если номер карты текущей позиции хотябы частично не совпадает с искомым значением  */
				echo 'mv_no_cscl_card_marker ';
				} /* Если ведется поиск по номеру бонусной карты, то все что не подходит - скрываем через класс mv_no_cscl_card_marker */
		}
		if ( (($tr->bonusAdd != 0) &&($tr->bonusAdd != '')) || ($tr->bonusRemove != 0) &&($tr->bonusRemove != '') ) {echo'mv_bonus_marker '; } /* Оплата бонусами */		
		if ( $tr->isReturn ) { echo'mv_return_marker '; } /* Оформлен возврат */
		if ( $tr->cashPay > 0 ) { echo'mv_cash_marker ';}  /* Оплата налом */
		if ( $tr->bankCardPay > 0 ) { echo'mv_bankCardPay_marker ';} /* Оплата банковской картой */
		if ( $tr->cardName != "") { echo'mv_cscl_card_marker ';}  /* Оплата с использованием карты лояльности */
		
		echo'">';
		/* /добавляем классы - маркеры */		
		
		echo'<div class="mv_handle">'; /* "Держатель" акаордеона */
		
		echo'<div class="g-cols  vc_row wpb_row vc_row-fluid">';
		/* блок иконки чека, номера заказа и ФИО */
		echo'<div class="mv_header_1 vc_col-lg-7  vc_col-sm-6  vc_col-xs-12 wpb_column vc_column_container"><img class="mv_img_label" src="' . plugin_dir_url( __FILE__ ) . '../img/receipt-green.svg">   No: ' . $tr->orderNumber . ' | <span>' . $tr->author . '</span></div>'; /* убрал преобразование в число number_format($tr->orderNumber, 0, ',', '') */
		
		/* блок иконок индикаторов */
		echo'<div class="mv_header_2 vc_col-lg-2  vc_col-sm-2  vc_col-xs-6 wpb_column vc_column_container"><p class="mv_header_2_content">';	
		
		/* индикатор возврата */
		if ( $tr->isReturn ) {echo'  <img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/forbidden.svg" title="' . __('Оформлен возврат', 'mv-web-reporter') . '!">'; } 		
		
		/* индикатор налички */
		if ( $tr->cashPay > 0 ) {echo'  <img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/money.svg" title="' . __('Наличные', 'mv-web-reporter') . '">'; } /* coins.svg wallet.svg */
		
		/* индикатор банковской карты */
		if ( $tr->bankCardPay > 0 ) {echo'  <img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/credit-card.svg"title="' . __('Банковская карта', 'mv-web-reporter') . '">'; } 
		
		/* индикатор оплаты бонусами */
		
		if ( (($tr->bonusAdd != 0) &&($tr->bonusAdd != '')) || ($tr->bonusRemove != 0) &&($tr->bonusRemove != '') ) {echo'  <img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/jewels.svg" title="' . __('Бонусы', 'mv-web-reporter') . '">'; }  /* luxury-2.svg */
		
		/* индикатор использования карты лояльности */		
		if ( $tr->cardName != "") {echo' <img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/cscl-card.svg" title="' . __('Карта лояльности', 'mv-web-reporter') . '">';} 
		
		echo'</p>';
		echo'</div>'; /* / mv_header_2*/		
		/* /блок иконок индикаторов */
		
		/* блок суммы и даты */
		$date_open = new DateTime($tr->dateOpen);
		$date_close = new DateTime($tr->dateClose);
		echo'<div class="mv_header_3 vc_col-lg-3  vc_col-sm-4  vc_col-xs-6 wpb_column vc_column_container">' . number_format($tr->orderValue,  2, ',', ' ') . '<br><span class="mv_date"><i class="fa fa-calendar" aria-hidden="true"></i>  '. date_format($date_open, 'd.m.Y H:i:s') . '</span></div>'; /*  временно убрал . ' - ' . date_format($date_close, 'd.m.Y H:i:s') .    и убрал <img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/calendar-rate.svg"> */
		
		echo'</div>'; /* /g-cols */
		echo'</div>'; /* /mv_handle */
		
		/* 
			
			Блок с данными в скрытом содержимом аккардеона  
			
		*/
		echo'<div class="panel">';
		
		/* 
			
			Таблица - Общие сведения 
			
		*/
		
		echo'<table class="mv_132_report_table">';
		echo'<tbody>';
		/* Шапка таблицы позиций */
		
		/* Даты */
		echo'<tr>';
		echo'<td class="mv_icon_row"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/time-1.svg"></td>'; /* calendar-rate.svg */
		echo'<td style="border: none;">'.__('Заказ открыт', 'mv-web-reporter').': ' . date_format($date_open, 'd.m.Y H:i:s') . '</td>';
		echo'</tr>';
		
		echo'<tr>';
		echo'<td class="mv_icon_row"> </td>'; 
		echo'<td style="border: none;">'.__('Заказ закрыт', 'mv-web-reporter').': ' . date_format($date_close, 'd.m.Y H:i:s') . '</td>';
		echo'</tr>';
		
		/* индикатор возврата */
		if ( $tr->isReturn ) { 		
			echo'<tr>';
			echo'<td class="mv_icon_row"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/forbidden.svg"></td>';
			echo'<td style="border: none;">'.__('Оформлен возврат', 'mv-web-reporter').'!</td>';
			echo'</tr>';
		}		
		
		/* индикатор налички */
		echo'<tr>';
		echo'<td class="mv_icon_row"><img class="mv_img_indicators"';
		if ( ($tr->cashPay == 0)&&($tr->cashPay == '')  ) {     
			echo'style="opacity: 0.2;"';
		}		
		echo' src="' . plugin_dir_url( __FILE__ ) . '../img/money.svg"></td>';
		echo'<td style="border: none;">'.__('Наличные', 'mv-web-reporter'). ': ' . number_format($tr->cashPay,  2, ',', ' ') . '</td>'; /* coins.svg wallet.svg */
		echo'</tr>';			
		
		/* индикатор сдачи */
		if  (($tr->cashChange != 0) && ($tr->cashChange != '')) {   
			echo'<tr>';
			echo'<td class="mv_icon_row"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/coins.svg"></td>';		
			echo'<td style="border: none;">'.__('Сдача', 'mv-web-reporter'). ': ' . number_format($tr->cashChange,  2, ',', ' ') . '</td>'; 
			echo'</tr>';		
		}
		
		/* индикатор банковской карты */
		echo'<tr>';
		echo'<td class="mv_icon_row"><img class="mv_img_indicators"';
		if ( ($tr->bankCardPay == 0)&&($tr->bankCardPay == '')  ) {     
			echo'style="opacity: 0.2;"';
		}		
		echo' src="' . plugin_dir_url( __FILE__ ) . '../img/credit-card.svg"></td>';
		echo'<td style="border: none;">'.__('Банковская карта', 'mv-web-reporter'). ': ' . number_format($tr->bankCardPay,  2, ',', ' ') . '</td>'; 
		echo'</tr>';	
		
		
		/* индикатор оплаты бонусами */
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
		/* Данные карты лояльности */
		if (($tr->cardName != 0) || ($tr->cardName != '') ) { 		
			echo'<tr>';
			echo'<td class="mv_icon_row"><img class="mv_img_indicators" src="' . plugin_dir_url( __FILE__ ) . '../img/cscl-card.svg"></td>';
			echo'<td style="border: none;"><a class="mv_link_to_another_report" href="#">'.__('Карта лояльности CSCL', 'mv-web-reporter') . ' No: ' . $tr->cardName . '</a></td>';
			echo'</tr>';
		}			
		
		echo'</tbody>';
		echo'</table>';
		
		
		/* Таблица позиций */
		echo'<table class="mv_132_report_table">';
		echo'<tbody>';
		/* Шапка таблицы позиций */
		echo'<tr class="mv_132_datatable_header" >';
		echo'<td class="mv_align_center" style="border: none;">' .__('Наименование', 'mv-web-reporter') . '</td>';
		echo'<td class="mv_align_center" style="border: none;">'.__('Цена', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_center" style="border: none;">'.__('Количество', 'mv-web-reporter').'</td>';
		echo'<td class="mv_align_center" style="border: none;">'.__('Сумма', 'mv-web-reporter').'</td>';		
		echo'</tr>';
		
		$mv_total = 0; /* переменная суммы */
		
		/* Цикл  вывода позиций */
		foreach ($tr->orderDetails as $od):
		echo'<tr class="mv_132_rt_border_top" >';
		echo'<td style="border: none;">' . $od->goodsName . '</td>';
		echo'<td class="mv_align_right" style="border: none;">'. number_format($od->priceRelease,  2, ',', ' ') .'</td>';
		echo'<td class="mv_align_center" style="border: none;">'. number_format($od->qty,  1, ',', ' ') .'</td>';
		echo'<td class="mv_align_right" style="border: none;">'. number_format(($od->qty * $od->priceRelease),  2, ',', ' ') .'</td>';
		$mv_total = $mv_total + ($od->qty * $od->priceRelease);
		echo'</tr>';
		endforeach;
		/* /Цикл  вывода позиций  */
		/* строка итогов */
		echo'<tr class="mv_132_datatable_total" >';
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
	
	function mv_132_extra_options_html(){
		ob_start();
		/* Блок вывода дополнительных параметров отчета */
		echo '<p><input id="mv_check_cards" class="mv_report_addition_param" type="checkbox" checked="checked" name="mv_check_cards" />'.__('Оплата банковской картой', 'mv-web-reporter'). '<br><input id="mv_check_cash" class="mv_report_addition_param" type="checkbox" checked="checked" name="mv_check_cash" />' .__('Оплата наличными', 'mv-web-reporter') . '<br><input id="mv_check_bonus" class="mv_report_addition_param" type="checkbox" checked="checked" name="mv_check_bonus" />' .__('Использование бонусов', 'mv-web-reporter') . '<br><input id="mv_cscl_card_use" class="mv_report_addition_param" type="checkbox" checked="checked" name="mv_cscl_card_use" />' .__('Использование карты лояльности', 'mv-web-reporter') .  '<br><input id="mv_check_return" class="mv_report_addition_param" type="checkbox" checked="checked" name="mv_check_return" />' .__('Заказы с возвратами', 'mv-web-reporter') .  '<br><a id="mv_all_check_off" href="#">' .__('Снять все', 'mv-web-reporter') . '</a><br><a id="mv_show_all" href="#">' .__('Показать все', 'mv-web-reporter') .'</a></p>';
		
		echo'<input class="mv_input_order_number" type="text" name="mv_cscl_csrd_number" id="mv_cscl_csrd_number" value="" placeholder="CSCL-card No:" data-required="true" aria-required="true"><br><a id="mv_find_number" class="w-btn style_solid color_primary icon_atleft" href="#"><i class="fa fa-search" aria-hidden="true"></i><span class="w-btn-label">' . __('Найти', 'mv-web-reporter') . '</span></a><br><a id="mv_clear_number" class="w-btn style_solid color_secondary icon_atleft" href="#"><i class="fa fa-eraser" aria-hidden="true"></i><span class="w-btn-label">' . __('Очистить', 'mv-web-reporter') . '</span></a>';
		
		echo '<script>
			/*
				styleSheets[n] содержить все таблицы стилей для данного документа. Первая таблица [0] в данном случае это подключеная (styles.css), вторая [1] тоже (style.css) третья [2] это втроеная mv_class1 и [3] встроенная .mv_ico.
				проверить в консоли можно через document.styleSheets
			*/
			
			function getStyleSheet (css_file_name) {
				for (var i=0; i<document.styleSheets.length; i++) {
					var sheet = document.styleSheets[ i ];
					if (sheet.href){
						if(sheet.href.indexOf(css_file_name) + 1) { /* ищем в списке таблиц стилей ту запись, в свойстве href которой присутствует название нашего файла стилей */
							return sheet;
						}
					}
				}
			}		
			
		
		/* Функция - обработчик проверяет состояния флагов и меняет отображение в зависимости от этого  */
		
		function mv_check_and_realise (){
		/* сначала отдельным блоком выключаем все классы, а затем включаем отображение - чтобы приоритет остался за включением */ 
		mv_stylesheet = getStyleSheet("report-132.css"); /* переменная ссылка на объект таблицы стилей с классами -маркерами */		
		
		/* Блок ОТКЛЮЧЕНИЯ отображения */
		
		/* выключаем банковские карты  */
		if ( !(jQuery("#mv_check_cards").prop("checked")) ){ 
			mv_stylesheet.addRule(".mv_bankCardPay_marker","display: none"); 
		}
		/* выключаем нал   */
		if (!(jQuery("#mv_check_cash").prop("checked"))){ 
			mv_stylesheet.addRule(".mv_cash_marker","display: none"); 		 
		}
		/* выключаем бонусы   */
		if (!(jQuery("#mv_check_bonus").prop("checked"))){ 
			mv_stylesheet.addRule(".mv_bonus_marker","display: none"); 
		}

		/* выключаем карты лояльности   */
		if (!(jQuery("#mv_cscl_card_use").prop("checked"))){ 
			mv_stylesheet.addRule(".mv_cscl_card_marker","display: none"); 
		}		
		
		/* выключаем возвраты   */
		if (!(jQuery("#mv_check_return").prop("checked"))){ 
			mv_stylesheet.addRule(".mv_return_marker","display: none"); 
		}	
		/* выключаем номера заказов - надо сначало включить ранее отключенный номер и запомнить новый, чтобы потом его включить */
		if (!(jQuery("#mv_check_cash").prop("checked"))){ 
			mv_stylesheet.addRule(".mv_cash_marker","display: none"); 
		}
		/* выключаем номера карт лояльности - надо сначало включить ранее отключенный номер и запомнить новый, чтобы потом его включить */
		
		
		/* Блок ВКЛЮЧЕНИЯ отображения */
		
		/* включаем банковские карты  */
		if (jQuery("#mv_check_cards").prop("checked")){
			mv_stylesheet.addRule(".mv_bankCardPay_marker","display: list-item");
		} 	
		/* включаем нал  */
		if (jQuery("#mv_check_cash").prop("checked")){
			mv_stylesheet.addRule(".mv_cash_marker","display: list-item");
		} 		
		/* включаем бонусы  */
		if (jQuery("#mv_check_bonus").prop("checked")){
			mv_stylesheet.addRule(".mv_bonus_marker","display: list-item");
		}
		/* включаем карты лояльности  */
		if (jQuery("#mv_cscl_card_use").prop("checked")){
			mv_stylesheet.addRule(".mv_cscl_card_marker","display: list-item");
		}		
		
		/* включаем возвраты  */
		if (jQuery("#mv_check_return").prop("checked")){
			mv_stylesheet.addRule(".mv_return_marker","display: list-item");
		}	
		}
		
		jQuery("#mv_all_check_off").click(function(event_ch){ /* Снять все  - тут надо снять галочки со всех чекбоксыов */
		jQuery("#mv_check_cards, #mv_check_cash, #mv_check_bonus, #mv_cscl_card_use, #mv_check_return").prop("checked", false); /* make all checkbox checed */
		mv_check_and_realise (); // Вызываем функцию - обработчик
		event_ch.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
		});
		jQuery("#mv_show_all").click(function(event_ch){ /* Show all  - тут надо очистить текстовый инпут и отметить все чекбоксы */
		jQuery("#mv_check_cards, #mv_check_cash, #mv_check_bonus, #mv_cscl_card_use, #mv_check_return").prop("checked", true); /* make all checkbox checed */
		mv_check_and_realise (); // Вызываем функцию - обработчик
		event_ch.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
		});
		
		jQuery("#mv_check_cards, #mv_check_cash, #mv_check_bonus, #mv_cscl_card_use, #mv_check_return").change(function(){ 
		mv_check_and_realise (); // Вызываем функцию - обработчик
		});
		
		/* обработка поля поиска по номеру карты лояльности  */		
		jQuery("#mv_find_number").click(function(){ /* клик по кнопке Найти */
			if ((document.getElementById("form_param_ref_organization").value != "")&&(document.getElementById("mv_cscl_csrd_number").value != "" )) { /* должна быть выбрана организация и поле парметра номера карты не должно быть пустым */
				jQuery("#form_param").submit(); //Отправляем данные формы "Субмитим"
			} else {
				if ( document.getElementById("mv_cscl_csrd_number").value == "" ){ /* если поле парметра пустое, то просто очищаем все элементы от класса mv_no_cscl_card_marker - отображаем их */
					jQuery(".mv_no_cscl_card_marker").removeClass("mv_no_cscl_card_marker");
				}
			}
			; 
		});
		
		jQuery("#mv_clear_number").click(function(){ /* клик по кнопке Найти */
			document.getElementById("mv_cscl_csrd_number").value = "";/* очищаем поле строки поиска */
			jQuery(".mv_no_cscl_card_marker").removeClass("mv_no_cscl_card_marker"); /* очищаем все элементы от класса mv_no_cscl_card_marker - отображаем их  */
		});
		</script>';
		
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
	}	
?>