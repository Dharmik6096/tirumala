<?php

namespace app\modules\welfarescheme\models;

use Yii;

/**
 * This is the model class for table "tbl_scheme_document_mapping_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property integer $mapping_id
 * @property integer $scheme_id
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
 */
class TblSchemeDocumentMappingHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_scheme_document_mapping_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_created_at', 'created_at', 'updated_at'], 'safe'],
            [['mapping_id', 'scheme_id', 'doc_id', 'is_mandate', 'originating_type'], 'integer'],
            [['operation_type'], 'string', 'max' => 10],
            [['history_created_by', 'created_by', 'updated_by'], 'string', 'max' => 14],
            [['union_code'], 'string', 'max' => 3],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operation_type' => 'Operation Type',
            'history_created_at' => 'History Created At',
            'history_created_by' => 'History Created By',
            'mapping_id' => 'Mapping ID',
            'scheme_id' => 'Scheme ID',
            'doc_id' => 'Doc ID',
            'is_mandate' => 'Is Mandate',
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
