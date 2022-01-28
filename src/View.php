<?php

namespace Livewire\ViewExtension;

use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\ViewExtension\Utilities\ValidateView;
use Livewire\ViewExtension\Action;
use Livewire\ViewExtension\Utilities\Changes;

/**
 * This class is the starting point of each view.
 * Usually you don't have to change this class.
 */
abstract class View extends Component
{
    /**
     * Attributes.
     */

    /**
     * View id must be unique.
     */
    public $viewId = 'view';

    /**
     * Values in data can be used in views.
     */
    public array $data;

    /**
     * Old data from the last update.
     */
    public array $oldData = [];

    /**
     * Oldest data from the last mount.
     */
    public array $mountData = [];

    /**
     * You should load any new data to cache in render method, wich could be updated over the time.
     */
    public array $cachedData = [];

    /**
     * Default visibility of view.
     */
    public $defaultVisibility = true;

    /**
     * Sessioned data.
     */
    public array $sessionedData = [];

    /**
     * All linked views.
     */
    public array $linkedViews = [];

    /**
     * All actions defined for this view.
     */
    public array $actions = [];

    /**
     * Default listeners merged with custom listeners.
     */
    public function getListeners()
    {
        return array_merge([
            $this->viewId . '-show' => 'showViewListener',
            $this->viewId . '-hide' => 'hideViewListener',
            $this->viewId . '-refresh' => 'refreshViewListener',
            'broadcast' => 'broadcastListener'
        ], $this->getViewListeners());
    }

    public function registerDefaultActions()
    {
        $this->registerAction('collection-order', \Livewire\ViewExtension\Actions\CollectionOrderAction::class);
        $this->registerAction('toggle', \Livewire\ViewExtension\Actions\ToggleAction::class);
    }

