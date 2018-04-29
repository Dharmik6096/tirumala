<?php

namespace app\modules\installation\models;

/**
 * This is the ActiveQuery class for [[InstallationIdentity]].
 *
 * @see InstallationIdentity
 */
class InstallationIdentityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return InstallationIdentity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return InstallationIdentity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
