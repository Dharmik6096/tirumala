<?php

namespace app\modules\welfarescheme\models;

use Yii;

/**
 * This is the model class for table "tbl_document_master_info_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
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
class TblDocumentMasterInfoHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_document_master_info_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['doc_name', 'union_code', 'doc_group', 'originating_org_code', 'originating_org_type', 'doc_id', 'is_active', 'originating_type', 'operation_type', 'doc_ext', 'history_created_at', 'created_at', 'updated_at', 'history_created_by', 'created_by', 'updated_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'operation_type' => 'Operation Type',
            'history_created_at' => 'History Created At',
            'history_created_by' => 'History Created By',
            'doc_id' => 'Doc ID',
            'doc_group' => 'Doc Group',
            'doc_name' => 'Doc Name',
            'doc_ext' => 'Doc Ext',
            'is_active' => 'Is Active',
            'union_code' => 'Union Code',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_type' => 'Originating Type',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
        ];
    }

}
