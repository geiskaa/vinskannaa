<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        // Log the user in after registration
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request for the application.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        // Coba login sebagai user
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard')->with('success', 'Login sebagai user!');
        }

        // Jika gagal, coba login sebagai seller
        if (Auth::guard('seller')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('seller/dashboard')->with('success', 'Login sebagai seller!');
        }

        // Jika keduanya gagal
        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }


    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function showRegistrationSellerForm()
    {
        return view('auth.register-seller');
    }

    /**
     * Handle seller registration.
     */
    public function registerSeller(Request $request)
    {
        $validator = $this->validatorSeller($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $seller = $this->createSeller($request->all());

            // Login seller secara otomatis
            auth('seller')->login($seller); // pastikan guard 'seller' tersedia

            // Redirect ke dashboard seller
            return redirect()->route('seller.dashboard')
                ->with('success', 'Akun seller berhasil dibuat! Selamat datang di dashboard toko Anda.');

        } catch (\Exception $e) {
            \Log::error('Gagal registrasi seller: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->except(['password', 'password_confirmation']),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat akun. Silakan coba lagi.')
                ->withInput();
        }
    }


    /**
     * Get a validator for an incoming registration request.
     */
    protected function validatorSeller(array $data)
    {
        return Validator::make($data, [
            'store_name' => ['required', 'string', 'max:255', 'unique:sellers'],
            'owner_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sellers'],
            'phone_number' => ['required', 'string', 'max:20', 'unique:sellers'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'description' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'terms' => ['required', 'accepted'],
        ], [
            'store_name.required' => 'Nama toko wajib diisi.',
            'store_name.unique' => 'Nama toko sudah digunakan.',
            'owner_name.required' => 'Nama pemilik wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.unique' => 'Nomor telepon sudah terdaftar.',
            'password.required' => 'Passwor wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'city.required' => 'Kota wajib diisi.',
            'province.required' => 'Provinsi wajib diisi.',
            'postal_code.required' => 'Kode pos wajib diisi.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus berformat jpeg, png, jpg, atau gif.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'banner.image' => 'Banner harus berupa gambar.',
            'banner.mimes' => 'Banner harus berformat jpeg, png, jpg, atau gif.',
            'banner.max' => 'Ukuran banner maksimal 2MB.',
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);
    }

    /**
     * Create a new seller instance after a valid registration.
     */
    protected function createSeller(array $data)
    {
        $sellerData = [
            'store_name' => $data['store_name'],
            'owner_name' => $data['owner_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => Hash::make($data['password']),
            'address' => $data['address'],
            'city' => $data['city'],
            'province' => $data['province'],
            'postal_code' => $data['postal_code'],
            'description' => $data['description'] ?? null,
            'status' => 'active', // Default status pending approval
        ];

        // Handle logo upload
        if (request()->hasFile('logo')) {
            $logoPath = $this->uploadImage(request()->file('logo'), 'seller-logos');
            $sellerData['logo'] = $logoPath;
        }

        // Handle banner upload
        if (request()->hasFile('banner')) {
            $bannerPath = $this->uploadImage(request()->file('banner'), 'seller-banners');
            $sellerData['banner'] = $bannerPath;
        }

        return Seller::create($sellerData);
    }

    /**
     * Upload and store image file.
     */
    protected function uploadImage($file, $directory)
    {
        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Store file in public disk
        $path = $file->storeAs($directory, $filename, 'public');

        return $path;
    }


    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                // ->mixedCase()
                // ->numbers()
                // ->symbols()
            ],
            'terms' => ['required', 'accepted'],
        ], [
            'terms.required' => 'You must agree to the terms and conditions.',
            'terms.accepted' => 'You must agree to the terms and conditions.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);
    }
}