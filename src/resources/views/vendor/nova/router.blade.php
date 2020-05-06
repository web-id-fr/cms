@extends('nova::layout')

@section('content')
    <div>
        <div class="w1/2">
            <select id="select-language-translatable"
                    class="w-full form-control form-select"
                    style="width: auto; position: relative; top: -4px; margin-bottom: 10px;"
                    onchange="Nova.$emit('change-language', this.value);"
            >
                @php
                    $langs = \Illuminate\Support\Arr::wrap(app(\Webid\Cms\Src\App\Services\LanguageService::class)->getUsedLanguage());
                    ksort($langs);
                @endphp
                @foreach($langs as $langCode => $langName)
                    <option value="{{ $langCode }}">{{ $langName }}</option>
                @endforeach
            </select>
        </div>
        <loading ref="loading"></loading>

        <transition name="fade" mode="out-in">
            <router-view :key="$route.name + ($route.params.resourceName || '')"></router-view>
        </transition>

        <portal-target name="modals" multiple></portal-target>
    </div>
@endsection
