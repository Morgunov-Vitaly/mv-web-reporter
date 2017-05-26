<?php
	
/* Это тестовые - фейковые элементы */
	/* Конструктор селективного одиночного списка */

function mv_param_form_constructor_temp($attr){
	ob_start();
?>
<div class="select_and_label_div">
	<label class="description" for="form_param_ref_organization2">Выберите организацию: </label>
	<div>
		<select required id="form_param_ref_organization2" name="ref_organization" >
			<option value="1" >--</option>					
			<option value="2" >Пример 1</option>	
			<option value="3" >Пример 2</option>	
			<option value="4" >Пример 3</option>	
			<option value="5" >Пример 4</option>	
		</select>
	</div>
</div>
<?php
	$html = ob_get_contents();
	ob_get_clean();
	
	return $html;
}	
/* / Конструктор селективного одиночного списка  */

/*  !!!!!!!!!!!!!!!!!!!!!!!  Добавляем шорткод  [mv_param_form_code] */

add_shortcode('mv_param_form_code', 'mv_param_form_constructor_temp');		




/* Конструктор селективного множественного списка  */
function mv_param_form_multy_constructor($attr){
	ob_start();
?>
<div class="select_and_label_div">
	<label class="description" for="form_param_cafe2">Выберите организацию: </label>
	<div>
		<select required id="form_param_cafe2" name="ref_organization" multiple="multiple"> 
			<option value="1"  >--</option>					
			<option value="2" >Пример 1</option>	
			<option value="3" >Пример 2</option>	
			<option value="4" >Пример 3</option>	
			<option value="5" >Пример 4</option>	
		</select>
	</div>
</div>
<?php
	$html = ob_get_contents();
	ob_get_clean();
	
	return $html;
}	
/* / Конструктор селективного множественного списка  */

/*  !!!!!!!!!!!!!!!!!!!!!!!  Добавляем шорткод  [mv_param_form_multy_code] */

add_shortcode('mv_param_form_multy_code', 'mv_param_form_multy_constructor');

?>