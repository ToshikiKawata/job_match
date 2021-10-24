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
    public function dashboard()
    {
        $jobOffers = JobOffer::latest()
            ->with('entries')
            ->MyJobOffer()
            ->paginate(5);

        return view('auth.company.dashboard', compact('jobOffers'));
    }
}
