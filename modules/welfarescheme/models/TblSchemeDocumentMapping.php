<?php

namespace app\modules\welfarescheme\models;

use Yii;
use app\modules\welfarescheme\models\TblSchemeDocumentMaster;
use app\modules\welfarescheme\models\TblSchemeMaster;
use app\modules\welfarescheme\models\TblSchemeApplicationDocuments;

/**
 * This is the model class for table "tbl_scheme_document_mapping".
 *
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
class TblSchemeDocumentMapping extends \app\models\ChildModel {

    public $application_id;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_scheme_document_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['scheme_id', 'doc_id', 'is_mandate', 'originating_type'], 'integer'],
                [['created_at', 'updated_at'], 'safe'],
                [['union_code'], 'string', 'max' => 3],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
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

    public function getDocId() {
        return $this->hasOne(TblSchemeDocumentMaster::className(), ['doc_id' => 'doc_id']);
    }

    public function getSchemeId() {
        return $this->hasOne(TblSchemeMaster::className(), ['scheme_id' => 'scheme_id']);
    }

    public function getExistingMapping() {
        $model = new TblSchemeDocumentMapping();
        $config = $model->find()
                ->select('doc_id')
                ->all();
        if (!empty($config)) {
            $codes = [];
            foreach ($config as $code) {
                $codes[] = $code->doc_id;
            }
            $query = $this->find()
                    ->where(['IN', 'doc_id', $codes])
                    ->all();
            return ArrayHelper::map($query, 'doc_id', 'doc_id');
        } else {
            return [];
        }
    }

    public function getExistMappedControl() {
        return $this->find()
                        ->where(['doc_id' => $this->doc_id])
                        ->one();
    }

    public function getApplicationDocument() {
        return $this->hasOne(TblSchemeApplicationDocuments::className(), ['scheme_id' => 'scheme_id', 'doc_id' => 'doc_id'])->andOnCondition(['tbl_scheme_application_documents.application_id' => $this->application_id]);
    }

    public function getDocumentMaster() {
        return $this->hasOne(TblSchemeDocumentMaster::className(), ['doc_id' => 'doc_id']);
    }

}
