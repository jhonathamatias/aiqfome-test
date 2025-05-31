<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Model;

use Carbon\Carbon;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Client extends Model
{
    use SoftDeletes;

    public bool $incrementing = false;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'clients';

    /**
     * The attributes that are mass assignable.
     * @var array<string>
     */
    protected array $fillable = ['id', 'name', 'email'];

    /**
     * The attributes that should be cast to native types.
     * @var array<string, string>
     */
    protected array $casts = ['created_at' => 'datetime', 'updated_at' => 'datetime'];

    protected string $keyType = 'string';

    protected string $primaryKey = 'id';
}
