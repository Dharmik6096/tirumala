<?php

use yii\web\View;

$script = "
var Checkboxes = $('.data-checkbox');
// For checked data
var backgroundColor = '#D6FFDE';
function showAllBack() {
	$('.data-checklist').each(function(){
		$(this).removeClass('hide');
	});
}
// Highlight selected checkboxes
Checkboxes.each(function(){
	var _t = $(this);

	if ( _t.is(':checked') )
	{
		_t.closest('label').css('background', backgroundColor);
	}
});

// Change background on check/uncheck
Checkboxes.on('change', function(){
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

	Checkboxes.each(function(){
		var _t = $(this);

		if ( ! _t.is(':checked') )
		{
			_t.closest('.data-checklist').addClass('hide');
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