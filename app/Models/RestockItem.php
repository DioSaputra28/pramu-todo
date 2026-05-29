<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['restock_list_id', 'product_id', 'quantity', 'status', 'scanned_at'])]
class RestockItem extends Model
{
    use HasFactory;

    /**
     * @var array<string, string|int>
     */
    protected $attributes = [
        'quantity' => 1,
        'status' => 'pending',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'scanned_at' => 'datetime',
        ];
    }

    public function restockList(): BelongsTo
    {
        return $this->belongsTo(RestockList::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
