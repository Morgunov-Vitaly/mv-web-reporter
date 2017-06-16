<?php
	/*
		
		Шордкод и Конструктор формы ввода предварительных параметров
		Такая форма позволяет более оперативно строить страницы отчетов без написания кода на странице
		
	*/
	
	/*  !!!!!!!!!!  Добавляем шорткод  [mv_param_form] !!!!!!!!!!!!! */
	add_shortcode('mv_param_form', 'mv_param_form_constructor');
	
	
	/* Конструктор формы ввода предварительных параметров отчетов */
	
	
	function mv_param_form_constructor(){
		ob_start();

	// Забираем токен из кукиса и смотрим не равен ли он '', если его нет, то нужно вызвать форму регистрации
	if ( isset( $_COOKIE['mv_cuc_token'] ) ) { /* токен есть надо просто вызвать конструктор формы параметров (выбора отчетов) */
		?><script type='text/javascript'> mv_flag_token_ask = 0; /* переменная флаг нужно ли делать запрос по токену -1 - токена нет 0- да более - нет */
        </script>
		<?php
	} else { // программно вызываем окно авторизации
		?><script type="text/javascript">
            mv_flag_token_ask = -1; /* переменная флаг нужно ли делать запрос по токену -1 - токена нет 0- да более - нет */
            jQuery(document).ready(function () { /* было function($)*/
				PUM.open(6132);
            });
        </script>
		<?php
	}
	?>
	<!-- Форма ввода предварительных параметров отчетов  -->
	<div id="form_param_container_inputs" style="display: none;">
		
		<form id="form_param" class="mv_form" >
			<ul>
				<li>			
					<div class="select_and_label_div">
						<label class="description" for="form_param_ref_organization"><?php _e('Выберите организацию: ', 'mv-web-reporter'); ?></label>
						<div>
							<select id="form_param_ref_organization" name="ref_organization" required  >
								<option value = "0" selected>--</option>				
							</select>
						</div> 
					</div>
				</li>
				<li id="form_param_cafe_place" style="display: none;">
					<div class="select_and_label_div">
						<label class="description" for="form_param_cafe"><?php _e('Выберите кофейню: ', 'mv-web-reporter'); ?></label>
						<div>
							<select id="form_param_cafe" name="cafe_ref"> 
								<option value="0" selected ><?php _e('Все кофейни ', 'mv-web-reporter'); ?></option>
							</select>
						</div> 
					</div> 
				</li>
				<li><p><a id="mv_td" class="mv-datepikcer" href="#"><?php _e('сегодня', 'mv-web-reporter'); ?></a><a id="mv_dd" class="mv-datepikcer" href="#"><?php _e('вчера', 'mv-web-reporter'); ?></a><a id="mv_ww" class="mv-datepikcer" href="#" ><?php _e('неделю назад', 'mv-web-reporter'); ?></a><a id="mv_mm" class="mv-datepikcer" href="#"><?php _e('месяц назад', 'mv-web-reporter'); ?></a><a id="mv_yy" class="mv-datepikcer" href="#"><?php _e('год назад', 'mv-web-reporter'); ?></a><a id="mv_more" class="mv-datepikcer" href="#"><?php _e('...', 'mv-web-reporter'); ?></a></p>
				<div id="mv_data_from" style="display: none">
				<label class="description" for="dateFrom"><?php _e('Дата от: ', 'mv-web-reporter'); ?></label>
					<script type="text/javascript">
						function mv_data_set(ord, orm, ory, vdd, vmm, vyy) { 
							// В параметрах указываем месяц от 1 до 12
							// Ограничения фунуции: можно вносить только один параметр для вычитания либо месяц либо год либо день
							// Параметры только вычитаются (отсчет назад)
							if ((ord == 0) && (orm == 0) && (ory == 0) ) { 
								mvordate = new Date(); //сейчас 
								ory = mvordate.getFullYear();
								orm = mvordate.getMonth() + 1; // получаем месяц от 0 до 11 и приводим к системе 1-12
								ord = mvordate.getDate(); // получаем день  от 1 до 31
							};  
							if (((ord == 31)&& (vmm != 0)) || ((ord == 29)&&(orm == 2) && (vmm != 0)))  {
								// вычитаем месяц(ы) в точно последний день месяца (31) или вычитаем месяц(ы) в точно последний день  февраля (29)
								ny = ory - vyy;
								nm = orm - vmm; //оставляем месяц тем-же или увеличиваем на 1 т.к. 0 день сам сделает вычитание
								nd = 0;
								} else {
								if ( ( (ord == 31)||((ord == 29) && (orm == 2)) ) && (vyy != 0))   {
									ny = ory - vyy;
									nm = orm - vmm; //оставляем месяц тем-же или увеличиваем на 1 т.к. 0 день сам сделает вычитание
									nd = 0;
									} else {
									// вычитаем год(ы) в точно последний день месяца (31) или вычитаем месяц(ы) в точно последний день  февраля (29)
									ny = ory - vyy;
									nm = orm - 1 - vmm; //уменьшаем на 1 т.к. система считает от 0 до 11 а в параметрах - привычная система от 1 до 12
									nd = ord - vdd;
								};						
							};
							var mvordate =  new Date(ny, nm, nd);  //преобразование из системы  от 1 до 12 в систему от 0 до 11 месяцев
							
							// добавляем убавляем значения вводных параметров 
							ny = mvordate.getFullYear();
							nm = mvordate.getMonth(); // получаем месяц от 0 до 11 
							nd = mvordate.getDate(); // получаем день  от 1 до 31
							nm = nm + 1; // приводим в соответствие с нормальным форматом от 1 до 12
							
							if (nd < 10) nd = '0' + nd; 
							if (nm < 10) nm = '0' + nm; 
							return ny + '-' + nm + '-' + nd; 
						}; 						
						
						var mv_datamonth = mv_data_set(0, 0, 0, 0, 0, 0); 
						
						document.write('<input id="dateFrom" required type="date" name="dateFrom" value="' + mv_datamonth + '" />');						
					</script>
					</div>
				</li>
				<li><label class="description" style="display: none" for="dateTo"><?php _e('Дата по: ', 'mv-web-reporter'); ?></label>
					<script type="text/javascript">
						var mv_datamonth = mv_data_set(0,0,0, 0, 0, 0);
						jQuery(document).ready(function($) {
							
							$("#mv_td").click(function(){
								$("#dateFrom").val(mv_data_set(0,0,0, 0, 0, 0)); // устанавливаем сегодня
								$("#dateTo").val(mv_data_set(0,0,0, 0, 0, 0)); // устанавливаем сегодня
								event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
								
							});
							
							$("#mv_dd").click(function(){
								$("#dateFrom").val(mv_data_set(0,0,0, 1, 0, 0)); // устанавливаем вчера
								$("#dateTo").val(mv_data_set(0,0,0, 1, 0, 0)); // устанавливаем вчера
								event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
								
							});
							$("#mv_ww").click(function(){
								$("#dateFrom").val(mv_data_set(0,0,0, 7, 0, 0)); // устанавливаем неделю назад
								$("#dateTo").val(mv_data_set(0,0,0, 7, 0, 0)); // устанавливаем +1 день
								event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
							});
							$("#mv_mm").click(function(){
								
								$("#dateFrom").val(mv_data_set(0,0,0, 0, 1, 0)); // устанавливаем месяц назад
								$("#dateTo").val(mv_data_set(0,0,0, 0, 1, 0)); // устанавливаем месяц назад
								event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
							});
							
							$("#mv_yy").click(function(){
								
								$("#dateFrom").val(mv_data_set(0,0,0, 0, 0, 1)); // устанавливаем год назад
								$("#dateTo").val(mv_data_set(0,0,0, 0, 0, 1)); // устанавливаем год день
								event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
							});
							
							$("#mv_more").click(function(){ // открываем поле ввода даты От вручную
								$("#mv_data_from").toggle("normal");
							});
							
							$("#dateFrom").change(function(){ // автоматическое присвоение Дате до того же значения, что и Дата От
								$("#dateTo").val(document.getElementById('dateFrom').value);
								event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
							});
							
						});
						document.write('<input id="dateTo" style="display: none" required type="date" name="dateTo" value="' + mv_datamonth + '" />');
					</script>
				</li>
				<li class="buttons">
                    <input type="hidden" name="form_id" value="form_param" />
					<input id="saveForm" disabled class="button_text w-btn  color_primary style_solid" type="submit" name="submit" value="<?php _e('Создать отчет', 'mv-web-reporter'); ?>" />
					
				</li>
			</ul>
		</form>
	</div>
	<!-- / Форма ввода предварительных параметров отчетов -->
	<?php
		$html = ob_get_contents();
		ob_get_clean();
		
		return $html;
	}	
	/* / Конструктор формы ввода предварительных параметров отчетов  */
?>