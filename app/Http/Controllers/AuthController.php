<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMessage;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $correct = auth()->attempt($request->all());
        if($correct){
            $accessToken = auth()->user()->createToken('token_'.Str::random(20))->plainTextToken;
            return response()->json(['user'=>UserResource::make(auth()->user()),'token'=>$accessToken]);
        }
        return response()->json(['message'=>'Utilisateur et/ou mot de passe incorrect'], 422);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['Deconnecté avec succès']);
    }

    public function resetPassword(Request $request){
        $request->validate(["email"=>"required|email"]);
        $user = User::where("email", $request->email)->firstOrFail();

        // Génération d'un lien de réinitialisation de mot de passe
        $token = Str::random(20);

        // Sauvegarde du token dans une table de réinitialisation
        Mail::to($user->email)->send(new PasswordResetMessage($token)); // Envoyez un email avec le lien de réinitialisation

        return response()->json(['message' => 'Un e-mail de réinitialisation a été envoyé.']);
    }

    public function verify(Request $request){
        $request->validate([
            "email"=> "required|email",
            "activateCount" => "required"
        ]);
        $user = User::where("email", $request->email)->firstOrFail();

        // Vérification du code d'activation
        if ($request->activateCount === $user->activation_code) {
            $user->update(["is_active" => true, "activation_code" => null]);
            return response()->json(["message" => "Compte activé avec succès !"]);
        }

        return response()->json(["message" => "Code d'activation invalide !"], 422);
    }

}
