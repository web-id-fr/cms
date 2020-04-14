<?php

namespace Webid\LanguageTool\Http\Controllers;

use App\Http\Controllers\Controller;
use Webid\LanguageTool\Http\Requests\CreateLanguageRequest;
use Webid\LanguageTool\Models\Language;
use Webid\LanguageTool\Repositories\LanguageRepository;

class LanguageController extends Controller
{
    /**
     * @var LanguageRepository
     */
    protected $languageRepository;

    /**
     * LanguageController constructor.
     *
     * @param LanguageRepository $languageRepository
     */
    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * Retourne les langues utilisées par le site
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->languageRepository->all());
    }

    /**
     * Retourne les langues disponibles
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function available()
    {
        $allPossible = [];

        foreach (config('translatable.locales') as $local => $language) {
            $allPossible[] = [
                'name' => $language,
                'flag' => Language::flagsByLocal($local),
            ];
        }

        return response()->json($allPossible);
    }

    /**
     * Ajoute puis retourne la langue fraichement ajouté
     *
     * @param CreateLanguageRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateLanguageRequest $request)
    {
        return response()->json(
            $this->languageRepository->store($request->all())
        );
    }

    /**
     * Supprime une langue
     *
     * @param Language $language
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function delete(Language $language)
    {
        $this->languageRepository->delete($language);

        return response(null, 204);
    }
}
