<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationCountMessage;
use Illuminate\Http\Client\Request;

class UserController extends Controller
{
    public function create(UserRequest $request){
        $activationCode  = Str::random(20);
        $user = User::create(array_merge($request->all(), ['activation_code' => $activationCode ]));
        //$user = User::create($request->all());
        Mail::to($user->email)->send(new ActivationCountMessage($activationCode ));
        return response()->json(["message" => "Mail de confirmation envoyé !"], 201);
    }

    public function index(){
        $user = User::all();
        return UserResource::collection($user);
    }

    public function view(int $id){
        $user = User::findOrFail($id);
        return UserResource::make($user);
    }

    public function update(UserRequest $request, int $id){
        $user = User::findorFail($id);
        $dataToUpdate = $request->only('name');
        $user->update($dataToUpdate);

        return new UserResource($user);
    }

    public function delete(int $id){
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès'], 204);
    }
}
