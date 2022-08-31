@extends('backend.layouts.app')

@section('title', __('Members'))

@section('breadcrumb-links')
@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
<x-backend.card>
    <x-slot name="header">
        @lang('Members')
    </x-slot>

    @if ($logged_in_user->hasAllAccess())
    <x-slot name="headerActions">
        <x-utils.link icon="c-icon cil-plus" class="card-header-action" :href="route('admin.members.create')" :text="__('Create Members')" />
    </x-slot>
    @endif

    <x-slot name="body">
        <livewire:backend.members-table />
    </x-slot>
</x-backend.card>
@endsection