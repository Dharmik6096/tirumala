<?php

namespace app\modules\welfarescheme\models;

use Yii;

/**
 * This is the model class for table "tbl_scheme_application_documents_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property integer $app_doc_id
 * @property integer $application_id
 * @property integer $doc_id
 * @property string $file_name
 * @property string $file_path
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblSchemeApplicationDocumentsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_scheme_application_documents_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['history_created_at', 'created_at', 'updated_at', 'scheme_id'], 'safe'],
                [['app_doc_id', 'application_id', 'doc_id', 'originating_type'], 'integer'],
                [['operation_type'], 'string', 'max' => 10],
                [['history_created_by', 'created_by', 'updated_by'], 'string', 'max' => 14],
                [['file_name'], 'string', 'max' => 100],
                [['file_path'], 'string', 'max' => 255],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
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
            'app_doc_id' => 'App Doc ID',
            'application_id' => 'Application ID',
            'doc_id' => 'Doc ID',
            'file_name' => 'File Name',
            'file_path' => 'File Path',
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
