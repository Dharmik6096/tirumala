<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblLoanProductSaleLocking;
use app\modules\payment\models\TblLoanProductSaleLockingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\payment\models\TblLoanProductSaleDetails;
use app\modules\payment\models\TblLoanProductSaleDetailsSearch;
use app\modules\payment\models\TblProductSaleTransaction;
use app\modules\payment\models\TblProductSaleTransactionHistory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * TblLoanProductSaleLockingController implements the CRUD actions for TblLoanProductSaleLocking model.
 */
class TblLoanProductSaleLockingController extends \app\controllers\ChildController {

    /**
     * Lists all TblLoanProductSaleLocking models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLoanProductSaleLockingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblLoanProductSaleLocking model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $searchModel = new TblLoanProductSaleDetailsSearch();
        $searchModel->data_lock = 1;
        $searchModel->lock_date = $model->locking_date;
        $searchModel->reference_code = $id;
        $dataProvider = $searchModel->viewsearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $model,
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblLoanProductSaleLocking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $searchModel = new TblLoanProductSaleLockingSearch();
        $searchModel->scenario = 'saleLockData';
        $saveModel = [];
        if (Yii::$app->request->post()) {
            $searchData = Yii::$app->request->post()['TblLoanProductSaleLockingSearch'];
            $postCodes = Yii::$app->request->post()['sale_detail_code'];
            $data = Yii::$app->request->post()['data'];
            $model = new TblLoanProductSaleLocking();
            $model->setAttributes($searchData);
            $model->locking_code = Yii::$app->general->getCodeAutoIncrement($model);
            $model->from_date = !empty($model->from_date) ? date('Y-m-d', strtotime($model->from_date)) : '';
            $model->to_date = !empty($model->to_date) ? date('Y-m-d', strtotime($model->to_date)) : '';
            $model->locking_date = !empty($model->locking_date) ? date('Y-m-d', strtotime($model->locking_date)) : '';
            $model->total_count = count($postCodes);
            if (Yii::$app->request->post()['submitType'] == 'lock') {
                $saveModel[] = $model;
                foreach ($postCodes as $i => $updateData) {
                    if (!empty($updateData)) {
                        if ($data[$i] == 'LOANPRODUCT') {
                            $modelSale = new TblLoanProductSaleDetails();
                            $existSale = $modelSale->find()->where(['sale_detail_code' => $updateData])->one();
//                        $historyModel = new TblLoanProductSaleDetailsHistory();
//                        Yii::$app->operation->history($existSale, $historyModel, UPDATE);
//                        $saveModel[] = $historyModel;
                        } elseif ($data[$i] = 'PRODUCT') {
                            $modelSale = new TblProductSaleTransaction();
                            $existSale = $modelSale->find()->where(['product_sale_transaction_code' => $updateData])->one();
                            $historyModel = new TblProductSaleTransactionHistory();
                            Yii::$app->operation->history($existSale, $historyModel, UPDATE);
                            $saveModel[] = $historyModel;
                        }
                        $existSale->scenario = 'locksale';
                        $existSale->data_lock = 1;
                        $existSale->lock_date = $model->locking_date;
                        $existSale->reference_code = $model->locking_code;
                        $saveModel[] = $existSale;
                    }
                }

                $transaction = $this->generalModel->saveTransaction($saveModel, ['PM Sale Data Lock', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            } else {
                $dataProvider = $searchModel->locksearch(Yii::$app->request->queryParams);
                $this->downloadData($dataProvider->getModels());
            }
        }
        $dataProvider = $searchModel->locksearch(Yii::$app->request->queryParams);
        return $this->render('create', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblLoanProductSaleLocking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblLoanProductSaleLocking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLoanProductSaleLocking::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExportProductSale($id) {
        $controls = [];
        $controls['ref_code'] = $id;
        $output = \Yii::$app->general->getSpData('Portal_download_pm_lock_data', $controls);


        $header = [
            'mime' => '	application/vnd.ms-excel',
            'extension' => 'xls',
            'writer' => IOFactory::WRITER_XLS,
        ];
        $objPHPExcel = new Spreadsheet();
        $sheet = $objPHPExcel->getActiveSheet();
        /* $objPHPExcel->getDefaultStyle()
          ->getNumberFormat()
          ->setFormatCode(
          \PHPExcel_Style_NumberFormat::FORMAT_TEXT
          ); */
        $file_header = !empty($output) ? array_keys($output[0]) : [];
        /* $file_header = array_map(function($file_header) {
          return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $file_header))));
          }, array_values($file_header)); */

        $sheet->fromArray(
                $file_header, // The data to set
                NULL, // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
        );
        $sheet->fromArray(
                $output, // The data to set
                NULL, // Array values with this value will not be set
                'A2'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
        );
        $labelArray = !empty($output) ? array_keys($output[0]) : [];
        $labelT = 'ProductSaleLock' . '-' . date('Ymdhis');
        $fileName = $labelT . '.' . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
//        $fileName = $this->data['title'] . '-' . date('Ymdhis') . '.' . $header['extension'] .
//                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = IOFactory::createWriter($objPHPExcel, IOFactory::WRITER_XLS);
        ob_end_clean();
        $objWriter->save('php://output');
        exit();
    }

    public function downloadData($downloadDetail) {
        $header = [
            'mime' => '	application/vnd.ms-excel',
            'extension' => 'xls',
            'writer' => IOFactory::WRITER_XLS,
        ];
        $objPHPExcel = new Spreadsheet();
        $sheet = $objPHPExcel->getActiveSheet();
        /* $objPHPExcel->getDefaultStyle()
          ->getNumberFormat()
          ->setFormatCode(
          \PHPExcel_Style_NumberFormat::FORMAT_TEXT
          ); */
        if (!empty($downloadDetail)) {
            for ($i = 0; $i < count($downloadDetail); $i++) {
                if (isset($downloadDetail[$i]['sale_detail_code'])) {
                    unset($downloadDetail[$i]['sale_detail_code']);
                }
            }
        }
        $file_header = !empty($downloadDetail) ? array_keys($downloadDetail[0]) : [];
        /* $file_header = array_map(function($file_header) {
          return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $file_header))));
          }, array_values($file_header)); */

        $sheet->fromArray(
                $file_header, // The data to set
                NULL, // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
        );
        $sheet->fromArray(
                $downloadDetail, // The data to set
                NULL, // Array values with this value will not be set
                'A2'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
        );
        $labelArray = !empty($downloadDetail) ? array_keys($downloadDetail[0]) : [];
        $labelT = 'PMSaleLock' . '-' . date('Ymdhis');
        $fileName = $labelT . '.' . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
//        $fileName = $this->data['title'] . '-' . date('Ymdhis') . '.' . $header['extension'] .
//                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        if (ob_get_contents()) ob_end_clean();
        $objWriter = IOFactory::createWriter($objPHPExcel, IOFactory::WRITER_XLS);
        $objWriter->save('php://output');
        exit();
    }

}
