<?php

use yii\helpers\Html;

$this->title = Yii::$app->label->title('view', 'Backend Data');
?>
<div class="panel panel-default panel-main">
    <div class="panel-body">
    <div class="row theme_border_left theme_border_right theme_border_bottom">
        <div class="col-md-6 padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 margin-bottom-10 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading">Religion Details</h4>
            </div>
            <div class="form-grid">
                <?php
                $attribute = [
                    ['attribute' => 'religion_code', 'filter' => FALSE],
                    ['attribute' => 'religion', 'filter' => FALSE],
                ];
                $grid_option = [
                    'id' => 'religion-list',
                    'attributes' => $attribute,
                    'active_column' => false,
                ];
                Yii::$app->grid->bind($religionData, $religionModel, $grid_option);
                ?>
            </div>
        </div>
        <div class="col-md-6 padding_10_0 theme-box theme_border_left">
            <div class="col-sm-12 col-md-12  margin-bottom-10 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading">Gender Details</h4>
            </div>
            <div class="form-grid">
                <?php
                $attribute = [
                    ['attribute' => 'gender_code', 'filter' => FALSE],
                    ['attribute' => 'gender', 'filter' => FALSE],
                ];
                $grid_option = [
                    'id' => 'gender-list',
                    'attributes' => $attribute,
                    'active_column' => false,
                ];
                Yii::$app->grid->bind($genderData, $genderModel, $grid_option);
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6 padding_10_0 theme-box theme_border_right">
            <div class="col-sm-12 col-md-12  margin-bottom-10 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading">Blood Group Details</h4>
            </div>
            <div class="form-grid">
                <?php
                $attribute = [
                    ['attribute' => 'blood_group_code', 'filter' => FALSE],
                    ['attribute' => 'blood_group', 'filter' => FALSE],
                ];
                $grid_option = [
                    'id' => 'blood-list',
                    'attributes' => $attribute,
                    'active_column' => false,
                ];
                Yii::$app->grid->bind($bloodData, $bloodModel, $grid_option);
                ?>
            </div>
        </div>
        <div class="col-md-6 padding_10_0 theme-box">
            <div class="col-sm-12 col-md-12  margin-bottom-10 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading">Qualification Details</h4>
            </div>
            <div class="form-grid">
                <?php
                $attribute = [
                    ['attribute' => 'qualification_code', 'filter' => FALSE],
                    ['attribute' => 'qualification_name', 'filter' => FALSE],
                    ['attribute' => 'sequences_no', 'filter' => FALSE],
                ];
                $grid_option = [
                    'id' => 'qualification-list',
                    'attributes' => $attribute,
                    'active_column' => false,
                ];
                Yii::$app->grid->bind($qualificationData, $qualificationModel, $grid_option);
                ?>
            </div>
        </div>


        <div class="clearfix"></div>
        <div class="col-md-12 padding_10_0 theme-box">
            <div class="col-sm-12 col-md-12  margin-bottom-10 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading">Relationship Details</h4>
            </div>
            <div class="form-grid">
                <?php
                $attribute = [
                    ['attribute' => 'relationship_code', 'filter' => FALSE],
                    ['attribute' => 'relationship', 'filter' => FALSE],
                ];
                $grid_option = [
                    'id' => 'relationship-list',
                    'attributes' => $attribute,
                    'active_column' => false,
                ];
                Yii::$app->grid->bind($relationshipData, $relationshipModel, $grid_option);
                ?>
            </div>
        </div>
    </div>
</div>
</div>