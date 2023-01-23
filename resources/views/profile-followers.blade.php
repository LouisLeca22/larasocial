<x-profile :sharedData="$sharedData" doctitle="Les followers de {{$sharedData['username']}}'}}">
  @include('profile-followers-only')
  </x-profile>