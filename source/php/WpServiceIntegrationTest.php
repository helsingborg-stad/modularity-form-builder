<?php

declare(strict_types=1);

namespace {
    function plugins_url($path = '', $plugin = '')
    {
        return 'https://example.test/wp-content/plugins/modularity-form-builder';
    }

    function wp_cache_get($key, $group = '', $force = false, &$found = null)
    {
        return [
            'css/modularity-form-builder.css' => 'css/modularity-form-builder.123.css',
            'js/modularity-form-builder-admin.js' => 'js/modularity-form-builder-admin.123.js',
        ];
    }

    function wp_register_style(...$args)
    {
        \ModularityFormBuilder\WpServiceIntegrationTest::$assetCalls[] = ['registerStyle', $args];
        return true;
    }

    function wp_enqueue_style(...$args)
    {
        \ModularityFormBuilder\WpServiceIntegrationTest::$assetCalls[] = ['enqueueStyle', $args];
    }

    function wp_register_script(...$args)
    {
        \ModularityFormBuilder\WpServiceIntegrationTest::$assetCalls[] = ['registerScript', $args];
        return true;
    }

    function wp_enqueue_script(...$args)
    {
        \ModularityFormBuilder\WpServiceIntegrationTest::$assetCalls[] = ['enqueueScript', $args];
    }

    function wp_localize_script(...$args)
    {
        \ModularityFormBuilder\WpServiceIntegrationTest::$assetCalls[] = ['localizeScript', $args];
        return true;
    }

    function add_filter(...$args)
    {
        \ModularityFormBuilder\WpServiceIntegrationTest::$assetCalls[] = ['addFilter', $args];
        return true;
    }
}

namespace ModularityFormBuilder {
    use PHPUnit\Framework\Attributes\RunInSeparateProcess;
    use PHPUnit\Framework\TestCase;
    use WpService\Implementations\NativeWpService;
    use WpUtilService\WpUtilService;

    class WpServiceIntegrationTest extends TestCase
    {
        public static array $assetCalls = [];

        #[RunInSeparateProcess]
        public function testNativeServiceEnqueuesAndLocalizesFormAssets(): void
        {
            self::$assetCalls = [];
            $enqueue = (new WpUtilService(new NativeWpService()))
                ->enqueue('/var/www/wp-content/plugins/modularity-form-builder');

            $enqueue->add('css/modularity-form-builder.css');
            $enqueue->add('js/modularity-form-builder-admin.js')
                ->with()
                ->translation('formbuilder', ['delete_confirm' => 'Confirm']);

            $calls = self::$assetCalls;
            static::assertSame(['registerStyle', [
                'css-modularity-form-buildercss',
                'https://example.test/wp-content/plugins/modularity-form-builder/assets/dist/css/modularity-form-builder.123.css',
                [], false, 'all',
            ]], $calls[0]);
            static::assertSame(['enqueueStyle', ['css-modularity-form-buildercss', '', [], false, 'all']], $calls[1]);
            static::assertSame(['registerScript', [
                'js-modularity-form-builder-adminjs',
                'https://example.test/wp-content/plugins/modularity-form-builder/assets/dist/js/modularity-form-builder-admin.123.js',
                [], false, true,
            ]], $calls[2]);
            static::assertSame('addFilter', $calls[3][0]);
            static::assertSame('script_loader_tag', $calls[3][1][0]);
            static::assertSame(['enqueueScript', ['js-modularity-form-builder-adminjs', '', [], false, []]], $calls[4]);
            static::assertSame(['localizeScript', [
                'js-modularity-form-builder-adminjs', 'formbuilder', ['delete_confirm' => 'Confirm'],
            ]], $calls[5]);
        }
    }
}
