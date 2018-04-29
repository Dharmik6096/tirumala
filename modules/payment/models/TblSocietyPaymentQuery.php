<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblSocietyPayment]].
 *
 * @see TblSocietyPayment
 */
class TblSocietyPaymentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblSocietyPayment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblSocietyPayment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
