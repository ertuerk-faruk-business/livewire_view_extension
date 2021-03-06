<?php

namespace Livewire\ViewExtension;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\ViewExtension\Utilities\ValidateView;
use Livewire\ViewExtension\Utilities\Browser;
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
    public string $viewId = 'view';

    /**
     * Parent View Id.
     */
    public string $parentViewId = '';

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
     * Sessioned data.
     */
    public array $sessionedData = [];

    /**
     * All linked views.
     */
    public array $linkedViews = [];

    /**
     * Auth Provider for current View
     */
    public string $authProvider = '';

    /**
     * Saved Http Parameters.
     */
    public array $httpParameters = [];

    /**
     * Set default visibility of this view.
     */
    public function getDefaultVisibility(): bool
    {
        return true;
    }

    /**
     * Component can change Browser.
     */
    public function canChangeBrowser(): bool
    {
        return true;
    }

    /**
     * Default listeners merged with custom listeners.
     */
    protected function getListeners()
    {
        return array_merge([
            $this->viewId . '-show' => 'showViewListener',
            $this->viewId . '-hide' => 'hideViewListener',
            $this->viewId . '-refresh' => 'refreshViewListener',
            $this->viewId . '-browser-history' => 'browserHistoryListener',
            'broadcast' => 'broadcastListener'
        ], $this->getViewListeners());
    }

    /**
     * Default actions.
     */
    protected function getActions(): array
    {
        return array_merge([
            'collection-order' => \Livewire\ViewExtension\Actions\CollectionOrderAction::class,
            'toggle' => \Livewire\ViewExtension\Actions\ToggleAction::class,
            'select' => \Livewire\ViewExtension\Actions\SelectAction::class,
        ], $this->getViewActions());
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
    abstract public function onMount();

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

        if (! empty($this->authProvider)) {
            Auth::shouldUse($this->authProvider);
        }

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
     * Define your listeners here.
     */
    public function getViewActions(): array
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
        if (!$this->validateView()) {
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

        $this->onBoot();

        $this->httpParameters = request()->all();
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
    public function mount(array $context = [])
    {
        $data = null;

        $settings = [
            'visibility' => $this->getData('visibility') ?? $this->getDefaultVisibility(),
            'was_visible' => $this->getData('was_visible') ?? false,
        ];

        $this->loadSession();

        /**
         * Apply settings on view.
         */
        $this->data['data'] = array_merge($this->getSession(), $this->httpParameters, $data ?? [], $context, $settings);

        if ($this->isVisible()) {
            $this->httpParameters = Browser::set($this);

            $this->httpParameters = Browser::merge($this->httpParameters, $this->data['data']);

            /**
            * Re apply with http parameters.
            */
            $this->data['data'] = array_merge($this->getSession(), $this->httpParameters, $data ?? [], $context, $settings);

            $data = $this->onMount() ?? [];

            $this->httpParameters = Browser::update($this);
        }

        $this->loadSession();

        /**
         * Re apply settings on view after mount.
         */
        $this->data['data'] = array_merge($this->getSession(), $this->httpParameters, $data ?? [], $context, $settings);

        $this->oldData = $this->data;

        $this->mountData = $this->data;

        $httpView = $this->httpParameters['view'] ?? null;

        if (! empty($httpView)) {
            if ($httpView == $this->viewId) {
                $this->showView($this->httpParameters['view']);
            }
        }
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
        $action = $this->getActions()[$id] ?? null;

        if (empty($action)) {
            return null;
        }

        $actionObject = eval("return new {$action};");

        return $actionObject->handle($this, $data);
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

        if ($this->isVisible()) {
            $this->onRefreshView($value);
        }
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

            return $this->getSession($key);
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
     * Get session as full array.
     */
    public function getSession(?string $key = null): array
    {
        return empty($key) ? $this->sessionedData : $this->sessionedData[$key];
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
     * Show current or other View.
     */
    public function showView(mixed $viewId = null, array $value = [])
    {
        if (!empty($viewId) && $viewId != $this->viewId) {

            $value['parent_view_id'] = $this->viewId;

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

    public function browserHistoryListener()
    {
        $this->httpParameters = Browser::set($this);
    }

    public function showViewListener(mixed $value = null)
    {
        if ($this->isVisible()) {
            return;
        }

        if (!$this->onShowView($value)) {
            return;
        }

        $this->parentViewId = $value['parent_view_id'];

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
     * Hide current or other View.
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

        $this->emit($this->parentViewId . '-browser-history');

        $this->data['data']['visibility'] = false;
    }

    /**
     * Save value to session as secret.
     */
    protected function saveSecret(string $key, mixed $value, ?string $viewId = null): void
    {
        $viewId = $viewId ?? $this->viewId;

        $data = session($viewId.'_secret') ?? [];

        $data[$key] = $value;

        session([
            $viewId.'_secret' => $data,
        ]);
    }

    protected function getSecret(string $key, ?string $viewId = null): mixed
    {
        $viewId = $viewId ?? $this->viewId;

        $data = session($viewId.'_secret') ?? [];

        if (empty($data)) {
            return null;
        }

        return $data[$key] ?? null;
    }

    /**
     * Delete Secret with Key. If null, all Secrets will be deleted.
     */
    protected function deleteSecret(?string $key = null, ?string $viewId = null): void
    {
        $viewId = $viewId ?? $this->viewId; 

        if (empty($key)) {
            session([
                $viewId.'_secret' => null,
            ]);
        }

        $data = session($viewId.'_secret') ?? [];

        if (empty($data)) {
            return;
        }

        $result = [];

        foreach ($data as $valueKey => $value) {
            if ($key != $valueKey) {
                $result[$valueKey] = $value;
            }
        }

        session([
            $viewId.'_secret' => $data,
        ]);
    }

    /**
     * Send a Broadcast to other Views.
     */
    public function broadcast(array $data, bool $refresh = true): void
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

            if ($this->isVisible()) {
                $this->onBroadcast($data);
            }
        }
    }

    /**
     * Handle incoming broadcasts here.
     */
    public function onBroadcast(array $data)
    {
    }
}
