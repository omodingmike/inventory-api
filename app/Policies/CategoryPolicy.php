<?php

    namespace App\Policies;

    use App\Models\inventory\Category;
    use App\Models\User;
    use Illuminate\Auth\Access\HandlesAuthorization;
    use Illuminate\Auth\Access\Response;

    class CategoryPolicy
    {
        use HandlesAuthorization;

        /**
         * Determine whether the user can view any models.
         *
         * @param User $user
         * @return Response|bool
         */
        public function viewAny ( User $user )
        {
            //
        }

        /**
         * Determine whether the user can view the model.
         *
         * @param User     $user
         * @param Category $category
         * @return Response|bool
         */
        public function view ( User $user , Category $category )
        {
            //
        }

        /**
         * Determine whether the user can create models.
         *
         * @param User $user
         * @return Response|bool
         */
        public function create ( User $user )
        {
            //
        }

        /**
         * Determine whether the user can update the model.
         *
         * @param User     $user
         * @param Category $category
         * @return Response|bool
         */
        public function update ( User $user , Category $category )
        {
            //
        }

        /**
         * Determine whether the user can delete the model.
         *
         * @param User     $user
         * @param Category $category
         * @return Response|bool
         */
        public function delete ( User $user , Category $category )
        {
            //
        }

        /**
         * Determine whether the user can restore the model.
         *
         * @param User     $user
         * @param Category $category
         * @return Response|bool
         */
        public function restore ( User $user , Category $category )
        {
            //
        }

        /**
         * Determine whether the user can permanently delete the model.
         *
         * @param User     $user
         * @param Category $category
         * @return Response|bool
         */
        public function forceDelete ( User $user , Category $category )
        {
            //
        }
    }
