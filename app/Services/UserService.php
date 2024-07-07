<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserService extends BaseService
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
    protected $cacheDuration = 86400; // Cache duration in minutes (24 hours)

    public function getUsers(array $attributes = ['*'])
    {
        return Cache::remember('users', $this->cacheDuration, function () use ($attributes) {
            return $this->model->user()->get($attributes);
        });
    }
    public function getAdmins(array $attributes = ['*'])
    {
        return Cache::remember('admins', $this->cacheDuration, function () use ($attributes) {
            return $this->model->admin()->get($attributes);
        });
    }
}
