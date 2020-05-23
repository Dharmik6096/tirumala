<?php

use yii\helpers\Url;
use yii\web\View;
?>
<?php
$this->title = Yii::t('app', 'Staff Salary Process');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <div id="maincontent">
            <?=
            $this->render('_search', ['model' => $model, 'type' => 'create',])
            ?>
        </div>
        <div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
            <?=
            $this->render('_form', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider])
            ?>
        </div>
    </div>
</div>


<?php
$script = "
    function reloadGrid(){
            var url = '" . Url::to(['/staffmanagement/tbl-staff-salary-process/process-grid']) . "'+ '?' + $('#staff-member-form').serialize();
                $.ajax({
                    type: 'get',
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#gridcontentSet').html(data);
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
   
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>