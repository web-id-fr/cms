<script>
    import Editor from '@tinymce/tinymce-vue';

    export default {
        name: 'trix-vue',
        props: ['name', 'value', 'placeholder'],
        components: {
            'editor': Editor
        },
        data() {
            return {
                content: null,
                init: false,
                config: {
                    relative_urls: false,
                    remove_script_host: false,
                    entity_encoding: "raw",
                    encoding: "UTF-8",
                    menubar: false,
                    plugins: "code textcolor preview link lists media table image imagetools emoticons charmap",
                    toolbar: "code | undo redo | formatselect | bold italic strikethrough underline forecolor backcolor | link image media | alignleft aligncenter alignright alignjustify | table | bullist numlist outdent indent blockquote | charmap emoticons | removeformat",
                    forced_root_block: "p",
                    automatic_uploads: true,
                    file_picker_types: 'image file media',
                    media_live_embeds: true,
                    image_advtab: true,
                    file_picker_callback: function (callback, value, meta) {
                        let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                        let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;
                        let url = '/nova/nova-filemanager';
                        tinyMCE.activeEditor.windowManager.openUrl({
                            url: url,
                            title: 'File manager',
                            width: x * 0.8,
                            height: y * 0.8,
                            onMessage: (api, message) => {
                                callback(message.content);
                            }
                        });
                    }
                }
            }
        },

        mounted: () => {
            this.content = this.value;
        },

        methods: {
            update() {
                this.content = this.value == undefined ? '' : this.value;
            },

            onChange() {
                this.$emit('change', this.content)
            },
        },

        watch: {
            'value': function (newValue) {
                if (!this.init) {
                    this.content = this.value;
                    this.init = true;
                }
            },
            'content': function(newValue) {
                this.$emit('change', this.content);
            }
        }
    }
</script>

<template>
    <editor v-model="content"
            ref="theEditor"
            @change="onChange"
            api-key="qw068v96pacv2vfc9nc69wnpkc3h3jzdsz643l6ioup1icd7"
            :init="config"
    ></editor>
</template>
