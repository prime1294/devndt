<?php

class Instamojo
{
    private $client_id;
    private $client_secret;
    private $url = "https://api.instamojo.com/oauth2/token/";
    private $env = "production"; //production 

    public function __construct($client_id, $client_secret)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    public function getToken() {
        if (substr( $this->client_id, 0, 5 ) === "test_") {
            $this->url = "https://test.instamojo.com/oauth2/token/";
            $this->env = "test";
        }
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, rawurldecode(http_build_query(array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'client_credentials'
        ))));
        $json = json_decode(curl_exec($curl));
        if(curl_error($curl))
        {
            echo 'error:' . curl_error($curl);
        }
        if (isset($json->error)) {
            return "Error: " . $json->error;
            throw new \Exception("Error: " . $json->error);
        }
        $this->token = $json;
        return $this->env . $json->access_token;
    }
}

// $instamojo = new Instamojo("NGKqBFcW2SpPxRfnUydw6EanbS6wFhELHPy35iBd", "BWdqyZpjTqYOYa1z8pJaTbmmWyLPfJtiT6qbCTxkLcyCXPwAguGfr3cCE9cyhTYZWSzbGfGTZtW3274b5iCczdut2mMXB8OnqyFjlRhlHGJcXZorDl9lGh2SRqa5bZ3W");
// $instamojo = new Instamojo("test_Skzma0w6MWR3hpANWGLZ19G32UGwlgUpHzR", "test_SNzRAL9R5w4C4nqjyRGlt5cYggzcwTVq85uOjsLKD7XJL0zQBCjbwJ8TIerkg6Xc371EOBC3iGGYivQP4m7kGT1P1oSQTWGDvrUzQ2AdRJe4CkczmbCVEsHu8OT");
$instamojo = new Instamojo("TZRN2STUrOODTtx93V4o8nxVTjbycAsbQgDoQG5R", "4dg8UOeLv7THynDB3hO3RDoPczfoVK3kBCjOncA2TZI2Lv9kTM3phHNMs8kPhRaX5AMMPQCkXxCELivX2kL0P2wTkgxnvKpToQhehuTP8be4Ry4jq2vpGuQL8N5tssFu");

echo $instamojo->getToken();
?>
