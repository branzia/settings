<?php 
namespace Branzia\Settings\Filament\Settings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Branzia\Settings\Contracts\FormSchema;

class EnvironmentSchema extends FormSchema 
{
    public static string $navigationLabel = 'Environment';
    public static string $navigationGroup = 'General';
    public static int $navigationSort = 0;

    public static function env(): array {
        return [
            'app.name' => 'APP_NAME',
            'app.debug' => 'APP_DEBUG',
            'app.locale' => 'APP_LOCALE',
            'app.maintenance.driver' => 'APP_MAINTENANCE_DRIVER',
            'log.channel' => 'LOG_CHANNEL',
            'log.stack' => 'LOG_STACK',
            'log.level' => 'LOG_LEVEL',
            'db.connection' => 'DB_CONNECTION',
            'db.host' => 'DB_HOST',
            'db.port' => 'DB_PORT',
            'db.database' => 'DB_DATABASE',
            'db.username' => 'DB_USERNAME',
            'db.password' => 'DB_PASSWORD',
            'session.driver' => 'SESSION_DRIVER',
            'session.lifetime' => 'SESSION_LIFETIME',
            'session.encrypt' => 'SESSION_ENCRYPT',
            'session.path' => 'SESSION_PATH',
            'session.domain' => 'SESSION_DOMAIN',
            'redis.client' => 'REDIS_CLIENT',
            'redis.host' => 'REDIS_HOST',
            'redis.password' => 'REDIS_PASSWORD',
            'redis.port' => 'REDIS_PORT',
            'mail.mailer' => 'MAIL_MAILER',
            'mail.scheme' => 'MAIL_SCHEME',
            'mail.host' => 'MAIL_HOST',
            'mail.port' => 'MAIL_PORT',
            'mail.username' => 'MAIL_USERNAME',
            'mail.password' => 'MAIL_PASSWORD',
            'mail.from.address' => 'MAIL_FROM_ADDRESS',
            'mail.from.name' => 'MAIL_FROM_NAME',
        ];

    }


