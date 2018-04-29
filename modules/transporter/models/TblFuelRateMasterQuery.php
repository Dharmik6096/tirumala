<?php

namespace app\modules\transporter\models;

/**
 * This is the ActiveQuery class for [[TblTransporter]].
 *
 * @see TblTransporter
 */
class TblFuelRateMasterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblTransporter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblTransporter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
