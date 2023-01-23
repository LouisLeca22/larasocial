<x-layout>
    <div class="container py-md-5 container--narrow">
      @unless ($posts->isEmpty())
      <h2 class="text-center mb-4">Les derniers articles des personnes que vous suivez</h2>
      <div class="list-group">
        @foreach($posts as $post)
        <x-post :post="$post" />
        @endforeach
      </div>

      <div class="mt-4">
        {{$posts->links()}}
      </div>
      
      @else
      <div class="text-center">
        <h2>Salut <strong>{{auth()->user()->username}}</strong>, ton feed est vide.</h2>
        <p class="lead text-muted">Votre feed liste les derniers postes des personnes que vous suivez. Si tu ne suis pas encore personne, tu peux utiliser le bouton &ldquo;Recherche&rdquo; pour trouver des articles qui t'intéressent et suivre les auteurs qui les ont écrits.</p>
      </div>
      @endunless
  
      </div>  
</x-layout>