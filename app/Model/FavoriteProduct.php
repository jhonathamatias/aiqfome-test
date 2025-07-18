<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property string $id
 * @property string $client_id
 * @property int $product_id
 * @property string $title
 * @property string $image
 * @property string $price
 * @property string $rating_rate
 * @property string $rating_count
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class FavoriteProduct extends Model
{
    public bool $incrementing = false;

    protected string $primaryKey = 'id';

    protected string $keyType = 'string';

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'favorite_products';

    /**
     * The attributes that are mass assignable.
     * @var string[] $fillable
     */
    protected array $fillable = ['id', 'client_id', 'product_id', 'title', 'image', 'price', 'rating_rate', 'rating_count'];

    /**
     * The attributes that should be cast to native types.
     * @var array<string, string> $casts
     */
    protected array $casts = ['product_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
