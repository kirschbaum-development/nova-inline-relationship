<template>
  <div class="flex border-b border-40">
    <div class="w-1/4 py-4">
      <slot>
        <h4 class="font-normal text-80">
          {{ field.name }}
        </h4>
      </slot>
    </div>
    <div class="w-3/4 py-4">
      <div>
        <RelationshipDetailItem
          v-for="(item, index) in field.value"
          :id="index"
          :key="index"
          :value="item"
          :label="field.name"
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
        collapsed:function(){
            return this.field.collapsed === true
        }
    }
}
</script>
