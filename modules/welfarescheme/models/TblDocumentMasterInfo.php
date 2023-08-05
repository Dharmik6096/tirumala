<?php

namespace app\modules\welfarescheme\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_document_master_info".
 *
 * @property integer $doc_id
 * @property string $doc_group
 * @property string $doc_name
 * @property string $doc_ext
 * @property integer $is_active
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblDocumentMasterInfo extends \app\models\ChildModel {

    public $is_mandate;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_document_master_info';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_active', 'originating_type'], 'integer'],
                [['is_mandate', 'created_by', 'updated_by', 'union_code', 'doc_ext', 'doc_name', 'created_at', 'is_active', 'originating_type', 'updated_at', 'doc_group', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['is_active'], 'default', 'value' => 1],
                [['doc_name', 'doc_group', 'union_code'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'doc_id' => Yii::t('app', 'Doc ID'),
            'doc_group' => Yii::t('app', 'Doc Group'),
            'doc_name' => Yii::t('app', 'Document Name'),
            'doc_ext' => Yii::t('app', 'Doc Ext'),
            'is_active' => Yii::t('app', 'Is Active'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}
