<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <div class="w-1/4 py-4">
                <slot>
                    <h4 class="font-normal text-80" v-text="field.singular ? field.singularLabel : field.pluralLabel"></h4>
                </slot>
            </div>
    
            <div class="w-3/4 py-4">
                <div>
                    <relationship-detail-item v-for="(item, index) in value"
                        :id="index"
                        :key="index"
                        :value="item"
                        :model-id="field.models[index]||0"
                        :model-key="field.modelKey"
                        :label="field.singularLabel"
                        :is-singular="field.singular"
                        :settings="field.settings"
                        :collapsed="collapsed">
                    </relationship-detail-item>
                </div>
            </div>
        </template>
    </PanelItem>
</template>

<script>
    import RelationshipDetailItem from "./RelationshipDetailItem";

    export default {
        components: { RelationshipDetailItem },

        props: [
            'index',
            'field',
            'resource',
            'resourceId',
            'resourceName',
        ],

        computed: {
            collapsed() {
                return this.field.collapsed === true
            },

            value() {
                return Array.isArray(this.field.value)
                    ? this.field.value
                    : [];
            }
        }
    }
</script>
