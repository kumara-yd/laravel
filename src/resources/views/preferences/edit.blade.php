<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('app.preferences') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl table-responsive">
                    <span class="text-white">{{ session('success') }}</span>
                    @if (! isset($create))
                    <form method="POST" action="{{ route('preferences.update', $preference->id ?? '') }}">
                        @method('PUT')
                        @else
                        <form method="POST" action="{{ route('preferences.store') }}">
                            @endif
                            @csrf
                            <!-- Group -->
                            <div class="mt-4">
                                <x-input-label for="group" :value="__('app.group')" />
                                <x-text-input id="group" class="block mt-1 w-full" type="text" name="group"
                                    :value="$preference->group ?? old('group')" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('group')" class="mt-2" />
                            </div>

                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('app.name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="$preference->name ?? old('name')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Value -->
                            <div class="mt-4">
                                <x-input-label for="value" :value="__('app.value')" />

                                <x-text-input id="value" class="block mt-1 w-full" type="text" name="value"
                                    autocomplete="new-password" :required="$create ?? false" 
                                    :value="$preference->value ?? old('value')"/>

                                <x-input-error :messages="$errors->get('value')" class="mt-2" />
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
                @if (isset($preference))
                <div class="max-w-xl">
                    <form method="post" action="{{ route('preferences.destroy', $preference->id) }}" class="p-6">
                        @csrf
                        @method('delete')

                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('app.deleteConfirm') }}
                        </h2>
                        @can('preferences.delete')
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