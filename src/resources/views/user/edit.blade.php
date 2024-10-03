<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('app.users') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl table-responsive">
                    <span class="text-white">{{ session('success') }}</span>
                    <span class="text-white">{{ session('error') }}</span>
                    @if (! isset($create))
                    <form method="POST" action="{{ route('users.update', $user->id ?? '') }}">
                        @method('PUT')
                    @else
                        <form method="POST" action="{{ route('users.store') }}">
                    @endif
                        @csrf
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('app.name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$user->name ?? old('name')" required
                                autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('app.email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="$user->email ?? old('email')" required
                                autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('app.password')" />

                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                autocomplete="new-password" :required="$create ?? false" />

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('app.confirmPassword')" />

                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation"
                             autocomplete="new-password" :required="$create ?? false" />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="role" :value="__('app.selectRolesActive')" />

                            <select id="role" name="role"
                                class="block mt-1 w-full text-gray-700 bg-white rounded-md shadow-sm border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 disabled:bg-gray-200 disabled:cursor-not-allowed">
                                <option value="">Please select a role</option>
                                @foreach ($roles as $role)
                                @if (isset($user))
                                    <option value="{{ $role }}" {{ in_array($role, $user->roles->pluck('name')->toArray() ?? []) ? 'selected' : '' }}>{{ ucwords($role) }}</option>
                                @else
                                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>{{ ucwords($role) }}</option>
                                @endif
                                @endforeach
                            </select>

                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="role" :value="__('app.selectMultipleRoles')" />

                            <select id="role" name="roleMultiple[]" multiple
                                class="block mt-1 w-full text-gray-700 bg-white rounded-md shadow-sm border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 disabled:bg-gray-200 disabled:cursor-not-allowed">
                                <option value="">Please select roles</option>
                                @foreach ($roles as $id => $role)
                                    @if (isset($user))
                                        <option value="{{ $id }}" {{ in_array($id, $user->roleMultiple->pluck('id')->toArray() ?? []) ? 'selected' : '' }}>
                                            {{ ucwords($role) }}
                                        </option>
                                    @else
                                        <option value="{{ $id }}" {{ in_array($id, old('roleMultiple', [])) ? 'selected' : '' }}>
                                            {{ ucwords($role) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                            <x-input-error :messages="$errors->get('roleMultiple')" class="mt-2" />
                        </div>
                        @can('roles.*')
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('app.save') }}
                            </x-primary-button>
                        </div>
                        @endcan
                    </form>
                </div>
                @if (isset($user))
                <div class="max-w-xl">
                    <form method="post" action="{{ route('users.destroy', $user->id) }}" class="p-6">
                        @csrf
                        @method('delete')

                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('app.deleteConfirm') }}
                        </h2>
                        @can('users.delete')
                        <div class="mt-6 flex justify-end">
                            <x-danger-button class="ms-3">
                                {{ __('app.delete') }}
                            </x-danger-button>
                        </div>
                        @endcan
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
