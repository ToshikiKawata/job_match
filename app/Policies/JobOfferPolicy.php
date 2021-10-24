<?php

namespace App\Policies;

use App\Models\JobOffer;
use App\Models\Company;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobOfferPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(company $company)
    {
        //
    }

    /**
     * Determine whether the company can view the model.
     *
     * @param  \App\Models\company  $company
     * @param  \App\Models\JobOffer  $jobOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(company $company, JobOffer $jobOffer)
    {
        //
    }

    /**
     * Determine whether the company can create models.
     *
     * @param  \App\Models\company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(company $company)
    {
        //
    }

    /**
     * Determine whether the company can update the model.
     *
     * @param  \App\Models\company  $company
     * @param  \App\Models\JobOffer  $jobOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(company $company, JobOffer $jobOffer)
    {
        return $company->id === $jobOffer->company_id;
    }

    /**
     * Determine whether the company can delete the model.
     *
     * @param  \App\Models\company  $company
     * @param  \App\Models\JobOffer  $jobOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(company $company, JobOffer $jobOffer)
    {
        return $company->id === $jobOffer->company_id;
    }

    /**
     * Determine whether the company can restore the model.
     *
     * @param  \App\Models\company  $company
     * @param  \App\Models\JobOffer  $jobOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(company $company, JobOffer $jobOffer)
    {
        //
    }

    /**
     * Determine whether the company can permanently delete the model.
     *
     * @param  \App\Models\company  $company
     * @param  \App\Models\JobOffer  $jobOffer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(company $company, JobOffer $jobOffer)
    {
        //
    }
}
