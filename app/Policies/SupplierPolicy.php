<?php

    namespace App\Policies;

    use App\Models\inventory\Supplier;
    use App\Models\inventory\User;
    use Illuminate\Auth\Access\HandlesAuthorization;
    use Illuminate\Auth\Access\Response;

    class SupplierPolicy
    {
        use HandlesAuthorization;

        /**
         * Determine whether the user can view any models.
         *
         * @param User $user
         * @return Response|bool
         */
        public function viewAny (User $user)
        {
            //
        }

        /**
         * Determine whether the user can view the model.
         *
         * @param User     $user
         * @param Supplier $supplier
         * @return Response|bool
         */
        public function view (User $user, Supplier $supplier)
        {
            //
        }

        /**
         * Determine whether the user can create models.
         *
         * @param User $user
         * @return Response|bool
         */
        public function create (User $user)
        {
            //
        }

        /**
         * Determine whether the user can update the model.
         *
         * @param User     $user
         * @param Supplier $supplier
         * @return Response|bool
         */
        public function update (User $user, Supplier $supplier)
        {
            //
        }

        /**
         * Determine whether the user can delete the model.
         *
         * @param User     $user
         * @param Supplier $supplier
         * @return Response|bool
         */
        public function delete (User $user, Supplier $supplier)
        {
            //
        }

        /**
         * Determine whether the user can restore the model.
         *
         * @param User     $user
         * @param Supplier $supplier
         * @return Response|bool
         */
        public function restore (User $user, Supplier $supplier)
        {
            //
        }

        /**
         * Determine whether the user can permanently delete the model.
         *
         * @param User     $user
         * @param Supplier $supplier
         * @return Response|bool
         */
        public function forceDelete (User $user, Supplier $supplier)
        {
            //
        }
    }
