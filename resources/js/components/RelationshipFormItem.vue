<template>
    <div class="card shadow-md mb-4">
        <div class="bg-30 flex p-2 border-b border-40">
            <div v-if="! field.singular && field.sortable"
                class="w-1/8 text-left py-2 px-2">
                <span class="relationship-item-handle py-2 px-2 cursor-move">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        width="24"
                        height="24">
                        <path class="heroicon-ui"
                            d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z">
                        </path>
                    </svg>
                </span>
            </div>

            <div class="w-5/8 flex-grow text-left py-2 px-2">
                <h4 class="font-normal text-80" v-text="label"></h4>
            </div>

            <div v-if="field.deletable" class="w-1/4 text-right">
                <button
                    class="btn btn-default btn-icon btn bg-transparent hover:bg-danger text-danger hover:text-white border border-danger hover:border-transparent inline-flex items-center relative mr-3"
                    title="Delete"
                    @click.prevent="removeItem">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        class="fill-current text-0">
                        <path fill-rule="nonzero"
                            d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <div v-for="(field, attrib) in fields"
            :key="attrib"
            class="nova-items-field-input-wrapper w-full">
            <component :is="'form-' + field.component"
                :ref="attrib"
                :field="field"
                :full-width-content="true"
                :errors="errors"
                :resource-id="modelId"
                :resource-name="modelKey">
            </component>
        </div>
    </div>
</template>

<script>
    export default {
        name: "relationship-form-item",

        props: [
            'value',
            'label',
            'id',
            'modelId',
            'modelKey',
            'errors',
            'field'
        ],

        computed: {
            fields() {
                return _.keyBy(
                    Object.keys({ ...this.value }).map(
                        attrib => {
                            return {
                                ...{
                                    'options': {}
                                },
                                ...this.value[attrib].meta,
                                ...{
                                    'attribute': (this.value[attrib].meta.component === "file-field") ?
                                        attrib + '?' + this.id :
                                        this.field.attribute + '_' + this.id + '_' + attrib, // This is needed to enable delete link for file without triggering duplicate id warning
                                    'name': this.field.attribute + '[' + this.id + '][' + attrib + ']',
                                    'deletable': this.modelId > 0, // Hide delete button if model Id is not present, i.e. new model
                                    'attrib': attrib
                                }
                            }
                        }
                    ), 'attrib'
                )
            },

            label() {
                return this.field.singular
                    ? this.field.singularLabel
                    : `${this.field.singularLabel} ${this.id + 1}`;
            }
        },

        methods: {
            getValueFromChildren() {
                return _.tap(new FormData(), formData => {
                    _(this.$refs).each(item => {
                        if (item[0].field.component === 'file-field') {
                            if (item[0].file) {
                                formData.append(item[0].field.attrib, item[0].file, item[0].fileName);
                            } else if (item[0].value) {
                                formData.append(item[0].field.attrib, String(item[0].value))
                            }
                        } else if (item[0].field.component === 'boolean-field') {
                            formData.append(item[0].field.attribute, item[0].trueValue);
                        } else {
                            item[0].fill(formData);
                        }
                    })
                })
            },

            fill(formData, parentAttrib) {
            	formData.append(`${parentAttrib}[${this.id}][modelId]`, this.modelId);
                this.getValueFromChildren().forEach(
                    (value, key) => {
                        let keyParts = key.split('_');

                        if (keyParts.length === 1) {
                            formData.append(`${parentAttrib}[${this.id}][values][${key}]`, value);
                            return;
                        }

                        let parentParts = parentAttrib.split('_');
                        let attrib = keyParts.slice(parentParts.length + 1).join('_');

                        formData.append(`${parentAttrib}[${this.id}][values][${attrib}]`, value);
                    }
                );
            },

            removeItem() {
                this.$emit('deleted', this.id);
            },
        },
    }
</script>
