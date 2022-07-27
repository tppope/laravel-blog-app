<x-dropdown>
    <x-slot name="trigger">
        <button class="py-2 pl-3 pr-9 text-sm font-semibold w-full text-left lg:w-32 lg:inline-flex">
            {{ isset($currentCategory) ? ucwords($currentCategory->name) : 'Category' }}

            <x-icon name="down-arrow" class="absolute pointer-events-none" style="right: 12px;" width="22"/>

        </button>
    </x-slot>
    <x-dropdown-item href="/" :active="! isset($currentCategory)">All</x-dropdown-item>

    @foreach($categories as $category)
        <x-dropdown-item href="/?category={{ $category->slug }}&{{ http_build_query(request()->except('category')) }}"
                         :active="isset($currentCategory) && $currentCategory->is($category)">
            {{ ucwords($category->name) }}
        </x-dropdown-item>

    @endforeach
</x-dropdown>
