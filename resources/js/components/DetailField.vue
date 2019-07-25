<template>
  <div class="flex border-b border-40">
    <div class="w-1/4 py-4">
      <slot>
        <h4 class="font-normal text-80">
          {{ field.singularLabel }}
        </h4>
      </slot>
    </div>
    <div class="w-3/4 py-4">
      <div>
        <RelationshipDetailItem
          v-for="(item, index) in value"
          :id="index"
          :key="index"
          :value="item"
          :label="field.singularLabel"
          :settings="field.settings"
          :collapsed="collapsed"
        />
      </div>
    </div>
  </div>
</template>

<script>
import RelationshipDetailItem from "./RelationshipDetailItem";

export default {
    components: {RelationshipDetailItem},

    props: ['resource', 'resourceName', 'resourceId', 'field'],

    computed:{
        collapsed: function(){
            return this.field.collapsed === true
        },

        value: function(){
            if(Array.isArray(this.field.value)){
                return this.field.value;
            }

            return [];
        }
    }
}
</script>
