{{-- Register Form Component --}}
<div class="mt-6">
    <form class="space-y-6" action="{{ route('register') }}" method="POST">
        @csrf

        {{-- Name Section --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-4">Nama Lengkap</label>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}"
                        placeholder="First Name"
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('first_name') border-red-300 @enderror">
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}"
                        placeholder="Last Name"
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('last_name') border-red-300 @enderror">
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Login Details Section --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-4">Login Details</label>
            <div class="space-y-4">
                <div>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Email"
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-300 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <div class="relative">
                        <input id="password" name="password" type="password" placeholder="Password"
                            class="appearance-none block w-full px-3 py-2 pr-10 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-300 @enderror">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            onclick="togglePassword('password')">
                            <svg id="eye-open-password" class="h-5 w-5 text-gray-400 hover:text-gray-500 cursor-pointer"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <svg id="eye-closed-password"
                                class="h-5 w-5 text-gray-400 hover:text-gray-500 cursor-pointer hidden" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21">
                                </path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Minimum 8 characters with at least one uppercase, one
                        lowercase, one special character and a number</p>
                </div>
                <div>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            placeholder="Confirm Password"
                            class="appearance-none block w-full px-3 py-2 pr-10 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            onclick="togglePassword('password_confirmation')">
                            <svg id="eye-open-password_confirmation"
                                class="h-5 w-5 text-gray-400 hover:text-gray-500 cursor-pointer" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <svg id="eye-closed-password_confirmation"
                                class="h-5 w-5 text-gray-400 hover:text-gray-500 cursor-pointer hidden" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Terms and Conditions --}}
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input id="terms" name="terms" type="checkbox"
                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded @error('terms') border-red-300 @enderror">
            </div>
            <div class="ml-3 text-sm">
                <label for="terms" class="text-gray-700">
                    By clicking 'Log In' you agree to our website
                    <a href="" class="text-blue-600 hover:text-blue-500">Terms & Conditions</a>.
                </label>
                @error('terms')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Keep me logged in --}}
        <div class="flex items-center">
            <div class="flex items-center h-5">
                <input id="remember" name="remember" type="checkbox"
                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
            </div>
            <div class="ml-3 text-sm">
                <label for="remember" class="text-gray-700">Keep me logged in</label>
            </div>
        </div>

        {{-- Submit Button --}}
        <div>
            <button type="submit"
                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <span class="flex items-center">
                    REGISTER
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            </button>
        </div>
    </form>
</div>

<script>
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const eyeOpen = document.getElementById('eye-open-' + fieldId);
        const eyeClosed = document.getElementById('eye-closed-' + fieldId);

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            passwordField.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>
