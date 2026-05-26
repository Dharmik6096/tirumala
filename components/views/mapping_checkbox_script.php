<?php

use yii\web\View;

$script = "
// For checked data
var backgroundColor = '#D6FFDE';
function showAllBack() {
	$('.data-checklist, .bmc-filter-checklist').each(function(){
		$(this).removeClass('hide');
	});
}
$('.data-checkbox:checked, .bmc-filter-checkbox:checked').each(function(){
	$(this).closest('label').css('background', backgroundColor);
});

$(document).on('change', '.data-checkbox, .bmc-filter-checkbox', function(){
	var _t = $(this);

	if ( _t.is(':checked') )
	{
		_t.closest('label').css('background', backgroundColor);
	}
	else
	{
		_t.closest('label').css('background', 'none');
	}
});


// Hide on not selected data
$('#show-only-selected-data').on('click', function(){
	$(this).addClass('hide');
	$('#show-all').removeClass('hide');

	$('.data-checkbox').each(function(){
		var _t = $(this);

		if ( ! _t.is(':checked') )
		{
			_t.closest('.data-checklist').addClass('hide');
		}
	});

	$('.bmc-filter-checkbox').each(function(){
		var _t = $(this);

		if ( ! _t.is(':checked') )
		{
			_t.closest('.bmc-filter-checklist').addClass('hide');
		}
	});
});

// Show all data back
$('#show-all').on('click', function(){
	$(this).addClass('hide');
	$('#show-only-selected-data').removeClass('hide');

	showAllBack();
});";

$this->registerJs($script, View::POS_END, 'mapping-checkboxes');
?>