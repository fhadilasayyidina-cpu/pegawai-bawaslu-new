<?php 

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class UserService
{

    public function __construct()
    {
        //
    }


    public function createUser(array $data): User
    {
        return User::create($data);
    }

    

    public function updateUser(User $user, array $data): bool
    {
        
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $user->update($data);
    }

    public function deleteUser(int $id): bool
    {
        $user = User::find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
    
   public function getAllUser(?string $role = null): Collection
    {
        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        // Ambil datanya dulu, baru sembunyikan kolomnya
        return $query->get()->makeHidden(['password', 'remember_token']);
    }


    public function getUserById(int $id): ?User
    {
        $user = User::find($id);
        
        return $user ? $user->makeHidden(['password', 'remember_token']) : null;
    }


}