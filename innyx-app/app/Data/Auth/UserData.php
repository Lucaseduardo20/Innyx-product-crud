<?php

namespace App\Data\Auth;

use Spatie\LaravelData\Data;
use App\Models\User;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?int $role_id,
        public ?bool $is_admin
    ) {}

    public static function fromModel(User $user): self
    {
        return new self (
            id: $user->id,
            name: $user->name,
            email: $user->email,
            role_id: null,
            is_admin: $user->is_admin
        );
    }
}
