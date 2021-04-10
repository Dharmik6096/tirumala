<?php

use app\modules\dcsoperation\models\TblMember;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\helpers\Url;

$class_cols = 'col-sm-3';
$this->title = Yii::t('app', Yii::$app->label->title('list', $title));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $breadcrum_title . $this->title; ?>
        <div onclick="exportThisWithParameter('recovery_grid', '<?= $this->title ?>')" class="widget_table_search_btn downloadDashboardExcel right_30 mis_custom_report"><i class="fa fa-file-excel-o"></i></div>

        <button onclick="exportThisWithParameter('recovery_grid', '<?= $this->title ?>')" type="button" class="headerIcon gread_header_icon btn btn-danger apply-shortcut btn-block" ><i class="fa fa-file-excel-o"></i></button> 
        <span class="right_align_date"><?= Yii::$app->controls->view_date($date) ?></span>
    </div>
    <div class="panel-body hide-grid-export">
        <div id="plant-list" class="grid-content">
            <div id="plant-list">
                <div id="w12" class="grid-view hide-resize" >
                    <div class="panel panel-default">
                        <div class="col-sm-6 farmer_rmrd_block">
                            <?php
                            echo $this->render('_dashboard_grid_block', ['date' => $date, 'class_cols' => $class_cols, 'blocks_data' => $blocks_data, 'union' => $union]);
                            ?>
                        </div>

                        <div id ="recovery_grid" class="col-sm-6" class="table table-striped">
                            <div id="dash_collapse_grid">
                                <div class="col-sm-12">
                                    <div class="table-responsive height_grid_f dashboard_collection_grid_tbl">
                                        <table class="table overflow_hidden table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="custom_grid_header">#</th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'BMC Code') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'BMC Name') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'DCS Code') ?></th>
                                                    <th class="custom_grid_header"><?= Yii::t('app', 'DCS Name') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($output)) {
                                                    $i = 0;
                                                    foreach ($output as $data) {
                                                        ?>
                                                        <tr>
                                                            <td class="custom_grid_normal"><?= ++$i; ?></td>
                                                            <?php
                                                            $tbl_dcs_model = new \app\modules\organisation\models\TblDcs();
                                                            $tbl_dcs_model->dcs_code = $data['dcs_code'];
                                                            ?>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['bmc_code'] ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['bmc_name'] ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['dcs_code'] ?></td>
                                                            <td class="grid_left_align custom_grid_normal"><?= $data['dcs_name'] ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr><td colspan="7s">No Data Available.</td></tr>
                                                <?php }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$script = "
   $(document).on('click', '.downloadDashboardExcel', function(){
    var idVal = $(this).attr('data-val');
    var titleVal = $(this).attr('data-title');
    fnExcelReport(idVal);
});

function fnExcelReport(idVal, titleVal = 'download')
{
    //bgcolor=\'#87AFC6\'
    var tab_text='<table border=\'2px\'><tr>';
    var textRange; var j=0;
    tab = document.getElementById(idVal); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) 
    {     
        tab_text=tab_text+tab.rows[j].innerHTML+'</tr>';
        //tab_text=tab_text+'</tr>';
    }

    tab_text=tab_text+'</table>';
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, '');//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,''); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ''); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf('MSIE '); 

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open('txt/html','replace');
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus(); 
        sa=txtArea1.document.execCommand('SaveAs',true,titleVal+'.xls');
    }  else {
        //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text), '_blank');  
    }
    return (sa);
}
 
";
$this->registerJs($script, View::POS_READY, 'widget-excel-download');
?>