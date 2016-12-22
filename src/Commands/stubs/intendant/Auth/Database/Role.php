<?php

namespace Intendant\{$stub_intendant_zone_upper}\Auth\Database;

use Illuminate\Database\Eloquent\Model;

class Role extends IntendantModel
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

        $this->setTable(config('intendant.'.$this->intendant_zone.'.database.roles_table'));
    }

    /**
     * A role belongs to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function administrators()
    {
        $pivotTable = config('intendant.'.$this->intendant_zone.'.database.role_users_table');

        return $this->belongsToMany(Administrator::class, $pivotTable, 'role_id', 'user_id');
    }

    /**
     * A role belongs to many permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        $pivotTable = config('intendant.'.$this->intendant_zone.'.database.role_permissions_table');

        return $this->belongsToMany(Permission::class, $pivotTable, 'role_id', 'permission_id');
    }

    /**
     * Check user has permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function can($permission)
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * Check user has no permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function cannot($permission)
    {
        return !$this->can($permission);
    }
}
