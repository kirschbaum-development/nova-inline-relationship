<template>
  <span>{{ indexLabel }}</span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],

    computed:{
        indexLabel: function(){
            let count = Object.keys(this.field.value).length;
            let label = (count > 1) ? this.field.pluralLabel : this.field.singularLabel;
            return `${count} ${label}`;
        }
    }
}
</script>
