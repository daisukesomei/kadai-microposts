@extends('layouts.app')

@section('content')
    <div class="sm:grid sm:grid-cols-3 sm:gap-10">
        <asaide class="mt-4">
            {{--ユーザー情報--}}
            @include('users.card')
        </asaide>
        <div class="sm:col-span-2 mt-4">
            {{--タブ--}}
            @include('users.navtabs')
            <div class="mt-4">
                {{--ユーザー一覧--}}
                @include('users.users')
            </div>
        </div>
    </div>
@endsection