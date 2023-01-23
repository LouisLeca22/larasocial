<x-profile :sharedData="$sharedData" doctitle="profile de {{$sharedData['username']}}">
  @include('profile-posts-only')
</x-profile>