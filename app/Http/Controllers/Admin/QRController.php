<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QRController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $url = route('tenant.public', ['tenant_slug' => $tenant->slug]);

        return view('admin.qr.index', compact('tenant', 'url'));
    }

    public function download(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $url = route('tenant.public', ['tenant_slug' => $tenant->slug]);

        if ($request->filled('table')) {
            $url .= '?t=' . $request->table;
        }

        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(500)
            ->margin(2)
            ->generate($url);

        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-' . $tenant->slug . '.png"');
    }
}
