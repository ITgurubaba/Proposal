<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryAttribute;
use App\Models\ProductImage;
use App\Models\SlugManager;
use App\Models\MetaData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing categories
        $categories = ProductCategory::where('status', 1)->get();

        if ($categories->isEmpty()) {
            $this->command->info('No categories found. Please create categories first.');
            return;
        }

        // Sample furniture products data
        $productsData = [
            // Living Room
            [
                'name' => 'Modern Leather Sofa',
                'description' => 'Comfortable 3-seater leather sofa with modern design',
                'content' => 'This elegant leather sofa features premium leather upholstery, high-density foam cushions, and sturdy hardwood frame. Perfect for modern living rooms.',
                'sku' => 'SOFA-ML-001',
                'stocks' => 15,
                'market_price' => 1299.99,
                'selling_price' => 999.99,
                'weight' => 85.5,
                'length' => 220,
                'width' => 90,
                'height' => 85,
                'image' => 'assets/frontend/SampleProductsImages/sofa/sofa- (1).jpg',
                'hover_image' => 'assets/frontend/SampleProductsImages/sofa/sofa- (2).jpg',
                'gallery_images' => [
                    'assets/frontend/SampleProductsImages/sofa/sofa- (3).jpg',
                    'assets/frontend/SampleProductsImages/sofa/sofa- (4).jpg',
                    'assets/frontend/SampleProductsImages/sofa/sofa- (5).jpg',
                ],
                'category_keywords' => ['sofa', 'living room', 'leather'],
                'tags' => ['modern', 'leather', 'comfortable'],
                'related_products' => [],
            ],
            [
                'name' => 'Wooden Coffee Table',
                'description' => 'Solid oak coffee table with storage compartment',
                'content' => 'Handcrafted from solid oak wood, this coffee table features a spacious storage compartment and tempered glass top for durability.',
                'sku' => 'TABLE-CT-001',
                'stocks' => 20,
                'market_price' => 349.99,
                'selling_price' => 279.99,
                'weight' => 25.0,
                'length' => 120,
                'width' => 60,
                'height' => 45,
                'image' => 'assets/frontend/SampleProductsImages/Table/table- (1).jpg',
                'hover_image' => 'assets/frontend/SampleProductsImages/Table/table- (2).jpg',
                'gallery_images' => [
                    'assets/frontend/SampleProductsImages/Table/table- (3).jpg',
                ],
                'category_keywords' => ['table', 'coffee', 'living room'],
                'tags' => ['wooden', 'storage', 'oak'],
                'related_products' => [],
            ],
            [
                'name' => 'TV Entertainment Unit',
                'description' => 'Large TV stand with storage for media devices',
                'content' => 'This spacious entertainment unit accommodates TVs up to 65 inches and provides ample storage for gaming consoles, DVDs, and media accessories.',
                'sku' => 'TV-EU-001',
                'stocks' => 12,
                'market_price' => 599.99,
                'selling_price' => 449.99,
                'weight' => 45.0,
                'length' => 180,
                'width' => 45,
                'height' => 60,
                'image' => 'assets/frontend/SampleProductsImages/Table/table- (1).jpg',
                'hover_image' => 'assets/frontend/SampleProductsImages/Table/table- (2).jpg',
                'gallery_images' => [
                    'assets/frontend/SampleProductsImages/Table/table- (3).jpg',
                ],
                'category_keywords' => ['tv stand', 'entertainment', 'living room'],
                'tags' => ['storage', 'modern', 'media'],
                'related_products' => [],
            ],

            // Bedroom
            [
                'name' => 'King Size Bed Frame',
                'description' => 'Solid wood king size bed frame with storage',
                'content' => 'Elegant king size bed frame made from solid mahogany wood, featuring under-bed storage drawers for optimal space utilization.',
                'sku' => 'BED-KS-001',
                'stocks' => 8,
                'market_price' => 899.99,
                'selling_price' => 699.99,
                'weight' => 65.0,
                'length' => 210,
                'width' => 185,
                'height' => 90,
                'image' => 'assets/frontend/SampleProductsImages/Bed/bed- (1).jpg',
                'hover_image' => 'assets/frontend/SampleProductsImages/Bed/bed- (2).jpg',
                'gallery_images' => [
                    'assets/frontend/SampleProductsImages/Bed/bed- (3).jpg',
                    'assets/frontend/SampleProductsImages/Bed/bed- (4).jpg',
                    'assets/frontend/SampleProductsImages/Bed/bed- (5).jpg',
                ],
                'category_keywords' => ['bed', 'bedroom', 'king size'],
                'tags' => ['wooden', 'storage', 'king'],
                'related_products' => [],
            ],
            [
                'name' => '6-Drawer Dresser',
                'description' => 'Spacious dresser with mirror and 6 drawers',
                'content' => 'This beautiful dresser comes with 6 spacious drawers and an attached mirror, providing both storage and functionality for your bedroom.',
                'sku' => 'DRESSER-6D-001',
                'stocks' => 10,
                'market_price' => 499.99,
                'selling_price' => 379.99,
                'weight' => 55.0,
                'length' => 140,
                'width' => 45,
                'height' => 180,
                'image' => 'assets/frontend/SampleProductsImages/Bed/bed- (1).jpg',
                'hover_image' => 'assets/frontend/SampleProductsImages/Bed/bed- (2).jpg',
                'gallery_images' => [
                    'assets/frontend/SampleProductsImages/Bed/bed- (3).jpg',
                ],
                'category_keywords' => ['dresser', 'bedroom', 'storage'],
                'tags' => ['mirror', 'drawers', 'spacious'],
                'related_products' => [],
            ],
            [
                'name' => 'Wardrobe with Sliding Doors',
                'description' => '3-door wardrobe with sliding mirror doors',
                'content' => 'Modern 3-door wardrobe featuring sliding mirror doors, internal shelves, and hanging space for clothes and accessories.',
                'sku' => 'WARDROBE-3D-001',
                'stocks' => 6,
                'market_price' => 799.99,
                'selling_price' => 599.99,
                'weight' => 75.0,
                'length' => 180,
                'width' => 60,
                'height' => 200,
                'image' => 'assets/frontend/SampleProductsImages/Bed/bed- (1).jpg',
                'hover_image' => 'assets/frontend/SampleProductsImages/Bed/bed- (2).jpg',
                'gallery_images' => [
                    'assets/frontend/SampleProductsImages/Bed/bed- (3).jpg',
                ],
                'category_keywords' => ['wardrobe', 'bedroom', 'storage'],
                'tags' => ['mirror', 'sliding doors', 'modern'],
                'related_products' => [],
            ],

            // Dining Room
            [
                'name' => '6-Seater Dining Table Set',
                'description' => 'Complete dining set with table and 6 chairs',
                'content' => 'Beautiful dining set including a solid wood table and 6 upholstered chairs, perfect for family gatherings and dinner parties.',
                'sku' => 'DINING-6S-001',
                'stocks' => 5,
                'market_price' => 1299.99,
                'selling_price' => 999.99,
                'weight' => 95.0,
                'length' => 180,
                'width' => 90,
                'height' => 75,
                'image' => 'assets/frontend/SampleProductsImages/Table/table- (1).jpg',
                'hover_image' => 'assets/frontend/SampleProductsImages/Table/table- (2).jpg',
                'gallery_images' => [
                    'assets/frontend/SampleProductsImages/Table/table- (3).jpg',
                ],
                'category_keywords' => ['dining', 'table', 'chairs'],
                'tags' => ['set', 'family', 'wooden'],
                'related_products' => [],
            ],
            [
                'name' => 'Kitchen Cabinet',
                'description' => 'Modern kitchen storage cabinet with glass doors',
                'content' => 'Elegant kitchen cabinet with glass doors and internal shelves, providing stylish storage solution for your kitchen essentials.',
                'sku' => 'CABINET-K-001',
                'stocks' => 15,
                'market_price' => 399.99,
                'selling_price' => 299.99,
                'weight' => 35.0,
                'length' => 80,
                'width' => 35,
                'height' => 180,
                'image' => 'assets/frontend/SampleProductsImages/Table/table- (1).jpg',
                'hover_image' => 'assets/frontend/SampleProductsImages/Table/table- (2).jpg',
                'gallery_images' => [
                    'assets/frontend/SampleProductsImages/Table/table- (3).jpg',
                ],
                'category_keywords' => ['cabinet', 'kitchen', 'storage'],
                'tags' => ['glass doors', 'modern', 'storage'],
                'related_products' => [],
            ],

            // Office
            [
                'name' => 'Executive Office Chair',
                'description' => 'Ergonomic office chair with lumbar support',
                'content' => 'Premium executive chair with adjustable height, lumbar support, and breathable mesh back for all-day comfort during work.',
                'sku' => 'CHAIR-EXEC-001',
                'stocks' => 25,
                'market_price' => 349.99,
                'selling_price' => 249.99,
                'weight' => 18.0,
                'length' => 65,
                'width' => 60,
                'height' => 110,
                'image' => 'assets/frontend/SampleProductsImages/Chair/chair- (1).jpg',
                'hover_image' => 'assets/frontend/SampleProductsImages/Chair/chair- (2).jpg',
                'gallery_images' => [
                    'assets/frontend/SampleProductsImages/Chair/chair- (3).jpg',
                    'assets/frontend/SampleProductsImages/Chair/chair- (4).jpg',
                ],
                'category_keywords' => ['chair', 'office', 'executive'],
                'tags' => ['ergonomic', 'adjustable', 'comfortable'],
                'related_products' => [],
            ],
            [
                'name' => 'L-Shaped Desk',
                'description' => 'Large L-shaped computer desk with storage',
                'content' => 'Spacious L-shaped desk providing ample workspace and built-in storage compartments for office supplies and documents.',
                'sku' => 'DESK-L-001',
                'stocks' => 8,
                'market_price' => 499.99,
                'selling_price' => 379.99,
                'weight' => 45.0,
                'length' => 150,
                'width' => 120,
                'height' => 75,
                'image' => 'assets/frontend/SampleProductsImages/Table/table- (1).jpg',
                'hover_image' => 'assets/frontend/SampleProductsImages/Table/table- (2).jpg',
                'gallery_images' => [
                    'assets/frontend/SampleProductsImages/Table/table- (3).jpg',
                ],
                'category_keywords' => ['desk', 'office', 'computer'],
                'tags' => ['L-shaped', 'storage', 'workspace'],
                'related_products' => [],
            ],
        ];

        $createdCount = 0;

        foreach ($productsData as $productData) {
            // Find matching categories based on keywords
            $matchedCategories = $this->findMatchingCategories($categories, $productData['category_keywords']);

            if ($matchedCategories->isEmpty()) {
                // If no specific match, assign to first available category
                $matchedCategories = $categories->take(1);
            }

            // Check if product already exists
            $product = Product::where('sku', $productData['sku'])->first();

            if ($product) {
                // Update existing product
                $product->update([
                    'name' => $productData['name'],
                    'stocks' => $productData['stocks'],
                    'description' => $productData['description'],
                    'content' => $productData['content'],
                    'image' => $productData['image'],
                    'hover_image' => $productData['hover_image'],
                    'market_price' => $productData['market_price'],
                    'selling_price' => $productData['selling_price'],
                    'weight' => $productData['weight'],
                    'height' => $productData['height'],
                    'width' => $productData['width'],
                    'length' => $productData['length'],
                    'tags' => json_encode($productData['tags']),
                    'related_products' => json_encode($productData['related_products']),
                    'is_featured' => rand(0, 1),
                    'status' => 1,
                    'category_id' => $matchedCategories->first()->id,
                ]);
            } else {
                // Create new product
                $product = Product::create([
                    'name' => $productData['name'],
                    'sku' => $productData['sku'],
                    'stocks' => $productData['stocks'],
                    'description' => $productData['description'],
                    'content' => $productData['content'],
                    'image' => $productData['image'],
                    'hover_image' => $productData['hover_image'],
                    'market_price' => $productData['market_price'],
                    'selling_price' => $productData['selling_price'],
                    'weight' => $productData['weight'],
                    'height' => $productData['height'],
                    'width' => $productData['width'],
                    'length' => $productData['length'],
                    'tags' => json_encode($productData['tags']),
                    'related_products' => json_encode($productData['related_products']),
                    'is_featured' => rand(0, 1),
                    'status' => 1,
                    'category_id' => $matchedCategories->first()->id, // Main category
                ]);

                // Create slug
                SlugManager::create([
                    'model' => Product::class,
                    'model_id' => $product->id,
                    'slug' => Str::slug($productData['name']),
                ]);

                // Create meta data
                MetaData::create([
                    'model' => Product::class,
                    'model_id' => $product->id,
                    'title' => $productData['name'],
                    'description' => $productData['description'],
                    'keywords' => implode(', ', $productData['tags']),
                ]);

                $createdCount++;
            }

            // Update/create categories (always do this for both new and existing products)
            ProductCategoryAttribute::where('product_id', $product->id)->delete(); // Clear existing
            foreach ($matchedCategories as $index => $category) {
                ProductCategoryAttribute::create([
                    'product_id' => $product->id,
                    'category_id' => $category->id,
                    'position' => $index + 1,
                ]);
            }

            // Update/create gallery images (always do this for both new and existing products)
            ProductImage::where('product_id', $product->id)->delete(); // Clear existing
            if (isset($productData['gallery_images']) && is_array($productData['gallery_images'])) {
                foreach ($productData['gallery_images'] as $index => $imagePath) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'name' => basename($imagePath),
                        'path' => $imagePath,
                        'position' => $index + 1,
                    ]);
                }
            }
        }

        $this->command->info("Created {$createdCount} products successfully!");
    }

    /**
     * Find categories that match the given keywords
     */
    private function findMatchingCategories($categories, $keywords)
    {
        $matched = collect();

        foreach ($categories as $category) {
            $categoryName = strtolower($category->name);
            foreach ($keywords as $keyword) {
                if (str_contains($categoryName, strtolower($keyword))) {
                    $matched->push($category);
                    break;
                }
            }
        }

        return $matched;
    }
}
