<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Services\TaskService;
use App\Services\UserService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;
    protected $userService;

    public function __construct(TaskService $taskService, UserService $userService)
    {
        $this->taskService = $taskService;
        $this->userService = $userService;

    }
    public function index()
    {

        $tasks = $this->taskService->paginate(10);

        return view('tasks.index', compact('tasks'));

    }

    public function create()
    {
        $users = $this->userService->getUsers(['id', 'name']);
        $admins = $this->userService->getAdmins(['id', 'name']);
        return view('tasks.create',compact(['users', 'admins']));
    }
    public function store(TaskRequest $request)
    {
        $taskDetails = $request->validated();
//        dd(taskDetails);
        $task = $this->taskService->create($taskDetails);

        return redirect()->route('tasks.index');
    }}
