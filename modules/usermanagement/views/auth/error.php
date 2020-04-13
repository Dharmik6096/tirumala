<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use yii\helpers\Html;
?>

<div class="dash">
    <span class="glyphicon glyphicon-exclamation-sign"></span>
    <h1>Please set your organization identity first.</h1>
    
    <?= Html::a('Create Identity',['/installation'],['class'=>'btn btn-default btn-create']); ?>
</div>

<?php
$css = <<<CSS
        
body{
    background-color: #EEEEEE;
}
.dash {
    text-align: center;
    margin: 250px 0 0;
}
.dash h1 {
    margin: 0;
}
.dash span{
        color: #ee162d;
    font-size: 60px;
    opacity: 0.5;
    margin-bottom: 30px;         
}
.btn{
    padding: 4px 15px;
    border-radius: 0;
    text-transform: capitalize;
    transition: all 0.3s ease;
}
.btn-create {
    background-color: #455A64;
    border-color: #455A64;
    color: #ffffff;
    margin-top: 30px;
}
.btn-create:hover, .btn-create:focus{
    background-color: #607D8B;
    border-color: #607D8B;
    color: #ffffff;
}
        
CSS;

$this->registerCss($css);
?>