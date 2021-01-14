<template>
  <el-select
    v-model="selected"
    multiple
    :placeholder="placeholder"
    @change="handleChangeLabel">
    <el-option
      v-for="item in list"
      :key="item.id"
      :label="item.name"
      :value="item.id"/>
  </el-select>
</template>

<script>
export default {
  name: '',
  props: {
    value: {
      type: Array,
      default: () => {
        []
      }
    },
    placeholder: {
      type: String,
      required: true
    },
    list: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      selected: []
    }
  },
  watch: {
    value: {
      handler (val) {
        this.selected = val
      },
      immediate: true
    }
  },
  created () {
  },
  methods: {
    handleChangeLabel (val) {
      this.$emit('input', val)
    }
  }
}
</script>

<style scoped>

</style>
