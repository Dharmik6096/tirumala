<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblDcsPurchaseRateApplicabitity]].
 *
 * @see TblDcsPurchaseRateApplicabitity
 */
class TblDcsPurchaseRateApplicabitityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateApplicabitity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateApplicabitity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
