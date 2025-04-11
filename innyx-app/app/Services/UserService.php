<?php

namespace App\Services;

use App\Data\AppointmentData;
use App\Models\User;
use App\Models\Product;
use App\Models\Role;
use App\Data\Auth\UserData;
use App\Data\Shared\PaginatedResponseData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function preview(User $user)
    {
        $data = [];

        $data['products'] = Product::where('user_id', $user->id)->count();

        if ($user->is_admin) {
            $data['products'] = Product::query()->count();
            $data['users'] = User::count();
        }

        $lastUpdatedProduct = Product::where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->first();

        $data['last_updated'] = $lastUpdatedProduct?->updated_at?->format('d/m/Y H:i') ?? 'Sem registros';


        return $data;
    }

    public function list(int $page = 1, string $search = ''): PaginatedResponseData
    {
        $query = User::with('role')
        ->when($search, function ($q) use ($search) {
            $q->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->where('id', '!=', auth()->id())
        ->latest();

        $paginator = $query->paginate(perPage: 10, page: $page);

        $usersData = collect($paginator->items())
            ->map(fn ($user) => UserData::fromModel($user))
            ->all();

        return new PaginatedResponseData(
            items: $usersData,
            current_page: $paginator->currentPage(),
            total: $paginator->total(),
            last_page: $paginator->lastPage()
        );
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $data['role_id'] = Role::query()->where('name', $data['role_name'])->value('id');
            $user = new User();
            $user->name = $data['name'];
            $user->role_id = $data['role_id'];
            $user->email = $data['email'];
            $user->password = '123123';
            $user->save();
            return $user;
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $data['role_id'] = Role::query()->where('name', $data['role_name'])->value('id');
            $user->name = $data['name'];
            $user->role_id = $data['role_id'];
            $user->email = $data['email'];
            $user->save();
            return $user->fresh('role');
        });
    }

    public function delete(User $user): void
    {
        $user->delete();
        $user->products()->delete();
    }

    public function resetPassword(int $id): string
    {
        $user = User::find($id);
        $user->password = '123123';
        $user->save();
        return 'Senha alterada com sucesso!';
    }

    public function setPassword(array $data)
    {
        $user = auth()->user();

        if(!Hash::check($data['currentPassword'], $user->password)) {
            return ['status' => 404, 'message' => 'Senha atual incorreta!'];
        }

        if (Hash::check($data['newPassword'], $user->password)) {
            return ['status' => 400, 'message' => 'A nova senha não pode ser igual à atual.'];
        }

        $user->password = Hash::make($data['newPassword']);
        $user->save();
        return ['status' => 200, 'message' => 'Senha alterada com sucesso!'];;
    }
}
