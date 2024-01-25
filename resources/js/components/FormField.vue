<template>
    <PanelItem
        :field="field"
        :errors="errors"
        :show-errors="false"
        class="mx-0 md:py-5"
    >
      <template #value>
          <draggable
              :list="items"
              item-key="id"
              handle=".relationship-item-handle"
              @start="drag = true"
              @end="drag = false"
          >
              <template
                  #item="{element, index}"
              >
                  <relationship-form-item
                      :ref="refName(index)"
                      :key="element.id"
                      :id="index"
                      :model-id="element.modelId"
                      :model-key="field.modelKey"
                      :value="element.fields"
                      :errors="errorList"
                      :field="field"
                      @deleted="removeItem(index)"
                  />
              </template>
          </draggable>
          <div
              v-if="!field.singular || !items.length"
          >
            <div class="w-full text-right">
                <button
                    type="button"
                    class="shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 mr-3"
                    @click="addItem()"
                >
                   {{ __("Add") }} {{ field.singularLabel.toLowerCase() }}
                </button>
            </div>
        </div>
      </template>
    </PanelItem>
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
            items: [],
            errorList: new Errors(),
            dragging: false,
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
            return Array.isArray(this.items) ? this.items : [];
        },
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.items = Array.isArray(this.field.value) ? this.field.value : [];
            this.items = this.items.map((item, index) => {
                return {
                	'id': this.getNextId(),
	                'modelId': this.field.models[index],
	                'fields': item
                }
            });

            if(this.field.singular){
                this.items.splice(1);
            }

            if(this.field.addChildAtStart && (this.items.length === 0)){
                this.items.push({
	                'id': this.getNextId(),
	                'modelId': 0,
	                'fields': {...this.field.settings}
                });
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
            if(!_.isEmpty(Object.entries(this.$refs).reduce((a,[k,v]) => (v ? (a[k]=v, a) : a), {}))){
                _(this.$refs).each(item => {
                      if(item && item.fields){
                        item.fill(formData, this.field.attribute);
                      }
                });
            }else{
                formData.append(this.field.attribute, []);
            }
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.items = Array.isArray(value) ? value : [];
        },

        getNextId() {
            this.id += 1;
            return this.id;
        },

        removeItem (index) {
            let value = [...this.items];
            value.splice(index, 1);
            this.handleChange(value);
        },

        addItem() {
            let value = [...this.items];
            value.push({
	            'id': this.getNextId(),
	            'modelId': 0,
	            'fields': {...this.field.settings}
            });
            this.handleChange(value);
        },

        refName(index) {
            return `child-${index}`;
        }
    }
}
</script>
