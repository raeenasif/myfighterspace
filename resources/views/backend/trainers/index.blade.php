@extends('backend.layouts.app')

@section('title', __('Trainers'))

@section('breadcrumb-links')
@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
<x-backend.card>
    <x-slot name="header">
        @lang('Trainers')
    </x-slot>

    @if ($logged_in_user->hasAllAccess())
    <x-slot name="headerActions">
        <x-utils.link icon="c-icon cil-plus" class="card-header-action" :href="route('admin.trainers.create')" :text="__('Create Trainer')" />
    </x-slot>
    @endif

    <x-slot name="body">
        <livewire:backend.trainers-table />
    </x-slot>
</x-backend.card>
@endsection