<?php

namespace Intendant\{$stub_intendant_zone_upper}\Auth\Database;

class OperationLog extends IntendantModel
{
    protected $fillable = ['user_id', 'path', 'method', 'ip', 'input'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('intendant.'.$this->intendant_zone.'.database.operation_log_table'));
    }

    public static $methodColors = [
        'GET'       => 'green',
        'POST'      => 'yellow',
        'PUT'       => 'blue',
        'DELETE'    => 'red',
    ];

    public static $methods = [
        'GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH', 'LINK', 'UNLINK', 'COPY', 'HEAD', 'PURGE',
    ];

    /**
     * Log belongs to users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Administrator::class);
    }
}
