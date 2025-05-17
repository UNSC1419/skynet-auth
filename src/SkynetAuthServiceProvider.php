<?php
namespace Seat\UNSC1419\SkynetAuth;

use Seat\Services\AbstractSeatPlugin;
use Seat\UNSC1419\SkynetAuth\Commands\UserCharacters;

class SkynetAuthServiceProvider extends AbstractSeatPlugin
{
    public function boot(): void
    {
        $this->addCommands();
        $this->add_routes();
        // $this->add_views();
        // $this->add_translations();
        // $this->add_migrations();
    }

    public function register(): void
    {
        // 侧菜单配置
        $this->mergeConfigFrom(__DIR__.'/Config/package.sidebar.php', 'package.sidebar');

        // 配置导入
        $this->mergeConfigFrom(
            __DIR__ . '/Config/skynetauth.config.php', 'skynetauth.config');
    }


    private function addCommands(): void
    {
        $this->commands([
            UserCharacters::class,
        ]);
    }

    private function add_routes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
    }

    // private function add_views(): void
    // {
    //     $this->loadViewsFrom(__DIR__.'/resources/views', 'skynet-auth');
    // }

    // private function add_translations(): void
    // {
    //     $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'skynet-auth');
    // }

    // private function add_migrations(): void
    // {
    //     $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    // }

    // 必须实现的元数据方法
    public function getName(): string
    {
        return "SkynetAuth 模块";
    }

    public function getPackageRepositoryUrl(): string
    {
        return "https://github.com/UNSC1419/skynet-auth";
    }

    public function getPackagistPackageName(): string
    {
        return "skynet-auth";
    }

    public function getPackagistVendorName(): string
    {
        return "UNSC1419";
    }
}
