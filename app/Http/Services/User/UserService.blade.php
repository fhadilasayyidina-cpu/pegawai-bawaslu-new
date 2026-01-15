<?php 

namespace App\Services\User;

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

   public function getAllUser(?string $nameOrEmail = null, ?string $role = null, ?string $accessScope = null): Collection
    {
        $query = User::query();
        if($nameOrEmail){
            $query->where(function($q) use ($nameOrEmail) {
                $q->where('name', 'like', '%' . $nameOrEmail . '%')
                  ->orWhere('email', 'like', '%' . $nameOrEmail . '%');
            });
        }

        if($accessScope){
            $query->where('access_scope', $accessScope);
        }

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