@inject('model', '\App\Domains\Auth\Models\User')

@extends('backend.layouts.app')

@section('title', __('Create User'))

@section('content')
<form action="{{ route('admin.members.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <x-backend.card>
        <x-slot name="header">
            @lang('Create User')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.members.index')" :text="__('Cancel')" />
        </x-slot>

        <x-slot name="body">
            <div x-data="{userType : '{{ $model::TYPE_USER }}'}">
                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label">@lang('Type')</label>

                    <div class="col-md-10">
                        <select name="type" class="form-control" required x-on:change="userType = $event.target.value">
                            <option value="{{ $model::TYPE_MEMBER }}">@lang('member')</option>
                        </select>
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>

                    <div class="col-md-10">
                        <input type="text" name="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') }}" maxlength="100" required />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="email" class="col-md-2 col-form-label">@lang('E-mail Address')</label>

                    <div class="col-md-10">
                        <input type="email" name="email" class="form-control" placeholder="{{ __('E-mail Address') }}" value="{{ old('email') }}" maxlength="255" required />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="password" class="col-md-2 col-form-label">@lang('Password')</label>

                    <div class="col-md-10">
                        <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Password') }}" maxlength="100" required autocomplete="new-password" />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="password_confirmation" class="col-md-2 col-form-label">@lang('Password Confirmation')</label>

                    <div class="col-md-10">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('Password Confirmation') }}" maxlength="100" required autocomplete="new-password" />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row" style="display:none">
                    <label for="Payouts " class="col-md-2 col-form-label">@lang('Payouts')</label>

                    <div class="col-md-10">
                        <input type="text" name="payouts" class="form-control" placeholder="{{ __('Payouts') }}" value="{{ old('Payouts') }}" />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row" style="display:none">
                    <label for="profileimage" class="col-md-2 col-form-label">@lang('Profile Image')</label>

                    <div class="col-md-10">
                        <input type="file" name="profileimage" id="profileimage" class="form-control" value="{{ old('profileimage') }}" placeholder="{{ __('Profile Image') }}" />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row" style="display:none">
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

                <div class="form-group row" style="display:none">
                    <label for="age" class="col-md-2 col-form-label">@lang('Age')</label>

                    <div class="col-md-10">
                        <input type="number" step="1" name="age" id="age" class="form-control" value="{{ old('age') }}" placeholder="{{ __('age') }}" maxlength="100" />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row" style="display:none">
                    <label for="Weight" class="col-md-2 col-form-label">@lang('weight')</label>

                    <div class="col-md-10">
                        <input type="number" step="1" name="weight" id="weight" class="form-control" value="{{ old('weight') }}" placeholder="{{ __('Weight') }}" />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row" style="display:none">
                    <label for="location" class="col-md-2 col-form-label">@lang('Location')</label>

                    <div class="col-md-10">
                        <input type="text" name="location" class="form-control" placeholder="{{ __('Location') }}" value="{{ old('location') }}" maxlength="100" />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row" style="display:none">
                    <label for="fightingstyle" class="col-md-2 col-form-label">@lang('Fighting style')</label>

                    <div class="col-md-10">
                        <select name="fightingstyle" id="fightingstyle" class="form-control" value="{{ old('fightingstyle') }}">
                            <option value="">Select</option>
                            <option value="Lethwei">Lethwei</option>
                            <option value="Sanda">Sanda</option>
                            <option value="Judo">Judo</option>
                        </select>
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row" style="display:none">
                    <label for="bio" class="col-md-2 col-form-label">@lang('Bio')</label>

                    <div class="col-md-10">
                        <textarea name="bio" class="form-control" placeholder="{{ __('Bio') }}" value="{{ old('bio') }}"></textarea>
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row" style="display:none">
                    <label for="goals" class="col-md-2 col-form-label">@lang('Goals')</label>

                    <div class="col-md-10">
                        <input type="text" name="goals" class="form-control" placeholder="{{ __('Goals') }}" value="{{ old('goals') }}" />
                    </div>
                </div>
                <!--form-group-->

                <div class="form-group row">
                    <label for="active" class="col-md-2 col-form-label">@lang('Active')</label>

                    <div class="col-md-10">
                        <div class="form-check">
                            <input name="active" id="active" class="form-check-input" type="checkbox" value="1" {{ old('active', true) ? 'checked' : '' }} />
                        </div>
                        <!--form-check-->
                    </div>
                </div>
                <!--form-group-->

                <div x-data="{ emailVerified : false }">
                    <div class="form-group row">
                        <label for="email_verified" class="col-md-2 col-form-label">@lang('E-mail Verified')</label>

                        <div class="col-md-10">
                            <div class="form-check">
                                <input type="checkbox" name="email_verified" id="email_verified" value="1" class="form-check-input" x-on:click="emailVerified = !emailVerified" {{ old('email_verified') ? 'checked' : '' }} />
                            </div>
                            <!--form-check-->
                        </div>
                    </div>
                    <!--form-group-->

                    <div x-show="!emailVerified">
                        <div class="form-group row">
                            <label for="send_confirmation_email" class="col-md-2 col-form-label">@lang('Send Confirmation E-mail')</label>

                            <div class="col-md-10">
                                <div class="form-check">
                                    <input type="checkbox" name="send_confirmation_email" id="send_confirmation_email" value="1" class="form-check-input" {{ old('send_confirmation_email') ? 'checked' : '' }} />
                                </div>
                                <!--form-check-->
                            </div>
                        </div>
                        <!--form-group-->
                    </div>
                </div>

                @include('backend.auth.includes.roles')

                @if (!config('boilerplate.access.user.only_roles'))
                @include('backend.auth.includes.permissions')
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create User')</button>
        </x-slot>
    </x-backend.card>
</form>
@endsection