<?php

namespace app\modules\tms\models;

use app\models\TblUserOrganizationMapping;
use app\modules\dcsoperation\models\TblMemberProvisional;
use app\modules\feedback\models\TblMppSurvey;
use app\modules\feedback\models\TblMRGMeetingMaster;
use app\modules\feedback\models\TblNonMemberHouseHoldVisit;
use app\modules\feedback\models\TblVCGMeetingMaster;
use app\modules\feedback\models\TblVCGMRGMember;
use app\modules\globalmaster\models\TblDesignation;
use app\modules\organisation\models\TblCustomerMasterProvisional;
use app\modules\organisation\models\TblDcsProvisional;
use app\modules\organisation\models\TblOrganizationLatlong;
use app\modules\product\models\TblIndentMaster;
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
            [['union_code', 'plant_code', 'mcc_plant_code', 'user_code', 'tracking_datetime'], 'required', 'on' => 'indexOther'],
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
        return $dataArray;
    }

    public function getUserDetail($params, $output) {
        $this->load($params);
        if (!empty($this->user_code)) {
            $userData = $this->userCode;
            $organization_mapping = TblUserOrganizationMapping::find()->where(['user_id' => $this->user_code])->all();
            $assign_detail_count = !empty($organization_mapping) ? Yii::t('app', $organization_mapping[0]->organization_type) . '(' . count($organization_mapping) . ')' : 'Not Available';
            $designation = !empty($userData->designation_code) && ($model = TblDesignation::findOne($userData->designation_code)) !== null ? $model->designation_name : 'Not Available';
            $formattedTrackingDate = Yii::$app->formatter->asDate($this->tracking_datetime, DATE_FORMAT);
            $dataArray = [];
            if(!empty($output)){
                $organizationLatlongData = TblOrganizationLatlong::find()->alias('A')
                    ->leftJoin('tbl_organization_latlong_applicability B', 'A.customer_type = B.applicable_for AND A.customer_code = B.applicable_code')
                    ->andWhere(['B.user_code' => $this->user_code, 'A.is_active' => 1])
                    ->andWhere(['IS NOT', 'A.lat_long', NULL])
                    ->andWhere(['!=', 'A.lat_long', ','])
                    ->select(['A.*', 'dcs.dcs_name', 'dcs.ref_code as dcs_ref_code', 'mcc.name', 'mcc.ref_code as mcc_ref_code', 'bmc.bmc_name', 'bmc.ref_code as bmc_ref_code'])
                    ->leftJoin('tbl_dcs dcs', 'dcs.dcs_code = A.customer_code')
                    ->leftJoin('tbl_mcc_plant mcc', 'mcc.mcc_plant_code = A.customer_code')
                    ->leftJoin('tbl_bmc bmc', 'bmc.bmc_code = A.customer_code')
                    ->asArray()
                    ->all();

                foreach ($organizationLatlongData as $result) {
                    $info = '<div class="map_info_content">';
                    $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'Type') . ' :</span> ' . Yii::t('app', $result['customer_type']) . '</p>';
                    $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'Name') . ' :</span> ' .
                        ($result['customer_type'] == 'MCC' ? $result['name'] . ' - ' . $result['mcc_ref_code'] :
                        ($result['customer_type'] == 'BMC' ? $result['bmc_name'] . ' - ' . $result['bmc_ref_code'] :
                        ($result['customer_type'] == 'DCS' ? $result['dcs_name']. ' - ' . $result['dcs_ref_code'] : $result['customer_code']))) . '</p>'; // OFFICE, HOME, OTHER
                    $info .= '</div>';
                    list($latitude, $longitude) = explode(',', $result['lat_long']);
                    $dataArray[] = [
                        'info' => $info,
                        'lat' => $latitude,
                        'long' => $longitude,
                    ];
                }
            }

            $userDetailData['organizationLatlongData'] = $dataArray;
            $userDetailData['userDetail'] = [
                'id' => $userData->id,
                'name' => $userData->name,
                'employee_id' => $userData->employee_id ?: 'Not Available',
                'mobile_no' => $userData->mobile_no ?: 'Not Available',
                'department' => $userData->department ?: 'Not Available',
                'designation' => $designation,
                'assign_detail_count' => $assign_detail_count,
                'tracking_datetime' => $this->tracking_datetime,
                'total_tasks' => TblTaskActivity::find()->where(['user_code' => $this->user_code, 'CAST(activity_datetime AS DATE)' => $formattedTrackingDate])->count(),
                'total_indents' => TblIndentMaster::find()->where(['created_by' => $this->user_code, 'CAST(indent_date AS DATE)' => $formattedTrackingDate])->count(),
                'total_enrollment_members' => TblMemberProvisional::find()->where(['created_by' => $this->user_code, 'CAST(created_at AS DATE)' => $formattedTrackingDate])->count(),
                'total_vlcc_creation' => TblDcsProvisional::find()->where(['created_by' => $this->user_code, 'CAST(created_at AS DATE)' => $formattedTrackingDate])->count(),
                'total_bulk_vendor_creation' => TblCustomerMasterProvisional::find()->where(['created_by' => $this->user_code, 'CAST(created_at AS DATE)' => $formattedTrackingDate])->count(),
                'total_vcg_mrg_member_selection' => TblVCGMRGMember::find()->where(['created_by' => $this->user_code, 'CAST(created_at AS DATE)' => $formattedTrackingDate])->count(),
                'total_vcg_meetings' => TblVCGMeetingMaster::find()->where(['created_by' => $this->user_code, 'CAST(created_at AS DATE)' => $formattedTrackingDate])->count(),
                'total_mrg_meetings' => TblMRGMeetingMaster::find()->where(['created_by' => $this->user_code, 'CAST(created_at AS DATE)' => $formattedTrackingDate])->count(),
                'total_household_visits' => TblNonMemberHouseHoldVisit::find()->where(['created_by' => $this->user_code, 'CAST(created_at AS DATE)' => $formattedTrackingDate])->count(),
                'total_mpp_survey' => TblMppSurvey::find()->where(['created_by' => $this->user_code, 'CAST(created_at AS DATE)' => $formattedTrackingDate])->count(),
            ];
            return $userDetailData;
        }
    }

}
