<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMccShiftLock;
use app\modules\collection\models\TblMccShiftLockHistory;
use yii\db\Expression;
use yii\db\ActiveQuery;
use yii\data\ArrayDataProvider;

/**
 * TblMccShiftLockSearch represents the model behind the search form about `app\modules\collection\models\TblMccShiftLock`.
 */
class TblMccShiftLockSearch extends TblMccShiftLock {

    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['shift_lock_code', 'union_code', 'plant_code', 'mcc_plant_code', 'shift_code', 'date_time_of_collection', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['data_lock', 'originating_type'], 'integer'],
                [['f_union_code', 'f_plant_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['shiftLock']],
                [['from_date', 'to_date', 'from_shift', 'to_shift', 'f_mcc_code', 'f_bmc_code'], 'safe'],
                [['x_col1', 'f_union_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['shiftLockMember']],
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
        $query = TblMccShiftLock::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'date_time_of_collection' => $this->date_time_of_collection,
            'data_lock' => $this->data_lock,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'shift_lock_code', $this->shift_lock_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'shift_code', $this->shift_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'x_col1', $this->x_col1])
                ->andFilterWhere(['like', 'x_col2', $this->x_col2])
                ->andFilterWhere(['like', 'x_col3', $this->x_col3])
                ->andFilterWhere(['like', 'x_col4', $this->x_col4])
                ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }

    public function viewsearch($params) {
        $query = TblMccShiftLockHistory::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'shift_lock_code' => $this->shift_lock_code,
        ]);
        $query->orderBy('id desc');
        return $dataProvider;
    }

    public function shiftlocksearch($params, $sp = 'portal_mcc_shift_lock_data') {
        $this->load($params);

        $output = [];
        if (!empty($params) && $this->validate()) {
            $sp_params = [
                'f_union_code' => '',
                'f_plant_code' => '',
                'f_mcc_code' => '',
                'from_date' => '',
                'from_shift' => '',
                'to_date' => '',
                'to_shift' => ''];

            $sp_params = array_merge($sp_params, $params['TblMccShiftLockSearch']);
            $mcc_array = $mcc_string = $params['TblMccShiftLockSearch']['f_mcc_code'];
            if (empty($this->f_mcc_code)) {
                $this->f_mcc_code = !empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : 0;
            } else if (is_array($mcc_array)) {
                $mcc_string = implode(',', $mcc_array);
                $this->f_mcc_code = ',' . $mcc_string . ',';
            }

            $from_shift = Yii::$app->general->getshift($sp_params['from_shift']);
            $to_shift = Yii::$app->general->getshift($sp_params['to_shift']);
            $sp_params['from_date'] = date('Y-m-d H:i:s', strtotime($sp_params['from_date'] . ' ' . $from_shift));
            $sp_params['to_date'] = date('Y-m-d H:i:s', strtotime($sp_params['to_date'] . ' ' . $to_shift));
            $sp_params['f_mcc_code'] = $this->f_mcc_code;
            unset($sp_params['from_shift']);
            unset($sp_params['to_shift']);


            $output = \Yii::$app->general->getSpData($sp, $sp_params);
            $this->f_mcc_code = $mcc_array;
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

    public function shiftlockmembersearch($params, $sp = 'portal_mcc_shift_lock_data_member') {
        $this->load($params);

        $output = [];
        if (!empty($params) && $this->validate()) {
            $sp_params = [
                'f_union_code' => '',
                'f_bmc_code' => '',
                'x_col1' => '',
                'from_date' => '',
                'from_shift' => '',
                'to_date' => '',
                'to_shift' => ''];

            $sp_params = array_merge($sp_params, $params['TblMccShiftLockSearch']);
            $bmc_array = $bmc_string = $params['TblMccShiftLockSearch']['f_bmc_code'];
            if (empty($this->f_bmc_code)) {
                $this->f_bmc_code = !empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : 0;
            } else if (is_array($bmc_array)) {
                $bmc_string = implode(',', $bmc_array);
                $this->f_bmc_code = ',' . $bmc_string . ',';
            }

            $from_shift = Yii::$app->general->getshift($sp_params['from_shift']);
            $to_shift = Yii::$app->general->getshift($sp_params['to_shift']);
            $sp_params['from_date'] = date('Y-m-d H:i:s', strtotime($sp_params['from_date'] . ' ' . $from_shift));
            $sp_params['to_date'] = date('Y-m-d H:i:s', strtotime($sp_params['to_date'] . ' ' . $to_shift));
            $sp_params['f_bmc_code'] = $this->f_bmc_code;
            unset($sp_params['from_shift']);
            unset($sp_params['to_shift']);


            $output = \Yii::$app->general->getSpData($sp, $sp_params);
            $this->f_bmc_code = $bmc_array;
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
        return $dataProvider;
    }

}
