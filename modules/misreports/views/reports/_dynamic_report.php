<?php 
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<?php 
 $header = [];
 $labels = [];
 $month_label = [];
 $value_label = [];
 foreach ($result[0] as $key => $value) {
     if(substr($key,0,5) == 'label'){
         array_push($labels,$key);
     }
     else if(is_numeric(substr($key,0,2))){
         $exploded_key = explode("##",$key);
         if(!in_array($exploded_key[1],$month_label)){
             array_push($month_label,$exploded_key[1]);
         }
         array_push($value_label,$key);
     }
 }

 $diff_array = (array_slice($month_label,count($month_label)-2,2));
?>
<div class="table-responsive overflow_hidden dashboard_tbl dashboard_table_section">
<div class="custom_report_table dynamic_report_table">
 <table id="custom_report" class="fht-table table table-striped">
     <thead>
         <tr>
             <?php
                 foreach ($labels as $key => $value) {
                 ?>
                 <th rowspan="2" class="custom_grid_header header_labels"><?= Yii::t('app', $model->getAttributeLabel(substr($value,6)))?></th>
             <?php
                 }
             ?>
             <?php
                 foreach ($month_label as $key => $value) {
                 ?>
                 <th colspan="3" class="custom_grid_header header_labels"><?= $value ?></th>
             <?php
                 }
             ?>
             <th colspan="3" class="custom_grid_header header_labels"><?= Yii::t('app', $diff_array[0]).'-'.Yii::t('app', $diff_array[1])?></th>
         </tr>
         <tr>
             <?php
                 for ($i=0; $i<=count($month_label); $i++){
                     ?>
                         <th class="custom_grid_header header_labels"><?= Yii::t('app', 'Quantity')?></th>
                         <th class="custom_grid_header header_labels"><?= Yii::t('app', 'Amount')?></th>
                         <th class="custom_grid_header header_labels"><?= Yii::t('app', 'Rate')?></th>
                     <?php
                 }
             ?>
         </tr>
     </thead>
     <tbody>
         <?php 
         // print_r($month_label);
         // print_r($value_label);die;
         // var_dump($result);die;
             foreach ($result as $k => $value) {
                 // print_r($value);die;
                 $diff_value_array = [];
                 ?>
                     <tr>
                         <?php
                             foreach ($labels as $ke => $title) {?>
                                 <td class="custom_grid_normal fixed_label" title="<?=$value[$title]?>"><?= substr($value[$title], 0, 10);?></td>      
                             <?php
                             }
                         ?>
                         <?php
                         $i=0;
                         foreach ($month_label as $key => $month_l) {
                             // echo $k;die;
                             foreach ($value_label as $key => $value_title) {
                                 $exploded_key = explode("##",$value_title);
                                 $k = $exploded_key[0].'##'.$month_l;
                                     if(!empty($exploded_key[1]) && $month_l == $exploded_key[1]){
                                         if(in_array($exploded_key[1],$diff_array)){
                                            $val = empty($value[$k]) ? '0' : $value[$k];
                                            $slice_key = explode('_',$exploded_key[0]);
                                            if(isset($diff_value_array[$slice_key[1]])){
                                                $diff_value_array[$slice_key[1]] = $val - $diff_value_array[$slice_key[1]];
                                            }
                                            else{
                                                $diff_value_array[$slice_key[1]] = $val;
                                            }
                                            // array_push($,$arr);
                                         }
                                 ?>
                                     <td class="number_align custom_grid_normal dynamic_value" title="<?= empty($value[$k]) ? '0' : $value[$k]?>"><?= empty($value[$k]) ? '0' : substr($value[$k], 0, 10)?></td>
                                 <?php
                                 }
                             }
                         }
                        //  var_dump($diff_value_array);
                         foreach ($diff_value_array as $key => $diff_v_a) {
                            ?>
                                <td class="number_align custom_grid_normal" title="<?= empty($diff_v_a) ? '0' : $diff_v_a?>"><?= empty($diff_v_a) ? '0' : substr($diff_v_a,0,10)?></td>
                            <?php
                         }

                         ?>
                     </tr>
                 <?php
             }
         ?>
     </tbody>              
 </table>
</div>
</div>
<?php
$script = "
$(document).ready(function(){
    $('#custom_report').CongelarFilaColumna({Columnas:".count($labels)."});
});
";
$this->registerJs($script, View::POS_READY, 'custom-report');
?>
