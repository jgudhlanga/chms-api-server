<?php
namespace App\Transformers;
use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract{

	public function transform(User $user)
	{
		return [
			'id' => $user->id,
			'apiToken' => $user->api_token ,
			'firstName' => $user->firstName,
			'name' => $user->name,
			'surname' => $user->surname,
			'email' => $user->email,
		];
	}
}