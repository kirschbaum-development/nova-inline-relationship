<template>
  <div class="card shadow-md mb-4">
    <div class="bg-30 flex p-2 border-b border-40">
      <div
        v-if="!field.singular"
        class="w-1/8 text-left py-2 px-2"
      >
        <span class="relationship-item-handle py-2 px-2 cursor-move">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            width="24"
            height="24"
          ><path
            class="heroicon-ui"
            d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"
          /></svg>
        </span>
      </div>
      <div class="w-5/8 flex-grow text-left py-2 px-2">
        <h4 class="font-normal text-80">
          {{ field.name }} {{ id+1 }}
        </h4>
      </div>
      <div class="w-1/4 text-right">
        <button
          class="btn btn-default btn-icon btn bg-transparent hover:bg-danger text-danger hover:text-white border border-danger hover:border-transparent inline-flex items-center relative mr-3"
          title="Delete"
          @click.prevent="removeItem()"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="20"
            height="20"
            viewBox="0 0 20 20"
            aria-labelledby="delete"
            role="presentation"
            class="fill-current text-0"
          ><path
            fill-rule="nonzero"
            d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"
          /></svg>
        </button>
      </div>
    </div>
    <div
      v-for="(field, attrib) in fields"
      :key="attrib"
      class="nova-items-field-input-wrapper w-full"
    >
      <component
        :is="'form-' + field.component"
        :ref="attrib"
        :field="field"
        :full-width-content="true"
        :errors="errors"
      />
    </div>
  </div>
</template>

<script>

    export default {
        name: "RelationshipFormItem",

        props:[
            'value',
            'label',
            'id',
            'errors',
            'field'
        ],

        computed:{
            fields: function(){
                return _.keyBy(
                    Object.keys({...this.value}).map(
                        attrib => {
                            return {
                                ...this.field.settings[attrib],
                                ...{
                                    'component': this.field.settings[attrib].component || 'text',
                                    'attribute': this.field.attribute + '_' + this.id + '_' + attrib,
                                    'singularLabel': this.field.settings[attrib].label||attrib,
                                    'value': this.value[attrib],
                                    'name': this.field.attribute + '[' + this.id + '][' + attrib + ']',
                                    'attrib': attrib
                                },
                                ...{
                                    'extraAttributes': {
                                        'name': this.field.attribute + '[' + this.id + '][' + attrib + ']'
                                    }
                                }
                            }
                        }
                    ), 'attrib'
                )
            }
        },

        methods:{
            getValueFromChildren: function() {
                return _.tap(new FormData(), formData => {
                    _(this.$refs).each(item => {
                        item[0].fill(formData);
                    })
                })
            },

            fill(formData) {
                let formObject = {};
                this.getValueFromChildren().forEach(
                    (value, key) => {
                        console.log(key, value);
                        formObject[key.split('_', 3)[2]] = value
                    }
                );
                formData[this.id] = formObject;
            },

            removeItem: function (){
                this.$emit('deleted', this.id);
            },
        },
    }
</script>
