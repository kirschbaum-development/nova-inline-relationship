<template>
    <div class="card shadow-md mb-4 border mr-2 rounded-lg">
        <div class="bg-30 flex p-2 border-b border-40 relationship-item-handle">
            <div
                class="w-1/8 text-left py-2 px-2 cursor-move">
                <span class="">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                    </svg>
                </span>
            </div>

            <div class="w-5/8 flex-grow text-left py-2 px-2">
                <h4 class="font-normal text-80" v-text="label"></h4>
            </div>

            <div v-if="field.deletable" class="w-1/4 text-right">
                <button
                    @click.stop="removeItem"
                    v-tooltip.click="__('Delete')"
                    class="toolbar-button hover:text-red-600 px-2 disabled:opacity-50 disabled:pointer-events-none"
                >
                    <Icon type="trash" />
                </button>
            </div>
        </div>

        <div v-for="(field, attrib) in fields"
            :key="attrib"
            class="nova-items-field-input-wrapper w-full">
            <component :is="'form-' + field.component"
                ref="{attrib}"
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
    // import draggable from 'vuedraggable'; 
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
                                ...this.value[attrib].meta,
                                ...{
                                    'attribute': (this.value[attrib].meta.component === "file-field") ?
                                        attrib + '?' + this.id :
                                        this.field.attribute + '_' + this.id + '_' + attrib, // This is needed to enable delete link for file without triggering duplicate id warning
                                    'name': this.value[attrib].meta.singularLabel,
                                    'deletable': this.modelId > 0, // Hide delete button if model Id is not present, i.e. new model
                                    'attrib': attrib,
                                    'options': this.value[attrib].options,
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
                        _(item).each(field => {
                            if (field.currentField.component === 'file-field') {
                                if (field.file) {
                                    formData.append(field.currentField.attrib, field.file, field.fileName);
                                } else if (field.value) {
                                    formData.append(field.currentField.attrib, String(field.value))
                                }
                            } else if (field.field.component === 'boolean-field') {
                                formData.append(field.currentField.attribute, field.trueValue);
                            } else {
                                field.fill(formData);
                            }
                        })

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
<style>
.relationship-item-handle{
    cursor: move;
}
</style>
