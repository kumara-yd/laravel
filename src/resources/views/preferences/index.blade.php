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
                    <div>
                        <span class="text-white">{{ session('success') }}</span>
                    </div>
                    @can('users-create')
                    <a href="{{ route('preferences.create') }}" class="btn btn-primary text-white">{{ __('app.add') }}</a>
                    @endcan
                    <table class="table-auto w-full border border-collapse shadow rounded-lg text-white"
                        aria-describedby="">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">{{ __('app.group') }}</th>
                                <th class="px-4 py-2">{{ __('app.name') }}</th>
                                <th class="px-4 py-2">{{ __('app.value') }}</th>
                                <th class="px-4 py-2">{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @can('preferences.read')
                            @foreach ($preferences as $preference)
                            <tr>
                                <td class="px-4 py-2 text-center">{{ $preference->group }}</td>
                                <td class="px-4 py-2 text-center">{{ $preference->name }}</td>
                                <td class="px-4 py-2 text-left">{{ $preference->value }}</td>
                                <td class="px-4 py-2 text-center">
                                    @can('preferences.update')
                                    <a href="{{ route('preferences.edit', $preference->id) }}" class="btn btn-primary">{{
                                        __('app.edit') }}</a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                            @endcan
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>