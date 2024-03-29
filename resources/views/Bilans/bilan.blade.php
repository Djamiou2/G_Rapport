@extends('layouts.app')

@section('content')
<div class="container-fluid bg-light">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header bg-primary text-white"> <strong>{{ __('Bilans') }}</strong></div>

        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif

          <div>
            @livewire('show-bilan')
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
