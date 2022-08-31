@inject('model', '\App\Domains\Auth\Models\User')

@extends('backend.layouts.app')

@section('title', __('Update User'))

@section('content')
<form action="{{ route('admin.trainers.update', $user) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <x-backend.card>
        <x-slot name="header">
            @lang('Update User')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.trainers.index')" :text="__('Cancel')" />
        </x-slot>

        <x-slot name="body">
            <div x-data="{userType : '{{ $user->type }}'}">
                @if (!$user->isMasterAdmin())
                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label">@lang('Type')</label>

                    <div class="col-md-10">
                        <select name="type" class="form-control" required x-on:change="userType = $event.target.value">
                            <option value="{{ $model::TYPE_TRAINER }}" {{ $user->type === $model::TYPE_TRAINER ? 'selected' : '' }}>@lang('Trainer')</option>
                        </select>
                    </div>
                </div>
                <!--form-group-->
                @endif

                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>

                    <div class="col-md-10">
                        <input type="text" name="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') ?? $user->name }}" maxlength="100" required />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="email" class="col-md-2 col-form-label">@lang('E-mail Address')</label>

                    <div class="col-md-10">
                        <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('E-mail Address') }}" value="{{ old('email') ?? $user->email }}" maxlength="255" required />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="profileimage" class="col-md-2 col-form-label">@lang('Profile Image')</label>

                    <div class="col-md-10">
                        <img style="width: 60px;
    height: auto;" src="{{asset('storage/profile').'/'.$user->profileimage}}" class="user-profile-image" />

                        <input type="file" name="profileimage" id="profileimage" class="form-control" placeholder="{{ __('Profile Image') }}" />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="subscription" class="col-md-2 col-form-label">@lang('Subscription')</label>

                    <div class="col-md-10">
                        <select name="subscription" id="subscription" class="form-control">
                            <option value="">Select</option>
                            <option value="Improve 5k">Improve 5k </option>
                            <option value="Gain">Gain</option>
                            <option value="Lose ">Lose</option>
                        </select>
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="age" class="col-md-2 col-form-label">@lang('Age')</label>

                    <div class="col-md-10">
                        <input type="number" step="1" name="age" id="age" class="form-control" placeholder="{{ __('age') }}" value="{{  old('age') ?? $user->age }}" maxlength="100" />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="Weight" class="col-md-2 col-form-label">@lang('Weight')</label>

                    <div class="col-md-10">
                        <input type="number" step="1" name="weight" id="age" class="form-control" placeholder="{{ __('Weight') }} " value="{{  old('weight') ?? $user->weight }}" />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="location" class="col-md-2 col-form-label">@lang('Location')</label>

                    <div class="col-md-10">
                        <input type="text" name="location" class="form-control" placeholder="{{ __('Location') }}" value="{{  old('location')?? $user->location }}" maxlength="100" />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="payouts" class="col-md-2 col-form-label">@lang('Payouts')</label>

                    <div class="col-md-10">
                        <input type="text" name="payouts" class="form-control" placeholder="{{ __('Payouts') }}" value="{{  old('location')?? $user->location }}" maxlength="100" />
                    </div>
                </div>
                <!--form-group-->


                <div class="form-group row">
                    <label for="fightingstyle" class="col-md-2 col-form-label">@lang('Fighting style')</label>

                    <div class="col-md-10">
                        <select name="fightingstyle" id="fightingstyle" class="form-control" value="{{ ($user->fightingstyle)? 'selected' : '' }}">
                            <option value="">Select</option>
                            <option {{ ($user->fightingstyle) == 'Lethwei' ? 'selected' : '' }} value="Lethwei">Lethwei</option>
                            <option {{ ($user->fightingstyle) == 'Sanda' ? 'selected' : '' }} value="Sanda">Sanda</option>
                            <option {{ ($user->fightingstyle) == 'Judo' ? 'selected' : '' }} value="Judo">Judo</option>
                        </select>
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="bio" class="col-md-2 col-form-label">@lang('Bio')</label>

                    <div class="col-md-10">
                        <textarea name="bio" class="form-control" placeholder="{{ __('Bio') }}">{{ old('bio')?? $user->bio }}</textarea>
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="goals" class="col-md-2 col-form-label">@lang('Goals')</label>

                    <div class="col-md-10">
                        <input type="text" name="goals" class="form-control" placeholder="{{ __('Goals') }}" value="{{ old('goals')?? $user->goals }}" />
                    </div>
                </div>
                <!--form-group-->
                @include('backend.trainers.roles')

                @if (!config('boilerplate.access.user.only_roles'))
                @include('backend.auth.includes.permissions')
                @endif

            </div>
        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update User')</button>
        </x-slot>
    </x-backend.card>
</form>
@endsection