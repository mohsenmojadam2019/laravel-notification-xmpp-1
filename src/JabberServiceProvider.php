<?php

namespace NotificationChannels\Jabber;

use Fabiang\Xmpp\Client as JabberService;
use Fabiang\Xmpp\Options;
use Illuminate\Support\ServiceProvider;

class JabberServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /*   $this->app->when(JabberChannel::class)
              ->needs(Jabber::class)
              ->give(function () {
                  $jabberConfig = config('services.jabber');
                  $options      = new Options($jabberConfig['address']);
                  $options->setUsername($jabberConfig['username'])
                  ->setPassword($jabberConfig['password']) ;
                  $client=new JabberService($options);
                  return new Jabber($client);
              });*/
        $this->app->when(JabberChannel::class)
              ->needs(Jabber::class)
              ->give(function () {
                  return new Jabber(
                      $this->app->make(JabberService::class)
                  );
              });
        $this->registerJabberService();
    }

    public function register()
    {
        //$this->registerJabberService();
    }

    protected function registerJabberService()
    {
        $this->app->bind(JabberService::class, function () {
            $config = $this->app['config']['services.jabber'];
            $username = array_get($config, 'username');
            $password = array_get($config, 'password');
            $address = array_get($config, 'address');
            $logger = $this->app['log'];
            $options = new Options($address);
            $options->setLogger($logger)
                  ->setUsername($username)
                  ->setPassword($password);

            return  new JabberService($options);
        });
    }

    /*public function boot()
    {

        $this->app->when(JabberChannel::class)
             ->needs(Jabber::class)
             ->give(function () {
                 $jabberConfig = config('services.jabber');
                 $options      = new Options($jabberConfig['address']);
                 $options->setUsername($jabberConfig['username'])
                 ->setPassword($jabberConfig['password']) ;

                 return new Jabber(
                     new Client(
                         $options
                     )
                 );
             });
    }*/
}
