
"use strict"

var dlabSettingsOptions = {};

function getUrlParams(dParam) 
	{
		var dPageURL = window.location.search.substring(1),
			dURLVariables = dPageURL.split('&'),
			dParameterName,
			i;

		for (i = 0; i < dURLVariables.length; i++) {
			dParameterName = dURLVariables[i].split('=');

			if (dParameterName[0] === dParam) {
				return dParameterName[1] === undefined ? true : decodeURIComponent(dParameterName[1]);
			}
		}
	}

(function($) {
	
	"use strict"
	
	/* var direction =  getUrlParams('dir');
	
	if(direction == 'rtl')
	{
        direction = 'rtl'; 
    }else{
        direction = 'ltr'; 
    } */
	
	function getThemeCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') { c = c.substring(1); }
			if (c.indexOf(name) == 0) { return c.substring(name.length, c.length); }
		}
		return "";
	}

	var themeVersion = getThemeCookie('version') || localStorage.getItem('version') || "light";

	dlabSettingsOptions = {
			typography: "poppins",
			version: themeVersion,
			layout: "vertical",
			primary: "color_1",
			headerBg: "color_3",
			navheaderBg: "color_2",
			sidebarBg: "color_2",
			sidebarStyle: "full",
			sidebarPosition: "fixed",
			headerPosition: "fixed",
			containerLayout: "full",
		};
		
	
	new dlabSettings(dlabSettingsOptions); 

	jQuery(window).on('resize',function(){
        /*Check container layout on resize */
		///alert(dlabSettingsOptions.primary);
        dlabSettingsOptions.containerLayout = $('#container_layout').val();
        /*Check container layout on resize END */
        
		new dlabSettings(dlabSettingsOptions); 
	});
	
})(jQuery);