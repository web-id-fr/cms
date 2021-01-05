<template>
    <div>
        <heading class="mb-6">{{ __('Languages') }}</heading>

        <!-- Search -->
        <div class="flex">
            <div class="relative h-9 mb-6 flex-no-shrink">
                <icon type="search" class="absolute search-icon-center ml-3 text-70" />

                <input
                        data-testid="search-input"
                        dusk="search"
                        class="appearance-none form-control form-input w-search pl-search"
                        placeholder="Search"
                        type="search"
                        v-model="search"
                >
            </div>
        </div>

        <card class="flex flex-wrap p-8" style="min-height: 300px">
            <div class="py-4 px-8" v-for="language in filteredLanguages" :key="language.name">
                <div class="flex flex-col text-center" v-if="!language.loading">
                    <div>
                        <country-flag :country='language.flag' size='normal'/>
                    </div>
                    <div>
                        <checkbox
                            class="py-2"
                            @input="toggleLanguage(language)"
                            :checked="language.used"
                        />
                        {{ language.name }}
                    </div>
                </div>
                <div v-else>
                    <svg viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="text-60 mx-auto block" style="width: 40px;"><circle cx="15" cy="15" r="12.5011"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="60" cy="15" r="11.4989" fill-opacity="0.3"><animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="105" cy="15" r="12.5011"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle></svg>
                </div>
            </div>
        </card>
    </div>
</template>

<script>
    import CountryFlag from 'vue-country-flag'

    export default {
        components: {
            CountryFlag
        },

        mounted() {
            Promise.all([
                Nova.request().get('/nova-vendor/language-tool/language/available'),
                Nova.request().get('/nova-vendor/language-tool/language'),
            ]).then((values) => {
                let languages = values[0].data;
                let languages_selected = values[1].data;
                for (let i = 0; i < languages.length; i++) {
                    languages[i].used = false;
                    languages[i].loading = false;
                }
                for (let i = 0; i < languages_selected.length; i++) {
                    for (let y = 0; y < languages.length; y++) {
                        if (languages_selected[i].name === languages[y].name) {
                            languages[y].used = true;
                            languages[y].id = languages_selected[i].id;
                        }
                    }
                }
                this.languages = languages;
            });
        },

        data: () => ({
            languages: [],
            search: ''
        }),

        methods: {
            toggleLanguage(language) {
                language.loading = true;
                if(language.used) {
                    this.delete(language);
                } else {
                    this.save(language);
                }
            },
            save(language) {
                Nova.request().post('/nova-vendor/language-tool/language', {
                    name: language.name,
                    flag: language.flag
                }).then((response) => {
                    console.log(response.data.id);
                    language.id = response.data.id;
                    language.used = !language.used;
                    language.loading = false;
                }).catch((error) => {
                    language.loading = false;
                    console.log(error.response);
                }).finally(() => {
                    location.reload();
                });
            },
            delete(language) {
                Nova.request().delete('/nova-vendor/language-tool/language/' + language.id).then((response) => {
                    language.used = !language.used;
                    language.loading = false;
                }).catch((error) => {
                    language.loading = false;
                }).finally(() => {
                    location.reload();
                });
            }
        },

        computed: {
            filteredLanguages: function() {
                if(!this.search) { return this.languages }

                let languagesFiltered = [];
                for (let i = 0; i < this.languages.length; i++) {{}
                    let name = this.languages[i].name.toLowerCase();
                    if(name.search(this.search.toLowerCase()) !== -1) {
                        languagesFiltered.push(this.languages[i]);
                    }
                }

                return languagesFiltered;
            }
        }
    }
</script>