    /**
     * Listeners.
     */

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }

    /**
     * This function is called in render method and must return a view.
     */
    protected function onRender()
    {
        return view($this->viewId);
    }

    /** 
     * This method is called once immediately after the component is created.
     */
    abstract public function onMount(mixed $context);

    /**
     * This method is called after the boot method.
     */
    public function onBoot()
    {
    }

    /**
     * This function is called if a validation error occures.
     */
    public function onValidationError(ValidationException $exception, array $messages)
    {
    }

    public function onHydrate()
    {
    }

    public function hydrate()
    {
        parent::hydrate();

        $this->onHydrate();
    }


    /**
     * Define your listeners here.
     * Your listeners will be merged with the default listeners.
     */
    public function getViewListeners(): array
    {
        return [];
    }

    /**
     * This method is called, if a view gets refreshed.
     */
    public function onRefreshView(mixed $value)
    {
    }

    /**
     * This function is called before every render method.
     */
    public function onUpdateCache()
    {
    }

    /**
     * This method is called, when data has been updated.
     */
    public function onUpdateData()
    {
    }

    /**
     * This function will be called, on show view.
     * Return if view can show.
     */
    public function onShowView(mixed $value): bool
    {
        return true;
    }

    /**
     * This function will be called, on hide view.
     * Return if view can hide.
     */
    public function onHideView(mixed $value): bool
    {
        return true;
    }

    /**
     * View uses components.
     */
    public function withComponents(): bool
    {
        return false;
    }

    /**
     * Renders current component.
     * Updates cache.
     */
    public function render()
    {
        $this->onUpdateCache();

        return $this->onRender();
    }

    /**
     * Callback when data has been updated.
     * If data is valid, component will be updated.
     */
    public function updatedData()
    {
        if (!$this->validateView($this->rules())) {
            return;
        }

        if ($this->withComponents()) {
            $this->handleUpdatedData();
        }

        $this->oldData = $this->data;

        $this->onUpdateData();
    }

    /**
     * Loads session before mount.
     */
    public function boot()
    {
        $this->loadSession();

        if ($this->withComponents()) {
            $this->changes = new Changes($this->oldData, $this->data ?? [], $this);
        }

        $this->registerDefaultActions();

        $this->onBoot();
    }

    public function loadSession()
    {
        $data = session($this->viewId) ?? [];

        $this->sessionedData = $data;
    }

    /**
     * This function will be called once.
     * Setup view and parse data from onMount into data['data']
     */
    public function mount(mixed $context = null)
    {
        $data = null;

        $settings = [
            'visibility' => $this->getData('visibility') ?? $this->defaultVisibility,
            'was_visible' => $this->getData('was_visible') ?? false,
            'loading' => $this->getData('loading') ?? false,
        ];

        $this->loadSession();

        /**
         * Apply settings on view.
         */
        $this->data['data'] = array_merge($this->getSession(), $data ?? [], $settings);

        $this->registerDefaultActions();

        if ($this->isVisible()) {
            $data = $this->onMount($context) ?? [];
        }

        $this->loadSession();

        /**
         * Re apply settings on view after mount.
         */
        $this->data['data'] = array_merge($this->getSession(), $data ?? [], $settings);

        $this->oldData = $this->data;

        $this->mountData = $this->data;

        $this->registerDefaultActions();
    }

    /**
     * Validate data with default or custom rules.
     * If rules are empty, data will alwyas be valid.
     */
    public function validateView(?array $rules = null)
    {
        $validation = new ValidateView($this, $rules ?? $this->rules());

        if (!$validation->isValid()) {
            foreach ($validation->messages as $key => $message) {
                $this->addError($key, $message[0]);
            }

            $this->onValidationError($validation->exception, $validation->messages);
        }

        return $validation->isValid();
    }

    /**
     * Excetues an action.
     */
    public function action(string $id, array $data = []): mixed
    {
        $action = $this->actions[$id] ?? null;

        if (empty($action)) {
            return null;
        }

        $action = $action['class'];

        $actionObject = eval("return new {$action};");

        return $actionObject->handle($this, $data);
    }

    /**
     * Register a Action for this view.
     */
    public function registerAction(string $id, string $class): void
    {
        $this->actions[$id] = [
            'id' => $id,
            'class' => $class,
        ];
    }

    /**
     * Refreshes view.
     */
    public function refreshView(?string $viewId = null, mixed $value = null)
    {
        if (!empty($viewId) && $viewId != $this->viewId) {
            $this->emit($viewId . '-refresh', $value);

            return;
        }

        $this->onRefreshView($value);
    }

    public function refreshViewListener(mixed $value = null)
    {
        $this->onRefreshView($value);
    }

    /**
     * Cache any value.
     */
    public function cache(string $key, mixed $value)
    {
        $this->cachedData[$key] = $value;
    }

    /**
     * Cache any value.
     */
    public function uncache(string $key)
    {
        if (array_key_exists($key, $this->cachedData)) {
            unset($this->cachedData[$key]);
        }
    }

    /**
     * Save value to session.
     */
    public function store(string $key, mixed $value, ?string $viewId = null)
    {
        $viewId = $viewId ?? $this->viewId;

        $data = session($viewId) ?? [];

        $data[$key] = $value;

        session([
            $viewId => $data,
        ]);

        if ($viewId == $this->viewId) {
            $this->cache($key, $value);
        }
    }

    /**
     * Get data with key.
     * Preferences cache data.
     */
    public function getData(string $key, mixed $default = null): mixed
    {
        if ($this->isCached($key)) {
            return $this->getCache($key);
        }

        if (empty($this->data) || !array_key_exists('data', $this->data) || !array_key_exists($key, $this->data['data'])) {

            if (!$this->isSessioned($key)) {

                return $default;
            }

            return $this->getSessioned($key);
        }

        return $this->data['data'][$key];
    }

    /**
     * Get data from cache.
     */
    public function getCache(string $key): mixed
    {
        return $this->cachedData[$key];
    }

    /**
     * Check if key exists in cache.
     */
    public function isCached(string $key): bool
    {
        return array_key_exists($key, $this->cachedData);
    }

    /**
     * Get data from session.
     */
    public function getSessioned(string $key): mixed
    {
        return $this->sessionedData[$key];
    }

    /**
     * Get session as full array.
     */
    public function getSession(): array
    {
        return $this->sessionedData;
    }

    /**
     * Check if key exists in session.
     */
    public function isSessioned(string $key): bool
    {
        return array_key_exists($key, $this->sessionedData);
    }

    /**
     * Current view is visible or not.
     */
    public function isVisible(): bool
    {
        return $this->getData('visibility') ?? false;
    }

    /**
     * Current view was visible or not.
     */
    public function wasVisible(): bool
    {
        if ($this->isVisible()) {

            return true;
        }

        return $this->getData('was_visible') ?? false;
    }

    /**
     * Show current view.
     * Call mount with value before changing the visibility.
     */
    public function showView(mixed $viewId = null, array $value = [])
    {
        if (!empty($viewId) && $viewId != $this->viewId) {
            $this->emit($viewId . '-show', $value);

            return;
        }

        if ($this->isVisible()) {
            return;
        }

        if (!$this->onShowView($value)) {
            return;
        }

        $this->data['data']['visibility'] = true;
        $this->data['data']['was_visible'] = true;

        $this->mount($value);
    }

    public function showViewListener(mixed $value = null)
    {
        if ($this->isVisible()) {
            return;
        }

        if (!$this->onShowView($value)) {
            return;
        }

        $this->data['data']['visibility'] = true;
        $this->data['data']['was_visible'] = true;

        $this->mount($value);
    }

    public function hideViewListener(mixed $value = null)
    {
        if (!$this->isVisible()) {
            return;
        }

        if (!$this->onHideView($value)) {
            return;
        }

        $this->data['data']['visibility'] = false;
    }

    /**
     * Hide current view.
     */
    public function hideView(?string $viewId = null, mixed $value = null)
    {
        if (!empty($viewId) && $viewId != $this->viewId) {
            $this->emit($viewId . '-hide', $value);

            return;
        }

        if (!$this->isVisible()) {
            return;
        }

        if (!$this->onHideView($value)) {
            return;
        }

        $this->data['data']['visibility'] = false;
    }

    public function isLoading(): bool
    {
        return $this->getData('loading') ?? false;
    }

    public function setLoading(bool $value)
    {
        $this->data['data']['loading'] = $value;
    }

    public function broadcast(array $data, bool $refresh = true)
    {
        $payload = array_merge([
            'from' => $this->viewId,
            'refresh' => $refresh,
        ], $data);

        $this->emit('broadcast', $payload);
    }

    public function broadcastListener(array $data): void
    {
        if ($data['from'] != $this->viewId) {
            if ($data['refresh']) {
                $this->refreshView();
            }
        }

        $this->onBroadcast($data);
    }

    public function onBroadcast(array $data)
    {
    }
}
