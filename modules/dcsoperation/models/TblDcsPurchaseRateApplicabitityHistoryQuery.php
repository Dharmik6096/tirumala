<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblDcsPurchaseRateApplicabitityHistory]].
 *
 * @see TblDcsPurchaseRateApplicabitityHistory
 */
class TblDcsPurchaseRateApplicabitityHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateApplicabitityHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateApplicabitityHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
