<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('app.roles') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl table-responsive">
                    <div>
                        <span class="text-white">{{ session('success') }}</span>
                    </div>
                    @can('roles.create')
                    <a href="{{ route('roles.create') }}" class="btn btn-primary text-white">{{ __('app.add') }}</a>
                    @endcan
                    <table class="table-auto w-full border border-collapse shadow rounded-lg text-white"
                        aria-describedby="">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">{{ __('app.name') }}</th>
                                <th class="px-4 py-2">{{ __('app.permission') }}</th>
                                <th class="px-4 py-2">{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @can('roles.read')
                            @foreach ($roles as $role)
                            <tr>
                                <td class="px-4 py-2 text-center">{{ $role->name }}</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ route('roles.show', $role->id) }}" class="btn btn-primary">{{ __('app.view') }}</a>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    @can('roles.update')
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary">{{ __('app.edit') }}</a>
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
