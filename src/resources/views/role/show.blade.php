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
                    <form method="POST" action="{{ route('roles.permissions', $role->id) }}">
                        @csrf
                        @method('PUT')
                    <table class="table-auto w-full border border-collapse shadow rounded-lg text-white"
                        aria-describedby="">
                        <thead>
                            <tr>
                                <th scope="col" class="px-4 py-2" rowspan="2">{{ __('app.navigations') }}</th>
                                <th scope="col" class="px-4 py-2" colspan="4">{{ __('app.permission') }}</th>
                            </tr>
                            <tr>
                                <th scope="col" class="px-4 py-2">Create</th>
                                <th scope="col" class="px-4 py-2">Read</th>
                                <th scope="col" class="px-4 py-2">Update</th>
                                <th scope="col" class="px-4 py-2">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @can('roles.read')
                            @foreach ($navigations as $nav)
                            <tr>
                                <td class="px-4 py-2 text-start">{{ $nav->name }}</td>
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" name="permission[]" value="{{ strtolower($nav->url).'.create' }}" {{ in_array(strtolower($nav->url).'.create', $permissions) ? 'checked' : ''}}>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" name="permission[]" value="{{ strtolower($nav->url).'.read' }}" {{ in_array(strtolower($nav->url).'.read', $permissions) ? 'checked' : ''}}>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" name="permission[]" value="{{ strtolower($nav->url).'.update' }}" {{ in_array(strtolower($nav->url).'.update', $permissions) ? 'checked' : ''}}>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" name="permission[]" value="{{ strtolower($nav->url).'.delete' }}" {{ in_array(strtolower($nav->url).'.delete', $permissions) ? 'checked' : ''}}>
                                </td>
                            </tr>
                            @if($nav->child->count() > 0)
                            @foreach ($nav->child as $child)
                            <tr>
                                <td class="px-6 py-2 text-start">{{ $child->name }}</td>
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" name="permission[]" value="{{ strtolower($child->url).'.create' }}" {{ in_array(strtolower($child->url).'.create', $permissions) ? 'checked' : ''}}>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" name="permission[]" value="{{ strtolower($child->url).'.read' }}" {{ in_array(strtolower($child->url).'.read', $permissions) ? 'checked' : ''}}>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" name="permission[]" value="{{ strtolower($child->url).'.update' }}" {{ in_array(strtolower($child->url).'.update', $permissions) ? 'checked' : ''}}>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" name="permission[]" value="{{ strtolower($child->url).'.delete' }}" {{ in_array(strtolower($child->url).'.delete', $permissions) ? 'checked' : ''}}>
                            </tr>
                            @endforeach
                            @endif
                            @endforeach
                            @endcan
                        </tbody>
                    </table>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __('app.save') }}</button>
                    <button type="button" id="check_all" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __('app.check_all') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // togle check all checkbox without jquery
        document.getElementById('check_all').addEventListener('click', function() {
            var checkboxes = document.getElementsByName('permission[]');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = true;
            }
        });
        
    </script>
</x-app-layout>
