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

    public $auth_url;

    function __construct($facebookAppID,$facebookAppVersion,$language,$country,$auth_url){
        $this->facebookAppID = $facebookAppID;
        $this->facebookAppVersion = $facebookAppVersion;
        $this->language = $language;
        $this->country = $country;

        $this->auth_url = $auth_url;
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

         // Fetch the user profile data from facebook
       	function getFbUserData(){
       	    FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
       	    function (user) {
       			console.log('[FB userData]', user);

           			$.ajax({
                  url: '".$this->auth_url."',
           				type: 'POST',
           				data:{
           					'email'		: user.email,
           					'first_name': user.first_name,
           					'last_name'	: user.last_name,
           					'id'		: user.id,
           					'username'	: user.first_name+'.'+user.last_name,
           					'picture'	: user.picture.data.url,
           				},
           				dataType: 'json',
           				success:function(data){
           					console.log('FB userdata',data);
                  },
           				error: function(j){
           					console.log(j);
           				}
           			});
       	    });
       	}








        </script>";
    }

}
