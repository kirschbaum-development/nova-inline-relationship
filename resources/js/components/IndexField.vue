<template>
  <span>{{ Object.keys(field.value).length }} {{ field.name }}(s)</span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
}
</script>
