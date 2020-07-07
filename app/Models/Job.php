<?php

namespace App\Models;

/**
 * Class Job
 * @property-read int $id
 * @property string $url
 * @property string $status
 * @property int $http_code
 * @package App\Models
 */
class Job extends Model
{
    const STATUS_NEW = 'NEW';
    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_DONE = 'DONE';
    const STATUS_ERROR = 'ERROR';

    protected static $tableName = 'jobs';

    protected $fillable = [
        'url',
        'status',
        'http_code',
    ];
}