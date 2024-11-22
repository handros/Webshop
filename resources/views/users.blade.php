@extends('layouts.app')
@section('title', 'Felhasználók')

@section('content')
@if (Auth::user()->is_admin)
    <div class="container">
        <h2 class="mb-4">Felhasználók</h2>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="align-middle">Név</th>
                    <th class="align-middle">Email</th>
                    <th class="align-middle">Admin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="align-middle">{{ $user->name }}</td>
                        <td class="align-middle">{{ $user->email }}</td>
                        <td class="align-middle">
                            @if ($user->is_admin)
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i></span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times-circle"></i></span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
