<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\usermanagement\models\User;
use yii\helpers\ArrayHelper;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblUnions;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_formula_master".
 *
 * @property string $formula_code
 * @property string $formula_description
 * @property string $formula
 * @property string $wef_date
 * @property string $dcs_code
 * @property string $milk_type_code
 * @property string $rate_type_code
 * @property string $union_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class TblFormulaMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_formula';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['formula_code', 'formula_description', 'wef_date', 'milk_type_code', 'rate_type_code', 'union_code'], 'required'],
                [['wef_date', 'dcs_code', 'milk_type_code', 'rate_type_code', 'union_code', 'created_at', 'updated_at'], 'safe'],
                [['is_active'], 'integer'],
                [['formula_code', 'formula_description', 'formula'], 'string', 'max' => 500],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['formula'], 'required', 'on' => ['TextFormula']],
                [['is_active'], 'default', 'value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'formula_code' => Yii::t('app', 'Formula Code'),
            'formula_description' => Yii::t('app', 'Formula'),
            'formula' => Yii::t('app', 'Formula'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'dcs_code' => Yii::t('app', 'DCS Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'rate_type_code' => Yii::t('app', 'Rate Type'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateType() {
        return $this->hasOne(TblRateType::className(), ['code' => 'rate_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblFormulaMasterQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblFormulaMasterQuery(get_called_class());
    }

    public function getCode() {
        $data = $this->find()->select(["convert(int,MAX(formula_code)) as formula_code"])->one();
        return str_pad((int) $data['formula_code'] + 1, 10, '0', STR_PAD_LEFT);
    }

    public function getPurchaseRateFormula($dropdwon = FALSE) {
        $formula = ['formula' => '', 'code' => ''];
        $data = TblFormulaMaster::find()->select('formula_description,formula_code')->where(['milk_type_code' => $this->milk_type_code, 'rate_type_code' => $this->rate_type_code, 'union_code' => $this->union_code, 'is_active' => 1])->andWhere(['<=', 'CONVERT(date, wef_date)', $this->wef_date])->orderBy(['formula_code' => SORT_DESC])->all();
        if ($dropdwon) {
            $formula = ArrayHelper::map($data, 'formula_code', 'formula_description');
        } else {
            $formula = ($data) ? ['formula' => $data[0]->formula_description, 'code' => $data[0]->formula_code] : $formula;
        }
        return $formula;
    }

    public function getSameTypeData() {
        return $this->find()->where(['union_code' => $this->union_code, 'milk_type_code' => $this->milk_type_code, 'rate_type_code' => $this->rate_type_code, 'is_active' => 1])->one();
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

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

}
