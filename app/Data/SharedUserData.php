<?php

namespace App\Data;

use App\Models\User;
use Spatie\LaravelData\Data;

class SharedUserData extends Data
{
    public function __construct(
        public string $publicId,
        public string $name,
        public string $email,
    ) {}

    public static function fromUser(User $user): self
    {
        return new self(
            publicId: $user->public_id,
            name: $user->name,
            email: $user->email,
        );
    }
}
