    public function allUsers(Request $request)
    {
        // Only CEO can view all users in the system
        if (!auth()->user()->isCeo()) {
            return abort(403);
        }
        
        $this->authorize('admin_users_list');
        
        $query = User::query();
        $query = $this->filters($query, $request);
        
        $users = $query->orderBy('users.created_at', 'desc')
            ->paginate(20);
        
        $users = $this->addUsersExtraInfo($users);
        
        $roles = Role::all();
        
        $data = [
            'pageTitle' => 'All Users',  // trans('admin/main.all_users')
            'users' => $users,
            'roles' => $roles,
        ];
        
        return view('admin.users.students', $data); // Reuse students template
    }

