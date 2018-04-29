<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TblAddressbook]].
 *
 * @see TblAddressbook
 */
class TblAddressbookQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblAddressbook[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblAddressbook|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
