<?php
/**
 * Ideated by Sergio Casizzone
 * User: jambtc
 * Date: 01/02/2021
 */
namespace jambtc\oauthfacebook;

class facebook extends \yii\base\Widget
{
    public $facebookAppID;
    public $facebookAppVersion;
    public $language;
    public $country;

    function __construct($facebookAppID,$facebookAppVersion,$language,$country){
        $this->facebookAppID = $facebookAppID;
        $this->facebookAppVersion = $facebookAppVersion;
        $this->language = $language;
        $this->country = $country;
    }

    public function loginButton(){
        $this->jsFB();
        return '<fb:login-button class="fb-login-button" data-size="large" data-button-type="login_with" data-use-continue-as="true" scope="public_profile,email"  onlogin="checkLoginState();"> </fb:login-button>';
    }

    public function jsFB(){
        echo "<script>
        window.fbAsyncInit = function() {
          FB.init({
            appId      : '".$this->facebookAppID."',
            cookie     : true,
            xfbml      : true,
            version    : '".$this->facebookAppVersion."'
          });

          FB.AppEvents.logPageView();

        };

        (function(d, s, id){
           var js, fjs = d.getElementsByTagName(s)[0];
           if (d.getElementById(id)) {return;}
           js = d.createElement(s); js.id = id;
           js.src = 'https://connect.facebook.net/".$this->language."_".$this->country."/sdk.js';
           fjs.parentNode.insertBefore(js, fjs);
         }(document, 'script', 'facebook-jssdk'));

         function checkLoginState() {
           FB.getLoginStatus(function(response) {
         	   console.log('[FB user data to save]',response);
             //statusChangeCallback(response);
         	   if (response.status == 'connected'){
         		    console.log('[FB utente connesso]');
         		     getFbUserData();
         		    // console.log('[FB userData]',userData)
         	   }else{
         		    console.log('[FB utente NON connesso]');
         	   }

           });
         }
        </script>";
    }

}
