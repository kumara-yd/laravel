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
                    <div>
                        <span class="text-white">{{ session('success') }}</span>
                    </div>
                    @can('navigations.create')
                    <a href="{{ route('navs.create') }}" class="btn btn-primary text-white">{{ __('app.add') }}</a>
                    @endcan
                    <table class="table-auto w-full border border-collapse shadow rounded-lg text-white"
                        aria-describedby="">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">{{ __('app.name') }}</th>
                                <th class="px-4 py-2">{{ __('app.url') }}</th>
                                <th class="px-4 py-2">{{ __('app.order') }}</th>
                                <th class="px-4 py-2">{{ __('app.active') }}</th>
                                <th class="px-4 py-2">{{ __('app.display') }}</th>
                                <th class="px-4 py-2">{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @can('navigations.read')
                            @foreach ($navigations as $nav)
                            <tr>
                                <td class="px-4 py-2 text-start">{{ $nav->name }}</td>
                                <td class="px-4 py-2 text-center">{{ $nav->url }}</td>
                                <td class="px-4 py-2 text-center">{{ $nav->order }}</td>
                                <td class="px-4 py-2 text-center">{{ $nav->active }}</td>
                                <td class="px-4 py-2 text-center">{{ $nav->display }}</td>
                                <td class="px-4 py-2 text-center">
                                    @can('navigations.update')
                                    <a href="{{ route('navs.edit', $nav->id) }}" class="btn btn-primary">{{ __('app.edit') }}</a>
                                    @endcan
                                </td>
                            </tr>
                            @if($nav->child->count() > 0)
                            @foreach ($nav->child as $child)
                            <tr>
                                <td class="px-6 py-2 text-start">{{ $child->name }}</td>
                                <td class="px-6 py-2 text-center">{{ $child->url }}</td>
                                <td class="px-6 py-2 text-center">{{ $child->order }}</td>
                                <td class="px-4 py-2 text-center">{{ $child->active }}</td>
                                <td class="px-4 py-2 text-center">{{ $child->display }}</td>
                                <td class="px-6 py-2 text-center">
                                    @can('navigations.update')
                                    <a href="{{ route('navs.edit', $child->id) }}" class="btn btn-primary">{{ __('app.edit') }}</a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @endforeach
                            @endcan
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
