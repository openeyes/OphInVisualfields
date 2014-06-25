$(document).ready(function() {
	function selectField(e) {
		var side = e.data.side, id = $(this).val();

		var field = window["OphInVisualfields_available_fields_" + side][id];

		$('#Element_OphInVisualfields_Image_image_' + side).attr('src', field.url);
		$('#Element_OphInVisualfields_Image_strategy_' + side).text(field.strategy);
		$('#Element_OphInVisualfields_Image_pattern_' + side).text(field.pattern);
	}
	
	$('#Element_OphInVisualfields_Image_right_field_id').change({side: "right"}, selectField);
	$('#Element_OphInVisualfields_Image_left_field_id').change({side: "left"}, selectField);
});

