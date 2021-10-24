<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Models\JobOfferView;
use App\Consts\CompanyConst;
use App\Models\Occupation;
use App\Consts\UserConst;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = '';
        foreach (config('fortify.users') as $guard) {
            if (Auth::guard(Str::plural($guard))->check()) {
                $user = Auth::guard(Str::plural($guard))->user();
            }
        }

        if (empty($user)) {
            return view('welcome');
        } else {
            $params = $request->query();
            $jobOffers = JobOffer::search($params)->openData()
                ->order($params)->with(['company', 'occupation'])->paginate(5);

            $occupation = $request->occupation;
            $jobOffers->appends(compact('occupation'));

            $search_occupation = empty($occupation) ? [] : ['occupation' => $occupation];

            $sort = empty($request->sort) ? [] : ['sort' => $request->sort];
            
            $occupations = Occupation::all();

            return view(
                'job_offers.index',
                compact(
                    'jobOffers',
                    'occupations',
                    'search_occupation',
                    'sort'
                )
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $occupations = Occupation::all();
        return view('job_offers.create', compact('occupations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobOffer  $jobOffer
     * @return \Illuminate\Http\Response
     */
    public function show(JobOffer $jobOffer)
    {
        $entry = '';

        if (Auth::guard(UserConst::GUARD)->check()) {
            JobOfferView::updateOrCreate([
                'job_offer_id' => $jobOffer->id,
                'user_id' => Auth::guard(UserConst::GUARD)->user()->id,
            ]);
            $entry = $jobOffer->entries()
                ->where('user_id', Auth::guard(UserConst::GUARD)->user()->id)->first();
        }
        return view('job_offers.show', compact('jobOffer', 'entry'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobOffer  $jobOffer
     * @return \Illuminate\Http\Response
     */
    public function edit(JobOffer $jobOffer)
    {
        $occupations = Occupation::all();
        return view('job_offers.edit', compact('jobOffer', 'occupations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobOffer  $jobOffer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobOffer $jobOffer)
    {
        if (Auth::guard(CompanyConst::GUARD)->user()->cannot('update', $jobOffer)) {
            return redirect()->route('job_offers.show', $jobOffer)
                ->withErrors('自分の求人情報以外は更新できません');
        }
        $jobOffer->fill($request->all());

        try {
            $jobOffer->save();
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors('求人情報更新処理でエラーが発生しました');
        }

        return redirect()->route('job_offers.show', $jobOffer)
            ->with('notice', '求人情報を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobOffer  $jobOffer
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobOffer $jobOffer)
    {
        if (Auth::guard(CompanyConst::GUARD)->user()->cannot('delete', $jobOffer)) {
            return redirect()->route('job_offers.show', $jobOffer)
                ->withErrors('自分の求人情報以外は削除できません');
        }

        try {
            $jobOffer->delete();
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors('求人情報削除処理でエラーが発生しました');
        }

        return redirect()->route('job_offers.index')
            ->with('notice', '求人情報を削除しました');
    }
}
