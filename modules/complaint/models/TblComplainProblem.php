<?php

namespace app\modules\complaint\models;

use Yii;

/**
 * This is the model class for table "tbl_complain_problem".
 *
 * @property integer $complain_problem_code
 * @property string $union_code
 * @property string $problem_desc
 * @property string $asset_code
 * @property string $created_at
 * @property string $created_by
 */
class TblComplainProblem extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complain_problem';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'problem_desc', 'asset_code', 'created_at', 'created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'complain_problem_code' => Yii::t('app', 'Complain Problem Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'problem_desc' => Yii::t('app', 'Problem Desc'),
            'asset_code' => Yii::t('app', 'Asset Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

//    public function geComplainProblemList($product_code = '') {
//        $value = $this->getComplainProblem($product_code);
//        $value = ArrayHelper::map($value, 'Id', 'cmpl_problem_name');
//        return $value;
//    }
//
//    public function getComplainProblem($product_code = '') {
//        $query = $this->find()->select(['Id', 'cmpl_problem_name'])->where(['is_active' => 1, 'cmpl_product_code' => $product_code]);
//        return $query->all();
//    }

}
