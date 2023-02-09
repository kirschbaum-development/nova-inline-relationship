<template>
    <div class="card shadow-md mb-4 border mr-2 rounded-lg">
        <div class="bg-30 flex p-2 border-b border-40">
            <span>
                <button class="btn btn-default btn-icon btn-white mr-3 p-1"
                    v-if="isCollapsed"
                    @click="isCollapsed=false">
                    <Icon type="minus" />
                </button>

                <button class="btn btn-default btn-icon btn-white mr-3 p-1"
                    v-else
                    @click="isCollapsed=true">
                    <Icon type="plus" />
                </button>
            </span>

            <span class="font-normal text-90 py-2 px-2" v-text="labelText"></span>
        </div>

        <transition name="slide-fade">
            <div v-if="! isCollapsed">
                <div class="w-full px-6"
                    v-for="(parameter, attrib) in fields"
                    :key="attrib">
                    <component :is="'detail-' + parameter.meta.component"
                        :field="parameter.meta"
                        :resource-id="modelId"
                        :resource-name="modelKey">
                    </component>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        name: "relationship-detail-item",

        props: {
            value: Object,
            settings: Object,
            collapsed: {
                type: Boolean,
                default: false
            },
            label: String,
            isSingular: Boolean,
            id: Number,
            modelId: Number,
            modelKey: String,
        },

        data() {
            return {
                isCollapsed: false
            }
        },

        computed: {
            fields() {
                let fields = { ...this.value };

                Object.keys(fields).map(
                    attrib => {
                        fields[attrib].meta['name'] = fields[attrib].meta['singularLabel'];
                    }
                );

                return fields;
            },

            labelText() {
                return this.isSingular
                    ? this.label
                    : `${this.label} ${this.id + 1}`;
            }
        },

        methods: {
            getLabel(attrib) {
                return this.getSettings(attrib, 'label') || attrib;
            },

            getSettings(attrib, key) {
                return this.settings && this.settings.hasOwnProperty(attrib) && this.settings[attrib].hasOwnProperty(key) ? this.settings[attrib][key] : ''
            },
        },

        watch: {
            collapsed() {
                this.isCollapsed = this.collapsed;
            }
        }
    }
</script>
