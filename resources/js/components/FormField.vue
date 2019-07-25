<template>
  <default-field
    :field="field"
    :errors="errors"
    :show-errors="false"
    :full-width-content="true"
  >
    <template slot="field">
      <draggable
        v-model="valueAsArray"
        handle=".relationship-item-handle"
        @start="drag=true"
        @end="drag=false"
      >
        <relationship-form-item
          v-for="(items, index) in value"
          :ref="index"
          :key="items.id"
          :id="index"
          :value="items.fields"
          :errors="errorList"
          :field="field"
          @deleted="removeItem(index)"
        />
      </draggable>
      <div
        v-if="!field.singular || !valueAsArray.length"
        class="bg-30 flex p-2 border-b border-40 rounded-lg"
      >
        <div class="w-full text-right">
          <button
            type="button"
            class="btn btn-default bg-transparent hover:bg-primary text-primary hover:text-white border border-primary hover:border-transparent inline-flex items-center relative mr-3"
            @click="addItem()"
          >
            Add new {{ field.singularLabel.toLowerCase() }}
          </button>
        </div>
      </div>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors, Errors } from 'laravel-nova'
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
            id: 0,
            errorList: new Errors()
        }
    },

    watch: {
        errors: function (errors) {
            let errObj = errors.errors.hasOwnProperty(this.field.attribute) ? errors.errors[this.field.attribute][0] : {};
            Object.keys(errObj).forEach(key=>{
                errObj[key.replace(/\./g , '_')] = errObj[key];
                delete errObj[key];
            });
            this.errorList =  new Errors(errObj);
        },
    },

    computed: {
        valueAsArray: function (){
            return Array.isArray(this.value) ? this.value : [];
        }
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = Array.isArray(this.field.value) ? this.field.value : [];
            this.value = this.value.map(item=>{
                return { 'id': this.getNextId(), 'fields':item }
            });

            if(this.field.singular){
                this.value.splice(1);
            }

            if(this.field.addChildAtStart && (this.value.length == 0)){
                this.value.push({ 'id': this.getNextId(), 'fields': {...this.field.settings}});
            }
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            try{
                this.fillValueFromChildren(formData)
            }catch(error){
                console.log(error);
            }
        },

        fillValueFromChildren: function(formData) {
            _(this.$refs).each(item => {
                if(item && Array.isArray(item) && item[0]){
                    item[0].fill(formData, this.field.attribute);
                }
            });
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = Array.isArray(value) ? value : [];
        },

        getNextId(){
            this.id += 1;
            return this.id;
        },

        removeItem (index) {
            let value = [...this.value];
            value.splice(index, 1);
            this.handleChange(value);
        },

        addItem(){
            let value = [...this.value];
            value.push({ 'id': this.getNextId(), 'fields': {...this.field.settings}});
            this.handleChange(value);
        },
    }
}
</script>
