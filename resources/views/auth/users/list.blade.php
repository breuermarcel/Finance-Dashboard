@extends('layouts.app')

@section('content')
    <div class="btn-toolbar mb-2">
        <div class="btn-group">
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-outline-secondary">{{ trans('Create') }}</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>{{ trans('ID') }}</th>
                <th>{{ trans('Firstname') }}</th>
                <th>{{ trans('Lastname') }}</th>
                <th>{{ trans('E-Mail') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->firstname }}</td>
                    <td>{{ $user->lastname }}</td>
                    <td>{{ $user->email }}</td>

                    <td class="text-end">
                        <a href="{{ route('users.edit', $user) }}"
                           class="btn btn-outline-secondary">{{ trans('Edit') }}</a>

                        <form class="d-inline" method="POST" action="{{ route('users.destroy', $user) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ trans('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
