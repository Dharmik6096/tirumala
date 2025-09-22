<?php

namespace app\modules\usermanagement\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use webvimark\modules\UserManagement\models\User;
use app\models\TblUserOrganizationMapping;

/**
 * UserSearch represents the model behind the search form about `webvimark\modules\UserManagement\models\User`.
 */
class UserSearch extends \webvimark\modules\UserManagement\models\search\UserSearch {

    public function rules() {
        return [
                [['id', 'superadmin', 'status', 'created_at', 'updated_at', 'email_confirmed', 'is_active'], 'integer'],
                [['username', 'gridRoleSearch', 'registration_ip', 'email', 'user_code', 'name', 'user_type_id'], 'string'],
                [['department', 'allow_app_login', 'mobile_no', 'is_engineer'], 'safe']
        ];
    }

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = User::find();
        $query->joinWith(['userType', 'departmentCode']);
        if (!Yii::$app->user->isSuperadmin && Yii::$app->session->get('organizations_type') != 'FEDERATION') {
            //  $query->joinWith(['organizations']);
            $org_array = [];
            $unions = explode(',', Yii::$app->session->get('Unions'));
            $feds = explode(',', Yii::$app->session->get('Federations'));
            $dcs = explode(',', Yii::$app->session->get('Dcs'));
            $plant = explode(',', Yii::$app->session->get('Plant'));
            $mcc = explode(',', Yii::$app->session->get('MCC'));
            $bmc = explode(',', Yii::$app->session->get('BMC'));
            $union_plant = empty(Yii::$app->session->get('Plant')) ? Yii::$app->general->getMappedData($unions, 'TblPlant', 'plant_code', 'union_code') : $plant;
            $plant_mcc = empty(Yii::$app->session->get('MCC')) ? Yii::$app->general->getMappedData($union_plant, 'TblMccPlant', 'mcc_plant_code', 'plant_code') : $mcc;
            $mcc_bmc = empty(Yii::$app->session->get('BMC')) ? Yii::$app->general->getMappedData($plant_mcc, 'TblDcsBmc', 'bmc_code', 'mcc_plant_code') : $bmc;
            $bmc_dcs = empty(Yii::$app->session->get('Dcs')) ? Yii::$app->general->getMappedData($mcc_bmc, 'TblDcs', 'dcs_code', 'bmc_code') : $dcs;
            $org_array = array_merge($unions, $feds, $plant, $mcc, $bmc, $dcs, $union_plant, $plant_mcc, $mcc_bmc, $bmc_dcs);
            $org_string = "'" . implode(',', $org_array) . "'";
            $command = Yii::$app->db->createCommand("SELECT distinct code from [SplitToTable](" . $org_string . ",',')");
            $org_codes = $command->sql;

            $organization_mapping = TblUserOrganizationMapping::find()->distinct()->select(['user_id'])
                    ->where(['tbl_user_organization_mapping.organization_type' => Yii::$app->general->getChildOrgs()[0]])
                    ->andWhere('tbl_user_organization_mapping.organization_code in (' . $org_codes . ')');

            $query->where(['superadmin' => 0]);
            $query->andWhere(['user.id' => $organization_mapping]);

            // $query->andWhere(['tbl_user_organization_mapping.organization_type' => Yii::$app->general->getChildOrgs()[0]]);
            // $query->andWhere('tbl_user_organization_mapping.organization_code in (' . $org_codes . ')');
            $query->orWhere(['user.id' => Yii::$app->session->get('UserCode')]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'superadmin' => $this->superadmin,
            'status' => $this->status,
            'user.is_active' => $this->is_active,
            'user.allow_app_login' => $this->allow_app_login,
            'user.is_engineer' => $this->is_engineer,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'user_code', $this->user_code])
                ->andFilterWhere(['like', 'tbl_user_types.user_type', $this->user_type_id])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'tbl_department.department', $this->department]);

        return $dataProvider;
    }

}
