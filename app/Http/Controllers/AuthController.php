<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use JWTAuth;
use JWTFactory;
use Validator;
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function isUnique($uname)
    {
      $user = User::where('uname',$uname)->first();
      if($user){
        return response()->json(['unique'=>false]);
      }
      return response()->json(['unique'=>true]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $req = $request->json()->all();
        $rules = [
          'name' => 'required|min:3',
          'uname' => 'required|unique:users,uname|min:3',
          'password' => 'required|min:6',
          'confirmedPassword' => 'required|same:password',
          'utype' => 'required'
        ];
        $validator = Validator::make($req, $rules);
        if ($validator->fails()) {
         	return response()->json(['errors' => $validator->errors()],422);
        }
        $user = new User;
        $user->name = $req['name'];
        $user->uname = $req['uname'];
        $user->password = $req['password'];
        $user->admin = $req['utype'];
        $saved = $user->save();
        //check if saved correctly
        if (!$saved) {
          return response()->json(["error" => "Sorry etwas ist schief gelaufen beim Server"], 422);
        }
        return response()->json(['success' => 'Neuer Benutzer wurde erfolgreich erstellt'], 201);
    }

    public function login()
    {
        // $user = User::findOrFail(3);
        // $user->password = 'ahmed55ayman';
        // $user->save();
        $credentials = request(['uname', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function changePassword(Request $request)
    {
        $req = $request->json()->all();
        $rules = [
          'oldPassword' => 'required',
          'newPassword' => 'required|min:6',
          'confirmedPassword' => 'required|same:newPassword'
        ];
        $error_messages = [
          'oldPassword.required' => 'Altes Passwort kann nicht leer sein',
          'newPassword.required' => 'Neues Passwort kann nicht leer sein',
          'newPassword.min' => 'Neues Passwort kann nicht weniger als 6 Zeichen sein',
          'confirmedPassword.required' => 'Neues Passwort Bestätigung kann nicht leer sein',
          'confirmedPassword.same' => 'Passwort Bestätigung muss genau wie neues Passwort sein',

        ];
        $validator = Validator::make($req, $rules, $error_messages);
        if ($validator->fails()) {
         	return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = auth()->user();
        if(!Hash::check($req['oldPassword'], $user->password)){
          return response()->json(['oldPassword' => 'Altes Passwort stimmt nicht'], 422);
        }
        $user->password = $req['newPassword'];
        $saved = $user->save();
        if(!$saved){
          return response()->json(['errors' => 'Sorry etwas ist schief gelaufen beim Server'], 422);
        }

        return response()->json(['success' => 'das Passwort wurde erfolgreich verändert'], 200);
    }

    //get all users
    public function allUsers()
    {
      $users = User::where('id','!=',auth()->user()->id)->get();
      return response()->json(['users' => $users], 200);
    }

    //delete user
    public function deleteUser($id)
    {
      $user = User::findOrFail($id);
      try {
        $user->delete();
      } catch (\Exception $e) {
        return response()->json(['error' => 'Sorry etwas ist schief gelaufen beim Server'], 422);
      }
      return response()->json(['success' => 'Der Benutzer "'.ucwords($user->name).'" wurde erfolgreich gelöscht'], 201);
    }
}
