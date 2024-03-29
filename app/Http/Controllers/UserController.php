<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;


class UserController extends Controller
{
    public function dashboard()
    {
        // Check if the user is authenticated
        if (Auth::check()) {

            $user = Auth::user();
            $posts = $user->posts;
            return view('dashboard.pages.index', compact('user', 'posts'));
        } else {

            return redirect()->route('user.login');
        }
    }
    public function getAllUserForApi()
    {
        $users = User::orderBy('id', 'DESC')->get();
        return response()->json($users);
    }
    public function storeUserForApi(UserRegistrationRequest $request)
    {
        $validated = $request->only(['name', 'email', 'password']);

        try {
            $user =  User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
        } catch (Exception $e) {
            Log::error($e);
        }
        return response()->json($user);
    }
    public function index()
    {
        $users = User::with(['posts' => function ($query) {
            // Retrieve posts where is_active is true
            $query->where('is_active', true);
        }])->orderBy('id', 'DESC')->get();
        return view('user.pages.index', compact('users'));
    }
    // user registration form
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('user.dashboard');
        } else {
            return view('user.pages.registration');
        }
    }
    // user log in form
    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('user.dashboard');
        } else {
            return view('user.pages.login');
        }
    }
    // user create
    public function store(UserRegistrationRequest $request)
    {
        $validated = $request->only(['name', 'email', 'password']);

        try {
            $user =  User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
        } catch (Exception $e) {
            Log::error($e);
        }
        return redirect()->route('user.login');
    }
    // user log in
    public function login(UserLoginRequest $request)
    {
        $validated = $request->only(['email', 'password']);

        if (Auth::attempt($validated)) {
            // Authentication was successful
            $user = Auth::user(); // Retrieve the authenticated user
            return redirect()->intended(route('user.dashboard'));
        } else {
            // Authentication failed
            return redirect()->route('user.login')->with('error', 'Invalid email or password');
        }
    }
    // user log out
    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.login');
    }
    // post filter for home page
    public function filterUserPost(Request $request)
    {
        $startDateInput = $request->input('start_date');
        $userName = $request->input('user_name');
        $startDate = Carbon::createFromFormat('m/d/Y', $startDateInput)->format('Y-m-d');
        $user = Auth::user();
        $filterUserPost = User::where('name', 'LIKE', "%$userName%")
            ->with(['posts' => function ($query) use ($startDate) {
                // Filter posts based on the specified start date and active status
                $query->whereDate('created_at', '=', $startDate)
                    ->where('is_active', true);
            }])
            ->get();
        return view('user.pages.filter', compact('filterUserPost'));
    }
}
