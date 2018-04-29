<?php

namespace app\modules\general\models;

/**
 * This is the ActiveQuery class for [[TblOrganisationType]].
 *
 * @see TblOrganisationType
 */
class TblOrganisationTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblOrganisationType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblOrganisationType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
