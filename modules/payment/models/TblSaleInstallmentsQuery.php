<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblSaleInstallments]].
 *
 * @see TblSaleInstallments
 */
class TblSaleInstallmentsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblSaleInstallments[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblSaleInstallments|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
