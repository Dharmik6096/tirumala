<?php

namespace app\modules\dcsoperation\models;

use Yii;
use webvimark\modules\UserManagement\models\User;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMilkClass;
use app\modules\syncutility\models\TblSentbox;
use yii\base\UserException;

/**
 * This is the model class for table "tbl_local_milk_sale_rate".
 *
 * @property string $local_sale_rate_code
 * @property string $created_at
 * @property double $rate
 * @property string $updated_at
 * @property string $wef_date
 * @property string $created_by
 * @property string $dcs_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property TblSubCenter $subCenterCode
 * @property User $updatedBy
 */
class TblLocalMilkSaleRate extends \app\models\ChildModel {

    public $originalDcsCode;
    public $is_sentbox = TRUE;
    public $saveChildRecords = TRUE;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_local_milk_sale_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['wef_date', 'milk_type_code', 'milk_class', 'rate', 'union_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'milk_quality_type_code', 'local_milk_rate_code', 'originalDcsCode'], 'safe'],
            [['rate'], 'number'],
            [['wef_date'], 'required'],
            [['dcs_code'], 'required', 'message' => 'You must select atleast one society.'],
            [['dcs_code', 'local_milk_rate_code'], 'required', 'on' => ['importCsv']],
            [['wef_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['wef_date'], 'convertDate', 'on' => ['importCsv']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code'], 'on' => ['importCsv']],
            [['dcs_code'], 'validateDCS', 'on' => ['importCsv']],
            [['dcs_code'], 'unique', 'targetAttribute' => ['local_milk_rate_code', 'dcs_code', 'wef_date'], 'skipOnEmpty' => false, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'on' => ['importCsv']],
            [['wef_date'], 'formatWefDate', 'except' => ['androidsync']],
            [['dcs_code'], 'setImport', 'on' => ['importCsv']],
            [['dcs_code'], 'filter', 'filter' => function ($value) {
                    $this->originalDcsCode = $value;
                    return is_array($value) ? implode(',', $value) : $value;
                }],
            [['dcs_code'], 'unique', 'targetAttribute' => ['dcs_code', 'wef_date', 'milk_quality_type_code', 'milk_type_code', 'milk_class'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            ['dcs_code', 'filter', 'filter' => function ($value) {
                    return isset($this->originalDcsCode) ? $this->originalDcsCode : $value;
                }, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'local_milk_sale_rate_code' => Yii::t('app', 'Local Milk Sale Rate Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'milk_class' => Yii::t('app', 'Milk Class'),
            'rate' => Yii::t('app', 'Rate'),
            'union_code' => Yii::t('app', 'Union'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Organization Code'),
            'originating_org_type' => Yii::t('app', 'Originating Organization Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'Extra Column 1'),
            'x_col2' => Yii::t('app', 'Extra Column 2'),
            'x_col3' => Yii::t('app', 'Extra Column 3'),
            'x_col4' => Yii::t('app', 'Extra Column 4'),
            'x_col5' => Yii::t('app', 'Extra Column 5'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'local_milk_rate_code' => Yii::t('app', 'Local Milk Rate Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkClass() {
        return $this->hasOne(TblMilkClass::className(), ['id' => 'milk_class']);
    }

    /**
     * @inheritdoc
     * @return TblLocalMilkSaleRateQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblLocalMilkSaleRateQuery(get_called_class());
    }

    public function convertDateDot() {
        try {
            $this->wef_date = Yii::$app->controls->view_date($this->wef_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->wef_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->wef_date = !empty($this->wef_date) ? Yii::$app->controls->view_date($this->wef_date, 'php:Y-m-d') : NULL;
        }
    }

    public function formatWefDate($attribute, $params) {
        $this->wef_date = !empty($this->wef_date) ? date('Y-m-d', strtotime($this->wef_date)) : '';
    }

    public function validateDCS() {
        $dcsModel = new TblDcs();
        $records = $dcsModel->find()->select(['dcs_code'])->where(['or', ['dcs_code' => $this->dcs_code], ['ref_code' => $this->dcs_code], ['dcs_code_ex' => $this->dcs_code]])->all();
        if (!empty($records) && count($records) == 1) {
            $this->dcs_code = $records[0]->dcs_code;
        } else {
            $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' Is Invalid.'));
            return false;
        }
    }

    public function setImport($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->union_code = Yii::$app->general->getforeignkey($this->mainDcsCode, 'union_code');
            if (empty($this->localMilkRateCode)) {
                $this->addError($attribute, Yii::t('app/validation', Yii::t('app', 'local_milk_rate_code') . '  is invalid.'));
            } else {
                $this->rate = Yii::$app->general->getforeignkey($this->localMilkRateCode, 'rate');
                $this->milk_quality_type_code = Yii::$app->general->getforeignkey($this->localMilkRateCode, 'milk_quality_type_code');
                $this->milk_class = Yii::$app->general->getforeignkey($this->localMilkRateCode, 'milk_class');
                $this->milk_type_code = Yii::$app->general->getforeignkey($this->localMilkRateCode, 'milk_type_code');
            }
        }
    }

    public function getMainDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getLocalMilkRateCode() {
        return $this->hasOne(TblLocalMilkRate::className(), ['local_milk_rate_code' => 'local_milk_rate_code']);
    }

    public function setTransactionData(&$model, $json, &$childModel) {
        $LocalMilkRateModel = new TblLocalMilkRate();
        $modelData = $LocalMilkRateModel->find()->where([
                    'union_code' => $model->union_code,
                    'milk_quality_type_code' => $model->milk_quality_type_code,
                    'milk_type_code' => $model->milk_type_code,
                    'milk_class' => $model->milk_class,
                    'rate' => $model->rate,
                ])->one();

        if (!empty($modelData)) {
            $model->local_milk_rate_code = $modelData->local_milk_rate_code;
        } else {
            $model->local_milk_rate_code = Yii::$app->general->getCodeAutoIncrement($LocalMilkRateModel);
            $attribute = $model->attributes;
            $LocalMilkRateModel->setAttributes($attribute);
            $LocalMilkRateModel->created_at = date('Y-m-d H:i:s');
            $childModel[] = $LocalMilkRateModel;
        }
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function afterDelete() {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

}
