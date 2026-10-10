<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

      public function index(Request $request)
    {
        $search = $request->search;

        $users = User::query()
            ->when($search, function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');

                });

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('backend.users.index', compact('users'));
    }

    public function create()
    {
        return view('backend.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            // Customers may have only a name and phone; everyone who logs in needs email + password
            'email'    => 'required_unless:role,customer|nullable|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required_unless:role,customer|nullable|min:6',
            'role'     => 'required|in:admin,manager,cashier,staff,customer',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => $request->filled('password') ? Hash::make($request->password) : null,
            'role'     => $request->role,
            'status'   => 1,
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/users'), $imageName);

            $data['image'] = 'uploads/users/' . $imageName;
        }

        User::create($data);

        return redirect()->route('users')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('backend.users.edit', compact('user'));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);


        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required_unless:role,customer|nullable|email|unique:users,email,' . $id,
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|in:admin,manager,cashier,staff,customer',
            'status'   => 'required|in:0,1',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'name'   => $request->name,
            'email'  => $request->email,
            'phone'  => $request->phone,
            'role'   => $request->role,
            'status' => $request->status,
        ];

        // Update image if a new image is uploaded
        if ($request->hasFile('image')) {

            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            $image = $request->file('image');

            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/users'), $imageName);

            $data['image'] = 'uploads/users/' . $imageName;
        }

        $user->update($data);

        return redirect()
            ->route('users')
            ->with('success', 'User updated successfully.');


        }


    public function delete($id)
    {
        $user = User::findOrFail($id);

        if ($user->image && file_exists(public_path($user->image))) {
            unlink(public_path($user->image));
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}