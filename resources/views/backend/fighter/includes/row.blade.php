<!-- <x-livewire-tables::bs4.table.cell>
    @include('backend.auth.user.includes.type', ['user' => $row])
</x-livewire-tables::bs4.table.cell> -->

<x-livewire-tables::bs4.table.cell>
    <a href="mailto:{{ $row->email }}">{{ $row->email }}</a>
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->name }}
</x-livewire-tables::bs4.table.cell>

<!-- <x-livewire-tables::bs4.table.cell>
    @include('backend.auth.user.includes.verified', ['user' => $row])
</x-livewire-tables::bs4.table.cell> -->

<x-livewire-tables::bs4.table.cell>
    No
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    No
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{$row-> created_at}}
</x-livewire-tables::bs4.table.cell>
<x-livewire-tables::bs4.table.cell>
    @include('backend.auth.user.includes.status', ['user' => $row])
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    @include('backend.fighter.includes.actions', ['user' => $row])
</x-livewire-tables::bs4.table.cell>