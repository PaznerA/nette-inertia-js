# Nette Inertia.js Extension

[![Build Status](https://github.com/PaznerA/nette-inertia-js/workflows/CI/badge.svg)](https://github.com/PaznerA/nette-inertia-js/actions)
[![Downloads this Month](https://img.shields.io/packagist/dm/PaznerA/nette-inertia-js.svg)](https://packagist.org/packages/PaznerA/nette-inertia-js)
[![Latest stable](https://img.shields.io/packagist/v/PaznerA/nette-inertia-js.svg)](https://packagist.org/packages/PaznerA/nette-inertia-js)
[![Coverage Status](https://coveralls.io/repos/github/PaznerA/nette-inertia-js/badge.svg?branch=master)](https://coveralls.io/github/PaznerA/nette-inertia-js?branch=master)

Modern JavaScript framework integration for Nette using Inertia.js

## Features

- 🚀 Support for Vue.js, React, and Svelte
- 🔄 Server-side rendering (SSR) support
- 🛠 Type-safe PHP 8 implementation
- 📦 Easy integration with Nette DI Container
- 🎨 Asset versioning support
- 🔌 Middleware for handling Inertia requests
- 🎯 Custom Latte macros

## Requirements

- PHP 8.1 or higher
- Nette 3.1 or higher
- npm/yarn for frontend dependencies

## Installation

1. Install via Composer:
```bash
composer require acme/nette-inertia
```

2. Register extension in your config.neon:
```neon
extensions:
    inertia: Acme\Inertia\InertiaExtension

inertia:
    framework: vue  # options: vue, react, svelte
    ssr: false
    rootView: App/Views/Root
    version: null   # optional - for asset versioning
```

3. Install frontend dependencies based on your chosen framework:

For Vue.js:
```bash
npm install @inertiajs/vue3
# or
yarn add @inertiajs/vue3
```

For React:
```bash
npm install @inertiajs/react
# or
yarn add @inertiajs/react
```

For Svelte:
```bash
npm install @inertiajs/svelte
# or
yarn add @inertiajs/svelte
```

## Usage

### Basic Setup

1. Create a base presenter:

```php
abstract class BasePresenter extends Acme\Inertia\InertiaPresenter
{
    // Your base presenter logic
}
```

2. Create your root template (App/Views/Root.latte):

```latte
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Inertia App</title>
    {block head}{/block}
</head>
<body>
    {inertia}
    {block scripts}{/block}
</body>
</html>
```

3. Set up your frontend entry point (e.g., assets/app.js):

```javascript
// Vue.js example
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
})
```

### Using in Presenters

```php
class HomepagePresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->renderInertia('Homepage/Index', [
            'welcome' => 'Hello from Inertia.js!',
            'timestamp' => new DateTime(),
        ]);
    }
}
```

### Creating Components

Vue.js example (Pages/Homepage/Index.vue):
```vue
<template>
    <div>
        <h1>{{ $page.props.welcome }}</h1>
        <p>Current time: {{ $page.props.timestamp }}</p>
    </div>
</template>

<script setup>
defineProps({
    welcome: String,
    timestamp: String,
})
</script>
```

## Advanced Configuration

### Server-Side Rendering (SSR)

Enable SSR in your configuration:

```neon
inertia:
    ssr: true
    # ... other options
```

### Asset Versioning

Implement version checking for automatic reload on asset changes:

```php
class AssetsVersionProvider
{
    public function getVersion(): string
    {
        return md5_file(WWW_DIR . '/dist/manifest.json');
    }
}
```

```neon
inertia:
    version: @AssetsVersionProvider::getVersion()
```

### Shared Data

Share data across all components:

```php
class BasePresenter extends InertiaPresenter
{
    protected function startup(): void
    {
        parent::startup();
        
        $this->inertia->share('user', $this->getUser()->isLoggedIn() 
            ? $this->getUser()->getIdentity()->toArray() 
            : null
        );
    }
}
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Create a Pull Request

## Testing

Run tests:
```bash
composer test
```

Run static analysis:
```bash
composer phpstan
```

Run coding standards check:
```bash
composer cs-check
```

## License

TODO: most likely MIT License