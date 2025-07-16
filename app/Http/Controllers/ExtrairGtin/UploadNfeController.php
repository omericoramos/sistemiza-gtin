<?php

namespace App\Http\Controllers\ExtrairGtin;

use App\Actions\UploadXmlZipAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadZipRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UploadNfeController extends Controller
{
    public function __construct(
        private UploadXmlZipAction $uploadXmlZip
    ) {}
    public function index()
    {
        return Inertia::render('ExtrairGtin/UploadNfe');
    }

    public function upload(UploadZipRequest $request)
    {
        $response = $this->uploadXmlZip->execute($request->file('file'));
        return $response;
    }
}
