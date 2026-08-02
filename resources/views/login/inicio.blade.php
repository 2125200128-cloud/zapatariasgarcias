<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 dark:bg-neutral-900">
    <main class="flex items-center justify-center py-4 px-4 md:px-8 lg:h-screen">
        <div class="max-w-6xl border border-slate-200 bg-white shadow-sm p-4 rounded-lg lg:p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="grid md:grid-cols-2 items-center gap-x-8 gap-y-12">
                
                {{-- Bloque de formulario --}}
                <div class="max-w-md mx-auto w-full p-2 md:p-4">
                    <div class="inline-block mb-10">
                        <a href="#">
                            <img src="https://readymadeui.com/readymadeui.svg" alt="logo"
                                class="w-40 block dark:invert dark:brightness-100" />
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded-md bg-red-50 border border-red-200 text-sm text-red-700 dark:bg-red-950 dark:border-red-900 dark:text-red-300">
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Formulario de login --}}
                    <form action="{{ url('/login') }}" method="POST" class="space-y-6">
                       @csrf
                        <div>
                            <label for="usuario"
                                class="mb-2 text-slate-900 font-medium text-sm inline-block dark:text-slate-50">Usuario</label>
                            <input type="text" id="usuario" name="usuario" value="{{ old('usuario') }}" placeholder="encargado.zapopan" required autofocus
                                class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                        </div>

                        <div class="relative">
                            <label for="password"
                                class="mb-2 text-slate-900 font-medium text-sm inline-block dark:text-slate-50">Contraseña</label>
                            <input type="password" id="password" name="password" placeholder="••••••••" required
                                class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                        </div>

                        <div class="flex items-start flex-wrap gap-2">
                            <label class="flex items-center group has-[input:checked]:text-slate-900">
                                <input id="remember" name="remember" type="checkbox" class="sr-only" />
                                <span
                                    class="flex h-4 w-4 shrink-0 items-center justify-center rounded outline-1 outline-slate-300 dark:outline-neutral-600 bg-white dark:bg-neutral-700 group-has-[input:checked]:bg-blue-600 group-has-[input:checked]:outline-blue-600 group-focus-within:outline-2 group-focus-within:outline-blue-600"
                                    aria-hidden="true">
                                    <svg class="size-3 text-white opacity-0 group-has-[input:checked]:opacity-100"
                                        viewBox="0 0 12 10" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 5l3 3 7-7" />
                                    </svg>
                                </span>
                                <span class="ml-3 text-sm text-slate-700 dark:text-slate-300">
                                    Recordarme
                                </span>
                            </label>

                            <a href="#"
                                class="ml-auto text-sm font-medium text-blue-700 dark:text-blue-500 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
                                Forgot password?
                            </a>
                        </div>

                        <button type="submit"
                            class="w-full py-2 px-3.5 text-sm rounded-md font-semibold cursor-pointer tracking-wide text-white border border-blue-600 bg-blue-600 hover:bg-blue-700 transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                            Entrar
                        </button>
                    </form>

                    <div class="flex items-center gap-4 my-8">
                        <hr class="w-full border-slate-300 dark:border-neutral-700" />
                        <p class="text-sm text-slate-700 text-center dark:text-slate-300">or</p>
                        <hr class="w-full border-slate-300 dark:border-neutral-700" />
                    </div>

                    <div>
                        <a href="#"
                            class="w-full flex items-center justify-center gap-2.5 py-2 px-3.5 text-sm rounded-md font-semibold text-slate-900 border border-slate-300 bg-white hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:text-slate-50 dark:border-neutral-600 dark:bg-neutral-700 dark:hover:bg-neutral-600">
                            Sign in with Google
                        </a>
                    </div>

                    <div class="mt-6 text-slate-900 text-sm text-center dark:text-slate-50">
                        Don't have an account?
                        <a href="#"
                            class="text-blue-700 hover:underline ml-1 font-medium dark:text-blue-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
                            Sign up
                        </a>
                    </div>
                </div>

                {{-- Bloque de imagen lateral --}}
                <div
                    class="aspect-square bg-gray-50 relative before:absolute before:inset-0 before:bg-indigo-600/70 rounded-md overflow-hidden w-full h-full">
                    <img src="https://readymadeui.com/team-image.webp" class="w-full h-full object-cover" alt="login img" />
                    <div class="absolute inset-0 m-auto max-w-sm p-6 flex items-center justify-center">
                        <div>
                            <h1 class="text-white text-3xl font-bold">Sign in</h1>
                            <p class="text-slate-100 text-base font-medium mt-6 leading-relaxed">
                                Sign in to your account and explore a world of possibilities. Your journey begins here.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</body>
</html>
