<x-layout>   
    <div class="container py-md-5">
      <div class="row align-items-center">
        <div class="col-lg-7 py-3 py-md-5">
          <h1 class="display-3">Toi aussi tu as envie d'écrire ?</h1>
          <p class="lead text-muted">Tu en as marre des tweets impersonnels et tu préférerais &ldquo;écrire&rdquo; de longs articles comme dans les années 90&rsquo;s ? Nous pensons que le retour au bon vieux blog est le meilleur moyen d'apprendre à réapprécier internet. Nos auteurs ont déjà publié {{$postCount}} articles, rejoins les !</p>
        </div>
        <div class="col-lg-5 pl-lg-5 pb-3 py-lg-5">
          <form action="/register" method="POST" id="registration-form">
            @csrf
            <div class="form-group">
              <label for="username-register" class="text-muted mb-1"><small>Nom d'utilisateur</small></label>
              <input value="{{old('username')}}" name="username" id="username-register" class="form-control" type="text" placeholder="Choisis un nom d'utilisateur" autocomplete="off" />

              @error("username")
              <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
              @enderror
            </div>

            <div class="form-group">
              <label for="email-register" class="text-muted mb-1"><small>E-mail</small></label>
              <input value="{{old('email')}}" name="email" id="email-register" class="form-control" type="text" placeholder="tonemail@example.com" autocomplete="off" />
              @error("email")
              <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
              @enderror
            </div>

            <div class="form-group">
              <label for="password-register" class="text-muted mb-1"><small>Mot de passe</small></label>
              <input  name="password" id="password-register" class="form-control" type="password" placeholder="Créer un mot de passe" />
              @error("password")
              <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
              @enderror
            </div>

            <div class="form-group">
              <label for="password-register-confirm" class="text-muted mb-1"><small>Confirmer le mot de passe</small></label>
              <input name="password_confirmation" id="password-register-confirm" class="form-control" type="password" placeholder="Taper à nouveau le mot de passe" />
              @error("password_confirmation")
              <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
              @enderror
            </div>

            <button type="submit" class="py-3 mt-4 btn btn-lg btn-success btn-block">Inscris-toi sur LaraSocial</button>
          </form>
        </div>
      </div>
    </div>
</x-layout>
    