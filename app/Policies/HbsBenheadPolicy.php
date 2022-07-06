<?php

namespace App\Policies;

use App\HbsBenhead;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HbsBenheadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any products.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->can('view_hbs_benhead')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the product.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function view(User $user, HbsBenhead $HbsBenhead)
    {
        if ($user->can('view_hbs_benhead')) {
            return true;
        }
    }

    /**
     * Determine whether the user can create products.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can('add_hbs_benhead')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function update(User $user, HbsBenhead $HbsBenhead)
    {
        if ($user->can('edit_hbs_benhead')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function delete(User $user, HbsBenhead $HbsBenhead)
    {
        if ($user->can('delete_hbs_benhead')) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the product.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function restore(User $user, HbsBenhead $HbsBenhead)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the product.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function forceDelete(User $user, HbsBenhead $HbsBenhead)
    {
        //
    }
}
