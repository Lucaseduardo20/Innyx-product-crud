<?php

namespace App\Services;

use App\Data\AppointmentData;
use App\Models\User;

class UserService
{
    public function preview(User $user)
    {
        $data = [];

        $data['products'] = Product::where('user_id', $user->id)->count();

        $lastUpdatedProduct = Product::where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->first();

        $data['last_updated'] = $lastUpdatedProduct?->updated_at?->format('d/m/Y H:i') ?? 'Sem registros';

        if ($user->is_admin) {
            $data['users'] = User::count();
        }

        return $data;
    }
}
