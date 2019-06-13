<template>
  <div class="card shadow-md mb-4">
    <div class="bg-30 flex p-2 border-b border-40">
      <div
        v-if="!singular"
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
          {{ label }} {{ id+1 }}
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
      v-for="(parameter, attrib) in value"
      :key="attrib"
      class="nova-items-field-input-wrapper w-full"
    >
      <component
        :is="'form-' + parameter.component"
        :field="parameter"
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
            'name',
            'singular',
            'id',
            'errors'
        ],

        mounted() {
            this.value.fill = this.fill
        },

        computed: {
            getValueFromChildren: function() {
                return _.tap(new FormData(), formData => {
                    _(this.value).each(item => {
                        if(item.hasOwnProperty('fill') && (item.fill instanceof Function)){
                            item.fill(formData)
                        }
                    })
                })
            },
        },

        methods:{
            /**
             * Provide a function that fills a passed FormData object with the
             * field's internal value attribute
             */
            fill(formData) {
                let formObject = {};
                this.getValueFromChildren.forEach(
                    (value, key) => {
                        formObject[key.split('_', 3)[2]] = value
                    }
                );
                formData[this.id] = formObject;
            },


            removeItem:function(){
                this.$emit('deleted', this.id);
            },

            getName:function(id, attrib){
                return this.name + '_' + id + '_' + attrib
            },

            getErrors:function(id, attrib){
                return this.errors && this.errors.hasOwnProperty(this.getName(id, attrib))?this.errors[this.getName(id, attrib)]:[]
            },

            hasError:function(id, attrib){
                return this.errors && this.errors.hasOwnProperty(this.getName(id, attrib))
            },

            getError:function(id, attrib){
                return this.hasError(id, attrib) ? this.errors[this.getName(id, attrib)][0] : '';
            }
        }
    }
</script>
