<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\syncutility\models\TblSentbox;
use app\modules\dcsaccounting\models\TblTax;

/**
 * This is the model class for table "tbl_tax_detail".
 *
 * @property integer $tax_detail_code
 * @property integer $tax_group_code
 * @property string $percentage
 * @property integer $type
 * @property integer $basic_tax_code
 * @property integer $tax_code
 * @property integer $is_active
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
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
class TblTaxDetail extends \app\models\ChildModel {

    public $basic_tax_name, $ledger_code, $tax_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_tax_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['basic_tax_code', 'tax_code', 'type', 'percentage', 'union_code'], 'required'],
                [['percentage'], 'number', 'max' => 100],
                [['tax_detail_code', 'tax_group_code', 'type', 'basic_tax_code', 'tax_code', 'is_active', 'originating_type'], 'safe'],
                [['percentage'], 'number'],
                [['union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'basic_tax_name', 'ledger_code', 'tax_name'], 'safe'],
                [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tax_detail_code' => Yii::t('app', 'Tax Detail Code'),
            'tax_group_code' => Yii::t('app', 'Tax Group Code'),
            'percentage' => Yii::t('app', 'Percentage'),
            'type' => Yii::t('app', 'Type'),
            'basic_tax_code' => Yii::t('app', 'Basic Tax Code'),
            'tax_code' => Yii::t('app', 'Tax Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
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

    public function getDetailData($tax_code) {
        $data = $this->getDetail($tax_code);
        return ArrayHelper::map($data, 'tax_detail_code', function($array, $key) {
                    $operation = ($array->type == 0) ? 'Addition' : 'Substraction';
                    return $array->basic_tax_code . '$' . $array->basicTaxCode['basic_tax_name'] . '$' . $array->percentage . '$' . $operation;
                });
    }

    public function getBasicTaxCode() {
        return $this->hasOne(TblBasicTax::className(), ['basic_tax_code' => 'basic_tax_code']);
    }

    public function getDetail($tax_code) {
        return $this->find()->select(['tax_detail_code', 'percentage', 'type', 'basic_tax_code', 'tax_code'])->where(['is_active' => 1, 'tax_code' => $tax_code])->all();
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
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

    public function getTaxCode() {
        return $this->hasOne(TblTax::className(), ['tax_code' => 'tax_code']);
    }

}
