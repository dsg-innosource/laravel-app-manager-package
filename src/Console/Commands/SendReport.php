<?php

namespace InnoSource\LaravelApplicationManager\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InnoSource\LaravelApplicationManager\Composer;

class SendReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lam:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reports application and server details to LAM';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('LAM: Starting report');

        if (! config('lam.uuid')) {
            $this->register();
        } else {
            $this->report();
        }
        
        $this->line('LAM: Details Sent');
        
        return self::SUCCESS;
    }

    public function register()
    {
        $this->line('LAM: Registering & Sending details');

        $client = new Client();
        
        $response = $client->post(config('lam.base_url') . '/api/register', [
            'form_params' => $this->getData(),
        ]);

        if ($response->getStatusCode() !== 200) {
            $this->error('LAM: Send Failed');
            $this->error($response->getReasonPhrase());
            
            return self::FAILURE;
        }

        $responseData = json_decode($response->getBody()->getContents());

        $this->addLamUuidToDotEnv($responseData->uuid);
    }

    public function report()
    {
        $this->line('LAM: Sending details');

        $client = new Client();

        $response = $client->post(config('lam.base_url') . '/api/report/' . config('lam.uuid'), [
            'form_params' => $this->getData(),
        ]);

        if ($response->getStatusCode() !== 200) {
            $this->error('LAM: Send Failed');
            $this->error($response->getReasonPhrase());
            
            return self::FAILURE;
        }
    }

    public function getData()
    {
        $this->line('LAM: Collecting details');

        return [
            'composer_versions' => $this->getComposerVersions(),
            'php_version' => phpversion(),
            'database_version' => $this->getMySQLVersion(),
            'config' => json_encode($this->getConfig()),
        ];
    }

    public function getMySQLVersion()
    {
        $data = DB::select("SHOW VARIABLES LIKE 'version'");

        return $data[0]->Value;
    }

    public function getConfig()
    {
        return [
            'app' => [
                'name' => config('app.name'),
                'env' => config('app.env'),
                'debug' => config('app.debug'),
                'url' => config('app.url'),
            ],
            'db' => [
                'default' => config('database.default'),
            ],
            'mail' => [
                'default' => config('mail.default'),
                'host' => config('mail.mailers.' . config('mail.default') . '.host'),
            ],
            'custom' => config('lam.custom'),
        ];
    }

    public function getComposerVersions()
    {
        $all = Composer::versions();
        $required = Composer::required();

        return collect($all)->filter(function ($version, $package) use ($required) {
            return isset($required[$package]);
        })->toArray();
    }

    public function addLamUuidToDotEnv($uuid)
    {
        file_put_contents(app()->environmentFilePath(), "\r\nLAM_UUID=" . $uuid, FILE_APPEND);
    }
}
