<?php

namespace App\Http\Controllers;

use App\Models\Patreon;
use Illuminate\Http\Request;
use App\Services\PatreonService;
use GuzzleHttp\Psr7\Response;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Http;

class PatreonController extends Controller
{
    protected $patreonService;

    public function __construct(PatreonService $patreonService)
    {
        $this->patreonService = $patreonService;
    }

    // Todo: make a service to run the code to connect to the api
    // https://www.patreon.com/oauth2/authorize?response_type=code&client_id=env("PATREON_CLIENT_ID")&redirect_uri=env("PATREON_REDIRECT_URI")

    // this returns the one time code to be used below as $request->code 
    // rGXclYVUrD06r4uaaGIZmA9iVSXLjC


    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // the returned url contains the code
        // https://budgeterapi.co.uk/api/patreon?code=<single use code>&state=None
        $code = $request->query('code');

        /* POST www.patreon.com/api/oauth2/token
            code=$code
            &grant_type=authorization_code
            &client_id=env("PATREON_CLIENT_ID")
            &client_secret= env("PATREON_CLIENT_SECRET")
            &redirect_uri=env("PATREON_REDIRECT_URI") */

        $response = Http::post("https://www.patreon.com/api/oauth2/token", [
            "code" => $code,
            "grant_type" => "authorization_code",
            "client_id" => env("PATREON_CLIENT_ID"),
            "client_secret" => env("PATREON_CLIENT_SECRET"),
            "redirect_uri" => env("PATREON_REDIRECT_URI"),
        ]);

        /* result:
        {
            "access_token": "",
            "expires_in": 2678400,
            "token_type": "Bearer",
            "scope": "identity",
            "refresh_token": "",
            "version": "0.0.1"
        }
        */
        // Todo: save this into database - create schema and run migration
        // https://docs.patreon.com/#step-7-keeping-up-to-date

    }

    /**
     * connect to the api, and store the data in the database
     * this is scheduled to run every minute in app/Console/Kernel.php
     * @param  \App\Models\Patreon  $patreon
     */
    public function getPatrons(Patreon $patreon)
    {
        // empty table
        Patreon::truncate();
        //run the service and update table
        $patreonService = new PatreonService();
        $patrons = $patreonService->getPatrons();

        //test for token or unauthorized error
        if (array_key_exists('errors', $patrons) && $patrons['errors'][0]['status'] == '401') {
            $this->sendErrorEmail();
            return response("Unauthorized. (Invalid Patreon Bearer Token)", 401);
        }

        $patronsData = $patrons['included'];
        foreach ($patronsData as $patronsItem) {

            // insert new data into the table
            $errors = 0;
            $patreon = new Patreon([
                'email' => $patronsItem['attributes']['email'],
                'pledge_created' => $patronsItem['attributes']['created']
            ]);
            if (!$patreon->save()) {
                $errors++;
            }
        }
        if ($errors > 0) {
            $this->sendErrorEmail();
            return response(500);
        }
        return response(200);
    }

    //TODO: implement code for emailing me if cron response is not 200
    public function sendErrorEmail()
    {
        // send me error email: there was an issue in the api response. Please check
    }


    /**
     * V2 code starts here, delete above if this works
     * 
     */
    public function getCodeControl()
    {
        $client_id = config('services.patreon.client_id');
        $redirect_uri = config('services.patreon.redirect_uri');

        $url = "https://www.patreon.com/oauth2/authorize?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri";
        return redirect($url);
    }

    public function patreonStoreCode(Request $request)
    {
        $validated = $request->validate([
            'patreonCode' => 'required|string|min:5|max:255',
            'userId'   => 'required|integer'
        ]);
        // Find the user by id
        $user = \App\Models\User::findOrFail($validated['userId']);

        // create the expiry date for the code
        $expiry_date = new \DateTime();  // Create a DateTime object with the current date and time
        $expiry_date->modify('+30 days');  // Add 30 days to the current date

        // Add the expiry date to the validated data collection
        $validated['patreon_expiry_date'] = $expiry_date->format('Y-m-d'); // or 'Y-m-d H:i:s' if you need the time too

        // Update the patreon_code for the found user
        $user->update([
            'patreon_code' => $validated['patreonCode'],
            'patreon_expiry_date' => $validated['patreon_expiry_date'],
        ]);
    }
}
