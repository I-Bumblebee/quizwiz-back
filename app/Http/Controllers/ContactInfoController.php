<?php

namespace App\Http\Controllers;

use App\Models\ContactInfo;
use Illuminate\Http\Request;

class ContactInfoController extends Controller
{
	public function __invoke(Request $request)
	{
		return response()->json(
			ContactInfo::first()
		);
	}
}
