<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create product_variants table
        if (!Schema::hasTable('product_variants')) {
            Schema::create('product_variants', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('product_id')->constrained()->onDelete('cascade');
                $table->string('sku')->unique();
                $table->string('barcode')->nullable()->unique();
                $table->decimal('weight_value', 10, 2)->nullable();
                $table->string('weight_unit', 20)->nullable();
                $table->string('unit')->default('Pcs'); // Smallest unit
                $table->integer('pcs_per_dus')->default(1); // Conversion factor
                $table->integer('min_stock_threshold')->default(10);
                $table->string('image_path')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 2. Add product_variant_id to inventory tables
        Schema::table('inventory_items', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_items', 'product_variant_id')) {
                $table->foreignUuid('product_variant_id')->nullable()->after('product_id');
            }
        });

        Schema::table('inventory_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_transactions', 'product_variant_id')) {
                $table->foreignUuid('product_variant_id')->nullable()->after('product_id');
            }
        });

        // 3. Migrate Data
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            if (!isset($product->sku)) continue; // Already dropped in a failed run?
            
            $existingVariant = DB::table('product_variants')->where('product_id', $product->id)->first();
            if (!$existingVariant) {
                $variantId = (string) \Illuminate\Support\Str::uuid();
                DB::table('product_variants')->insert([
                    'id' => $variantId,
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'weight_value' => $product->weight_value,
                    'weight_unit' => $product->weight_unit,
                    'unit' => $product->unit,
                    'pcs_per_dus' => 1,
                    'min_stock_threshold' => $product->min_stock_threshold,
                    'image_path' => $product->image_path,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ]);
            } else {
                $variantId = $existingVariant->id;
            }

            DB::table('inventory_items')->where('product_id', $product->id)->update(['product_variant_id' => $variantId]);
            DB::table('inventory_transactions')->where('product_id', $product->id)->update(['product_variant_id' => $variantId]);
        }

        // 4. Clean up inventory_items
        Schema::table('inventory_items', function (Blueprint $table) {
            // Drop constraints that depend on the unique index or product_id
            $table->dropForeign(['minimarket_id']);
            $table->dropUnique('inventory_items_minimarket_id_product_id_unique');
            
            if (Schema::hasColumn('inventory_items', 'product_id')) {
                $table->dropColumn('product_id');
            }
            
            // Re-add constraints
            $table->foreign('minimarket_id')->references('id')->on('minimarkets')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->unique(['minimarket_id', 'product_variant_id'], 'inv_items_market_variant_unique');
        });

        // 5. Clean up inventory_transactions
        Schema::table('inventory_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_transactions', 'product_id')) {
                // Drop foreign key first
                try {
                    $table->dropForeign('inventory_transactions_product_id_foreign');
                } catch (\Exception $e) {}
                $table->dropColumn('product_id');
            }
            
            $table->foreign('product_variant_id', 'inv_trans_variant_foreign')->references('id')->on('product_variants')->onDelete('cascade');
        });

        // 6. Final cleanup on products
        Schema::table('products', function (Blueprint $table) {
            $cols = ['sku', 'barcode', 'weight_value', 'weight_unit', 'unit', 'min_stock_threshold', 'image_path'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
