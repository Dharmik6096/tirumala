<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_tax_depends".
 *
 * @property integer $tax_depends_code
 * @property integer $tax_detail_code
 * @property integer $steps
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
class TblTaxDepends extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_tax_depends';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['tax_depends_code'], 'required'],
                [['tax_depends_code', 'tax_detail_code', 'originating_type'], 'integer'],
                [['union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
                [['created_at', 'updated_at', 'tax_details_code', 'steps', 'is_active'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tax_depends_code' => Yii::t('app', 'Tax Depends Code'),
            'tax_detail_code' => Yii::t('app', 'Tax Detail Code'),
            'steps' => Yii::t('app', 'Steps'),
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

    public function getTaxDetails() {
        return $this->hasOne(TblTaxDetail::className(), ['tax_detail_code' => 'tax_detail_code']);
    }

    public function getRecords($value) {

        $query = $this->find()->where(['tax_detail_code' => $value, 'is_active' => 1])->all();
        $data = [];
        if ($query) {
            foreach ($query as $row) {
                $operation = ($row->taxDetails->type == 0) ? 'Addition' : 'Substraction';
                $data[] = ['tax_code' => $row->taxDetails->basic_tax_code, 'tax_name' => $row->taxDetails->basicTaxCode->basic_tax_name, 'percentage' => $row->taxDetails->percentage, 'operation' => $operation];
            }
        }
        return $data;
    }

    public function getDepends($detail_id) {
        return $this->find()->select(['tax_detail_code', 'steps'])->where(['tax_detail_code' => $detail_id, 'is_active' => 1])->all();
    }

    public function getDetailRecord($id) {
        return $this->find()->where(['tax_detail_code' => $id])->all();
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
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
