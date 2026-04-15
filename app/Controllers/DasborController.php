<?php

namespace App\Controllers;

use App\Services\DashboardService;

/**
 * DasborController - Controller untuk halaman dashboard utama
 */
class DasborController extends BaseController
{
    protected DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index()
    {
        $this->setPageData('Dashboard', 'Ringkasan sistem inventaris dan statistik');

        $data = $this->dashboardService->getDashboardData();

        return $this->render('dashboard/index', $data);
    }
}
