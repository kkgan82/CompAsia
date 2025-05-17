<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class Product extends Model implements ToCollection
{
    use HasFactory;

    // Table name
    protected $table = 'product_master_list';

    // Fields allowed
    protected $fillable = [
        'product_id',
        'types',
        'brand',
        'model',
        'capacity',
        'quantity',
    ];

    // filters products
    public function scopeFilterByProductId($query, $productId)
    {
        if (!empty($productId)) {
            return $query->where('product_id', 'like', '%' . $productId . '%');
        }

        return $query;
    }

    // import data from exel
    public function collection(Collection $rows): void
    {
        $productChanges = [];

        foreach ($rows->skip(1) as $row) {
            $productId = $row[0];
            $model     = trim($row[3]);
            $capacity  = trim($row[4]);
            $status    = strtolower(trim($row[5]));
            $quantity  = 1;

            if (!$productId || !$model || !$capacity || !in_array($status, ['buy', 'sold'])) {
                continue;
            }

            // Track all changes in products
            if (!isset($productChanges[$productId])) {
                $productChanges[$productId] = [
                    'model'    => $model,
                    'capacity' => $capacity,
                    'quantity' => 0,
                ];
            }

            // Apply quantity difference in memory
            if ($status === 'buy') {
                $productChanges[$productId]['quantity'] += $quantity;
            } elseif ($status === 'sold') {
                $productChanges[$productId]['quantity'] -= $quantity;
            }
        }

        // Apply changes in database
        foreach ($productChanges as $productId => $data) {
            $product = self::where('product_id', $productId)->first();

            if ($product) {
                $product->quantity = max(0, $product->quantity + $data['quantity']); 
                $product->save();
            } elseif ($data['quantity'] > 0) {
                // Create only if net quantity is > 0
                self::create([
                    'product_id' => $productId,
                    'types'      => 'Smartphone',
                    'brand'      => 'Apple',
                    'model'      => $data['model'],
                    'capacity'   => $data['capacity'],
                    'quantity'   => $data['quantity'],
                ]);
            }
        }
    }
}
