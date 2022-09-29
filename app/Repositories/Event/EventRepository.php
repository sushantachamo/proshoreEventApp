<?php

namespace App\Repositories\Event;

use App\Repositories\Repository;
use App\Models\User;
use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;


/**
 * Class EventRepository
 * @package App\Repositories\Event
 */
class EventRepository extends Repository
{
    /**
     * EventRepository constructor.
     * @param Event $model
     */
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    /**
     * Query paginated event list
     *
     * @param Request $request
     * @param array $columns
     * @return LengthAwarePaginator|Collection|Model[]
     */
    public function getPaginatedList(Request $request, $columns = array('*'), User $user = null)
    {
        $searchParams = $request->all();
        $limit        = Arr::get($searchParams, 'limit', 10);
        $keyword      = Arr::get($searchParams, 'keyword', '');
        $query        = $this->model->newQuery();



        // if (!empty($keyword)) {
        //     $query->where(function (Builder $query) use ($keyword) {
        //         $query->where('occasion_name', 'LIKE', '%' . $keyword . '%')
        //             ->orwhereHas(('design'), function ($q) use ($keyword) {
        //                 $q->where('name', 'LIKE', '%' . $keyword . '%');
        //             });
        //     });
        // }

        // if (isset($status)) {
        //     $query->where('status', '=', $status);
        // }

        // if (isset($companyId)) {
        //     $query->where('company_id', '=', $companyId);
        // }

        // if (isset($type)) {
        //     $query->where('type', '=', $type);
        // }

        // if (isset($occasionId)) {
        //     $query->where(function (Builder $query) use ($occasionId) {
        //         $query->whereHas(('occasion'), function ($q) use ($occasionId) {
        //             $q->where('id', '=', $occasionId);
        //         });
        //     });
        // }
        return $query->latest()->get();
        return $query->latest()->paginate($limit);
    }

    

}