    public static function baseSchema(): array
    {
        return [

            Section::make(__('app-settings::app-settings.form.application'))->schema([
                TextInput::make('app.name')->label(__('app-settings::app-settings.form.app_name')),
                 Grid::make()->schema([
                    FileUpload::make('app_logo')
                        ->label(fn () => __('app-settings::app-settings.form.logo'))
                        ->image()
                        ->directory('assets')
                        ->visibility('public')
                        ->moveFiles()
                        ->imageEditor()
                        ->getUploadedFileNameForStorageUsing(fn () => 'site_logo.png'),
                    FileUpload::make('app_dark_logo')
                        ->label(fn () => __('app-settings::app-settings.form.dark-logo'))
                        ->image()
                        ->directory('assets')
                        ->visibility('public')
                        ->moveFiles()
                        ->imageEditor()
                        ->getUploadedFileNameForStorageUsing(fn () => 'site_dark_logo.png'),
                    FileUpload::make('app_favicon')
                        ->label(fn () => __('app-settings::app-settings.form.favicon'))
                        ->image()
                        ->directory('assets')
                        ->visibility('public')
                        ->moveFiles()
                        ->getUploadedFileNameForStorageUsing(fn () => 'site_favicon.ico')
                        ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon']),
                ])->columns(3),
                Toggle::make('app.debug')->label(__('app-settings::app-settings.form.app_debug'))->helperText('Enable or disable application debug mode.'),
                Select::make('app.locale')->label(__('app-settings::app-settings.form.language'))->options(['en' => 'English','es' => 'Spanish','fr' => 'French'])->default('en')->helperText('The language your website will use.'),
                Select::make('app.maintenance.driver')->label(__('app-settings::app-settings.form.maintenance_driver'))->options(['file' => 'File','redis' => 'Redis','array'=> 'Array'])->default('file')->helperText('Choose how Laravel stores the maintenance mode flag.')
            ])->collapsible()->collapsed(),
            Section::make(__('app-settings::app-settings.form.logging'))->schema([
                Select::make('log.channel')->label(__('app-settings::app-settings.form.channel'))->options([
                    'stack' => 'Stack', 'single' => 'Single', 'daily' => 'Daily'
                ]),
                Select::make('log.stack')->label(__('app-settings::app-settings.form.stack'))->options([
                    'single' => 'Single', 'slack' => 'Slack'
                ]),
                Select::make('log.level')->label(__('app-settings::app-settings.form.level'))->options([
                    'debug' => 'Debug', 'info' => 'Info', 'warning' => 'Warning', 'error' => 'Error'
                ]),
            ])->collapsible()->collapsed(),
            Section::make(__('app-settings::app-settings.form.database_configuration'))->schema([
                Select::make('db.connection')->label(__('app-settings::app-settings.form.connection'))->options(['mysql' => 'MySQL','pgsql' => 'PostgreSQL','sqlite' => 'SQLite','sqlsrv' => 'SQL Server'])->default('mysql'),
                TextInput::make('db.host')->label(__('app-settings::app-settings.form.host'))->default('127.0.0.1'),
                TextInput::make('db.port')->label(__('app-settings::app-settings.form.port'))->numeric()->default(3306),
                TextInput::make('db.database')->label(__('app-settings::app-settings.form.database_name')),
                TextInput::make('db.username')->label(__('app-settings::app-settings.form.username')),
                TextInput::make('db.password')->label(__('app-settings::app-settings.form.password'))->password()->revealable(),
            ])->columns(2)->collapsible()->collapsed(),
            Section::make(__('app-settings::app-settings.form.session_settings'))->schema([
                Select::make('session.driver')->label(__('app-settings::app-settings.form.driver'))->options(['file' => 'File','database' => 'Database','redis' => 'Redis','array' => 'Array (Testing)',])->default('file')->required(),
                TextInput::make('session.lifetime')->label(__('app-settings::app-settings.form.lifetime'))->numeric()->default(120)->required(),
                Toggle::make('session.encrypt')->label(__('app-settings::app-settings.form.encrypt'))->default(false),
                TextInput::make('session.path')->label(__('app-settings::app-settings.form.path'))->default('/')->required(),
                TextInput::make('session.domain')->label(__('app-settings::app-settings.form.domain'))->default(null)->helperText('Leave empty for default domain.'),
            ])->collapsible()->collapsed(),
            Section::make(__('app-settings::app-settings.form.redis_settings'))->schema([
                Select::make('redis.client')->label(__('app-settings::app-settings.form.client'))->options(['phpredis' => 'phpredis','predis' => 'predis'])->default('phpredis')->required(),
                TextInput::make('redis.host')->label(__('app-settings::app-settings.form.host'))->default('127.0.0.1')->required(),
                TextInput::make('redis.password')->label(__('app-settings::app-settings.form.password'))->default(null)->password()->revealable()->nullable(),
                TextInput::make('redis.port')->label(__('app-settings::app-settings.form.port'))->default('6379')->numeric()->required(),
            ])->columns(2)->collapsible()->collapsed(),
            Section::make(__('app-settings::app-settings.form.mail_settings'))->schema([
                Select::make('mail.mailer')->label(__('app-settings::app-settings.form.mailer'))->options([
                    'smtp' => 'SMTP',
                    'sendmail' => 'Sendmail',
                    'log' => 'Log',
                    'mailgun' => 'Mailgun',
                    'ses' => 'SES',
                ])->required(),
                TextInput::make('mail.scheme')->label(__('app-settings::app-settings.form.scheme'))->placeholder('tls or ssl')->nullable(),
                TextInput::make('mail.host')->label(__('app-settings::app-settings.form.host'))->required(),
                TextInput::make('mail.port')->label(__('app-settings::app-settings.form.port'))->numeric()->required(),
                TextInput::make('mail.username')->label(__('app-settings::app-settings.form.username'))->nullable(),
                TextInput::make('mail.password')->label(__('app-settings::app-settings.form.password'))->password()->revealable()->nullable(),
                TextInput::make('mail.from.address')->label(__('app-settings::app-settings.form.from_address'))->email()->required(),
                TextInput::make('mail.from.name')->label(__('app-settings::app-settings.form.from_name'))->required(),
            ])->columns(2)->collapsible()->collapsed()
        ];

    }

}