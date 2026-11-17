
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
	
	var savedThemeVersion = (typeof getCookie === 'function' && getCookie('version')) || localStorage.getItem('version') || 'light';
	if (savedThemeVersion !== 'dark' && savedThemeVersion !== 'light') {
		savedThemeVersion = 'light';
	}

	dlabSettingsOptions = {
			typography: "poppins",
			version: savedThemeVersion,
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
        dlabSettingsOptions.containerLayout = $('#container_layout').val();
        dlabSettingsOptions.version = document.body.getAttribute('data-theme-version') || savedThemeVersion;
        /*Check container layout on resize END */
        
		new dlabSettings(dlabSettingsOptions); 
	});
	
})(jQuery);