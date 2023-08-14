<?php

namespace app\modules\document\models;

use Yii;

/**
 * This is the model class for table "tbl_document_mapping_history".
 *
 * @property integer $id
 * @property integer $mapping_id
 * @property integer $doc_id
 * @property integer $is_mandate
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblDocumentMappingHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_document_mapping_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'mapping_id', 'doc_id', 'is_mandate', 'originating_type', 'created_at', 'created_by', 'updated_by', 'history_created_by', 'updated_at', 'history_created_at', 'originating_org_code', 'originating_org_type', 'operation_type', 'master_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'mapping_id' => Yii::t('app', 'Mapping ID'),
            'doc_id' => Yii::t('app', 'Doc ID'),
            'is_mandate' => Yii::t('app', 'Is Mandate'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
