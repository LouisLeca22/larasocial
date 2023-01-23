<x-profile :sharedData="$sharedData" doctitle="Les personnes que {{$sharedData['username']}} suit ">
@include('profile-following-only')
</x-profile>