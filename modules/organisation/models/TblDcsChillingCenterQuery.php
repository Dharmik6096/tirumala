<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblDcsChillingCenter]].
 *
 * @see TblDcsChillingCenter
 */
class TblDcsChillingCenterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsChillingCenter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsChillingCenter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
