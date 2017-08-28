/* МОИ */

/* Функция установки дат и диапазонов */
function mv_data_set(ord, orm, ory, vdd, vmm, vyy) { 
	// В параметрах указываем месяц от 1 до 12
	// ord -исходный день  (если 0 - то текущее число)
	// orm -исходный месяц от 1 до 12 (если 0 - то текущий  месяц)
	// ory -исходный год (если 0 - то текущий  год)
	
	// если vdd и vmm и vyy равны 0, то считаем  на указанную в параметрах orx даты
	// vdd - дней назад
	// vmm - месяцев назад
	// vyy - лет назад
	
	// Ограничения фунуции: можно вносить только один параметр для вычитания либо месяц либо год либо день
	// Параметры только вычитаются (отсчет назад)

	
	mvordate = new Date(); //сейчас 
	if (ord == 0) { ord = mvordate.getDate();} /* получаем день  от 1 до 31 */
	if (orm == 0) { orm = mvordate.getMonth() + 1; } /* получаем текущий месяц от 0 до 11 и приводим к системе 1-12 */
	if (ory == 0) { ory = mvordate.getFullYear(); } /* текущий год */
	
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
	nm = nm + 1; /* приводим в соответствие с нормальным форматом от 1 до 12 */
	if (nd < 10) nd = "0" + nd; 
	if (nm < 10) nm = "0" + nm;
	return ny + "-" + nm + "-" + nd; 
};
		
/* / Функция установки дат и диапазонов */		

/* Функция сравнения двух дат  если вторая дата  = 0 то сравниваем с текущей датой */
/*
	*	Дата задается в формате гггг-мм-дд 2017-12-30
	*	если дата 1, указанная  в параметре:
	*	больше чем дата 2 или текущая, функция вернет 1
	*	равна дата 2 или текущей, функция вернет 0
	*	меньше даты 2 или текущей, функция вернет -1
	
*/
function mv_check_data (mv_first_data, mv_second_data) {
	var data_mv_first_data;
	var data_mv_second_data;
	if (mv_second_data == 0) {
	data_mv_second_data = new Date(); //сейчас
	} else {
		data_mv_second_data = new Date(mv_second_data); // дата 2
		}
	data_mv_first_data = new Date(mv_first_data); // дата 1
	
	if (data_mv_first_data > data_mv_second_data ){ return 1; } else
	if (data_mv_first_data === data_mv_second_data ){ return 0; } else
	if (data_mv_first_data < data_mv_second_data ){ return -1; };
}
/* / Функция сравнения даты на предмет превышения текущей даты */

/* / МОИ */

/*
	* Date Format 1.2.3
	* (c) 2007-2009 Steven Levithan <stevenlevithan.com>
	* MIT license
	*
	* Includes enhancements by Scott Trenda <scott.trenda.net>
	* and Kris Kowal <cixar.com/~kris.kowal/>
	*
	* Accepts a date, a mask, or a date and a mask.
	* Returns a formatted version of the given date.
	* The date defaults to the current date/time.
	* The mask defaults to dateFormat.masks.default.
*/

var dateFormat = function () {
	var	token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
	timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
	timezoneClip = /[^-+\dA-Z]/g,
	pad = function (val, len) {
		val = String(val);
		len = len || 2;
		while (val.length < len) val = "0" + val;
		return val;
	};
	
	// Regexes and supporting functions are cached through closure
	return function (date, mask, utc) {
		var dF = dateFormat;
		
		// You can't provide utc if you skip other args (use the "UTC:" mask prefix)
		if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
			mask = date;
			date = undefined;
		}
		
		// Passing date through Date applies Date.parse, if necessary
		date = date ? new Date(date) : new Date;
		if (isNaN(date)) throw SyntaxError("invalid date");
		
		mask = String(dF.masks[mask] || mask || dF.masks["default"]);
		
		// Allow setting the utc argument via the mask
		if (mask.slice(0, 4) == "UTC:") {
			mask = mask.slice(4);
			utc = true;
		}
		
		var	_ = utc ? "getUTC" : "get",
		d = date[_ + "Date"](),
		D = date[_ + "Day"](),
		m = date[_ + "Month"](),
		y = date[_ + "FullYear"](),
		H = date[_ + "Hours"](),
		M = date[_ + "Minutes"](),
		s = date[_ + "Seconds"](),
		L = date[_ + "Milliseconds"](),
		o = utc ? 0 : date.getTimezoneOffset(),
		flags = {
			d:    d,
			dd:   pad(d),
			ddd:  dF.i18n.dayNames[D],
			dddd: dF.i18n.dayNames[D + 7],
			m:    m + 1,
			mm:   pad(m + 1),
			mmm:  dF.i18n.monthNames[m],
			mmmm: dF.i18n.monthNames[m + 12],
			yy:   String(y).slice(2),
			yyyy: y,
			h:    H % 12 || 12,
			hh:   pad(H % 12 || 12),
			H:    H,
			HH:   pad(H),
			M:    M,
			MM:   pad(M),
			s:    s,
			ss:   pad(s),
			l:    pad(L, 3),
			L:    pad(L > 99 ? Math.round(L / 10) : L),
			t:    H < 12 ? "a"  : "p",
			tt:   H < 12 ? "am" : "pm",
			T:    H < 12 ? "A"  : "P",
			TT:   H < 12 ? "AM" : "PM",
			Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
			o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
			S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
		};
		
		return mask.replace(token, function ($0) {
			return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
		});
	};
}();

// Some common format strings
dateFormat.masks = {
	"default":      "ddd mmm dd yyyy HH:MM:ss",
	shortDate:      "m/d/yy",
	mediumDate:     "mmm d, yyyy",
	longDate:       "mmmm d, yyyy",
	fullDate:       "dddd, mmmm d, yyyy",
	shortTime:      "h:MM TT",
	mediumTime:     "h:MM:ss TT",
	longTime:       "h:MM:ss TT Z",
	isoDate:        "yyyy-mm-dd",
	isoTime:        "HH:MM:ss",
	isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
	isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
	dayNames: [
		"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
		"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
	],
	monthNames: [
		"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
		"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
	]
};

// For convenience...
Date.prototype.format = function (mask, utc) {
	return dateFormat(this, mask, utc);
};