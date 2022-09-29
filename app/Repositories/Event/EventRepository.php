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
use Carbon\Carbon;


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
        $filter      = Arr::get($searchParams, 'filter', '');
        $query        = $this->model->newQuery();

        if (!empty($filter)) {
            $query->where(function (Builder $query) use ($filter) {
                $today = Carbon::today();
                if($filter == 'finshedEvents') {
                    $query->where('end_date', '<', $today->format('Y-m-d'));
                }
                else if($filter == 'finshedEventsLast7Days') {
                    $query->whereBetween('end_date', [$today->subDay(7)->format('Y-m-d'), $today->addDay(7)->format('Y-m-d')]);
                }
                else if($filter == 'upcomingEvents') {
                    $query->where('start_date', '>', $today->format('Y-m-d'));
                }
                else if($filter == 'upcomingEventsWithIn7days') {
                    $query->whereBetween('start_date', [$today->format('Y-m-d'), $today->addDay(7)->format('Y-m-d')]);
                }
            });
        }
        return $query->latest()->paginate($limit);
    }

    

}
