<?php

namespace App\Http\Controllers\ExtrairGtin;

use App\Actions\ExtractCodeGtinAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExtractGtinCodeNfeController extends Controller
{
    public function __construct(
        private ExtractCodeGtinAction $extractCodeGtin
    ) {}
    public function __invoke()
    {
        $extractedCodes = $this->extractCodeGtin->execute();
        return $extractedCodes;
    }
}
