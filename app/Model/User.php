<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property string $id
 * @property string $email
 * @property string $password
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Model
{
    public bool $incrementing = false;

    protected string $primaryKey = 'id';

    protected string $keyType = 'string';
    
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'users';

    /**
     * The attributes that are mass assignable.
     * @var array<string>
     */
    protected array $fillable = ['id', 'email', 'password'];

    /**
     * The attributes that should be cast to native types.
     * @var array<string, string>
     */
    protected array $casts = ['created_at' => 'datetime', 'updated_at' => 'datetime'];
}
