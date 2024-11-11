@extends('layouts.app')
@section('title', 'Rendelések')

@section('content')
<div class="container">

    @if (Session::has('order_created'))
        <div class="alert alert-success" role="alert">
            #{{ Session::get('order_created')->id }} számú rendelés leadva.
        </div>
    @endif

    @if (Session::has('order_deleted'))
        <div class="alert alert-success" role="alert">
            #{{ Session::get('order_deleted')->id }} számú rendelés törölve.
        </div>
    @endif

    @if (Session::has('order_updated'))
        <div class="alert alert-success" role="alert">
            #{{ Session::get('order_updated')->id }} számú rendelés elkészítve.
        </div>
    @endif

    @auth
        @if (Auth::user()->is_admin)
            <div>
                <div class="row justify-content-between">
                    <div class="col-12 col-md-8">
                        <h1>Rendelések</h1>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="float-lg-end">
                            <a href="{{ route('orders.create') }}" role="button" class="btn btn btn-success mb-1"><i class="fas fa-plus-circle"></i> Új rendelés</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8 mb-5">
                    <p>Ezen az oldalon betekintést nyerhetnek az összes termékemre, ami alatt találhatják majd az aukcióra bocsátottakat is.</p>
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="align-middle">Rendelési szám</th>
                            <th class="align-middle">Megrendelő</th>
                            <th class="align-middle">Rendelve</th>
                            <th class="align-middle"></th>
                            <th class="align-middle text-center">Kész</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td class="align-middle">#{{ $order->id }}</td>
                                <td class="align-middle">{{ $order->orderer->name }}</td>
                                <td class="align-middle">{{ $order->created_at }}</td>
                                <td class="align-middle">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-info">
                                        <span>Részletek</span> <i class="fas fa-angle-right"></i>
                                    </a>

                                    <div class="btn-group float-right">
                                        <form method="POST" action="{{ route('orders.destroy', $order) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    @if ($order->ready)
                                        <button type="button" class="btn btn-success" disabled><i class="far fa-check-circle"></i></button>
                                    @else
                                        <form action="{{ route('orders.update', $order->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-secondary" name="ready" value="1">
                                                <i class="fas fa-toggle-off"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div>
                <div class="row justify-content-between">
                    <div class="col-12 col-md-8">
                        <h2 class="mb-4">Rendeléseim <span class="badge bg-info">{{ $myOrderCount }}</span></h2>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="float-lg-end">
                            <a href="{{ route('orders.create') }}" role="button" class="btn btn btn-success mb-1"><i class="fas fa-plus-circle"></i> Új rendelés</a>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="align-middle">Rendelési szám</th>
                            <th class="align-middle">Rendelve</th>
                            <th class="align-middle"></th>
                            <th class="align-middle text-center">Elkészült</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($myOrders as $order)
                            <tr>
                                <td class="align-middle">#{{ $order->id }}</td>
                                <td class="align-middle">{{ $order->created_at }}</td>
                                <td class="align-middle">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-info">
                                        <span>Részletek</span> <i class="fas fa-angle-right"></i>
                                    </a>
                                </td>
                                <td class="align-middle text-center">
                                    @if ($order->ready)
                                        <button type="button" class="btn btn-success" disabled><i class="far fa-check-circle"></i></button>
                                    @else
                                        <button type="button" class="btn btn-danger" disabled><i class="far fa-times-circle"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <p>Rendelései megtekintéséhez kérem <a href="{{ route('login') }}">jelentkezzen be</a> vagy <a href="{{ route('register') }}">regisztáljon</a>.</p>
    @endauth
</div>
@endsection
