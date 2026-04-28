<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

    /**
     * The default instance of the Renderer service.
     *
     * @var Renderer|null
     */
    protected static $renderer;

    /**
     * The PermintaanService instance.
     *
     * @param boolean $getShared
     *
     * @return \App\Services\PermintaanService
     */
    public static function permintaan($getShared = true)
    {
        if ($getShared) {
            if (! isset(static::$instances['permintaan'])) {
                static::$instances['permintaan'] = new \App\Services\PermintaanService(
                    model(\App\Models\Permintaan\PermintaanModel::class),
                    model(\App\Models\Permintaan\ItemPermintaanModel::class),
                    model(\App\Models\MasterData\BarangModel::class),
                    model(\App\Models\Stok\MutasiStokModel::class),
                    model(\App\Models\Notifikasi\NotifikasiModel::class)
                );
            }

            return static::$instances['permintaan'];
        }

        return new \App\Services\PermintaanService(
            model(\App\Models\Permintaan\PermintaanModel::class),
            model(\App\Models\Permintaan\ItemPermintaanModel::class),
            model(\App\Models\MasterData\BarangModel::class),
            model(\App\Models\Stok\MutasiStokModel::class),
            model(\App\Models\Notifikasi\NotifikasiModel::class)
        );
    }
}
