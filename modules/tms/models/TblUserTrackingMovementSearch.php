<?php

namespace app\modules\tms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tms\models\TblUserTrackingMovement;
use yii\db\Query;

/**
 * TblUserTrackingMovementSearch represents the model behind the search form about `app\modules\tms\models\TblUserTrackingMovement`.
 */
class TblUserTrackingMovementSearch extends TblUserTrackingMovement {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tracking_id', 'originating_type'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'user_code'], 'required'],
            [['tracking_datetime', 'lat_long', 'module_name', 'module_code', 'user_code', 'mobile_no', 'login_type', 'device_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'union_code', 'plant_code', 'mcc_plant_code'], 'safe'],
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
    public function search($params, $returnType = 'dataProvider') {
        $this->load($params);

        $query = TblUserTrackingMovement::find()
                ->joinWith('userCode')
                ->innerJoin(
                ['last_movement' => (new Query())
            ->select(['user_code', 'MAX(tracking_datetime) AS max_tracking_datetime'])
            ->from('tbl_user_tracking_movement')
            ->groupBy('user_code')], 'tbl_user_tracking_movement.user_code = last_movement.user_code AND tbl_user_tracking_movement.tracking_datetime = last_movement.max_tracking_datetime'
        );

        if (!empty($this->tracking_datetime)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_user_tracking_movement.tracking_datetime, 126)', date('Y-m-d', strtotime($this->tracking_datetime))]);
        }

        if ($this->user_code != 0) {
            $query->andFilterWhere(['tbl_user_tracking_movement.user_code' => $this->user_code]);
        }

        if ($returnType == 'latLong') {
            $dataArray = [];
            if (!empty($this->user_code) || $this->user_code == 0) {
                $results = $query->all();
                foreach ($results as $result) {
                    list($latitude, $longitude) = explode(',', $result->lat_long);
                    $dataArray[] = [
                        'user_code' => $result->user_code,
                        'user_name' => $result->userCode->name,
                        'tracking_datetime' => $result->tracking_datetime,
                        'lat' => $latitude,
                        'long' => $longitude,
                    ];
                }
            }
            return $dataArray;
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => false,
            ]);

            if (!$this->validate()) {
                $query->where('0=1');
                return $dataProvider;
            }

            return $dataProvider;
        }
    }

    public function searchUser($params) {
        $this->load($params);
        $dataArray = [];
        if (!empty($this->user_code) && !empty($this->tracking_datetime)) {
            $results = TblUserTrackingMovement::find()
                    ->where(['user_code' => $this->user_code])
                    ->andWhere(['like', 'CONVERT(VARCHAR(25), tbl_user_tracking_movement.tracking_datetime, 126)', date('Y-m-d', strtotime($this->tracking_datetime))])
                    ->orderBy(['tracking_datetime' => SORT_ASC])
                    ->all();

            foreach ($results as $result) {
                list($latitude, $longitude) = explode(',', $result->lat_long);
                $dataArray[] = [
                    'tracking_datetime' => $result->tracking_datetime,
                    'lat' => $latitude,
                    'long' => $longitude,
                ];
            }
        }

        return $dataArray;
    }

}
