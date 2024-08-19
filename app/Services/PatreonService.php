<?php

namespace App\Services;

use App\Models\Patreon;
use Exception;
use Illuminate\Support\Facades\Http;

class PatreonService
{
  protected $patreon;
  protected $client_id;
  protected $redirect_uri;


  public function __construct()
  {
    $this->client_id = config('services.patreon.client_id');
    $this->redirect_uri = config('services.patreon.redirect_uri');
  }

  public function getPatrons()
  {
    // this is used by the patreoncontroller getPatrons function

    $response = Http::withToken(env('PATREON_TEMP_BEARER'))->get("https://www.patreon.com/api/oauth2/api/campaigns/11120253/pledges?include=patron.null");
    return $response->json();

    /* 
    response looks like this:<pre>array(4) {
  ["data"]=>
  array(1) {
    [0]=>
    array(4) {
      ["attributes"]=>
      array(6) {
        ["amount_cents"]=>
        int(100000)
        ["created_at"]=>
        string(29) "2023-09-28T09:51:57.689+00:00"
        ["currency"]=>
        string(3) "HUF"
        ["declined_since"]=>
        NULL
        ["patron_pays_fees"]=>
        bool(false)
        ["pledge_cap_cents"]=>
        int(100000)
      }
      ["id"]=>
      string(9) "145635846"
      ["relationships"]=>
      array(1) {
        ["patron"]=>
        array(2) {
          ["data"]=>
          array(2) {
            ["id"]=>
            string(9) "102216230"
            ["type"]=>
            string(4) "user"
          }
          ["links"]=>
          array(1) {
            ["related"]=>
            string(42) "https://www.patreon.com/api/user/102216230"
          }
        }
      }
      ["type"]=>
      string(6) "pledge"
    }
  }
  ["included"]=>
  array(1) {
    [0]=>
    array(3) {
      ["attributes"]=>
      array(18) {
        ["about"]=>
        NULL
        ["created"]=>
        string(29) "2023-09-28T09:33:37.000+00:00"
        ["current_user_block_status"]=>
        string(4) "none"
        ["email"]=>
        string(19) "dgloria@freemail.hu"
        ["facebook"]=>
        NULL
        ["first_name"]=>
        string(7) "Beatrix"
        ["full_name"]=>
        string(7) "Beatrix"
        ["gender"]=>
        int(1)
        ["image_url"]=>
        string(38) "https://c8.patreon.com/3/200/102216230"
        ["is_email_verified"]=>
        bool(true)
        ["last_name"]=>
        string(0) ""
        ["social_connections"]=>
        array(13) {
          ["discord"]=>
          NULL
          ["facebook"]=>
          NULL
          ["google"]=>
          NULL
          ["instagram"]=>
          NULL
          ["reddit"]=>
          NULL
          ["spotify"]=>
          NULL
          ["spotify_open_access"]=>
          NULL
          ["tiktok"]=>
          NULL
          ["twitch"]=>
          NULL
          ["twitter"]=>
          NULL
          ["twitter2"]=>
          NULL
          ["vimeo"]=>
          NULL
          ["youtube"]=>
          NULL
        }
        ["thumb_url"]=>
        string(38) "https://c8.patreon.com/3/200/102216230"
        ["twitch"]=>
        NULL
        ["twitter"]=>
        NULL
        ["url"]=>
        string(40) "https://www.patreon.com/user?u=102216230"
        ["vanity"]=>
        NULL
        ["youtube"]=>
        NULL
      }
      ["id"]=>
      string(9) "102216230"
      ["type"]=>
      string(4) "user"
    }
  }
  ["links"]=>
  array(1) {
    ["first"]=>
    string(97) "https://www.patreon.com/api/oauth2/api/campaigns/11120253/pledges?page%5Bcount%5D=10&sort=created"
  }
  ["meta"]=>
  array(1) {
    ["count"]=>
    int(1)
  }
}
</pre>
*/
  }
}
