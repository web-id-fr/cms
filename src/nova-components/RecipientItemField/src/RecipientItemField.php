<?php

namespace Webid\RecipientItemField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\Modules\Form\Models\Form;
use Webid\Cms\Modules\Form\Models\Service;
use Webid\Cms\Modules\Form\Repositories\RecipientRepository;

class RecipientItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'RecipientItemField';

    /**
     * @param string $name
     * @param string|null $attribute
     * @param callable|null $resolveCallback
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $name, ?string $attribute = null, callable $resolveCallback = null)
    {
        $recipientRepository = app()->make(RecipientRepository::class);

        $allField = $recipientRepository->all();
        $allField->map(function ($recipient) {
            return $recipient;
        });

        $this->withMeta(['items' => $allField]);

        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @param NovaRequest $request
     * @param string $requestAttribute
     * @param object $model
     * @param string $attribute
     *
     * @return void
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $recipientItems = $request[$requestAttribute];
        $recipientItems = collect(json_decode($recipientItems, true));

        $recipientItemIds = [];

        $recipientItems->each(function ($recipientItem, $key) use (&$recipientItemIds) {
            $recipientItemIds[] = $recipientItem['id'];
        });

        if (get_class($model) == Form::class) {
            Form::saved(function ($model) use ($recipientItemIds) {
                $model->recipients()->sync($recipientItemIds);
            });
        } elseif (get_class($model) == Service::class) {
            Service::saved(function ($model) use ($recipientItemIds) {
                $model->recipients()->sync($recipientItemIds);
            });
        }
    }

    /**
     * @param mixed $resource
     * @param null $attribute
     */
    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);
        $resource->recipients();

        $valueInArray = [];
        $resource->recipients->each(function ($item) use (&$valueInArray) {
            $valueInArray[] = $item;
        });

        if ($valueInArray) {
            $this->value = $valueInArray;
        }
    }
}
