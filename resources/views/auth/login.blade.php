@extends('layouts.header')
@section('content')
    <section class="h-screen flex items-center justify-center relative overflow-hidden bg-[url('../../assets/images/01.jpg')] bg-no-repeat bg-center bg-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black"></div>
        <div class="container">
            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1">
                <div class="relative overflow-hidden bg-white dark:bg-slate-900 shadow-md dark:shadow-gray-800 rounded-md">
                    <div class="p-6">
                        <a href="">
                            <img width="50" src="favicon.png" class="mx-auto block dark:hidden" alt="">
                            <img width="50" src="favicon.png" class="mx-auto dark:block hidden" alt="">
                        </a>
                        <h5 class="my-6 text-xl font-semibold">Admin Login</h5>
                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif

                        <x-validation-errors class="mb-4" />
                        <form action="{{route('login')}}" method="post" class="text-start">
                            @csrf
                            <div class="grid grid-cols-1">
                                <div class="mb-4">
                                    <label class="font-medium" for="LoginEmail">Email Address:</label>
                                    <input id="LoginEmail" type="email" name="email" class="form-input border !border-gray-200 dark:!border-gray-800 mt-3" placeholder="name@example.com">
                                </div>

                                <div class="mb-4">
                                    <label class="font-medium" for="LoginPassword">Password:</label>
                                    <input id="LoginPassword" type="password" name="password" class="form-input border !border-gray-200 dark:!border-gray-800 mt-3" placeholder="Password:">
                                </div>

                                <div class="flex justify-between mb-4">
                                    <div class="flex items-center mb-0">
                                        <input class="form-checkbox size-4 appearance-none rounded border border-gray-200 dark:border-gray-800 accent-green-600 checked:appearance-auto dark:accent-green-600 focus:border-green-300 focus:ring-0 focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50 me-2" type="checkbox" value="" id="RememberMe">
                                        <label class="form-checkbox-label text-slate-400" for="RememberMe">Remember me</label>
                                    </div>
                                    <p class="text-slate-400 mb-0"><a href="#" class="text-slate-400">Forgot password ?</a></p>
                                </div>

                                <div class="mb-4">
                                    <button type="submit" class="btn btn-primary text-white rounded-md w-full" style="background-color: blue">Login / Sign in</button>
                                </div>

{{--                                <div class="text-center">--}}
{{--                                    <span class="text-slate-400 me-2">Don't have an account ?</span> <a href="signup.html" class="text-black dark:text-white font-medium">Sign Up</a>--}}
{{--                                </div>--}}
                            </div>
                        </form>
                    </div>

                    <div class="px-6 py-2 bg-slate-50 dark:bg-slate-800 text-center">
                        <p class="mb-0 text-slate-400">© <script>document.write(new Date().getFullYear())</script> </p>
                    </div>
                </div>
            </div>
        </div>
    </section><!--end section -->

@endsection
