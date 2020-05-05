<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'profile_image' => ['image', 'mimes:jpeg,jpg,png', 'required', 'max:2048'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        if ($data['profile_image']->getMimeType() == 'image/jpeg')
            $image = imagecreatefromjpeg($data['profile_image']->getRealPath());
        elseif ($data['profile_image']->getMimeType() == 'image/png')
            $image = imagecreatefrompng($data['profile_image']->getRealPath());

        $destination = $data['profile_image']->getFilename();
        imagejpeg($image, $destination, 75);

        $client = new Client();
        $response = json_decode($client->request('POST', env('IMGBB_URL_WITH_API_KEY'), [
            'form_params' => [
                'image' => base64_encode(file_get_contents($destination)),
                'name' => $data['email'],
                'connect_timeout' => 20,
                'read_timeout' => 20,
            ]
        ])->getBody()->getContents())->data->thumb->url;
        File::delete($destination);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'profile_image' => $response,
        ]);
    }
}
