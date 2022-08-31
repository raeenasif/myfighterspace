@extends('backend.layouts.app')

@section('title', __('View Trainers'))

@section('content')
<x-backend.card>
    <x-slot name="header">
        @lang('View Trainers')
    </x-slot>

    <x-slot name="headerActions">
        <x-utils.link class="card-header-action" :href="route('admin.trainers.index')" :text="__('Back')" />
    </x-slot>

    <x-slot name="body">
        <table class="table table-hover">
            <!-- <tr>
                <th>@lang('Type')</th>
                <td>@include('backend.auth.user.includes.type')</td>
            </tr> -->

            <!-- <tr>
                <th>@lang('Avatar')</th>
                <td><img style="width: 60px;
                 height: auto;" src="{{asset('storage/profile').'/'.$user->profileimage}}" class="user-profile-image" /></td>
            </tr> -->

            <tr>
                <th>@lang('Name')</th>
                <td>{{ $user->name }}</td>
            </tr>

            <tr>
                <th>@lang('Age')</th>
                <td>{{ $user->age }}</td>
            </tr>


            <tr>
                <th>@lang('Sex')</th>
                <td>{{ $user->Sex }}</td>
            </tr>

            <tr>
                <th>@lang('Weight')</th>
                <td>{{ $user->weight}}</td>
            </tr>

            <tr>
                <th>@lang('location')</th>
                <td>{{ $user->location }}</td>
            </tr>

            <tr>
                <th>@lang('Fighting style')</th>
                <td>{{ $user->fightingstyle}}</td>
            </tr>

            <tr>
                <th>@lang('Bio')</th>
                <td>{{ $user->bio}}</td>
            </tr>

            <tr>
                <th>@lang('Goals')</th>
                <td>{{ $user->goals }}</td>
            </tr>

            <tr>
                <th>@lang('Bookings')</th>
                <td>No</td>
            </tr>

            <tr>
                <th>@lang('Payouts')</th>
                <td>{{ $user->payouts }}</td>
            </tr>

            <tr>
                <th>@lang('Fights')</th>
                <td>No</td>
            </tr>

            <tr>
                <th>@lang('Winnings')</th>
                <td>No</td>
            </tr>

            <tr>
                <th>@lang('Status')</th>
                <td>@include('backend.auth.user.includes.status', ['user' => $user])</td>
            </tr>

            <tr>
                <th>@lang('Payouts ')</th>
                <td>{{ $user->created_at }}</td>
            </tr>

            <tr>
                <th>@lang('Verified')</th>
                <td>@include('backend.auth.user.includes.verified', ['user' => $user])</td>
            </tr>

        </table>
    </x-slot>

    <x-slot name="footer">
        <small class="float-right text-muted">
            <strong>@lang('Account Created'):</strong> @displayDate($user->created_at) ({{ $user->created_at->diffForHumans() }}),
            <strong>@lang('Last Updated'):</strong> @displayDate($user->updated_at) ({{ $user->updated_at->diffForHumans() }})

            @if($user->trashed())
            <strong>@lang('Account Deleted'):</strong> @displayDate($user->deleted_at) ({{ $user->deleted_at->diffForHumans() }})
            @endif
        </small>
    </x-slot>
</x-backend.card>
@endsection