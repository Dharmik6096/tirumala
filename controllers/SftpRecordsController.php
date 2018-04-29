<?php

namespace app\controllers;

use Yii;
use app\models\SftpRecords;
use app\models\SftpRecordsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PHPExcel;

/**
 * SftpRecordsController implements the CRUD actions for SftpRecords model.
 */
class SftpRecordsController extends Controller {

    public $freeAccessActions = ['upload-data'];

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionUploadData() {
        $results = SftpRecords::find()->where(['status' => 'n'])->all();
        $header = [
            'mime' => 'application/csv',
            'extension' => 'csv',
            'writer' => 'CSV',
        ];
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $rowCount = 1;
        $column = 'A';
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'Transaction  Type');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'Beneficiary Code');
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'Beneficiary Account Number');
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'Instrument Amount');
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 'Beneficiary Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, 'Drawee Location');
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, 'Print Location');
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, 'Bene Address 1');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, 'Bene Address 2');
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, 'Bene Address 3');
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, 'Bene Address 4');
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, 'Bene Address 5');
        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, 'Instruction Reference Number');
        $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, 'Customer Reference Number');
        $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, 'Payment details 1');
        $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, 'Payment details 2');
        $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, 'Payment details 3');
        $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, 'Payment details 4');
        $objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, 'Payment details 5');
        $objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, 'Payment details 6');
        $objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, 'Payment details 7');
        $objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, 'Cheque Number');
        $objPHPExcel->getActiveSheet()->SetCellValue('W' . $rowCount, 'Cheque Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowCount, 'MICR NO');
        $objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowCount, 'IFSC COD');
        $objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, 'BENE BANK');
        $objPHPExcel->getActiveSheet()->SetCellValue('AA' . $rowCount, 'Bene Bank Bracnh');
        $objPHPExcel->getActiveSheet()->SetCellValue('AB' . $rowCount, 'Bene Emai Id');
        foreach ($results as $row) {
            $rowCount++;

            //$objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount)
            //        ->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            //$objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)
            //        ->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row['trans_type']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['ben_code']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['ben_acc_no']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['inst_amt']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row['ben_name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row['drawee_loc']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row['print_loc']);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row['ben_addr_1']);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row['ben_addr_2']);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row['ben_addr_3']);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row['ben_addr_4']);
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $row['ben_addr_5']);
            $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $row['inst_ref_no']);
            $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $row['cust_ref_no']);
            $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $row['pay_details_1']);
            $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $row['pay_details_2']);
            $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $row['pay_details_3']);
            $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, $row['pay_details_4']);
            $objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, $row['pay_details_5']);
            $objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, $row['pay_details_6']);
            $objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, $row['pay_details_7']);
            $objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, $row['cheque_no']);
            $objPHPExcel->getActiveSheet()->SetCellValue('W' . $rowCount, $row['cheque_date']);
            $objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowCount, $row['micr_no']);
            $objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowCount, $row['ifsc']);
            $objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, $row['bene_bank']);
            $objPHPExcel->getActiveSheet()->SetCellValue('AA' . $rowCount, $row['bene_branch']);
            $objPHPExcel->getActiveSheet()->SetCellValue('AB' . $rowCount, $row['bene_email']);
        }
        $fileName = "payment_disburse." . $header['extension'];
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
        $path=Yii::$app->basePath . '/sftp';
        Yii::$app->general->checkDirectory($path);
        $objWriter->save($path.$fileName);
    }

    /**
     * Lists all SftpRecords models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SftpRecordsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SftpRecords model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SftpRecords model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new SftpRecords();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SftpRecords model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SftpRecords model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SftpRecords model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SftpRecords the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SftpRecords::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
