<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;

class UserController extends Controller
{
    public function showUsersView(Request $request)
    {
        if(!Gate::allows('view-users'))
            abort(403);

        $isFilterPanelOpen = false;

        if($request->action && $request->action == 'clear search')
            $search = null;
        else
            $search = $request->search ?? null;

        if($request->action && $request->action == 'reset filters')
            $filters = [
                'gender' => [],
                'role' => [],
            ];
        else
            $filters = [
                'gender' => $request->genders ?? [],
                'role' => $request->roles ?? [],
            ];

        if($this->areUsersFiltered($filters))
            $isFilterPanelOpen = true;

        if($search || $this->areUsersFiltered($filters))
            $users = $this->getUsersFiltered($search, $filters);
        else
            $users = $this->getRoleSpecificUsers();

        $sort = $request->sort ?? 'date created';

        return view('users', [
            'isFilterPanelOpen' => $isFilterPanelOpen,
            'search' => $search,
            'filters' => $filters,
            'sort' => $sort,
            'users' => $this->sortUsers($users, $sort),
            'results' => count($users).' '.Pluralizer::plural('result', count($users)),
        ]);
    }

    private function areUsersFiltered($filters): bool
    {
        return count($filters['gender']) || count($filters['role']);
    }

    private function getUsersFiltered($search, $filters): array
    {
        $users = [];

        foreach($this->getRoleSpecificUsers() as $user)
            if($this->userMatchesSearch($user, $search) && $this->userMatchesFilters($user, $filters))
                array_push($users, $user);

        return $users;
    }

    private function getRoleSpecificUsers()
    {
        if(auth()->user()->userRole->role->name == 'admin')
            return User::all();
        else
            return User::all()->filter(function($user)
            {
                return $user->userRole->role->name != 'admin';
            });
    }

    private function userMatchesSearch($user, $search): bool
    {
        if($search
           && !str_contains(mb_strtolower($user->userDetails->first_name, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($user->userDetails->last_name, 'UTF-8'), mb_strtolower($search, 'UTF-8')))
            return false;

        return true;
    }

    private function userMatchesFilters($user, $filters): bool
    {
        if($filters['gender'] && !in_array($user->userDetails->gender, $filters['gender']))
            return false;

        if($filters['role'] && !in_array($user->userRole->role->name, $filters['role']))
            return false;

        return true;
    }

    private function sortUsers($users, $sort): Collection
    {
        if(str_contains($sort, 'first name'))
            $sortedUsers = collect($users)->sortBy(function($user)
            {
                return $user->userDetails->first_name;
            }, SORT_REGULAR, str_contains($sort, 'desc'));
        else if(str_contains($sort, 'last name'))
            $sortedUsers = collect($users)->sortBy(function($user)
            {
                return $user->userDetails->last_name;
            }, SORT_REGULAR, str_contains($sort, 'desc'));
        else if(str_contains($sort, 'date created'))
            $sortedUsers = collect($users)->sortBy(function($user)
            {
                return strtotime($user->created_at);
            }, SORT_REGULAR, str_contains($sort, 'desc'));
        else
            return $users;

        return $sortedUsers;
    }

    public function showCreateUserView()
    {
        if(!Gate::allows('create-user'))
            abort(403);

        return view('create-user', [
            'roles' => $this->getRoles(),
        ]);
    }

    private function getRoles(): array
    {
        $roles = array();

        foreach(Role::all() as $role)
            if(!(auth()->user()->userRole->role->name == 'hr' && $role == 'admin'))
                array_push($roles, [
                    'name' => $role->name,
                    'value' => $role->id,
                ]);

        return $roles;
    }

    public function storeCreatedUser(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user',
            'phoneNumber' => 'required|string|max:255',
        ]);

        $password = Str::random(10);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        $user->userDetails()->create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'gender' => $request->gender,
            'phone_number' => $request->phoneNumber,
        ]);

        $user->userRole()->create([
            'role_id' => $request->role,
        ]);

        Mail::to($user)->send(new UserCreated($user, $password));

        return redirect('/create-user')->with('notification', 'User '.$user->userDetails->first_name.' '.$user->userDetails->last_name.' created.');
    }

    public function updateUsers(Request $request)
    {
        if(!$request->selectedIds)
            return redirect()->back()->with('notification', 'You have not selected any users.');

        $users = User::whereIn('id', $request->selectedIds)->get();

        return redirect('/'.$request->route)->with('users', $users);
    }

    public function showEditUsersView(Request $request)
    {
        if(!Gate::allows('edit-users'))
            abort(403);

        return view('edit-users', [
            'users' => session('users'),
            'roles' => $this->getRoles(),
        ]);
    }

    public function saveEditedUsers(Request $request)
    {
        for($i = 0; $i < count($request->ids); $i++)
        {
            $user = User::find($request->ids[$i]);
            $user->userDetails->first_name = $request->firstName[$i];
            $user->userDetails->last_name = $request->lastName[$i];
            $user->userDetails->gender = $request->gender[$i];
            $user->userRole->role_id = $request->role[$i];
            $user->email = $request->email[$i];
            $user->userDetails->phone_number = $request->phoneNumber[$i];

            $user->save();
            $user->userDetails->save();
            $user->userRole->save();
        }

        return redirect('/users')->with('notification', 'Changes to '.count($request->ids).' '.Pluralizer::plural('user', count($request->ids)).' saved.');
    }

    public function deleteUsers(Request $request)
    {
        if(!Gate::allows('delete-users'))
            abort(403);

        foreach(session('users') as $user)
            $user->delete();

        return redirect('/users')->with('notification', count(session('users')).' '.Pluralizer::plural('user', count(session('users'))).' deleted.');
    }
}
