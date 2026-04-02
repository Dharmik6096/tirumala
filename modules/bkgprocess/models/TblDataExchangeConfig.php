<?php

namespace app\modules\bkgprocess\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "tbl_data_exchange_config".
 *
 * @property integer $data_exchange_code
 * @property string $union_code
 * @property string $tbl_name
 * @property string $sp_name
 * @property string $last_execution
 * @property integer $interval
 * @property string $next_execution
 * @property integer $priority
 * @property string $json_key
 * @property string $update_key
 * @property string $update_key_with
 * @property integer $is_active
 */
class TblDataExchangeConfig extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_data_exchange_config';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'tbl_name', 'sp_name', 'json_key', 'update_key', 'update_key_with'], 'string'],
                [['last_execution', 'next_execution', 'api_type', 'res_param_keys', 'authentication_key'], 'safe'],
                [['interval', 'priority', 'is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'data_exchange_code' => Yii::t('app', 'Data Exchange Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'tbl_name' => Yii::t('app', 'Tbl Name'),
            'sp_name' => Yii::t('app', 'Sp Name'),
            'last_execution' => Yii::t('app', 'Last Execution'),
            'interval' => Yii::t('app', 'Interval'),
            'next_execution' => Yii::t('app', 'Next Execution'),
            'priority' => Yii::t('app', 'Priority'),
            'json_key' => Yii::t('app', 'Json Key'),
            'update_key' => Yii::t('app', 'Update Key'),
            'update_key_with' => Yii::t('app', 'Update Key With'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    public function getDataExchangeConfig($limit = 100) {
        $query = $this->find()
                ->where(['is_active' => 1])
                ->andWhere(['<=', 'tbl_data_exchange_config.next_execution', date('Y-m-d H:i:s')]);
                if (!empty($this->api_type)) {
                    $query->andWhere(['api_type' => $this->api_type]);
                }
        $query->limit($limit);
        $query->orderBy(['priority' => SORT_ASC]);
        return $query->all();
    }

}
