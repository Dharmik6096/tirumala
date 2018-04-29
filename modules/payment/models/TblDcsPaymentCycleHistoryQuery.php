<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblDcsPaymentCycleHistory]].
 *
 * @see TblDcsPaymentCycleHistory
 */
class TblDcsPaymentCycleHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsPaymentCycleHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsPaymentCycleHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
