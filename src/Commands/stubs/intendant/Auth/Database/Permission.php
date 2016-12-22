<?php

namespace Intendant\{$stub_intendant_zone_upper}\Auth\Database;

class Permission extends IntendantModel
{
    protected $fillable = ['name', 'slug'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('intendant.'.$this->intendant_zone.'.database.permissions_table'));
    }

    /**
     * Permission belongs to many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        $pivotTable = config('intendant.'.$this->intendant_zone.'.database.role_permissions_table');

        return $this->belongsToMany(Role::class, $pivotTable, 'permission_id', 'role_id');
    }
}
