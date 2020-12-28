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
?>
<div class="table-responsive overflow_hidden dashboard_tbl dashboard_table_section">
 <table class="table dynamic_report_table table-striped">
     <thead>
         <tr>
             <?php
                 foreach ($labels as $key => $value) {
                 ?>
                 <th rowspan="2"><?= Yii::t('app', $model->getAttributeLabel(substr($value,6)))?></th>
             <?php
                 }
             ?>
             <?php
                 foreach ($month_label as $key => $value) {
                 ?>
                 <th colspan="3"><?= $value ?></th>
             <?php
                 }
             ?>
         </tr>
         <tr>
             <?php
                 for ($i=0; $i<count($month_label); $i++){
                     ?>
                         <th><?= Yii::t('app', 'Quantity')?></th>
                         <th><?= Yii::t('app', 'Amount')?></th>
                         <th><?= Yii::t('app', 'Rate')?></th>
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
                 ?>
                     <tr>
                         <?php
                             foreach ($labels as $ke => $title) {?>
                                 <td><?= $value[$title]?></td>      
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
                                 ?>
                                     <td class="number_align"><?= empty($value[$k]) ? '0' : $value[$k]?></td>
                                 <?php
                                 }
                             }
                         }
                         ?>
                     </tr>
                 <?php
             }
         ?>
     </tbody>              
 </table>
</div>
