<?php

namespace app\modules\collection\models;

/**
 * This is the ActiveQuery class for [[TblSendSms]].
 *
 * @see TblSendSms
 */
class TblSendSmsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblSendSms[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblSendSms|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
