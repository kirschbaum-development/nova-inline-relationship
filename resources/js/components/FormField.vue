<template>
  <default-field
    :field="field"
    :errors="errors"
    :show-errors="false"
  >
    <template slot="field">
        <draggable
        v-model="value"
        handle=".relationship-item-handle"
        @start="drag=true"
        @end="drag=false"
      >
        <relationship-form-item
          v-for="(item, index) in fields"
          :id="index"
          :key="index"
          :value="item"
          :errors="errorList"
          :name="field.attribute"
          :singular="field.singular"
          :label="field.name"
          @deleted="removeItem(index)"
        />
      </draggable>
      <div
        v-if="!field.singular"
        class="bg-30 flex p-2 border-b border-40 rounded-lg"
      >
        <div class="w-full text-right">
          <button
            type="button"
            class="btn btn-default bg-transparent hover:bg-primary text-primary hover:text-white border border-primary hover:border-transparent inline-flex items-center relative mr-3"
            @click="addItem()"
          >
            Add new {{ field.name }}
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
            errorBag: [],
            fields: []
        }
    },

    computed: {
        getValueFromChildren: function() {
            return _.tap([], formData => {
                _(this.fields).each(item => {
                    item.fill(formData)
                })
            })
        },
    },

    watch: {
        'errors': function (errors) {
            this.errorList = errors.errors.hasOwnProperty(this.field.attribute) ? new Errors(errors.errors[this.field.attribute][0]) : new Errors();
        },

        'value': function(){
            if(Array.isArray(this.value)){
                this.fields = [...this.value].map((relatedFields, id)=> {
                    return _.keyBy(
                        Object.keys({...relatedFields}).map(
                            attrib => {
                                return {
                                    ...this.field.settings[attrib],
                                    ...{
                                        'component': this.field.settings[attrib].component || 'text',
                                        'attribute': this.field.attribute + '_' + id + '_' + attrib,
                                        'singularLabel': this.field.settings[attrib].label||attrib,
                                        'value': this.value[id][attrib],
                                        'name': this.field.attribute + '[' + id + '][' + attrib + ']',
                                        'attrib': attrib
                                    },
                                    ...{
                                        'extraAttributes': {
                                                                'name': this.field.attribute + '[' + id + '][' + attrib + ']'
                                                            }
                                        }
                                }
                            }
                        ), 'attrib'
                    )
                })
            }
        }
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = Array.isArray(this.field.value) ? this.field.value : [];
            if(this.field.singular){
                this.value = this.value.splice(1);
            }

            if(this.field.addChildAtStart && (this.value.length == 0)){
                this.value.push({...this.field.defaults});
            }
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            this.handleChange(this.getValueFromChildren);
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
    }
}
</script>
