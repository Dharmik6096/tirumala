<?php

namespace app\modules\document\models;

use Yii;

/**
 * This is the model class for table "tbl_document_master_type".
 *
 * @property string $master_type
 * @property string $master_type_name
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 */
class TblDocumentMasterType extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_document_master_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['master_type_code', 'master_type_name', 'originating_type', 'updated_at', 'created_at', 'originating_org_code', 'originating_org_type', 'updated_by', 'created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'master_type' => Yii::t('app', 'Master Type'),
            'master_type_name' => Yii::t('app', 'Master Type Name'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

}
