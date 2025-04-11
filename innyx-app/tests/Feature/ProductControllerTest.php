<?php

namespace Tests\Unit\Http\Controllers;

use App\Data\Product\ProductData;
use App\Data\Product\ProductResponseData;
use App\Http\Controllers\ProductController;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $productService;
    private ProductController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = Mockery::mock(ProductService::class);
        $this->controller = new ProductController($this->productService);
    }

    public function testIndexForRegularUser()
    {
        $user = User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);

        $products = Product::factory()->count(3)->make(['user_id' => $user->id]);
        $responseData = new ProductResponseData(
            products: collect($products)->map(fn($p) => ProductData::fromModel($p))->all(),
            current_page: 1,
            total: 3,
            last_page: 1
        );

        $request = new Request(['page' => 1, 'search' => 'test']);

        $this->productService
            ->shouldReceive('list')
            ->with(1, 'test')
            ->andReturn($responseData);

        $response = $this->controller->index($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            [
                'products' => array_map(fn($product) => $product->toArray(), $responseData->products),
                'current_page' => $responseData->current_page,
                'total' => $responseData->total,
                'last_page' => $responseData->last_page,
            ],
            $response->getData(true)
        );
    }

    public function testStore()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = Category::factory()->create();

        $requestData = [
            'name' => 'Test Product',
            'price' => 100.50,
            'category_id' => $category->id,
            'user_id' => $user->id
        ];

        $request = Mockery::mock(StoreProductRequest::class);
        $request->shouldReceive('validated')->andReturn($requestData);

        $product = Product::factory()->make(['user_id' => $user->id]);
        $productData = ProductData::fromModel($product);

        $this->productService
            ->shouldReceive('store')
            ->with($requestData)
            ->andReturn($productData);

        $response = $this->controller->store($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testShow()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create();
        $productData = ProductData::fromModel($product->load('category'));

        $this->productService
            ->shouldReceive('show')
            ->with($product)
            ->andReturn($productData);

        $response = $this->controller->show($product);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($productData->toArray(), $response->getData(true));
    }


    public function testUpdate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create();

        $requestData = [
            'name' => 'Updated Product',
            'price' => 150.75
        ];

        $request = Mockery::mock(UpdateProductRequest::class);
        $request->shouldReceive('validated')->andReturn($requestData);

        $updatedProduct = ProductData::fromModel($product->fresh());

        $this->productService
            ->shouldReceive('update')
            ->with($product, $requestData)
            ->andReturn($updatedProduct);

        $response = $this->controller->update($request, $product);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDestroy()
    {
        $product = Product::factory()->create();

        $this->productService
            ->shouldReceive('delete')
            ->with($product)
            ->once();

        $response = $this->controller->destroy($product);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
