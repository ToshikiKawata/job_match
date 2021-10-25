<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobOffer;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        $params = $request->query();
        $jobOffers = JobOffer::latest()
            ->with('entries')
            ->MyJobOffer()
            ->searchStatus($params)
            ->paginate(5);
        // dd($jobOffers);
        return view('auth.company.dashboard', compact('jobOffers'));
    }
}
