<?php

namespace app\modules\tms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tms\models\TblUserTrackingMovement;
use yii\data\ArrayDataProvider;
use yii\db\Expression;
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
            [['union_code', 'plant_code', 'mcc_plant_code', 'user_code'], 'required', 'on' => 'indexOther'],
            [['tracking_datetime', 'lat_long', 'module_name', 'module_code', 'user_code', 'mobile_no', 'login_type', 'device_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'union_code', 'plant_code', 'mcc_plant_code', 'department'], 'safe'],
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

        if (!empty($this->mcc_plant_code) && empty($this->user_code)) {
            $sp_param[] = $this->mcc_plant_code;
            $sp_param[] = 'MCC';
            $outputs = \Yii::$app->general->getSpData('sp_app_get_user_list_for_complain_assisgn', $sp_param);
            $userData = array_column($outputs, 'user_id');
        } else if (!empty($this->user_code)) {
            $userData = (array) $this->user_code;
        } else {
            $data = $this->getLatestUserTracking();
            $userData = array_column($data, 'user_code');
        }

        $sp_params = [implode(',', $userData)];
        $output = \Yii::$app->general->getSpData('proc_user_last_tracking_list', $sp_params);
        if ($returnType === 'latLong') {
            $dataArray = [];
            $serial_number = 1;
            foreach ($output as $row) {
                if (!empty($row['lat_long']) && strpos($row['lat_long'], ',') !== false) {
                    list($latitude, $longitude) = explode(',', $row['lat_long']);
                    $dataArray[] = [
                        'user_code' => $row['user_code'],
                        'user_name' => $row['user_name'],
                        'tracking_datetime' => $row['tracking_datetime'],
                        'lat' => trim($latitude),
                        'long' => trim($longitude),
                        'serial_number' => $serial_number++,
                    ];
                }
            }
            return $dataArray;
        } 

        return new ArrayDataProvider([
            'allModels' => $output,
            'pagination' => false,
            'sort' => [
                'attributes' => ['tracking_datetime', 'user_name', 'user_code', 'lat_long', 'login_type', 'mobile_no'],
            ],
        ]);
    }

    public function getLatestUserTracking() {
        $dateFilter = date('Y-m-d') . ' 00:00:00.000000';

        $userTracking = (new Query())
                ->select([
                    'user_code' => 'tu.created_by',
                    'created_at' => 'tu.created_at',
                    'rn' => new Expression('ROW_NUMBER() OVER (PARTITION BY tu.created_by ORDER BY tu.created_at DESC)')
                ])
                ->from(['tu' => 'tbl_user_tracking_movement'])
                ->innerJoin(['u' => 'user'], 'tu.created_by = u.user_code')
                ->where(['>=', 'tu.created_at', $dateFilter]);

        $flutterTracking = (new Query())
                ->select([
                    'user_code' => 'tf.created_by',
                    'created_at' => 'tf.created_at',
                    'rn' => new Expression('ROW_NUMBER() OVER (PARTITION BY tf.created_by ORDER BY tf.created_at DESC)')
                ])
                ->from(['tf' => 'tbl_flutter_app_tracking'])
                ->where(['>=', 'tf.created_at', $dateFilter])
                ->andWhere(['NOT IN', 'tf.login_type', ['farmer', 'VSP', '']]);

        $combinedTracking = (new Query())
                ->select(['user_code', 'created_at'])
                ->from(['ut' => $userTracking])
                ->where(['rn' => 1])
                ->union(
                (new Query())
                ->select(['user_code', 'created_at'])
                ->from(['ft' => $flutterTracking])
                ->where(['rn' => 1]), true
        );

        $rankedCombined = (new Query())
                ->select([
                    '*',
                    'rnq' => new Expression('ROW_NUMBER() OVER (PARTITION BY user_code ORDER BY created_at DESC)')
                ])
                ->from(['ct' => $combinedTracking]);

        $query = (new Query())
                ->select('*')
                ->from(['rc' => $rankedCombined])
                ->where(['rnq' => 1])
                ->orderBy(['created_at' => SORT_DESC]);

        return $query->all();
    }

    public function searchUser($params) {
        $this->load($params);
        $dataArray = [];
        if (!empty($this->user_code) && !empty($this->tracking_datetime)) {
            $sp_param[] = date('Y-m-d', strtotime($this->tracking_datetime));
            $sp_param[] = date('Y-m-d', strtotime($this->tracking_datetime));
            $sp_param[] = $this->user_code;

            $results = \Yii::$app->general->getSpData('proc_user_tracking_list', $sp_param);

            foreach ($results as $result) {
                list($latitude, $longitude) = explode(',', $result['lat_long']);
                $dataArray[] = [
                    'tracking_datetime' => $result['tracking_datetime'],
                    'lat' => $latitude,
                    'long' => $longitude,
                ];
            }
        }
        return array_slice($dataArray, 0, 25);
    }

}
