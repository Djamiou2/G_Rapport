<div class="row">
    <div class="card">
        <div class="card-body container-fluid ">
            <form method="POST" wire:submit.prevent="update">
                @csrf
                @method('POST')

                @if (Session::get('error'))
                    <div class="p-5">
                        <div class="alert alert-danger" role="alert">
                            {{ Session::get('error') }}
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du client :</label>
                    <input type="text" class="form-control  @error('nom')is-invalid
           @enderror"
                        name="nom" wire:model="nom" required>
                    @error('nom')
                        <div class="invalid-feedback">Le champ nom est requis.</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse du client :</label>
                    <input type="text" class="form-control  @error('adresse')is-invalid
           @enderror"
                        name="nom" wire:model="adresse" required>
                    @error('adresse')
                        <div class="invalid-feedback">Le champ Adresse est requis.</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email du client :</label>
                    <input type="text" class="form-control  @error('email')is-invalid
           @enderror"
                        name="email" wire:model="email" required>
                    @error('email')
                        <div class="invalid-feedback">Le champ email est requis.</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="contact" class="form-label">Contact du client :</label>
                    <input type="number" class="form-control  @error('contact')is-invalid
           @enderror"
                        name="contact" wire:model="contact" required>
                    @error('contact')
                        <div class="invalid-feedback">Le champ contact est requis.</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mb-4">

                    <a href="{{ route('clients') }}" class="btn btn-sm btn-danger text-white fs-6">Retourner à la
                        liste</a>
                    <button type="submit" class="btn main-color text text-bold">Mettre à jour</button>
                </div>

            </form>
        </div>
    </div>
</div>
