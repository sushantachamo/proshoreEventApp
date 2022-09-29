<?php


namespace App\Repositories;

use App\MOdels\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class BaseRepository
 * @package App\Repositories\Eloquent
 */
class Repository implements RepositoryInterface
{
    /**
     * Eloquent model methods used in the repository
     */
    const METHOD_ALL = 'all';
    const METHOD_FIND = 'find';
    const METHOD_STORE = 'store';
    const METHOD_UPDATE = 'update';
    const METHOD_DELETE = 'delete';
    const METHOD_COUNT = 'count';
    const METHOD_UPDATE_OR_CREATE = 'updateOrCreate';
    const METHOD_FIND_BY = 'findBy';
    const METHOD_FIRST_OR_NEW = 'firstOrNew';

    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all resources
     *
     * @param array $columns
     *
     * @return Collection
     */
    public function all($columns = array('*'), User $user = null)
    {
        if($this->checkModelHasAccessMethod($user)){
            $this->model->hasAccess(self::METHOD_ALL, $user);
        }

        return $this->model->all();
    }

    /**
     * Store newly created resource
     *
     * @param array $data
     *
     * @return Model
     */
    public function store(array $data, User $user = null)
    {
        if($this->checkModelHasAccessMethod($user)){
            $this->model->hasAccess(self::METHOD_STORE, $user);
        }

        return $this->model->create($data);
    }

    /**
     * Update specific resource.
     *
     * @param array $data
     * @param       $id
     *
     * @return bool
     */
    public function update($id, array $data, User $user = null)
    {
        if($this->checkModelHasAccessMethod($user)){
            $this->model->hasAccess(self::METHOD_UPDATE, $user, $id);
        }

        return $this->model->find($id)->update($data);
    }

    /**
     * Update or create specific resource.
     *
     * @param array $data
     * @param       $id
     *
     * @return mixed
     */
    public function updateOrCreate($id, array $data, User $user = null)
    {
        return $this->model->updateOrCreate(['id' => $id], $data);
    }

    /**
     * Delete specific resource
     *
     * @param $id
     *
     * @return bool
     */
    public function delete($id, User $user = null)
    {
        if($this->checkModelHasAccessMethod($user)){
            $this->model->hasAccess(self::METHOD_DELETE, $user);
        }

        return $this->model->destroy($id);
    }

    /**
     * Find specific resource
     *
     * @param       $id
     * @param array $columns
     *
     * @return Object
     */
    public function find($id, $columns = array('*'), User $user = null)
    {
        if($this->checkModelHasAccessMethod($user)){
            $this->model->hasAccess(self::METHOD_FIND, $user, $id);
        }

        return $this->model->find($id, $columns);
    }

    /**
     * Find specific resource by given attribute
     *
     * @param       $attribute
     * @param       $value
     * @param array $columns
     *
     * @return Object
     */
    public function findBy($attribute, $value, $columns = array('*'), User $user = null)
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * Find specific model by given attributes or new model
     * @param array $attributes
     * @param $values
     * @return Builder|Model
     */
    public function firstOrNew($attributes = [], $values = [], User $user = null)
    {
        return $this->model->firstOrNew($attributes);
    }

    /**
     * Get paginated resources
     *
     * @param Request $request
     * @param array $columns
     *
     * @return Collection|Model[]
     */
    public function getPaginatedList(Request $request, $columns = array('*'), User $user = null)
    {
        if($this->checkModelHasAccessMethod($user)){
            $this->model->hasAccess(self::METHOD_ALL, $user);
        }

        return $this->model->paginate();
    }

    /**
     * Count model
     */
    public function count(User $user = null)
    {
        if($this->checkModelHasAccessMethod($user)){
            $this->model->hasAccess(self::METHOD_COUNT, $user);
        }

        return $this->model->count();
    }

    private function checkModelHasAccessMethod(User $user = null)
    {
        return $user &&  method_exists($this->model, 'hasAccess');
    }

}
