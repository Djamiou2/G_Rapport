@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card ">
                    <div class="card-header text-black text-bold main-color">
                        {{ __('Création d\'un utilisateur') }}
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div>
                            @livewire('create-user')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
