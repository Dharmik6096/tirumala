<?php

namespace app\modules\welfarescheme\models;

use Yii;
use app\modules\welfarescheme\models\TblSchemeDocumentMapping;
use app\modules\welfarescheme\models\TblSchemeDocumentMaster;

/**
 * This is the model class for table "tbl_scheme_application_documents".
 *
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
class TblSchemeApplicationDocuments extends \app\models\ChildModel {

    public $is_mandate, $doc_name, $doc_ext;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_scheme_application_documents';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['application_id', 'doc_id', 'originating_type'], 'safe'],
                [['created_at', 'updated_at'], 'safe'],
                [['file_name'], 'string', 'max' => 100],
                [['file_path'], 'string', 'max' => 255],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
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

    public function getDocumentMappingCode() {
        return $this->hasOne(TblSchemeDocumentMapping::className(), ['scheme_id' => 'scheme_id', 'doc_id' => 'doc_id']);
    }

    public function getDocumentMasterCode() {
        return $this->hasOne(TblSchemeDocumentMaster::className(), ['doc_id' => 'doc_id']);
    }

}
