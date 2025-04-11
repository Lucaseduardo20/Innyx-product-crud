<?php

namespace Tests\Unit\Http\Controllers;

use App\Data\Auth\UserData;
use App\Data\Shared\PaginatedResponseData;
use App\Http\Controllers\UserController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Mockery;

class UserControllerTest extends TestCase
{
    private UserService $userService;
    private UserController $controller;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = Mockery::mock(UserService::class);
        $this->controller = new UserController($this->userService);
    }

    public function testGetPreview()
    {
        $user = User::factory()->create(['role_id' => Role::factory()]);
        $expectedData = [
            'products' => 10,
            'users' => 5,
            'last_updated' => now()->format('d/m/Y H:i')
        ];

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('user')->andReturn($user);

        $this->userService
            ->shouldReceive('preview')
            ->with($user)
            ->andReturn($expectedData);

        $response = $this->controller->get_preview($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedData, $response->getData(true));
    }

    public function testStore()
    {
        $user = User::factory()->make();
        $requestData = [
            'name' => $user->name,
            'email' => $user->email,
            'role_name' => 'admin'
        ];

        $request = Mockery::mock(StoreUserRequest::class);
        $request->shouldReceive('validated')->andReturn($requestData);

        $this->userService
            ->shouldReceive('create')
            ->with($requestData)
            ->andReturn($user);

        $response = $this->controller->store($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(UserData::fromModel($user)->toArray(), $response->getData(true));
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role_name' => 'admin'
        ];

        $request = Mockery::mock(UpdateUserRequest::class);
        $request->shouldReceive('validated')->andReturn($updatedData);

        $this->userService
            ->shouldReceive('update')
            ->with($user, $updatedData)
            ->andReturn($user);

        $response = $this->controller->update($request, $user);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(UserData::fromModel($user)->toArray(), $response->getData(true));
    }

    public function testDestroy()
    {
        $user = User::factory()->create();

        $this->userService
            ->shouldReceive('delete')
            ->with($user)
            ->once();

        $response = $this->controller->destroy($user);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testResetPass()
    {
        $userId = 1;
        $request = new Request(['id' => $userId]);
        $message = 'Senha alterada com sucesso!';

        $this->userService
            ->shouldReceive('resetPassword')
            ->with($userId)
            ->andReturn($message);

        $response = $this->controller->resetPass($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($message, $response->getData(true));
    }

    public function testChangePassSuccess()
    {
        $requestData = [
            'currentPassword' => 'old_password',
            'newPassword' => 'new_password'
        ];

        $request = new Request($requestData);
        $responseData = ['status' => 200, 'message' => 'Senha alterada com sucesso!'];

        $this->userService
            ->shouldReceive('setPassword')
            ->with($requestData)
            ->andReturn($responseData);

        $response = $this->controller->changePass($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($responseData['message'], $response->getData(true));
    }

    public function testChangePassWithWrongCurrentPassword()
    {
        $requestData = [
            'currentPassword' => 'wrong_password',
            'newPassword' => 'new_password'
        ];

        $request = new Request($requestData);
        $responseData = ['status' => 404, 'message' => 'Senha atual incorreta!'];

        $this->userService
            ->shouldReceive('setPassword')
            ->with($requestData)
            ->andReturn($responseData);

        $response = $this->controller->changePass($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals($responseData['message'], $response->getData(true));
    }

    public function testChangePassWithSamePassword()
    {
        $requestData = [
            'currentPassword' => 'old_password',
            'newPassword' => 'old_password'
        ];

        $request = new Request($requestData);
        $responseData = ['status' => 400, 'message' => 'A nova senha não pode ser igual à atual.'];

        $this->userService
            ->shouldReceive('setPassword')
            ->with($requestData)
            ->andReturn($responseData);

        $response = $this->controller->changePass($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals($responseData['message'], $response->getData(true));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
