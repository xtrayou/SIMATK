<?php

namespace App\Models\Pengguna;

use CodeIgniter\Model;

class PenggunaModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'password', 'name', 'role_id', 'is_active'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Find user by username
     */
    public function findByUsername(string $username): ?array
    {
        return $this->select('users.*, roles.name as role')
                    ->join('roles', 'roles.id = users.role_id', 'left')
                    ->where('username', $username)
                    ->first();
    }
}
