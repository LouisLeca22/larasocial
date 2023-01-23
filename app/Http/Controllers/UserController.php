<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{   

    public function showCorrectHomepage(){
        if(auth()->check()){
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(4)]);
        } else {
            // if(Cache::has('postCount')){
            //     $postCount = Cache::get('postCount');
            // } else {
            //     $postCount = Post::count();
            //     Cache::put('postCount', $postCount, 20);
            // }

            $postCount = Cache::remember('postCount', 20, function(){
                return Post::count();
            });
            return view('homepage', ['postCount' => $postCount]);
        }
    }

    public function login(Request $request){
        $fields = $request->validate([
            "loginusername" => "required",
            "loginpassword" => "required"
        ]);

        if(auth()->attempt(['username' => $fields['loginusername'], 'password' => $fields['loginpassword']])){
            $request->session()->regenerate();
            return redirect("/")->with('success', 'Tu es connecté !');
        } else {
            return redirect("/")->with('failure', 'Impossible de se connecter. Réessayer !');
        }
    }

    public function loginApi(Request $request){
        $fields = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if(auth()->attempt($fields)){
            $user = User::where('username', $fields['username'])->first();
            $token = $user->createToken('appToken')->plainTextToken;
            return $token;
        } 

        return response()->json(['message' => "Ressource non trouvée"], 404);
    }

    public function register(Request $request){
        $fields = $request->validate([
            "username" => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            "email" => ['required', 'email', Rule::unique('users', 'email')],
            "password" => ['required', 'min:6', 'confirmed']
        ]);

        $fields["password"] = bcrypt($fields['password']);
        $user = User::create($fields);
        auth()->login($user);
        return redirect('/')->with("success", "Votre compte a bien été créé");
    }

    public function logout(){
        auth()->logout();
        return redirect("/")->with('success', 'Tu es déconnecté !');
    }

    private function getSharedData(User $user){
        $postsCount = $user->posts()->count();
        $followerCount = $user->followers()->count();
        $followingCount = $user->followingTheseUsers()->count();

        $currentlyFollowing = 0;
        if(auth()->check()){
         $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }

        View::share("sharedData", ['avatar' => $user->avatar, 'username' => $user->username, 'postsCount' => $postsCount, 'currentlyFollowing' => $currentlyFollowing, 'followerCount' => $followerCount, 'followingCount' => $followingCount]);
    }

    public function profile(User $user){
        $this->getSharedData($user);
        $posts = $user->posts()->latest()->get();
     
        return view('profile-posts', [ 'posts' => $posts]);
    }

    public function profileRaw(User $user){
        return response()->json(['HTML' => view('profile-posts-only', ['posts' => $user->posts()->latest()->get()])->render(), 'docTitle' => "Profile de ".$user->username]);
    }

    
    public function profileFollowers(User $user){
        $this->getSharedData($user);
        $followers = $user->followers()->latest()->get();
        return view('profile-followers', ['followers' => $followers]);
    }

    public function profileFollowersRaw(User $user){
        return response()->json(['HTML' => view('profile-followers-only', ['followers' => $user->followers()->latest()->get()])->render(), 'docTitle' => "Followers | ".$user->username]);
    }

    public function profileFollowing(User $user){
        $this->getSharedData($user);
        $following = $user->followingTheseUsers()->latest()->get();
        return view('profile-following', [ 'following' => $following]);
    }

    public function profileFollowingRaw(User $user){
        return response()->json(['HTML' => view('profile-following-only', ['following' => $user->followingTheseUsers()->latest()->get()])->render(), 'docTitle' => $user->username." | Following"]);
    }

    public function showAvatarForm(){
        return view('avatar-form');
    }

    public function storeAvatar(Request $request){
        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);

        $user = auth()->user();
        $filename = $user->id . '-'.uniqid().'.jpg';

        $img = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        Storage::put('public/avatars/'.$filename, $img);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if($oldAvatar != "/fallback-avatar.jpg"){
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        }

        return back()->with('success', 'Votre nouvelle image a été téléchargée');
    }


}
