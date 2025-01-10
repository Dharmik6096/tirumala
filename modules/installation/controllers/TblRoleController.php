<?php

namespace app\modules\installation\controllers;

use Yii;
use app\modules\installation\models\TblRole;
use app\modules\installation\models\TblRoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\installation\models\TblAction;
use app\modules\installation\models\TblRoleActionMapping;
use app\modules\installation\models\TblUserRoleMapping;
use app\modules\installation\models\TblUserAndroid;
use app\modules\installation\models\TblUserDownloadAck;
use app\modules\installation\models\TblAndroidInstallationDetails;

/**
 * TblRoleController implements the CRUD actions for TblRole model.
 */
class TblRoleController extends \app\controllers\ChildController {

    /**
     * Lists all TblRole models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblRoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblRole model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblRole model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblRole();
        $this->viewFile = "create";
        if ($this->model->load(Yii::$app->request->post())) {
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Role', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblRole model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = "update";
        $saveModel = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $data = Yii::$app->general->getSpData('portal_role_wise_identity_user', [$id]);
            $saveModel[] = $this->model;
            $this->setDownloadAck($data, $saveModel);
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Role', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblRole model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblRole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblRole the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblRole::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAppMenuMappingOld($id) {
        $models = $this->findModel($id);
        $this->model = new TblAction();
        $menuArray = [];
        $menuArray = $this->model->getActionDetail();
        $mappingModel = new TblRoleActionMapping();
        $selectedArray = [];
        $selectedArray = $mappingModel->getExistMapingMenu($id);
        if (Yii::$app->request->post()) {
            $postArray = [];
            $postArray = Yii::$app->request->post('child_routes');
            $master = [];
            $auto_inc = 1;
            $newAssignments = [];
            if (!empty($postArray)) {
                $newAssignments = $postArray;
            }
            $oldAssignments = [];
            if (!empty($selectedArray)) {
                $oldAssignments = array_keys($selectedArray);
            }
            $toAssign = array_diff($newAssignments, $oldAssignments);
            $toRevoke = array_values(array_diff($oldAssignments, $newAssignments));
            $delete = [];
            if (!empty($toRevoke)) {
                foreach ($toRevoke as $revoke_key) {
                    $model = new TblRoleActionMapping();
                    $model->action_code = (string) $revoke_key;
                    $model->role_code = $id;
                    $record = $model->getExistMappedmenus();
//                    $historyModel = new TblRoleActionMappingHistory();
//                    Yii::$app->operation->history($record, $historyModel, 'DELETE');
//                    $master[] = $historyModel;
                    if (!empty($record)) {
                        $delete[] = $record;
                    }
                }
            }

            if (!empty($toAssign)) {
                foreach ($toAssign as $Assign_key) {
                    $model = new TblRoleActionMapping();
                    $model->action_code = $Assign_key;
                    $model->role_code = $id;
                    $master[] = $model;
                    $auto_inc++;
                }
            }
            $data = Yii::$app->general->getSpData('portal_role_wise_identity_user', [$id]);
            $this->setDownloadAck($data, $master);
            $transaction = $this->generalModel->saveDeleteTransaction($master, [], $delete, ['Role Menu Mapping', 'edit']);
            $selectedArray = !empty($postArray) ? $postArray : [];
            if ($transaction == 'customRedirect') {
                return $this->render('_action_map', [
                            'model' => $this->model,
                            'mappingModel' => $mappingModel,
                            'selectedArray' => $selectedArray,
                            'menuArray' => $menuArray,
                            'id' => $id,
                            'models' => $models
                ]);
            }
        }
        return $this->render('_action_map', [
                    'model' => $this->model,
                    'mappingModel' => $mappingModel,
                    'selectedArray' => $selectedArray,
                    'menuArray' => $menuArray,
                    'id' => $id,
                    'models' => $models
        ]);
    }

    public function setDownloadAck($model, &$saveModel) {
        if (!empty($model)) {
            foreach ($model as $a) {
                $modelUsr = new TblUserAndroid();
                $modelUsr->setAttributes($a);
                $type = $modelUsr->getOrgType($modelUsr, 'type');
                $ackModel = new TblUserDownloadAck();
                $ackModel->attributes = $modelUsr->attributes;
                $existAck = $ackModel->getExistDataAck($type);
                if (!empty($existAck)) {
                    foreach ($existAck as $exist) {
                        $ackModel->updateAll(['download_pending' => 3], ['ack_id' => $exist['ack_id']]);
                    }
                }
                $this->setDownldAck($ackModel, $saveModel, $modelUsr);
            }
        }
    }

    public function setDownldAck($ackModel, &$saveModel, $usrData) {
        $dest_org_type = $usrData->getOrgType($usrData, 'type');
        $dest_org_id = $usrData->getOrgType($usrData, 'code');
        $androidInstallationDetail = new TblAndroidInstallationDetails();
        $activeDevice = $androidInstallationDetail->getActiveDeviceData($dest_org_id, $dest_org_type);
        if (!empty($activeDevice)) {
            foreach ($activeDevice as $value) {
                $usrAckModel = new TblUserDownloadAck();
//                $usrAckModel->attributes = $ackModel->attributes;
                $usrAckModel->device_id = $value->device_id;
                $usrAckModel->hash_key = NULL; //$hashKeys['hash_key'];               
                $usrAckModel->union_code = $ackModel->union_code;
                $usrAckModel->plant_code = $ackModel->plant_code;
                $usrAckModel->mcc_plant_code = $ackModel->mcc_plant_code;
                $usrAckModel->bmc_code = $ackModel->bmc_code;
                $usrAckModel->dcs_code = $ackModel->dcs_code;
                $usrAckModel->user_code = $usrData->user_code;
                $usrAckModel->download_pending = 1;
                $saveModel[] = $usrAckModel;
            }
        }
    }

    public function actionAppMenuMapping($id) {
        $models = $this->findModel($id);
        $this->model = new TblAction();
        $menuArray = $this->model->getActionDetail();
        $mappingModel = new TblRoleActionMapping();
        $selectedArray = $mappingModel->getExistMapingMenu($id);

        if (Yii::$app->request->post()) {
            $postArray = Yii::$app->request->post('child_routes', []);
            $newAssignments = !empty($postArray) ? $postArray : [];
            $oldAssignments = !empty($selectedArray) ? array_keys($selectedArray) : [];

            $toAssign = array_diff($newAssignments, $oldAssignments);
            $toRevoke = array_diff($oldAssignments, $newAssignments);

            $roleActionModels = [];
            if (!empty($toAssign)) {
                foreach ($toAssign as $assignKey) {
                    $roleActionModels[] = [
                        'action_code' => $assignKey,
                        'role_code' => $id,
                    ];
                }
            }

            $data = Yii::$app->general->getSpData('portal_role_wise_identity_user', [$id]);
            $ackIdsToUpdate = [];
            $saveModels = [];
            $this->setDownloadAckBatch($data, $saveModels, $ackIdsToUpdate);

            $db = Yii::$app->getDb();
            $transaction = $db->beginTransaction();
            try {
                if (!empty($toRevoke)) {
                    TblRoleActionMapping::deleteAll(['action_code' => $toRevoke, 'role_code' => $id]);
                }

                if (!empty($roleActionModels)) {
                    Yii::$app->getDb()->createCommand()->batchInsert('tbl_role_action_mapping', ['action_code', 'role_code'], $roleActionModels)->execute();
                }

                if (!empty($ackIdsToUpdate)) {
                    TblUserDownloadAck::updateAll(['download_pending' => 3], ['ack_id' => $ackIdsToUpdate]);
                }

                if (!empty($saveModels)) {
                    $firstAttributesModel = $saveModels[0]->attributes();
                    unset($firstAttributesModel[0]);
                    $chunks = array_chunk($saveModels, 1000);
                    foreach ($chunks as $chunk) {
                        $rows = array_map(function ($model) {
                            $attributes = $model->attributes;
                            unset($attributes['ack_id']);
                            return $attributes;
                        }, $chunk);
                        Yii::$app->getDb()->createCommand()->batchInsert('tbl_user_download_ack', $firstAttributesModel, $rows)->execute();
                    }
                }

                $transaction->commit();
                Yii::$app->getSession()->setFlash('success', ['type' => 'success', 'message' => 'Menu and action permissions updated successfully.']);
            } catch (\yii\db\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => 'Database error occurred while updating menu and action permissions']);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => 'An error occurred while updating menu and action permissions']);
            }

            return $this->render('_action_map', [
                        'model' => $this->model,
                        'mappingModel' => $mappingModel,
                        'selectedArray' => $newAssignments,
                        'menuArray' => $menuArray,
                        'id' => $id,
                        'models' => $models
            ]);
        }
        return $this->render('_action_map', [
                    'model' => $this->model,
                    'mappingModel' => $mappingModel,
                    'selectedArray' => $selectedArray,
                    'menuArray' => $menuArray,
                    'id' => $id,
                    'models' => $models
        ]);
    }

    public function setDownloadAckBatch($model, &$saveModel, &$ackIdsToUpdate) {
        if (!empty($model)) {
            foreach ($model as $a) {
                $ackModel = new TblUserDownloadAck();
                $ackModel->setAttributes($a);
                $dest_org_type = $this->getOrgType($ackModel);
                $dest_org_id = $this->getOrgType($ackModel, 'code');
                $existAck = $ackModel->getExistDataAck($dest_org_type);
                if (!empty($existAck)) {
                    foreach ($existAck as $exist) {
                        $ackIdsToUpdate[] = $exist['ack_id'];
                    }
                }
                $androidInstallationDetail = new TblAndroidInstallationDetails();
                $activeDevice = $androidInstallationDetail->getActiveDeviceData($dest_org_id, $dest_org_type);
                if (!empty($activeDevice)) {
                    foreach ($activeDevice as $value) {
                        $ackModel->device_id = $value->device_id;
                        $ackModel->hash_key = NULL;
                        $ackModel->download_pending = 1;
                        $ackModel->created_at = date('Y-m-d H:i:s');
                        $ackModel->created_by = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                        $ackModel->originating_org_code = \Yii::$app->session->get('organizations_code');
                        $ackModel->originating_org_type = 'PORTAL';
                        $ackModel->originating_type = 0;
                        $saveModel[] = $ackModel;
                    }
                }
            }
        }
    }

    public function getOrgType($data, $return = 'type') {
        if ($return == 'type') {
            if (!empty($data->dcs_code)) {
                return 'VLC';
            } elseif (!empty($data->bmc_code)) {
                return 'BMC';
            } elseif (!empty($data->mcc_plant_code)) {
                return 'MCC';
            }
        } else {
            if (!empty($data->dcs_code)) {
                return $data->dcs_code;
            } elseif (!empty($data->bmc_code)) {
                return $data->bmc_code;
            } elseif (!empty($data->mcc_plant_code)) {
                return $data->mcc_plant_code;
            }
        }
    }

}
