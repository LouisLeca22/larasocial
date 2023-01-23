<x-layout doctitle="Editing {{$post->title}}">
    <div class="container py-md-5 container--narrow">
        <p><small><strong><a href="/post/{{$post->id}}">&laquo; Retour</a></strong></small></p>
        <form action="/post/{{$post->id}}" method="POST">
            @method("PUT")   
            @csrf
          <div class="form-group">
            <label for="post-title" class="text-muted mb-1"><small>Titre</small></label>
            <input value="{{old('title', $post->title)}}"  name="title" id="post-title" class="form-control form-control-lg form-control-title" type="text" placeholder="" autocomplete="off" />
            @error('title')
            <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
            @enderror
          </div>
  
          <div class="form-group">
            <label for="post-body" class="text-muted mb-1"><small>Corps de l'article</small></label>
            <textarea  name="body" id="post-body" class="body-content tall-textarea form-control" type="text">
                {{old('body', $post->body)}}
            </textarea>
            @error('body')
            <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
            @enderror
          </div>
  
          <button class="btn btn-primary">Sauvegarder les changements</button>
        </form>
      </div>
</x-layout>