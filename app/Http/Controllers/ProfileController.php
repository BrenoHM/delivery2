<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Timeline;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        //dd($request->all());

        $request->user()->fill([
            'name' => $request->name,
            'email' => $request->email
        ]);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        if($request->user()->role == 'client') {

            $data = [
                'domain' => $request->domain,
                'primaryColor' => $request->primaryColor,
                'secondaryColor' => $request->secondaryColor,
                'zip_code' => $request->zip_code,
                'street' => $request->street,
                'number' => $request->number,
                'neighborhood' => $request->neighborhood,
                'state' => $request->state,
                'city' => $request->city,
                'type_pix_key' => $request->type_pix_key,
                'pix_key' => $request->pix_key
            ];

            if( $request->logo ) {
                $path = Storage::put('tenants/'.$request->tenant_id.'/logo', $request->logo, 'public');
                if( $path ) {
                    $data['logo'] = env('AWS_URL') . '/' . $path;
                }
            }

            //update in tenant
            $request->user()->tenant()->update($data);

            foreach($request->timeline as $time){
                Timeline::updateOrCreate([
                    'tenant_id'   => Auth::user()->tenant_id,
                    'day_of_week' => $time['day_of_week'],
                ],[
                    'start' => $time['start'] ? $time['start'] : '00:00:00',
                    'end' => $time['end'] ? $time['end'] : '00:00:00'
                ]);
            }
        }

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();
        $user->tenant()->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
