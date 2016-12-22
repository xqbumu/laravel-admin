<?php

namespace Intendant\{$stub_intendant_zone_upper}\Auth\Database;

use Intendant\{$stub_intendant_zone_upper}\Facades\Incore;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Class Common.Model for Intendant
 *
 */
class IntendantModel extends EloquentModel
{
    protected $intendant_zone;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->intendant_zone = Incore::getModuleZone();

        $connection = config('intendant.'.$this->intendant_zone.'.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        parent::__construct($attributes);
    }

}
