<?php

namespace App\Data\Auth;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class RegisterData extends Data
{
    public function __construct(
        #[Required, Max(255)]
        public string $name,

        #[Required, Email, Max(255)]
        public string $email,

        #[Required, Min(6)]
        public string $password,
    ) {}
}
