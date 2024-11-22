@extends('layouts.app')
@section('title', 'Rólam')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-body">
                <h1 class="mb-4 text-center">Bemutatkozás</h1>
                <p class="mb-4 text-center">
                    Üdvözöllek az oldalamon, ahol bemutatom az összes ékszert és festményt, amit készítettem az elmúlt években. <br>
                    A folyamatosan frissülő portfóliómban előfordulhatnak olyan művek, amiket aukcióra bocsátok, melyekre <strong>licitálni</strong> lehet egy adott időpontig és a végén legmagasabb ajánlat viheti. <br>
                    Ezen felül a bejelentkezett vendégeim <strong>rendeléseket</strong> is leadhatnak.
                </p>

                <div class="d-flex justify-content-center mb-3">
                        <a href="{{ asset('images/icon.png') }}" target="_blank">
                            <img src="{{ asset('images/icon.png') }}"
                                class="card-img-top img-fluid"
                                alt="Kép"
                                style="max-width: 400px;">
                        </a>
                </div>

                <hr>

                <h1 class="mb-4 text-center">Kapcsolat</h1>
                <p class="text-center"><i class="fas fa-user-circle"></i> Név: My Name</p>
                <p class="text-center"><i class="fas fa-home"></i> Város: Budapest</p>
                <p class="text-center"><i class="fas fa-envelope"></i> Email: myname@example.com</p>
                <p class="text-center"><i class="fab fa-instagram"></i> Instagram: <a href="https://www.instagram.com/" target="_blank" role="button" class="btn btn-sm btn-light mb-1">@mytag</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
