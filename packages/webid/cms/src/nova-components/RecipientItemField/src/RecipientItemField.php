<?php

namespace Webid\RecipientItemField;

use App\Models\Modules\Form\Form;
use App\Models\Modules\Form\Service;
use App\Repositories\Modules\Form\RecipientRepository;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class RecipientItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'RecipientItemField';

    /**
     * @var $relationModel
     */
    public $relationModel;

    /**
     * @param string $name
     * @param string|null $attribute
     * @param mixed|null $resolveCallback
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $name, ?string $attribute = null, ?mixed $resolveCallback = null)
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
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param $requestAttribute
     * @param $model
     * @param $attribute
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $recipientItems = json_decode($request[$requestAttribute]);
        $recipientItems = collect(json_decode(json_encode($recipientItems), true));

        $recipientItemIds = [];

        $recipientItems->each(function ($recipientItem, $key) use (&$recipientItemIds) {
            $recipientItemIds[] = $recipientItem['id'];
        });

        if(get_class($model) == Form::class) {
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
        $resource->chargeRecipientItems();

        $valueInArray = [];
        $resource->recipient_items->each(function ($item) use (&$valueInArray) {
            $valueInArray[] = $item;
        });

        if ($valueInArray) {
            $this->value = $valueInArray;
        }
    }
}
