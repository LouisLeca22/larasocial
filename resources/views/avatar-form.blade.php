<x-layout doctitle="Gére ta photo de profil">
<div class="container container--narrow py-md-5">
    <h2 class="text-center mb-3">Télécharger une nouvelle image</h2>
    <form action="/manage-avatar" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <input type="file" name="avatar" required>
            @error('avatar')
            <p class="alert small alert-danger shadow-sm">{{$message}}</p>
            @enderror
        </div>
        <button class="btn btn-primary">Sauvegarder</button>
    </form>
</div>
</x-layout>