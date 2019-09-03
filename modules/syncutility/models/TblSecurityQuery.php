<?php

namespace app\modules\syncutility\models;

/**
 * This is the ActiveQuery class for [[TblSecurity]].
 *
 * @see TblSecurity
 */
class TblSecurityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblSecurity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblSecurity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
