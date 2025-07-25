# Branzia Settings

A powerful settings manager built for **FilamentPHP**, allowing you to manage `.env` and database-stored settings with a dynamic schema and beautiful admin panel.

> 🔧 Built with flexibility, extendability, and developer-friendliness in mind.

---

## ✨ Features

- ✅ Manage `.env` variables visually via Filament
- ✅ Store & retrieve settings from a dedicated database table
- ✅ Create settings dynamically using reusable schema classes
- ✅ Group settings by tab, section, and sort order
- ✅ Extend existing settings using `extend()` method
- ✅ Automatically registers with Filament sidebar
- ✅ Fully customizable and modular

---

## 🧱 Installation

```bash
composer require branzia/settings
```

⚙️ Usage
Defining Settings Form

Extend the Branzia\Settings\Contracts\FormSchema contract in your own class:

```php
namespace App\Settings;

use Branzia\Settings\Contracts\FormSchema;
use Filament\Forms;

class SiteSettings extends FormSchema
{
    public static string $tab = 'Site';
    public static string $group = 'General';
    public static int $sort = 1;

    public static function env(): array
    {
        return [
            'app.name' => 'APP_NAME',
        ];
    }

    protected static function baseSchema(): array
    {
        return [
            Forms\Components\TextInput::make('app.name')
                ->label('Site Name')
                ->required(),
        ];
    }
}
```
## 🔁 The env() method maps form fields to .env variables for persistence.


## 🔁 Syncing to `.env` and Laravel Config

You can link form inputs to Laravel config and environment variables by defining the `env()` method in your schema:

```php
public static function env(): array
{
    return [
        'app.name' => 'APP_NAME',
        'app.debug' => 'APP_DEBUG',
        'mail.mailer' => 'MAIL_MAILER',
    ];
}
```