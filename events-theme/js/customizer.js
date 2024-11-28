if (typeof wp !== 'undefined') {
	wp.customize('custom_css', function (value) {
		value.bind(function (newCSS) {
			jQuery('#custom-css').text(newCSS);
		});
	});
}