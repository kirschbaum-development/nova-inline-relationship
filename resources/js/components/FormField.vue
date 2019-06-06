<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <draggable v-model="value" handle=".relationship-item-handle" @start="drag=true" @end="drag=false">
                <relationship-form-item :value="item" :id="index" :key="index" :errors="errorList" :name="field.attribute" :label="field.name" :singular="field.singular" @deleted="removeItem(index)" v-for="(item, index) in value" />
            </draggable>
            <div class="bg-30 flex p-2 border-b border-40 rounded-lg" v-if="!field.singular">
                <div class="w-full text-right">
                    <button type="button" class="btn btn-default bg-transparent hover:bg-primary text-primary hover:text-white border border-primary hover:border-transparent inline-flex items-center relative mr-3" @click="addItem()">
                    Add new {{field.name}}
                </button>
                </div>
            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import draggable from 'vuedraggable'
import RelationshipFormItem from './RelationshipFormItem.vue'

export default {
    components:{
        draggable,
        RelationshipFormItem
    },

    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data: function(){
        return {
            errorBag: []
        }
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = Array.isArray(this.field.value)?this.field.value : [];
            if(this.field.singular){
                this.value = this.value.splice(1);
            }
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, JSON.stringify(this.value) || '{}')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },

        removeItem (index) {
            let value = [...this.value];
            value.splice(index, 1);
            this.handleChange(value);
        },

        addItem(){
            let value = [...this.value];
            value.push({...this.field.defaults});
            this.handleChange(value);
        },
    },

    watch:{
        'errors': function (errors) {
            this.errorList = errors.errors.hasOwnProperty(this.field.attribute)?errors.errors[this.field.attribute][0]:{};
        }
    }
}
</script>
