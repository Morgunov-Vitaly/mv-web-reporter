/*!
	* jQuery Accordion widget
 * http://nefariousdesigns.co.uk/projects/widgets/accordion/
 * 
 * Source code: http://github.com/nefarioustim/jquery-accordion/
 *
 * Copyright © 2010 Tim Huegdon
 * http://timhuegdon.com
 */
 
 
 /* Функция костыль, чтобы заработал аккардеон */ 
 // Limit scope pollution from any deprecated API
(function() {
 
var matched, browser;
 
// Use of jQuery.browser is frowned upon.
// More details: http://api.jquery.com/jQuery.browser
// jQuery.uaMatch maintained for back-compat
jQuery.uaMatch = function( ua ) {
    ua = ua.toLowerCase();
 
    var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
        /(msie) ([\w.]+)/.exec( ua ) ||
        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
        [];
 
    return {
        browser: match[ 1 ] || "",
        version: match[ 2 ] || "0"
    };
};
 
matched = jQuery.uaMatch( navigator.userAgent );
browser = {};
 
if ( matched.browser ) {
    browser[ matched.browser ] = true;
    browser.version = matched.version;
}
 
// Chrome is Webkit, but Webkit is also Safari.
if ( browser.chrome ) {
    browser.webkit = true;
} else if ( browser.webkit ) {
    browser.safari = true;
}
 
jQuery.browser = browser;
})();
 
 /* Функция костыль, чтобы заработал аккардеон */
 
(function($) {
    var debugMode = false;
    
    function debug(msg) {
        if(debugMode && window.console && window.console.log){
            window.console.log(msg);
        } else {
            alert(msg);
        }
    }
    
    $.fn.mv_accordion = function(config) {
        var defaults = {
            "handle":           "h3",
            "panel":            ".panel",
            "speed":            200,
            "easing":           "swing",
            "canOpenMultiple":  false,
            "canToggle":        false,
            "activeClassPanel": "open",
            "activeClassLi":    "active",
            "lockedClass":      "locked"
        };
        
        if (config) {
            $.extend(defaults, config);
        }
        
        this.each(function() {
            var mv_accordion   = $(this),
                reset       = {
                    height:         0,
                    marginTop:      0,
                    marginBottom:   0,
                    paddingTop:     0,
                    paddingBottom:  0
                },
                panels      = mv_accordion.find(">li>" + defaults.panel)
                                .each(function() {
                                    var el = $(this);
                                    el
                                        .data("dimensions", {
                                            marginTop:      el.css("marginTop"),
                                            marginBottom:   el.css("marginBottom"),
                                            paddingTop:     el.css("paddingTop"),
                                            paddingBottom:  el.css("paddingBottom"),
                                            height:   '100%'      //this.offsetHeight - parseInt(el.css("paddingTop")) - parseInt(el.css("paddingBottom")) // Непонятно зачем они намутили это - ведь в итоге при изменении размера текст мог неумещаться
                                        })
                                        .bind("panel-open.mv_accordion", function(e, clickedLi) {
                                            var panel = $(this);
                                            clickedLi.addClass(defaults.activeClassLi);
                                            panel
                                                .css($.extend({overflow: "hidden"}, reset))
                                                .addClass(defaults.activeClassPanel)
                                                .show()
                                                .animate($.browser.msie && parseInt($.browser.version) < 8 ? panel.data("dimensions") : $.extend({opacity: 1}, panel.data("dimensions")), {
                                                    duration:   defaults.speed,
                                                    easing:     defaults.easing,
                                                    queue:      false,
                                                    complete:   function(e) {
                                                        if ($.browser.msie) {
                                                            this.style.removeAttribute('filter');
                                                        }
                                                    }
                                                });
                                        })
                                        .bind("panel-close.mv_accordion", function(e) {
                                            var panel = $(this);
                                            panel.closest("li").removeClass(defaults.activeClassLi);
                                            panel
                                                .removeClass(defaults.activeClassPanel)
                                                .css({
                                                    overflow: "hidden"
                                                })
                                                .animate($.browser.msie && parseInt($.browser.version) < 8 ? reset : $.extend({opacity: 0}, reset), {
                                                    duration:   defaults.speed,
                                                    easing:     defaults.easing,
                                                    queue:      false,
                                                    complete:   function(e) {
                                                        if ($.browser.msie) {
                                                            this.style.removeAttribute('filter');
                                                        }
                                                        panel.hide();
                                                    }
                                                });
                                        });
                                    
                                    return el;
                                })
                                .hide(),
                handles     = mv_accordion.find(
                                " > li > "
                                + defaults.handle
                            )
                                .wrapInner('<a class="mv_accordion-opener" href="#open-panel" />');
            
            mv_accordion
                .find(
                    " > li."
                    + defaults.activeClassLi
                    + " > "
                    + defaults.panel
                    + ", > li."
                    + defaults.lockedClass
                    + " > "
                    + defaults.panel
                )
                .show()
                .addClass(defaults.activeClassPanel);
            
            var active = mv_accordion.find(
                " > li."
                + defaults.activeClassLi
                + ", > li."
                + defaults.lockedClass
            );
            
            if (!defaults.canToggle && active.length < 1) {
                mv_accordion
                    .find(" > li")
                    .first()
                    .addClass(defaults.activeClassLi)
                    .find(" > " + defaults.panel)
                    .addClass(defaults.activeClassPanel)
                    .show();
            }
            
            mv_accordion.delegate(".mv_accordion-opener", "click", function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                
                var clicked     = $(this),
                    clickedLi   = clicked.closest("li"),
                    panel       = clickedLi.find(">" + defaults.panel).first(),
                    open        = mv_accordion.find(
                        " > li:not(."
                        + defaults.lockedClass
                        + ") > "
                        + defaults.panel
                        + "."
                        + defaults.activeClassPanel
                    );
                
                if (!clickedLi.hasClass(defaults.lockedClass)) {
                    if (panel.is(":visible")) {
                        if (defaults.canToggle) {
                            panel.trigger("panel-close");
                        }
                    } else {
                        panel.trigger("panel-open", [clickedLi]);
                        if (!defaults.canOpenMultiple) {
                            open.trigger("panel-close");
                        }
                    }
                }
            });
        });
        
        return this;
    };
})(jQuery);