<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SuccessMail;
use App\Models\Country;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required', 'string', 'max:255',
            'email' => 'required', 'string', 'email', 'max:255', 'unique:users',
            'password' => 'required', 'string', 'min:8', 'confirmed',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'city_id' => 'integer',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = $this->createUser($data);

        if ($user) {
            $this->sendSuccessMail($user, $data);
        }

        return $user;
    }
    
    /**
     * Show registration page.
     *
     */
    public function showRegistrationForm()
    {
        $countries = Country::get(["name", "id"]);
        return view("auth.register", compact("countries"));
    }

    /**
     * create user
     *
     */
    protected function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            // 'password' => md5($data['password']),  // Unncomment if you want to use md5 encryption also from LoginController
            'password' => Hash::make($data['password']),
            'dob' => $data['dob'],
            'gender' => $data['gender'],
            'country_id' => $data['country_id'],
            'state_id' => $data['state_id'],
            'city_id' => $data['city_id'],
        ]);
    }


    /**
     * send success e-mail
     *
     */
    protected function sendSuccessMail(User $user, array $data)
    {
        $mailData = [
            'title' => 'Mail from demo@gmail.com',
            'body' => 'Welcome to User Management System ' . $data['name']
        ];

        try {
            Mail::to($user->email)->send(new SuccessMail($mailData));
        } catch (Exception $e) {
            // Log the error or handle it as necessary
            \Log::error('Failed to send email to user: ' . $e->getMessage());
        }
    }
}
