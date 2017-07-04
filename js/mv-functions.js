/*!
	Модуль с JS функциями используемыми в плагине
*/


/* Функция для чтения куки */
/*
	для проверок можно использовать:
	mv_getCookie( 'mv_cuc_token' ) - проверяем наличие токена
	mv_getCookie( 'mv_cuc_user' ) - проверяем наличия логина
	
*/

function mv_getCookie(name) {
	var matches = document.cookie.match(new RegExp(
	"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}
/* /Функция для чтения куки */



/* Функция для удаления куки */

function delete_cookie(cookie_name)
{
	var cookie_date = new Date();  // Текущая дата и время
	cookie_date.setTime(cookie_date.getTime() - 1);
	document.cookie = cookie_name + "=; path=/; expires=" + cookie_date.toGMTString();
}
/* /Функция для удаления куки */

/* Функция  при  наличии токена  - отображаем все с классом .mv_token_show, скрываем все с классом .mv_notoken_show  */
function mv_show_hide_token_on() {
	jQuery(document).ready(function ($) {
		
		$(".mv_token_show").slideDown('normal');// показать .mv_token_show - все объекты, которые должны отображаться только при наличии токена
		$(".mv_notoken_show").slideUp('normal');// скрыть .mv_notoken_show - все объекты, которые должны отображаться когда нет токена
		
	})
}
/*  / Функция  при  наличии токена  - отображаем все с классом .mv_token_show, скрываем все с классом .mv_notoken_show  */				


/* Функция  при  отсутствии токена  - скрываем все с классом .mv_token_show, отображаем все с классом .mv_notoken_show  */
function mv_show_hide_token_off() {
	jQuery(document).ready(function($) {
		
		$(".mv_token_show").slideUp('normal');// показать .mv_token_show - все объекты, которые должны отображаться только при наличии токена
		$(".mv_notoken_show").slideDown('normal');// скрыть .mv_notoken_show - все объекты, которые должны отображаться когда нет токена
		
	})
}
/*  / Функция  при  наличии токена  - отображаем все с классом .mv_token_show, скрываем все с классом .mv_notoken_show  */