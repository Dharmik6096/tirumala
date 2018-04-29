<?php

namespace app\modules\general\models;

/**
 * This is the ActiveQuery class for [[TblSocietyVendor]].
 *
 * @see TblSocietyVendor
 */
class TblSocietyVendorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblSocietyVendor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblSocietyVendor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
