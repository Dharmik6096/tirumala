<?php

use yii\helpers\Html;

$this->title = Yii::$app->label->title('view', 'Backend Data');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body">
        <div class="col-sm-6">
            <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Religion Details</h5></div>
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
        <div class="col-sm-6">
            <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Gender Details</h5></div>
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
        <div class="col-sm-6">
            <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Blood Group Details</h5></div>
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
        <div class="col-sm-6">
            <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Qualification Details</h5></div>
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
        <div class="col-sm-6">
            <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Relationship Details</h5></div>
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