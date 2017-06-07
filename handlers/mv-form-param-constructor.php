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
				<li><label class="description" for="dateFrom"><?php _e('Дата от: ', 'mv-web-reporter'); ?></label>
					<script type="text/javascript">
						
						var now = new Date(); 
						function mv_data_set( mvdate, vdd, vmm, vyy ) { // дней, месяцев и лет назад 
							vdd = vdd || 0;
							vmm = vmm || 0;
							vyy = vyy || 0;
							var ddc = new Date( mvdate );
							ddc.setDate( mvdate.getDate() - vdd); /* по умолчанию вчера -1  -7 -неделю назад и т.д. месяц и год назад -просто отнимаем от мес или года */
							var dd = ddc.getDate();
							if (dd < 10) dd = '0' + dd;
							
							var mm = ddc.getMonth() + 1 - vmm;
							if (mm < 10) mm = '0' + mm;
							
							var yy = ddc.getFullYear() - vyy;
							
							return yy + '-' + mm + '-' + dd;
						}
						
						var mv_datamonth = mv_data_set(now, 0, 0, 0); 
						
						document.write('<input id="dateFrom" required type="date" name="dateFrom" value="' + mv_datamonth + '" />');						
					</script>
					<p><a id="mv_td" class="mv-datepikcer" href="#" ><?php _e('сегодня', 'mv-web-reporter'); ?></a><a id="mv_dd" class="mv-datepikcer" href="#" ><?php _e('вчера', 'mv-web-reporter'); ?></a><a id="mv_ww" class="mv-datepikcer" href="#" ><?php _e('неделю назад', 'mv-web-reporter'); ?></a><a id="mv_mm" class="mv-datepikcer" href="#" ><?php _e('месяц назад', 'mv-web-reporter'); ?></a><a id="mv_yy" class="mv-datepikcer" href="#" ><?php _e('год назад', 'mv-web-reporter'); ?></a> </p>
				</li>
				<li><label class="description" for="dateTo"><?php _e('Дата по: ', 'mv-web-reporter'); ?></label>
					<script type="text/javascript">
						var now = new Date(); 
						var mv_datamonth = mv_data_set(now, 0, 0, 0);
						jQuery(document).ready(function($) {
						
							$("#mv_td").click(function(){
								$("#dateFrom").val(mv_data_set(now, 0, 0, 0)); // устанавливаем вчера
								$("#dateTo").val(mv_data_set(now, 0, 0, 0)); // устанавливаем +1 день
								event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
								
							});
							
							$("#mv_dd").click(function(){
								$("#dateFrom").val(mv_data_set(now, 1, 0, 0)); // устанавливаем вчера
								$("#dateTo").val(mv_data_set(now, 1, 0, 0)); // устанавливаем +1 день
								event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
								
							});
							$("#mv_ww").click(function(){
								$("#dateFrom").val(mv_data_set(now, 7, 0, 0)); // устанавливаем неделю назад
								$("#dateTo").val(mv_data_set(now, 7, 0, 0)); // устанавливаем +1 день
								event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
							});
							$("#mv_mm").click(function(){
								
								$("#dateFrom").val(mv_data_set(now, 0, 1, 0)); // устанавливаем неделю назад
								$("#dateTo").val(mv_data_set(now, 0, 1, 0)); // устанавливаем +1 день
								event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
							});

							$("#mv_yy").click(function(){
								
								$("#dateFrom").val(mv_data_set(now, 0, 0, 1)); // устанавливаем неделю назад
								$("#dateTo").val(mv_data_set(now, 0, 0, 1)); // устанавливаем +1 день
								event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
							});
							
						});
						document.write('<input id="dateTo" required type="date" name="dateTo" value="' + mv_datamonth + '" />');
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