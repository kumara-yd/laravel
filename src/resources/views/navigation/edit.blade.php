<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('app.navigations') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl table-responsive">
                    <span class="text-white">{{ session('success') }}</span>
                    @if (! isset($create))
                    <form method="POST" action="{{ route('navs.update', $navigation->id ?? '') }}">
                        @method('PUT')
                        @else
                        <form method="POST" action="{{ route('navs.store') }}">
                            @endif
                            @csrf
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('app.name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="$navigation->name ?? old('name')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="url" :value="__('app.url')" />
                                <x-text-input id="url" class="block mt-1 w-full" type="text" name="url"
                                    :value="$navigation->url ?? old('url')" required autofocus autocomplete="url" />
                                <x-input-error :messages="$errors->get('url')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="icon" :value="__('app.icon')" />
                                <x-text-input id="icon" class="block mt-1 w-full" type="text" name="icon"
                                    :value="$navigation->icon ?? old('icon')" required autofocus autocomplete="icon" />
                                <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="order" :value="__('app.order')" />
                                <x-text-input id="order" class="block mt-1 w-full" type="number" name="order"
                                    :value="$navigation->order ?? old('order')" required autofocus autocomplete="order" />
                                <x-input-error :messages="$errors->get('order')" class="mt-2" />
                            </div>
                            <div>                          
                                <div class="flex items-center mt-1">
                                    <input type="checkbox" id="display" name="display" class="rounded-md" value="1" {{ isset($navigation->display)&&$navigation->display=='1' ?
                                    'checked' : '' }} />
                                    <label for="display" class="ml-2 text-sm font-medium text-gray-700 text-white px-4">
                                        {{ __('app.display') }}
                                    </label>
                                </div>
                            
                                <x-input-error :messages="$errors->get('display')" class="mt-2" />
                            </div>
                            <div>                            
                                <div class="flex items-center mt-1">
                                    <input type="checkbox" id="active" name="active" class="rounded-md" value="1" {{ isset($navigation->active)&&$navigation->active=='1' ?
                                    'checked' : '' }} />
                                    <label for="active" class="ml-2 text-sm font-medium text-gray-700  text-white px-4">
                                        {{ __('app.active') }}
                                    </label>
                                </div>
                            
                                <x-input-error :messages="$errors->get('active')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="parent_id" :value="__('app.parent')" />
                                <select id="parent_id" name="parent_id"
                                    class="block mt-1 w-full text-gray-700 bg-white rounded-md shadow-sm border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2.5 disabled:bg-gray-200 disabled:cursor-not-allowed">
                                    <option value="">{{ __('app.selectParent') }}</option>
                                    @if ($navigations)
                                    @foreach ($navigations as $navigationOption)
                                    <option value="{{ $navigationOption->id }}" {{ ($navigation->parent_id ?? old('parent_id'))==$navigationOption->id ? 'selected' : '' }}>
                                        {{ $navigationOption->name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                                <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
                            </div>
                            @can('navigations.create')
                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ms-4">
                                    {{ __('app.save') }}
                                </x-primary-button>
                            </div>
                            @endcan
                        </form>
                </div>
                @if (isset($navigation))
                <div class="max-w-xl">
                    <form method="post" action="{{ route('navs.destroy', $navigation->id) }}" class="p-6">
                        @csrf
                        @method('delete')

                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('app.deleteConfirm') }}
                        </h2>
                        @can('navigations.delete')
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
