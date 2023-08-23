<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblLoanProductSaleLocking;
use yii\data\ArrayDataProvider;

/**
 * TblLoanProductSaleLockingSearch represents the model behind the search form about `app\modules\payment\models\TblLoanProductSaleLocking`.
 */
class TblLoanProductSaleLockingSearch extends TblLoanProductSaleLocking {

    public $plant_code, $mcc_plant_code, $bmc_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['locking_code', 'from_date', 'to_date', 'locking_date', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'type'], 'safe'],
                [['total_count', 'originating_type'], 'integer'],
                [['plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
                [['from_date', 'to_date', 'locking_date', 'union_code'], 'required', 'on' => ['saleLockData']],
                [['to_date'], function ($attribute, $params) {
                    Yii::$app->general->dateRangeValidate($this, $attribute, $params, 'from_date', 'to_date', 10, '>', 'Day Difference can not be greater than 10.');
                }, 'skipOnEmpty' => false, 'on' => ['saleLockData']],
                [['union_code'], 'required', 'on' => ['saleLockData']],
        ];
    }

    public function attributeLabels() {
        return [
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'locking_date' => Yii::t('app', 'Booking Date'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = TblLoanProductSaleLocking::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_loan_product_sale_locking');

        if (!empty($this->from_date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), from_date, 126)', date('Y-m-d', strtotime($this->from_date))]);

        if (!empty($this->to_date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), to_date, 126)', date('Y-m-d', strtotime($this->to_date))]);

        if (!empty($this->locking_date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), locking_date, 126)', date('Y-m-d', strtotime($this->locking_date))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'total_count' => $this->total_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'locking_code', $this->locking_code])
                ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }

    public function viewsearch($params) {
        $query = TblLoanProductSaleDetails::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

//        Yii::$app->general->filterByNumber($query, $this, ['rate', 'quantity', 'amount']);
        // grid filtering conditions
        $query->andWhere([
            'tbl_loan_product_sale_details.reference_code' => $this->reference_code,
            'tbl_loan_product_sale_details.lock_date' => $this->lock_date,
            'tbl_loan_product_sale_details.data_lock' => $this->data_lock,
        ]);



        return $dataProvider;
    }

    public function locksearch($params) {
        //var_dump($params); exit;
        $this->load($params);

        $output = [];
        if (!empty($params)) {
            $sp_params = [
                'union_code' => '',
                'plant_code' => '',
                'union_code' => '',
                'mcc_plant_code' => '',
                'bmc_code' => '',
                'from_date' => '',
                'to_date' => '',
            ];
            $sp_params = array_merge($sp_params, $params['TblLoanProductSaleLockingSearch']);

            if (empty($this->plant_code)) {
                $this->plant_code = !empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : 0;
            }
            if (empty($this->mcc_plant_code)) {
                $this->mcc_plant_code = !empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : 0;
            }
            if (empty($this->bmc_code)) {
                $this->bmc_code = !empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : 0;
            }
            $sp_params['from_date'] = date('Y-m-d', strtotime($sp_params['from_date']));
            $sp_params['to_date'] = date('Y-m-d', strtotime($sp_params['to_date']));
            $sp_params['plant_code'] = $this->plant_code;
            $sp_params['mcc_plant_code'] = $this->mcc_plant_code;
            $sp_params['bmc_code'] = $this->bmc_code;
            unset($sp_params['locking_date']);

            $output = \Yii::$app->general->getSpData('Portal_loan_product_sale_data_lock', $sp_params);
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails

            $output = [];
        }
        $dataProvider = new ArrayDataProvider();
        if (!empty($output)) {
            $attr = '';
            foreach ($output[0] as $att => $value) {
                $attr .= "'" . $att . "',";
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $output,
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [],
                    'attributes' => [
                        $attr
                    ],
                ],
            ]);
        }
        //var_dump($output); exit;
        return $dataProvider;
    }

}
