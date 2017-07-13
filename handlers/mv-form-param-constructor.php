<?php
	/*
		
		Шордкод и Конструктор формы ввода предварительных параметров
		Такая форма позволяет более оперативно строить страницы отчетов без написания кода на странице
		
	*/
	
	/*  !!!!!!!!!!  Добавляем шорткод  [mv_param_form] !!!!!!!!!!!!! */
	add_shortcode('mv_param_form', 'mv_param_form_constructor');
	
	
	/* 
		Конструктор 
		формы ввода предварительных параметров 
		для построения отчетов 
	*/
	
	function mv_param_form_constructor($atts){
		global $mv_login_popup;  // глобальная переменная со сведениями по модальному окну LogIn
		$params = shortcode_atts( array( //  задаем значения по умолчанию
		'type' => 1, // тип 1 (по умолчанию) - отчет, по всем кофейням 2- тип, когда обязательно выбрана одна из кофеен (первая по списку)
		), $atts );	
		
		//PC::debug($params['type']);
		ob_start();
		
		
		// Забираем токен из кукиса и смотрим не равен ли он '', если его нет, то нужно вызвать форму регистрации
		if ( isset( $_COOKIE['mv_cuc_token'] ) ) { /* токен есть надо просто вызвать конструктор формы параметров (выбора отчетов) */
			?><script type='text/javascript'>
			mv_flag_token_ask = 0; /* переменная флаг нужно ли делать запрос по токену -1 - токена нет 0- да более - нет */
			mv_document_ready = 0;
		</script>
		<?php
			} else { // программно вызываем окно авторизации если нет токена
		?>
		<script type="text/javascript">
            mv_flag_token_ask = -1; /* переменная флаг нужно ли делать запрос по токену -1 - токена нет 0- да более - нет */
			mv_document_ready = 0;
            jQuery(document).ready(function () { /* было function($)*/
				PUM.open(<?php echo $mv_login_popup ?>);
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
							<select id="form_param_ref_organization" name="ref_organization" required>
								<option value = "0" selected>--</option>				
							</select>
						</div> 
					</div>
				</li>
				<li id="form_param_cafe_place" style="display: none;">
					<div class="select_and_label_div">
						<label class="description" for="form_param_cafe"><?php _e('Выберите кофейню: ', 'mv-web-reporter'); ?></label>
						<div>
							<select id="form_param_cafe" name="cafe_ref" required> 
								<option value="0" selected ><?php _e( '00 Все кофейни', 'mv-web-reporter' ); ?></option> <!-- только если id=102 или ему подобные (с опцией "00 все кофейни" ) -->
							</select>
						</div> 
					</div> 
				</li>
				<li><p>
					<input  type="radio" id="mv_td" name="mv_radio" checked /><label for="mv_td"><?php _e('сегодня', 'mv-web-reporter'); ?></label>
					<input  type="radio" id="mv_dd" name="mv_radio" /><label for="mv_dd"><?php _e('вчера', 'mv-web-reporter'); ?></label>
					<input  type="radio" id="mv_ww" name="mv_radio" /><label for="mv_ww"><?php _e('неделю назад', 'mv-web-reporter'); ?></label>
					<input  type="radio" id="mv_mm" name="mv_radio" /><label for="mv_mm"><?php _e('месяц назад', 'mv-web-reporter'); ?></label>
					<input  type="radio" id="mv_yy" name="mv_radio" /><label for="mv_yy"><?php _e('год назад', 'mv-web-reporter'); ?></label>
					<input  type="radio" id="mv_more" name="mv_radio" /><label for="mv_more"><?php _e('...', 'mv-web-reporter'); ?></label>
				</p>
				</li>
				<li>
					<div class="mv_data_from" style="display: none">
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
					<label class="description" style="display: none" for="dateTo"><?php _e('Дата по: ', 'mv-web-reporter'); ?></label>
					<script type="text/javascript">
						document.write('<input id="dateTo" style="display: none" required type="date" name="dateTo" value="' + mv_datamonth + '" />');						
					</script>
					<script type="text/javascript">
						var mv_datamonth = mv_data_set(0,0,0, 0, 0, 0);
						jQuery(document).ready(function($) {
							
							$("#mv_td").click(function(){ //клик по кнопке сегодня
								$("#dateFrom").val(mv_data_set(0,0,0, 0, 0, 0)); // устанавливаем сегодня
								$("#dateTo").val(mv_data_set(0,0,0, 0, 0, 0)); // устанавливаем сегодня
								if (document.getElementById('form_param_ref_organization').value != "0") { // должна быть выбрана организация
									$("#form_param").submit(); //Отправляем данные формы "Субмитим"
								}; 
								// event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
								
								
							});
							
							$("#mv_dd").click(function(){ //клик по кнопке вчера
								$("#dateFrom").val(mv_data_set(0,0,0, 1, 0, 0)); // устанавливаем вчера
								$("#dateTo").val(mv_data_set(0,0,0, 1, 0, 0)); // устанавливаем вчера
								if (document.getElementById('form_param_ref_organization').value != "0") { // должна быть выбрана организация
									$("#form_param").submit(); //Отправляем данные формы "Субмитим"
								}; 
								
							});
							$("#mv_ww").click(function(){ //клик по кнопке неделю назад
								$("#dateFrom").val(mv_data_set(0,0,0, 7, 0, 0)); // устанавливаем неделю назад
								$("#dateTo").val(mv_data_set(0,0,0, 7, 0, 0)); // устанавливаем +1 день
								if (document.getElementById('form_param_ref_organization').value != "0") { // должна быть выбрана организация
									$("#form_param").submit(); //Отправляем данные формы "Субмитим"
								}; 
							});
							$("#mv_mm").click(function(){ //клик по кнопке месяц назад
								
								$("#dateFrom").val(mv_data_set(0,0,0, 0, 1, 0)); // устанавливаем месяц назад
								$("#dateTo").val(mv_data_set(0,0,0, 0, 1, 0)); // устанавливаем месяц назад
								if (document.getElementById('form_param_ref_organization').value != "0") { // должна быть выбрана организация
									$("#form_param").submit(); //Отправляем данные формы "Субмитим"
								}; 
							});
							
							$("#mv_yy").click(function(){ //клик по кнопке год назад
								
								$("#dateFrom").val(mv_data_set(0,0,0, 0, 0, 1)); // устанавливаем год назад
								$("#dateTo").val(mv_data_set(0,0,0, 0, 0, 1)); // устанавливаем год день
								if (document.getElementById('form_param_ref_organization').value != "0") { // должна быть выбрана организация
									$("#form_param").submit(); //Отправляем данные формы "Субмитим"
								}; 
							});
							
							$("#mv_more").click(function(){ // открываем поле ввода "Дата ОТ" вручную
								$(".mv_data_from").toggle("normal");
							});
							
							$("#dateFrom").change(function(){ // автоматическое присвоение "Дата ДО" того же значения, что и Дата От
								$("#dateTo").val(document.getElementById('dateFrom').value);
								//event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
							});
							event.preventDefault(); // Отменяем стандартное действие кнопки Submit в форме
						});
					</script>
				</li>
				<li class="mv_data_from buttons" style="display: none">
                    <input type="hidden" name="form_id" value="form_param" />
					<input id="mv_submit_make_report" disabled class="button_text w-btn  color_primary style_solid" type="submit" name="submit" value="<?php _e('Создать отчет', 'mv-web-reporter'); ?>" />
					
				</li>
			</ul>
		</form>
	</div>
	
	<script type="text/javascript">	
		
		// Функция - параметр для сортировки массива списка организаций кофеен и т.д. по алфавиту с помощью метода .sort
		function mvcompareObjects(a, b) {
			if (a.text < b.text) return -1;
			if (a.text > b.text) return 1;
			return 0;
		};	
		
		
		/* 
			!!!!!!!!!!! 
			Функция конструктор селектов 
			списка ОРГАНИЗАЦИЙ   
			!!!!!!!!!!! 
		*/
		
		function mv_form_construct(result) { // передаем параметр - полученный объект и возвращаем список в mv_results_data
			var lastFirm = "";
			// простой случай accessType == "company" доступ ко всем компаниями кофейням
			mv_default_coffee = 0; // Все кофейни по умолчанию
			mv_default_org = mv_result.ref_default_access_object;  // считываем значение из глобальной переменной минуя result - коряво, надо все через result делать
			mv_results_data = []; // создаем массив значений первого списка (организаций)
			mv_results_data.length = 0;
			//mv_results_data[0] = {"id": "0", "text": "--"};
			
			for (var organization in result) if (result.hasOwnProperty(organization)) {
				var t = result[organization]; //выбираем значения с ключем organization
				var mv_local_arr = {};
				mv_local_arr.length = 0; //дополнительно обнуляем
				if (lastFirm != t.ref) {
					/* условие для отсечки повторяющихся значений, наверное, он тут не нужен */
					mv_local_arr['id'] = t.ref;
					mv_local_arr['text'] = t.name;
					mv_results_data.push(mv_local_arr);
					//console.log("t.name ");
					lastFirm = t.ref;
					//Устанавливаем значение переменной mv_default_cofee - кофейня по умолчанию для данного пользователя
					if (mv_result.accessType == "coffeeshop") { // accessType == "cofeeshop" даны права на просмотр только своей кофейни организацию надо найти перебором 
						//console.log(t.ref);
						for (var coffeeshop in t.divisions) if (t.divisions.hasOwnProperty(coffeeshop)) {
							var c = t.divisions[coffeeshop]; // t.divisions - массив кофеен
							if (c.Ref == mv_result.ref_default_access_object) { // находим организацию к которой принадлежит указанная в ref_default_access_object кофейня
								mv_default_org = t.ref; // значение организации по умолчанию
								mv_default_coffee = mv_result.ref_default_access_object; // По умолчанию кофейня указанная в параметрах удаленного сервера 
								break; // остановить перебор
							}
						}
					}
				}
			}
			// вставить сортировщик списка организаций нужен ли нам пункт 0 "--"  - "типа не выбрана организация"?
			// start сортируем полученный массив
			mv_results_data.sort(mvcompareObjects);
			// end сортируем полученный массив		
			//вызвать построитель списка кофеен
			
			/* Динамически меняем селект */
			mv_sel_org.empty(); //Обнуляем список организаций переменная mv_sel_org хранит в себе экземпляр объекта
			mv_sel_org.select2({data: mv_results_data}); // заполняем список организаций новыми значениями
			
			//Устанавливаем значения  элементов списка по умолчанию - установленные для данного пользователя в УПП
			jQuery(function ($) {
			
			$("#form_param_ref_organization").val(mv_default_org).trigger('change');
			onchangeCoffeeSelect(mv_result.organizations); //Вызваем конструктор списка кофеен
			//$("#form_param_cafe").val(mv_default_coffee).trigger('change');
			
			$("span.select2-container--default").css("width", "100%");  // костыль, чтобы изменить кривую вставку width = 1px
			$("#form_param_container_inputs").slideDown('normal');/* показать форму form_param_container_inputs Контейнер для всей формы ввода предварительных параметров */	
			});
		}
		/* / Функция конструктор селектов списка ОРГАНИЗАЦИЙ  */
		
		
		
		/* 
			!!!!!!!!!!! 
			Функция конструктор 
			списка КОФЕЕН 
			!!!!!!!!!! 
		*/
		function onchangeCoffeeSelect(result) {
			var mv_param = <?php echo $params['type']; ?>; // передаем переменную с типом формы ввода предварительных параметров
			var firmSelObj = document.getElementById('form_param_ref_organization'); // указатель на селект организаций
			var mv_make_report_button = document.getElementById('mv_submit_make_report'); //указатель на кнопку Создать отчет
			var form_param_cafe_place = document.getElementById('form_param_cafe_place'); //создаем указатель на контейнер со вторым селектом по ID
			var currentFirm = firmSelObj.value; // запоминаем, что выбрали в первом селекте
			if (currentFirm == "0") {
				form_param_cafe_place.style.display = "none"; //организация не выбрана -- значит прячем второй список и кнопку отправить отчет
				mv_make_report_button.disabled = 1;//id="mv_submit_make_report" disabled
				} else { 
				/* блок для случая, когда выбрали организацию */
				var caffeeSelObj = document.getElementById('form_param_cafe'); //создаем указатель на селект кофеен по ID
				mv_results2_data = [];  // создаем массив значений второго списка (кофеен)
				mv_results2_data.length = 0; //обнуляем
				
				if ((mv_param == 1) && (mv_result.accessType == "company")) { /* 1 тип - возможен  отчет, по всем кофейням и у пользователя есть право выбирать */
					/* очистим список кофеен - всех, кроме первого элемента в случае отчета с опцией '00 Все кофейни' */
					mv_results2_data[0] = {"id": "0", "text": "<?php _e( '00 Все кофейни', 'mv-web-reporter' ); ?>"};
					
				} 
				
				//console.log ( mv_results2_data);
				
				for (var organization in result) if (result.hasOwnProperty(organization)) { //перебираем массив организаций из-за неоптимальной структуры объекта: у организации есть номер от 0 до .., вместо Ref'a a Ref находится во внутренних свойствах т.е. mv_result.organizations.[0].Ref :(
					var t = result[organization];
					
					if (t.ref == currentFirm) { // находим выбранную компанию (может есть способ без перебора компаний?)
						//var selLen = caffeeSelObj.options.length; // определяем количество строк <option > во втором селекте
						for (var coffeeshop in t.divisions) if (t.divisions.hasOwnProperty(coffeeshop)) {
							var c = t.divisions[coffeeshop]; // t.divisions - массив подразделений (кофеен)
							if (( c['active']=="1") || ((mv_result.accessType == "coffeeshop") && (c['Ref'] == mv_result.ref_default_access_object)) ){ // учитываем только активные кофейни, но если это назначенная кофейня для пользователя с уровнем доступа "coffeeshop" то ее тоже включаем в список
								var mv_local_arr = {}; //обнуляем
								mv_local_arr.length = 0; //дополнительно обнуляем
								//else {
								//caffeeSelObj.options[selLen ++] = new Option(c.name, c.ref); // создания новых элементов списка мы //используем конструктор	Option(text, value), где text — это отображаемая метка элемента списка, а value — //её значение.
								mv_local_arr['id'] = c['Ref'];
								mv_local_arr['text'] = c['Name'];
								mv_results2_data.push(mv_local_arr); //добавляем значение
								//}
							}
						}
					}
				}
				// start сортируем полученный массив
				mv_results2_data.sort(mvcompareObjects);
				// end сортируем полученный массив
				
				// для случая тип 2 и для пользователя с доступом company устанавливаем первую из списка кофейню выбранной по умолчанию
				if ((mv_result.accessType == "company") && (mv_param == 2)) {						
					// цикл который прерывется на первой же итерации
					for (var refcoffeeshop in mv_results2_data) {
						mv_default_coffee = mv_results2_data[refcoffeeshop].id;
						break;
					}					
				} 
				
				jQuery(function ($) {
					mv_sel_coffee.empty(); //Обнуляем список
					mv_sel_coffee.select2({data: mv_results2_data}); // заполняем список новыми значениями
					
					/* Устанавливаем значение по умолчанию */
					//if (mv_result.accessType == "coffeeshop") {
					$("#form_param_cafe").val(mv_default_coffee).trigger('change'); // mv_result.ref_default_access_object
					//вызвать submit?
					//}
					/* / Устанавливаем значение по умолчанию */
					$("span.select2-container--default").css("width", "100%"); // костыль, чтобы изменить кривую вставку width = 1px
					
					form_param_cafe_place.style.display = "block"; // Включить отображение списка
					mv_make_report_button.disabled = 0;//id="mv_submit_make_report" enabled
					
				});
				
			} /*/ блок для случая, когда выбрали организацию */
		}
		
		/*  / Функция конструктор списка КОФЕЕН  */
		
		/* 
			!!!!!!!!!!!! 
			Обработчик - формы ввода 
			предварительных параметров отчетов 
			ПО ТОКЕНУ 
			!!!!!!!!!!!! 
		*/
		jQuery(document).ready(function ($) {
			// проверяем наличие токена
			mv_token = mv_getCookie('mv_cuc_token');
			if ((mv_token != "") && (typeof mv_token != "undefined") && (mv_flag_token_ask === 0)) { /* если токен не пустой и флаг усановлен на 0 */
				mv_flag_token_ask = mv_flag_token_ask + 1;
				/* изменяем флаг им можно будет пользоваться для подсчета кол-во срабатываний данного обработчика */
				
				//$("#mv_report_progress_circle").slideDown('normal'); // Отображаем колесо загрузчик ожидание slideUp('normal')
				$.ajax({
					type: 'GET',
					url: '<?php echo admin_url( "admin-ajax.php" ); ?>', /* URL к которму подключаемся */
					data: {
						action: 'mv_ask_params_list', /* Вызывам обработчик  mv_ask_params_list */
						mv_nonce: '<?php echo wp_create_nonce( "mv_ask_params_list" ); ?>',
						mv_token: mv_getCookie('mv_cuc_token') // передаем токен
					},
					success: function (result, status) {
						console.log("<?php _e( 'Статус AJAX запроса списка организаций и кофеен по токену: ', 'mv-web-reporter' ); ?>" + status); // Выводим сообщение об ошибках
						if (result != ""){
							mv_result = JSON.parse(result); // $.parseJSON(result); //функция JQuery
							if ((typeof mv_result.mv_error_code == "undefined") && (mv_result.token != '') && (mv_result.token != "undefined")) { // все получилось или нам пришел 0!
								$("#pum-<?php echo $mv_login_popup?>").popmake('close'); // закрываем диалогово окно LogIn если оно почему-то открыто
								console.log("<?php _e( 'Результат запроса списка по токену успешен: ', 'mv-web-reporter' ); ?>");
								//console.log ( mv_result.message );
								//console.log ( mv_result.token );
								//console.log ( mv_result.organizations );
								mv_form_construct(mv_result.organizations); //конструктор, который заносит список организаций в mv_results_data
							}
							else {
								
								$("#mv_login_error").slideDown('normal'); //выводим сообщение об ошибке в форме - но если форма закрыта, то ничего не выведет
								console.log("<?php _e( 'Код ошибки запроса списка по токену: ', 'mv-web-reporter' ); ?>" + mv_result.mv_error_code + " <?php _e( 'Сообщение: ', 'mv-web-reporter' ); ?>" + mv_result.message);
								/* Здесь надо вывести окно с сообщением об ошибке или сделать редирект на соответсвующую страницу 401, 403 и т.д. */
							}
							}else {
							console.log("<?php _e( 'Удаленный сервер вернул пустую строку: ', 'mv-web-reporter' ); ?>" + result);
						}
						$("#mv_report_progress_circle").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
					},
					error: function (result, status, jqxhr) { // срабатывает только в случае если не сработает AJAX запрос на WP
						$("#mv_report_progress_circle").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
						alert("<?php _e( 'Упс! Возникла ошибка при запросе списка по токену к серверу WP! Ответ сервера: ', 'mv-web-reporter' ); ?>" + result);
						console.log("<?php _e( 'Статус: ', 'mv-web-reporter' ); ?>" + status);
						console.log("<?php _e( 'jqXHR статус: ', 'mv-web-reporter' ); ?>" + jqxhr.status + " " + jqxhr.statusText);
						console.log(jqxhr.getAllResponseHeaders());
					}
				});
			}
		});
		/*  / Конструктор формы ввода предварительных параметров отчетов при наличии токена  */
		
	</script>
	
	<!-- / Форма ввода предварительных параметров отчетов -->
	<?php
		$html = ob_get_contents();
		ob_get_clean();
		
		return $html;
	}	
	/* / Конструктор формы ввода предварительных параметров отчетов  */
?>