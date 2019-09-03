<?php

namespace app\modules\syncutility\models;

use Yii;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_pendrive_import_export".
 *
 * @property integer $id
 * @property string $union_code
 * @property string $dcs_code
 * @property string $file_name
 * @property integer $no_of_records
 * @property resource $mode
 * @property string $created_at
 * @property string $created_by
 * @property integer $no_of_records_ignore
 * @property integer $download_counter
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblPendriveImportExport extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_pendrive_import_export';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'dcs_code', 'file_name', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
            [['no_of_records', 'no_of_records_ignore', 'download_counter', 'originating_type'], 'integer'],
            [['mode', 'created_by'], 'safe'],
            [['dcs_code'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'union_code' => Yii::t('app', 'Union Code'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'file_name' => Yii::t('app', 'File Name'),
            'no_of_records' => Yii::t('app', 'No Of Records'),
            'mode' => Yii::t('app', 'Mode'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'no_of_records_ignore' => Yii::t('app', 'No Of Records Ignore'),
            'download_counter' => Yii::t('app', 'Download Counter'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblPendriveImportExportQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblPendriveImportExportQuery(get_called_class());
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

}
