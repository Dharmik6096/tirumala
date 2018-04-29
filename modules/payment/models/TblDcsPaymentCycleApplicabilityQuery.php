<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblDcsPaymentCycleApplicability]].
 *
 * @see TblDcsPaymentCycleApplicability
 */
class TblDcsPaymentCycleApplicabilityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsPaymentCycleApplicability[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsPaymentCycleApplicability|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
